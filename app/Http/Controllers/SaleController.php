<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sale, SaleDetail, Product};
use DB;

class SaleController extends Controller
{
    public function index() {
    $title = 'Data Penjualan'; 
    $sales = \App\Models\Sale::latest()->get();
    
    // PASTIKAN NAMA VIEWNYA BENAR
    // Kalau folder kamu namanya 'sale' (tanpa s), maka tulis 'sale.index'
    // Kalau folder kamu namanya 'sales' (pakai s), maka tulis 'sales.index'
    return view('sales.index', compact('sales', 'title')); 
}

    public function create() {
    $title = 'Tambah Penjualan'; // Tambahkan ini
    $products = \App\Models\Product::where('stock', '>', 0)->get();
    $next_number = 'SL-' . date('Ymd') . '-' . (\App\Models\Sale::count() + 1);
    return view('sales.create', compact('products', 'next_number', 'title'));
}

    public function store(Request $request) {
        $request->validate([
            'sale_date' => 'required|date',
            'products.*.serial' => 'required',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'sale_number' => $request->sale_number,
                'sale_date' => $request->sale_date,
                'total_price' => $request->total_price,
            ]);

            foreach ($request->products as $item) {
                $product = Product::where('serial_number', $item['serial'])->first();
                
                // Kurangi Stok
                $product->decrement('stock', $item['qty']);

                // Simpan Sale Detail (Sesuaikan nama kolom di sini)
                SaleDetail::create([
                    'sale_id'        => $sale->id, 
                    'product_serial' => $item['serial'],
                    'selling_price'  => $item['price'], // UBAH 'price' MENJADI 'selling_price'
                    'qty'            => $item['qty'],
                    'subtotal'       => $item['price'] * $item['qty'], 
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Penjualan Berhasil!');
    }

    public function edit($id) {
        $title = 'Edit Penjualan';
        $sale = Sale::with('details.product')->findOrFail($id);
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products', 'title'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.serial' => 'required',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        $sale = Sale::findOrFail($id);

        DB::transaction(function () use ($request, $sale) {
            // 1. Kembalikan stok lama
            foreach ($sale->details as $detail) {
                Product::where('serial_number', $detail->product_serial)->increment('stock', $detail->qty);
            }

            // 2. Hapus detail lama
            SaleDetail::where('sale_id', $sale->id)->delete();

            // 3. Tambah detail baru dan kurangi stok baru
            foreach ($request->products as $item) {
                $product = Product::where('serial_number', $item['serial'])->first();
                
                // Kurangi Stok
                $product->decrement('stock', $item['qty']);

                // Simpan Sale Detail
                SaleDetail::create([
                    'sale_id'        => $sale->id, 
                    'product_serial' => $item['serial'],
                    'selling_price'  => $item['price'],
                    'qty'            => $item['qty'],
                    'subtotal'       => $item['price'] * $item['qty'], 
                ]);
            }

            // 4. Update data penjualan utama
            $sale->update([
                'sale_date' => $request->sale_date,
                'total_price' => $request->total_price,
            ]);
        });

        return redirect()->route('sales.index')->with('success', 'Penjualan Berhasil Diperbarui!');
    }

    public function destroy($id) {
        $sale = Sale::findOrFail($id);
        // Tambahkan stok balik sebelum hapus
        foreach($sale->details as $detail) {
            Product::where('serial_number', $detail->product_serial)->increment('stock', $detail->qty);
        }
        $sale->delete();
        return back()->with('success', 'Data dihapus & Stok dikembalikan');
    }
}