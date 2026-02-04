<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'title' => 'Products',
            'datas' => Product::all()
        ]);
    }

    public function create()
    {
        return view('products.create', [
            'title' => 'Create Product',
        ]);
    }

    public function store(Request $request)
    {
        // (This method remains the same as before)
        $validated = $request->validate([
            'serial_number' => 'required|string|max:255|unique:products,serial_number',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'expiration_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $fileName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images/products'), $fileName);
            $validated['picture'] = $fileName;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', [
            'product' => $product,
            'title' => 'Edit Product'
        ]);
    }

    // --- UPDATED UPDATE METHOD ---
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
        
        // Start with basic data
        $validated = $request->except(['picture', 'delete_picture', '_token', '_method']);

        // Path to current image
        $currentImagePath = public_path('images/products/' . $product->picture);

        // --- LOGIC 1: Handle Explicit Deletion Request ---
        // This runs if the user clicked "Remove Image" in the view
        if ($request->input('delete_picture') == '1') {
            if (!empty($product->picture) && file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }
            // Set picture column to null in database
            $validated['picture'] = null;
        }

        // --- LOGIC 2: Handle New File Upload ---
        // This runs if the user selected a new file (overrides deletion if both happen)
        if ($request->hasFile('picture')) {
            // Delete old image if it exists and hasn't just been deleted above
            if (!empty($product->picture) && file_exists($currentImagePath)) {
                 unlink($currentImagePath);
            }

            // Upload new image
            $fileName = time() . '_' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('images/products'), $fileName);
            
            // Set new filename for database
            $validated['picture'] = $fileName;
        }

        // Update the database record
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $imagePath = public_path('images/products/' . $product->picture);
        
        if ($product->picture && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}