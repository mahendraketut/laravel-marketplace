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

        return $this->showResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        if ($request->validated()) {
            $product = Product::create($request->all());
            return $this->createdResponse($product->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $product->category_id = $product->category->name;
        return $this->showResponse($product->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        if ($request->validated()) {
            $product = Product::findOrFail($id);

            $product->update($request->all());
            return $this->updatedResponse($product->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->notFoundResponse();
        }
        $product->delete();
        return $this->deletedResponse($product->toArray());
    }

    /**
     * get all products that are deleted
     */
    public function getSoftDelete()
    {
        $softDeletedProducts = Product::onlyTrashed()->get();

        if ($softDeletedProducts->isEmpty()) {
            return $this->notFoundResponse();
        }

        return $this->showResponse($softDeletedProducts);
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
                return $this->restoredResponse($product->toArray());
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
