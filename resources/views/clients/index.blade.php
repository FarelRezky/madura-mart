@extends('layout.master')

@section('title', 'Manajemen Klien - Madura Mart')

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
                    <i class="fas fa-handshake text-success me-2"></i>Daftar Klien Bisnis
                </h5>
                <div class="d-flex align-items-center flex-wrap">
                    <form action="{{ route('clients.index') }}" method="GET" class="mb-2 mb-md-0">
                        <div class="input-group me-3 border-radius-md" style="width: 250px;">
                            <span class="input-group-text text-body border-0 bg-gray-100">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-gray-100" placeholder="Cari klien...">
                        </div>
                    </form>
                    <a href="{{ route('clients.create') }}" class="btn bg-gradient-success btn-sm mb-0 text-nowrap hover-scale">
                        <i class="fas fa-plus me-2"></i> Tambah Klien
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 mt-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Informasi Klien</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kontak (HP)</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr class="transition-base">
                                <td>
                                    <div class="d-flex px-3 py-1 align-items-center">
                                        <div>
                                            <div class="avatar avatar-sm me-3 rounded-circle shadow bg-gradient-success d-flex align-items-center justify-content-center">
                                                <span class="text-white font-weight-bold text-xs">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $client->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $client->email ?? 'Tidak ada email' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-weight-bold text-dark">{{ $client->phone ?? '-' }}</span>
                                </td>
                                <td>
                                    <p class="text-xs text-secondary mb-0 text-truncate" style="max-width: 250px;">
                                        {{ $client->address ?? 'Alamat belum diisi' }}
                                    </p>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-link text-info text-gradient px-3 mb-0">
                                        <i class="fas fa-pencil-alt me-2"></i>Edit
                                    </a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus klien ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                            <i class="far fa-trash-alt me-2"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data klien yang didaftarkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 mt-4">
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-base { transition: all 0.2s ease-in-out; }
    tbody tr:hover { background-color: #f8f9fa !important; transform: scale(1.005); }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }
</style>
@endsection