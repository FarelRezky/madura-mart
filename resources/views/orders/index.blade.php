@extends('layout.master')

@section('title', 'Manajemen Order - Madura Mart')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        
        @if (session('success'))
            <div class="alert alert-success text-white alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header pb-0 bg-white d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 mb-md-0 font-weight-bolder text-dark">
                    <i class="fas fa-shopping-basket text-warning me-2"></i>Daftar Order / Pesanan
                </h5>
                <div class="d-flex align-items-center flex-wrap">
                    <form action="{{ route('orders.index') }}" method="GET" class="mb-2 mb-md-0">
                        <div class="input-group me-3 border-radius-md" style="width: 250px;">
                            <span class="input-group-text text-body border-0 bg-gray-100">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-gray-100" placeholder="Cari No. Order / Nama...">
                        </div>
                    </form>
                    <a href="{{ route('orders.create') }}" class="btn bg-gradient-warning btn-sm mb-0 text-nowrap hover-scale text-white">
                        <i class="fas fa-plus me-2"></i> Buat Order Baru
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 mt-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Detail Pesanan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Harga</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr class="transition-base">
                                <td>
                                    <div class="d-flex px-3 py-1 align-items-center">
                                        <div>
                                            <div class="avatar avatar-sm me-3 rounded-circle shadow bg-gradient-warning d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box text-white text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $order->order_number ?? 'ORD-000' }}</h6>
                                            <p class="text-xs text-secondary mb-0"><i class="fas fa-user me-1"></i> {{ $order->customer_name ?? 'Pelanggan' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-weight-bold text-dark">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @php
                                        $statusColor = match(strtolower($order->status ?? 'pending')) {
                                            'pending' => 'bg-gradient-secondary',
                                            'processing' => 'bg-gradient-info',
                                            'completed' => 'bg-gradient-success',
                                            'cancelled' => 'bg-gradient-danger',
                                            default => 'bg-gradient-warning',
                                        };
                                    @endphp
                                    <span class="badge badge-sm {{ $statusColor }} shadow-sm">{{ strtoupper($order->status ?? 'Pending') }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-link text-info text-gradient px-3 mb-0" data-bs-toggle="tooltip" title="Edit Order">
                                        <i class="fas fa-pencil-alt me-2"></i>Edit
                                    </a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0" data-bs-toggle="tooltip" title="Hapus Order">
                                            <i class="far fa-trash-alt me-2"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 text-light"></i><br>
                                    Belum ada transaksi pesanan saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-base { transition: all 0.2s ease-in-out; }
    tbody tr:hover { background-color: #fcf8f2 !important; transform: scale(1.005); } /* Hover oranye tipis */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }
</style>
@endsection