<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|max:60',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'required|integer|max:100',
            'short_description' => 'nullable|string|max:150',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);


        $product = new Product;
        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->quantity = $validated['quantity'];
        $product->short_description = $validated['short_description'] ?? null;
        $product->save();

        $product->categories()->attach($validated['categories']);

        if ($product->exists) {
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'product_name' => $product->name 
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product.'
            ], 500);
        }
    }

}
