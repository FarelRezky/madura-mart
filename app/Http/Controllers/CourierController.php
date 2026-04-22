<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;

class CourierController extends Controller
{
    public function index() {
        $title = 'Data Kurir';
        $couriers = Courier::latest()->get();
        return view('couriers.index', compact('couriers', 'title'));
    }

    public function create() {
        $title = 'Tambah Kurir';
        return view('couriers.create', compact('title'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'vehicle_number' => 'nullable|string|max:15',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        Courier::create($request->all());
        return redirect()->route('couriers.index')->with('success', 'Data Kurir berhasil ditambahkan!');
    }

    public function destroy($id) {
        Courier::findOrFail($id)->delete();
        return back()->with('success', 'Data Kurir berhasil dihapus!');
    }
}