<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Traits\JsonResponseTrait;

class BrandController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        $data = [
            'brands' => $brands
        ];

        $meta = [
            'TotalBrands' => $brands->count(),
            'ActionAt' => now(),
            'Message' => 'Data berhasil diambil'
        ];

        return $this->formatJsonResponse('Data berhasil diambil', $data, $meta, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        if ($request->validated()) {
            $brand = Brand::create($request->all());
            $data = [
                'brand' => $brand
            ];
            $meta = [
                'ActionAt' => now(),
                'Message' => 'Brand berhasil ditambahkan'
            ];
            return $this->formatJsonResponse('Brand berhasil ditambahkan', $data, $meta, 201);
        } else {
            return $this->formatJsonResponse('Data gagal ditambahkan', $request, $request->errors(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = [
            'brand' => $brand
        ];
        $meta = [
            'ActionAt' => now(),
            'Message' => 'Data berhasil diambil'
        ];
        return $this->formatJsonResponse('Data berhasil diambil', $data, $meta, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        if ($request->validated()) {
            $brand = Brand::findOrFail($id);
            $brand->update($request->all());
            $data = [
                'brand' => $brand
            ];
            $meta = [
                'ActionAt' => now(),
                'Message' => 'Brand berhasil diupdate'
            ];
            return $this->formatJsonResponse('Brand berhasil diupdate', $data, $meta, 200);
        } else {
            return $this->formatJsonResponse('Data gagal diupdate', $request, $request->errors(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        $data = [
            'brand' => $brand
        ];
        $meta = [
            'ActionAt' => now(),
            'Message' => 'Brand berhasil dihapus'
        ];
        return $this->formatJsonResponse('Brand berhasil dihapus', $data, $meta, 200);
    }

    /**
     * Display the removed data
     */
    public function trash()
    {
        $brands = Brand::onlyTrashed()->get();
        $data = [
            'brands' => $brands
        ];
        $meta = [
            'ActionAt' => now(),
            'Message' => 'Data berhasil diambil'
        ];
        return $this->formatJsonResponse('Data berhasil diambil', $data, $meta, 200);
    }

    /**
     * Restore the specified resource
     */
    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();
        $data = [
            'brand' => $brand
        ];
        $meta = [
            'ActionAt' => now(),
            'Message' => 'Brand berhasil direstore'
        ];
        return $this->formatJsonResponse('Brand berhasil direstore', $data, $meta, 200);
    }
}
