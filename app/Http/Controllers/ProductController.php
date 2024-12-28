<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cacheKey = 'products:' . md5($request->fullUrl());

        $products = Cache::remember($cacheKey, 600, function () use ($request) {
            Log::info("Fetching products from database");

            $query = Product::query();

            if ($request->has('name')) {
                $query->filterByName($request->name);
            }

            if ($request->has('min_price') && $request->has('max_price')) {
                $query->filterByPrice($request->min_price, $request->max_price);
            }

            if ($request->has('min_amount') && $request->has('max_amount')) {
                $query->filterByAmount($request->min_amount, $request->max_amount);
            }

            return $query->get();
        });

        // If products are found in the cache, it's a cache hit. Log this after getting the products.
        Log::info("Cache hit for key: " . $cacheKey);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'amount' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        Cache::forget('products:' . md5($request->fullUrl()));

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check if the product is cached first
        $cacheKey = 'product:' . $id;
        $product = Cache::remember($cacheKey, 3600, function () use ($id) {
            return Product::findOrFail($id);
        });

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the product
        $product = Product::findOrFail($id);

        // Validate the input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'amount' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        Cache::forget('product:' . $id);
        Cache::forget('products:' . md5($request->fullUrl()));

        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        Cache::forget('product:' . $id);
        Cache::forget('products:' . md5(request()->fullUrl()));

        return response()->json(['message' => 'Product deleted'], 200);
    }
}

