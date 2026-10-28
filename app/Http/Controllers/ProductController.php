<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Listar todos los productos",
     *     tags={"Productos"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:name,price_asc,price_desc'],
        ]);

        $query = Product::where('is_active', true);
        $search = trim($filters['search'] ?? '');

        if ($search !== '') {
            $query->where(function ($products) use ($search) {
                $products->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        match ($filters['sort'] ?? null) {
            'name' => $query->orderBy('name'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        if ($request->wantsJson()) {
            return response()->json($query->get());
        }

        $products = $query->paginate(12)->withQueryString();
        $catalogProductsCount = Product::where('is_active', true)->count();
        $availableProductsCount = Product::where('is_active', true)->where('stock', '>', 0)->count();
        $featuredProducts = (clone $query)
            ->whereNotNull('image')
            ->take(8)
            ->get(['id', 'name', 'image', 'price']);

        return view('products.index', compact('products', 'catalogProductsCount', 'availableProductsCount', 'featuredProducts'));
    }

    public function show(Product $product)
    {
        if (! $product->is_active && (! Auth::check() || ! Auth::user()->isAdmin())) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    public function dashboard()
    {
        $products = Product::all();

        return view('products.dashboard', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 required={"name", "description", "price", "stock"},
     *
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="stock", type="integer"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Producto creado"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|file|max:10240',
            'image_url' => 'nullable|url:http,https|max:255',
        ]);

        if ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        } elseif ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image_url']);

        $data['hours_required'] = 4;
        $data['is_active'] = $request->has('is_active');

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()->route('products.dashboard')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|file|max:10240',
            'image_url' => 'nullable|url:http,https|max:255',
        ]);

        if ($request->filled('image_url')) {
            if ($product->image && ! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->image_url;
        } elseif ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image_url']);

        $data['is_active'] = $request->has('is_active');

        $product->update($data);

        return redirect()->route('products.dashboard')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.dashboard')->with('success', 'Producto eliminado exitosamente.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return redirect()->back()->with('success', 'Estado del producto actualizado.');
    }

    public function comunidad()
    {
        $products = Product::all();

        return view('products.comunidad', compact('products'));
    }
}
