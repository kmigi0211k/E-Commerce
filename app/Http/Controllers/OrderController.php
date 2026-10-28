<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function adminOrders()
    {
        $orders = Order::with(['user.person', 'items.product'])
            ->latest()
            ->get();

        return view('orders.admin', compact('orders'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (! is_array($cart) || empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $quantities = [];
        foreach ($cart as $id => $item) {
            $productId = filter_var($id, FILTER_VALIDATE_INT);
            $quantity = filter_var(is_array($item) ? ($item['quantity'] ?? null) : null, FILTER_VALIDATE_INT);

            if ($productId === false || $productId < 1 || $quantity === false || $quantity < 1) {
                return redirect()->route('cart.index')->with('error', 'El carrito contiene cantidades o productos inválidos.');
            }

            $quantities[$productId] = $quantity;
        }

        $products = Product::whereIn('id', array_keys($quantities))->get()->keyBy('id');
        $total = 0;

        foreach ($quantities as $id => $quantity) {
            $product = $products->get($id);

            if (! $product || ! $product->is_active || $product->stock < $quantity || $product->price === null || $product->price < 0) {
                return redirect()->route('cart.index')->with('error', 'Hay productos no disponibles o con existencias insuficientes en tu carrito.');
            }

            $cart[$id]['name'] = $product->name;
            $cart[$id]['price'] = $product->price;
            $cart[$id]['image'] = $product->image;
            $cart[$id]['stock'] = $product->stock;
            $cart[$id]['quantity'] = $quantity;
            $cart[$id]['available'] = true;
            $total += $product->price * $quantity;
        }

        session()->put('cart', $cart);
        $checkoutToken = $this->checkoutTokenFor($quantities);

        return view('orders.checkout', compact('cart', 'total', 'checkoutToken'));
    }

    /**
     * @OA\Post(
     *     path="/orders/process",
     *     summary="Procesar la compra del carrito actual",
     *     tags={"Pedidos"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Compra procesada exitosamente",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="order_id", type="integer")
     *         )
     *     ),
     *
     *     @OA\Response(response=400, description="Error en el proceso (stock insuficiente, carrito vacío)")
     * )
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (! is_array($cart) || empty($cart)) {
            return $this->orderError($request, 'Tu carrito está vacío.');
        }

        $quantities = [];
        foreach ($cart as $id => $item) {
            $productId = filter_var($id, FILTER_VALIDATE_INT);
            $quantity = filter_var(is_array($item) ? ($item['quantity'] ?? null) : null, FILTER_VALIDATE_INT);

            if ($productId === false || $productId < 1 || $quantity === false || $quantity < 1) {
                return $this->orderError($request, 'El carrito contiene cantidades o productos inválidos.');
            }

            $quantities[$productId] = $quantity;
        }

        $checkoutToken = $this->checkoutTokenFor($quantities);
        $submittedToken = $request->input('checkout_token');
        if (! is_string($submittedToken) || ! hash_equals($checkoutToken, $submittedToken)) {
            return $this->orderError($request, 'La confirmación de compra expiró. Revisa tu carrito e inténtalo nuevamente.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,transfer,bancolombia,mastercard,daviplata'],
        ]);
        $paymentMethod = $validated['payment_method'];

        $userId = Auth::id();
        $result = DB::transaction(function () use ($quantities, $checkoutToken, $userId, $paymentMethod) {
            $products = Product::whereIn('id', array_keys($quantities))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $existingOrder = Order::where('checkout_token', $checkoutToken)->first();
            if ($existingOrder) {
                return $existingOrder->user_id === $userId
                    ? ['order' => $existingOrder]
                    : ['error' => 'No se pudo confirmar este pedido.'];
            }

            foreach ($quantities as $id => $quantity) {
                $product = $products->get($id);

                if (! $product || ! $product->is_active) {
                    return ['error' => 'Uno o más productos de tu carrito ya no están disponibles.'];
                }

                if ($product->stock < $quantity) {
                    return ['error' => 'Existencias insuficientes para '.$product->name.'.'];
                }

                if ($product->price === null || ! is_numeric($product->price) || $product->price < 0) {
                    return ['error' => 'El precio de '.$product->name.' no es válido.'];
                }
            }

            $total = 0;
            foreach ($quantities as $id => $quantity) {
                $total += $products->get($id)->price * $quantity;
            }

            $order = Order::firstOrCreate(
                ['checkout_token' => $checkoutToken],
                [
                    'user_id' => $userId,
                    'total' => $total,
                    'status' => 'pending_payment',
                    'payment_method' => $paymentMethod,
                ]
            );

            if (! $order->wasRecentlyCreated) {
                return $order->user_id === $userId
                    ? ['order' => $order]
                    : ['error' => 'No se pudo confirmar este pedido.'];
            }

            foreach ($quantities as $id => $quantity) {
                $product = $products->get($id);

                $product->decrement('stock', $quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            return ['order' => $order];
        });

        if (isset($result['error'])) {
            return $this->orderError($request, $result['error']);
        }

        $order = $result['order'];
        session()->forget(['cart', 'checkout_token', 'checkout_fingerprint']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => '¡Compra realizada con éxito!',
                'order_id' => $order->id,
            ], 200);
        }

        return redirect()->route('orders.success', $order->id)->with('success', 'Tu pedido fue registrado y está pendiente de confirmación de pago.');
    }

    private function checkoutTokenFor(array $quantities): string
    {
        ksort($quantities);
        $fingerprint = hash('sha256', json_encode($quantities, JSON_THROW_ON_ERROR));
        $token = session('checkout_token');

        if (! is_string($token) || $token === '' || ! hash_equals((string) session('checkout_fingerprint', ''), $fingerprint)) {
            $token = (string) Str::uuid();
            session([
                'checkout_token' => $token,
                'checkout_fingerprint' => $fingerprint,
            ]);
        }

        return $token;
    }

    private function orderError(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['error' => $message], 400);
        }

        return redirect()->route('cart.index')->with('error', $message);
    }

    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.success', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // Delete related items first to avoid foreign key constraints issues (if not cascading)
        $order->items()->delete();
        $order->delete();

        return redirect()->back()->with('success', 'Pedido eliminado correctamente.');
    }

    public function confirmPayment(Order $order): RedirectResponse
    {
        if ($order->status === 'pending_payment') {
            $order->update(['status' => 'completed']);
        }

        return redirect()->route('orders.admin')->with('success', 'El pago del pedido fue confirmado.');
    }
}
