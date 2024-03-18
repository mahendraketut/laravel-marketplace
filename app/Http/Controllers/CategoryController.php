<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('nama', 'like', '%i%');
        }])->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        if ($request->validated()) {
            $category = Category::create($request->all());
            return response()->json(['message' => 'Category berhasil ditambahkan', 'category' => $category], 201);
        } else {
            return response()->json(['message' => $request->errors()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        if ($request->validated()) {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return response()->json(['message' => 'Category berhasil diupdate', 'category' => $category], 201);
        } else {
            return response()->json(['message' => $request->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category berhasil dihapus']);
    }

    /**
     * Display the removed data
     */
    public function trash()
    {
        $categories = Category::onlyTrashed()->get();
        return response()->json(['categories' => $categories]);
    }

    /**
     * Restore the specified resource
     */
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return response()->json(['message' => 'Category berhasil direstore']);
    }
}
