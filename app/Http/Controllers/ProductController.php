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
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['images', 'discount'])->firstOrFail();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $discountedPrice = $product->price;
        if ($product->discount) {
            $discountedPrice = $product->discount->type === 'percent'
                ? $product->price * (1 - $product->discount->amount / 100)
                : $product->price - $product->discount->amount;
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => [
                'full' => $product->price,
                'discounted' => $product->getDiscountedPrice(),
            ],
            'discount' => [
                'amount' => $product->discount ? $product->discount->amount : 0,
                'type' => $product->discount ? $product->discount->type : null,
            ],
            'images' => $product->images->pluck('path'),
        ]);
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
                'discounted' => $product->getDiscountedPrice(),
            ],
            'discount' => $product->discount ? [
                'type' => $product->discount->type,
                'amount' => $product->discount->discount,
            ] : null,
            'images' => $product->images->pluck('path'),
        ];
    }
}
