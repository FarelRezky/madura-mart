<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Distributor;

class PurchaseController extends Controller
{
    // ... (index, create, store methods remain the same) ...

    public function index()
    {
        return view('purchases.index', [
            'title' => 'Purchases',
            'datas' => Purchase::with(['distributor', 'details'])->latest()->get()
        ]);
    }

    public function create()
    {
        return view('purchases.create', [
            'title' => 'Create Purchase',
            'products' => Product::all(),
            'distributors' => Distributor::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'note_number' => 'required|string|max:15|unique:purchases,note_number',
            'purchase_date' => 'required|date',
            'distributor_id' => 'required|exists:distributors,id',
            'total_price' => 'required|integer|min:0',
            'products' => 'required|array|min:1',
            'products.*.serial_number' => 'required|exists:products,serial_number',
            'products.*.purchase_price' => 'required|integer|min:0',
            'products.*.selling_margin' => 'required|integer|min:0',
            'products.*.purchase_amount' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'note_number' => $validated['note_number'],
                'purchase_date' => $validated['purchase_date'],
                'distributor_id' => $validated['distributor_id'],
                'total_price' => $validated['total_price'],
            ]);

            foreach ($validated['products'] as $productData) {
                $subtotal = $productData['purchase_price'] * $productData['purchase_amount'];
                PurchaseDetail::create([
                    'note_number_purchase' => $purchase->note_number,
                    'serial_number_product' => $productData['serial_number'],
                    'purchase_price' => $productData['purchase_price'],
                    'selling_margin' => $productData['selling_margin'],
                    'purchase_amount' => $productData['purchase_amount'],
                    'subtotal' => $subtotal,
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully');
    }

    public function edit(string $id)
    {
        // We load details so we can display existing items in the edit form
        $purchase = Purchase::with('details')->findOrFail($id);
        return view('purchases.edit', [
            'purchase' => $purchase,
            'title' => 'Edit Purchase',
            'products' => Product::all(),
            'distributors' => Distributor::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            // Note number validation ignores current ID
            'note_number' => 'required|string|max:15|unique:purchases,note_number,' . $id,
            'purchase_date' => 'required|date',
            'distributor_id' => 'required|exists:distributors,id',
            'total_price' => 'required|integer|min:0',
            'products' => 'required|array|min:1',
            'products.*.serial_number' => 'required|exists:products,serial_number',
            'products.*.purchase_price' => 'required|integer|min:0',
            'products.*.selling_margin' => 'required|integer|min:0',
            'products.*.purchase_amount' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($purchase, $validated) {
            // 1. Update Header
            $purchase->update([
                'note_number' => $validated['note_number'],
                'purchase_date' => $validated['purchase_date'],
                'distributor_id' => $validated['distributor_id'],
                'total_price' => $validated['total_price'],
            ]);

            // 2. Delete ALL existing items for this purchase
            \App\Models\PurchaseDetail::where('note_number_purchase', $purchase->note_number)->delete();

            // 3. Create NEW items from the form data
            // (If you deleted a row in the HTML, it is not in $validated['products'], so it won't be recreated here)
            foreach ($validated['products'] as $productData) {
                $subtotal = $productData['purchase_price'] * $productData['purchase_amount'];
                
                \App\Models\PurchaseDetail::create([
                    'note_number_purchase' => $purchase->note_number,
                    'serial_number_product' => $productData['serial_number'],
                    'purchase_price' => $productData['purchase_price'],
                    'selling_margin' => $productData['selling_margin'],
                    'purchase_amount' => $productData['purchase_amount'],
                    'subtotal' => $subtotal,
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully');
    }

    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        // Cascade delete details first (safeguard)
        PurchaseDetail::where('note_number_purchase', $purchase->note_number)->delete();
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully');
    }
}