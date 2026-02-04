<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Recommended for file handling

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index', [
            'title' => 'Products',
            'datas' => Product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', [
            'title' => 'Create Product',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|max:255|unique:products,serial_number',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'expiration_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $picturePath = null;
        
        // CORRECTION: Saving to 'images/products'
        if ($request->hasFile('picture')) {
            $fileName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images/products'), $fileName);
            $picturePath = $fileName;
        }

        $validated['picture'] = $picturePath;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product data has been successfully saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', [
            'product' => $product,
            'title' => 'Edit Product'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'serial_number' => 'required|string|max:255|unique:products,serial_number,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'expiration_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $oldName = $product->name;

        $validated = $request->only([
            'serial_number',
            'name',
            'type',
            'expiration_date',
            'price',
            'stock'
        ]);

        // Handle picture upload
        if ($request->hasFile('picture')) {
            // CORRECTION: Delete from 'images/products'
            if ($product->picture && file_exists(public_path('images/products/' . $product->picture))) {
                unlink(public_path('images/products/' . $product->picture));
            }
            
            // CORRECTION: Save to 'images/products'
            $fileName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images/products'), $fileName);
            $validated['picture'] = $fileName;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'The Product Data, ' . $oldName . ' become ' . $request->name . ', has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // CORRECTION: Delete from 'images/products'
        if ($product->picture && file_exists(public_path('images/products/' . $product->picture))) {
            unlink(public_path('images/products/' . $product->picture));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product data has been successfully deleted!');
    }
}