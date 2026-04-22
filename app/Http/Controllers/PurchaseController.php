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
public function index()
    {
        return view('purchases.index', [
            'title' => 'Purchases',
            'purchases' => Purchase::with(['distributor', 'details.product'])->latest()->get()
        ]);
    }

    public function create()
    {
        $title = 'Create Purchase';
        
        $distributors = \App\Models\Distributor::all(); 
        $products = \App\Models\Product::all(); 

        return view('purchases.create', compact('title', 'distributors', 'products'));
    }

    public function store(Request $request)
    {
        // 1. Tambahkan validasi untuk expired_date dan selling_price
        $validated = $request->validate([
            'note_number' => 'required|string|max:15|unique:purchases,note_number',
            'purchase_date' => 'required|date',
            'distributor_id' => 'required|exists:distributors,id',
            'total_price' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.serial_number' => 'required|exists:products,serial_number',
            'products.*.expired_date' => 'required|date', // <-- BARU
            'products.*.purchase_price' => 'required|numeric|min:0',
            'products.*.selling_margin' => 'required|numeric|min:0',
            'products.*.selling_price' => 'required|numeric|min:0', // <-- BARU
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
                
                // 2. Simpan juga data expired_date dan selling_price ke tabel detail
                PurchaseDetail::create([
                    'note_number_purchase' => $purchase->note_number,
                    'serial_number_product' => $productData['serial_number'],
                    'expired_date' => $productData['expired_date'], // <-- BARU
                    'purchase_price' => $productData['purchase_price'],
                    'selling_margin' => $productData['selling_margin'],
                    'selling_price' => $productData['selling_price'], // <-- BARU
                    'purchase_amount' => $productData['purchase_amount'],
                    'subtotal' => $subtotal,
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully');
    }

    public function edit(string $id)
    {
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
            'note_number' => 'required|string|max:15|unique:purchases,note_number,' . $id,
            'purchase_date' => 'required|date',
            'distributor_id' => 'required|exists:distributors,id',
            'total_price' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.serial_number' => 'required|exists:products,serial_number',
            'products.*.expired_date' => 'required|date', // <-- BARU
            'products.*.purchase_price' => 'required|numeric|min:0',
            'products.*.selling_margin' => 'required|numeric|min:0',
            'products.*.selling_price' => 'required|numeric|min:0', // <-- BARU
            'products.*.purchase_amount' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($purchase, $validated) {
            $purchase->update([
                'note_number' => $validated['note_number'],
                'purchase_date' => $validated['purchase_date'],
                'distributor_id' => $validated['distributor_id'],
                'total_price' => $validated['total_price'],
            ]);

            \App\Models\PurchaseDetail::where('note_number_purchase', $purchase->note_number)->delete();

            foreach ($validated['products'] as $productData) {
                $subtotal = $productData['purchase_price'] * $productData['purchase_amount'];
                
                \App\Models\PurchaseDetail::create([
                    'note_number_purchase' => $purchase->note_number,
                    'serial_number_product' => $productData['serial_number'],
                    'expired_date' => $productData['expired_date'], // <-- BARU
                    'purchase_price' => $productData['purchase_price'],
                    'selling_margin' => $productData['selling_margin'],
                    'selling_price' => $productData['selling_price'], // <-- BARU
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
        PurchaseDetail::where('note_number_purchase', $purchase->note_number)->delete();
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully');
    }
}