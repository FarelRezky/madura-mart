@extends('layout.master')

{{-- Paksa panggil menu di sini jika master-mu tidak otomatis memanggilnya --}}
@section('menu')
    @include('layout.menu')
@endsection

{{-- Kita coba pakai section 'content' karena ini standar Laravel --}}
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root { --soft-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); }
    .card-modern { border: none; border-radius: 16px; box-shadow: var(--soft-shadow); background: #fff; margin-top: 20px; }
    
    /* SCROLLING TABLE */
    .table-wrapper { width: 100%; overflow-x: auto !important; padding-bottom: 15px; }
    .table-wrapper::-webkit-scrollbar { height: 10px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

    .table-modern { width: max-content !important; min-width: 100%; border-collapse: collapse; }
    .table-modern th, .table-modern td {
        white-space: nowrap !important;
        padding: 1.2rem 1.5rem;
        min-width: 150px !important;
        vertical-align: middle;
    }
    .table-modern thead th { background-color: #f8f9fa; color: #8392ab; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
</style>

<div class="container-fluid py-4">
    <div class="row">
        {{-- Jika menu sampingmu hilang, coba cek apakah kamu butuh div sidebar di sini --}}
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 font-weight-bolder">Data Delivery</h5>
                        <p class="text-sm text-muted mb-0">Shipping & Delivery Management</p>
                    </div>
                    <a href="{{ route('delivery.create') }}" class="btn btn-primary btn-sm mb-0 shadow-sm" style="background-image: linear-gradient(310deg, #cb0c9f 0%, #cb0c9f 100%);">
                        <i class="fas fa-plus me-2"></i>Tambah Delivery
                    </a>
                </div>

                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-wrapper px-4">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th style="min-width: 80px !important;">No</th>
                                    <th>Resi / Kode</th>
                                    <th>Kurir</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
    @php $final_data = $deliveries ?? []; @endphp

    @forelse($final_data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td class="font-weight-bold text-dark">{{ $item->resi_kode }}</td>
            <td>{{ $item->kurir }}</td>
            <td>
                <span class="badge bg-gradient-success">{{ $item->status }}</span>
            </td>
            <td class="text-center">
                {{-- TOMBOL EDIT --}}
                <a href="{{ route('delivery.edit', $item->id) }}" class="btn btn-link text-warning p-0 me-3 mb-0">
                    <i class="fas fa-edit fs-5"></i>
                </a>
                
                {{-- TOMBOL HAPUS (Wajib pake Form) --}}
                <form action="{{ route('delivery.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data resi {{ $item->resi_kode }}?')">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger p-0 mb-0">
                        <i class="fas fa-trash fs-5"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data delivery.</td>
        </tr>
    @endforelse
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection