<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\JsonResponseTrait;

class ProductController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category');

        // search mechanism
        if ($request->has('search') && $request->search !== '') {
            $products = $products->where('nama', 'like', '%' . $request->search . '%');
        }

        // sort mechanism. Allows sorting by name, and price in ascending or descending order
        if ($request->has('sort')) {
            $sortField = $request->input('sort', 'nama');  // default is to sort by name
            $sortDirection = $request->input('direction', 'asc');  // default is to sort in ascending order
            $products = $products->orderBy($sortField, $sortDirection);
        }

        // filter mechanism. Allows filtering by category id and price range
        // filter by category id
        if ($request->has('category_id')) {
            $products = $products->where('category_id', $request->category_id);
        }

        // filter by brand
        if ($request->has('brand_id')) {
            $products = $products->where('brand_id', $request->brand_id);
        }

        // filter by price range
        if ($request->has('price_min') && $request->has('price_max')) {
            $products = $products->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        // pagination mechanism
        $products = $products->paginate(10);   // get 10 products per page

        $data = [
            'products' => $products
        ];

        $meta = [
            'ActionAt' => now(),
            'Message' => "Data berhasil diambii",
            'ProductsCount' => count($products),
            'CurrentPage' => $products->currentPage(),
            'LastPage' => $products->lastPage(),
        ];

        return $this->formatJsonResponse('Data berhasil diambil', $data, $meta, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        if ($request->validated()) {
            $product = Product::create($request->all());
            return response()->json(['message' => 'Product berhasil ditambahkan', 'product' => $product], 201);
        } else {
            return response()->json(['message' => $request->errors()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $product->category_id = $product->category->name;
        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        if ($request->validated()) {
            $product = Product::findOrFail($id);

            $product->update($request->all());
            return response()->json(['message' => 'Product berhasil diupdate', 'product' => $product]);
        } else {
            return response()->json(['message' => $request->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product tidak ditemukan'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product berhasil dihapus']);
    }

    /**
     * get all products that are deleted
     */
    public function getSoftDelete()
    {
        $softDeletedProducts = Product::onlyTrashed()->get();

        if ($softDeletedProducts->isEmpty()) {
            return response()->json(['message' => 'Tidak ditemukan produk yang dihapus']);
        }

        return response()->json(['products' => $softDeletedProducts]);
    }

    /**
     * restore all products that are deleted
     */
    public function restoreSoftDelete($id)
    {
        // Logic to restore the soft-deleted product
        $product = Product::onlyTrashed()->find($id);

        try {
            if ($product->restore()) {
                return response()->json(['message' => 'Product berhasil direstore', 'product' => $product]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Product tidak ditemukan'], 404);
        }
    }
}