<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // 1. TAMPILKAN DATA & PENCARIAN
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Cari berdasarkan nama, email, atau telepon
        $clients = Client::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
        })->latest()->paginate(5);

        return view('clients.index', [
            'title' => 'Client Management',
            'clients' => $clients
        ]);
    }

    // 2. HALAMAN TAMBAH KLIEN
    public function create()
    {
        return view('clients.create', ['title' => 'Tambah Klien']);
    }

    // 3. PROSES SIMPAN KLIEN BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Klien berhasil ditambahkan!');
    }

    // 4. HALAMAN EDIT KLIEN
    public function edit(Client $client)
    {
        return view('clients.edit', [
            'title' => 'Edit Klien',
            'client' => $client
        ]);
    }

    // 5. PROSES UPDATE KLIEN
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Data klien berhasil diperbarui!');
    }

    // 6. PROSES HAPUS KLIEN
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Klien berhasil dihapus!');
    }
}