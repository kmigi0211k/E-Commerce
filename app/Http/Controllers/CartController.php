<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cart",
     *     summary="Ver el contenido del carrito",
     *     tags={"Carrito"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Contenido del carrito",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="cart", type="object"),
     *             @OA\Property(property="total", type="number")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $cart = is_array($cart) ? $cart : [];
        $productIds = array_filter(array_keys($cart), fn ($id) => filter_var($id, FILTER_VALIDATE_INT) !== false);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $total = 0;

        foreach ($cart as $id => &$item) {
            $item = is_array($item) ? $item : [];
            $product = $products->get($id);
            $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);

            if ($product) {
                $item['name'] = $product->name;
                $item['price'] = $product->price;
                $item['image'] = $product->image;
                $item['stock'] = $product->stock;
            }

            $item['available'] = $product
                && $product->is_active
                && $product->price !== null
                && is_numeric($product->price)
                && $product->price >= 0
                && $quantity !== false
                && $quantity > 0
                && $product->stock >= $quantity;

            if ($product && $product->price !== null && $product->price >= 0 && $quantity !== false && $quantity > 0) {
                $total += $product->price * $quantity;
            }
        }
        unset($item);
        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json(['cart' => $cart, 'total' => $total]);
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * @OA\Post(
     *     path="/cart/add/{product}",
     *     summary="Añadir un producto al carrito",
     *     tags={"Carrito"},
     *
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Producto añadido"),
     *     @OA\Response(response=400, description="Stock insuficiente")
     * )
     */
    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        if ($product->price === null || ! is_numeric($product->price) || $product->price < 0) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'El precio de este producto no es válido.'], 422);
            }

            return redirect()->back()->with('error', 'No es posible añadir este producto porque su precio no es válido.');
        }

        $cart = session()->get('cart', []);

        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;

        if ($currentQty + 1 > $product->stock) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Stock insuficiente'], 400);
            }

            return redirect()->back()->with('error', 'Lo sentimos, solo quedan '.$product->stock.' unidades disponibles de este producto.');
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);
        $this->refreshCheckoutToken($cart);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Producto añadido', 'cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $product = Product::findOrFail($data['id']);
        $cart = session()->get('cart', []);

        if (! $product->is_active || $data['quantity'] > $product->stock) {
            return response()->json([
                'success' => false,
                'error' => 'La cantidad solicitada no está disponible.',
            ], 422);
        }

        if (! isset($cart[$product->id])) {
            return response()->json(['success' => false, 'error' => 'El producto no está en tu carrito.'], 404);
        }

        $cart[$product->id]['quantity'] = $data['quantity'];
        $cart[$product->id]['name'] = $product->name;
        $cart[$product->id]['price'] = $product->price;
        $cart[$product->id]['image'] = $product->image;
        $cart[$product->id]['stock'] = $product->stock;
        session()->put('cart', $cart);
        $this->refreshCheckoutToken($cart);

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $cart = session()->get('cart', []);

        if (isset($cart[$data['id']])) {
            unset($cart[$data['id']]);
            session()->put('cart', $cart);
            $this->refreshCheckoutToken($cart);
        }

        if (! $request->expectsJson()) {
            return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
        }

        return response()->json(['success' => true]);
    }

    public function clear()
    {
        session()->forget('cart');
        $this->forgetCheckoutToken();

        return redirect()->route('cart.index')->with('success', 'Carrito vaciado.');
    }

    private function forgetCheckoutToken(): void
    {
        session()->forget(['checkout_token', 'checkout_fingerprint']);
    }

    private function refreshCheckoutToken(array $cart): void
    {
        $quantities = [];
        foreach ($cart as $id => $item) {
            $productId = filter_var($id, FILTER_VALIDATE_INT);
            $quantity = filter_var(is_array($item) ? ($item['quantity'] ?? null) : null, FILTER_VALIDATE_INT);

            if ($productId === false || $productId < 1 || $quantity === false || $quantity < 1) {
                $this->forgetCheckoutToken();

                return;
            }

            $quantities[$productId] = $quantity;
        }

        ksort($quantities);
        $fingerprint = hash('sha256', json_encode($quantities, JSON_THROW_ON_ERROR));
        session([
            'checkout_token' => (string) Str::uuid(),
            'checkout_fingerprint' => $fingerprint,
        ]);
    }
}
