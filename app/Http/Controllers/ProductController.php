<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['images', 'discount'])->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string|unique:products',
            'price' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['images', 'discount'])->findOrFail($id);
        return response()->json($this->formatProductResponse($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'slug' => 'string|unique:products,slug,'.$id,
            'price' => 'integer',
            'active' => 'boolean',
        ]);

        $product->update($validatedData);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }

    private function formatProductResponse($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'slug' => $product->slug,
            'price' => [
                'full' => $product->price,
                'discounted' => $product->discount ? $this->calculateDiscountedPrice($product) : $product->price,
            ],
            'discount' => $product->discount ? [
                'type' => $product->discount->type,
                'amount' => $product->discount->discount,
            ] : null,
            'images' => $product->images->pluck('path'),
        ];
    }

    private function calculateDiscountedPrice($product)
    {
        if ($product->discount->type === 'percent') {
            return $product->price * (100 - $product->discount->discount) / 100;
        } else {
            return $product->price - $product->discount->discount;
        }
    }

    public function showBySlug($slug)
{
    $product = Product::with(['images', 'discount'])->where('slug', $slug)->firstOrFail();
    return response()->json($this->formatProductResponse($product));
}
}
