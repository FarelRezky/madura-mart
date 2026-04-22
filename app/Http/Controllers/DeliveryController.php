<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery; // WAJIB ADA agar tidak error 'Class Delivery not found'

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Delivery';
        // Ambil data dari database agar tabel tidak kosong
        $deliveries = Delivery::latest()->get(); 
        return view('delivery.index', compact('title', 'deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Delivery';
        return view('delivery.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        // 'kode_resi' adalah nama input di FORM
        // 'resi_kode' adalah nama kolom di DATABASE
        'kode_resi' => 'required|string|max:255|unique:deliveries,resi_kode', 
        'kurir'     => 'required|string|max:255',
        'status'    => 'required|string'
    ]);

    // 2. Simpan
    \App\Models\Delivery::create([
        'resi_kode' => $request->kode_resi,
        'kurir'     => $request->kurir,
        'status'    => $request->status,
    ]);

    return redirect()->route('delivery.index')->with('success', 'Data Berhasil Disimpan!');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Delivery';
        $delivery = Delivery::findOrFail($id);
        return view('delivery.edit', compact('delivery', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);

        $request->validate([
            'kode_resi' => 'required|string|max:255|unique:deliveries,resi_kode,' . $id,
            'kurir'     => 'required|string|max:255',
            'status'    => 'required|string'
        ]);

        $delivery->update([
            'resi_kode' => $request->kode_resi,
            'kurir'     => $request->kurir,
            'status'    => $request->status,
        ]);

        return redirect()->route('delivery.index')->with('success', 'Data Delivery berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();

        return redirect()->route('delivery.index')->with('success', 'Data Delivery berhasil dihapus!');
    }
}