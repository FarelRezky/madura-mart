@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<style>
    :root { --soft-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); }
    .card-modern { border: none; border-radius: 16px; box-shadow: var(--soft-shadow); background: #fff; margin-top: 20px; }
    .table-wrapper { width: 100%; overflow-x: auto !important; padding-bottom: 15px; }
    .table-modern { width: max-content !important; min-width: 100%; border-collapse: collapse; }
    .table-modern th, .table-modern td { padding: 1.2rem 1.5rem; vertical-align: middle; white-space: nowrap !important; }
    .table-modern thead th { background-color: #f8f9fa; color: #8392ab; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 font-weight-bolder">Data Kurir / Ekspedisi</h5>
                        <p class="text-sm text-muted mb-0">Manajemen armada pengiriman Madura Mart.</p>
                    </div>
                    <a href="{{ route('couriers.create') }}" class="btn btn-primary btn-sm mb-0 shadow-sm" style="background-image: linear-gradient(310deg, #cb0c9f 0%, #cb0c9f 100%);">
                        <i class="fas fa-plus me-2"></i>Tambah Kurir
                    </a>
                </div>

                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-wrapper px-4">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kurir</th>
                                    <th>No. HP</th>
                                    <th>Plat Kendaraan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($couriers as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-weight-bold text-dark">{{ $item->name }}</td>
                                        <td>{{ $item->phone ?? '-' }}</td>
                                        <td>{{ $item->vehicle_number ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $item->status == 'Aktif' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('couriers.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kurir {{ $item->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 mb-0">
                                                    <i class="fas fa-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data kurir.</td>
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