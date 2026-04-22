<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Cari berdasarkan nomor order atau nama customer
        $orders = Order::when($search, function ($query, $search) {
            return $query->where('order_number', 'like', "%{$search}%")
                         ->orWhere('customer_name', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('orders.index', [
            'title' => 'Manajemen Order',
            'orders' => $orders
        ]);
    }

    public function create() { return view('orders.create', ['title' => 'Tambah Order']); }
    public function store(Request $request) { /* Nyusul di tahap 2 */ }
    public function edit(Order $order) { return view('orders.edit', ['title' => 'Edit Order', 'order' => $order]); }
    public function update(Request $request, Order $order) { /* Nyusul di tahap 2 */ }
    public function destroy(Order $order) {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus!');
    }
}