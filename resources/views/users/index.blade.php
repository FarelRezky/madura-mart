@extends('layout.master')

@section('title', 'Manajemen Pengguna - Madura Mart')

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
        @if (session('error'))
            <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header pb-0 bg-white d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-2 mb-md-0 font-weight-bolder text-dark">
                    <i class="fas fa-users text-primary me-2"></i>Daftar Pengguna
                </h5>
                <div class="d-flex align-items-center flex-wrap">
                    <form action="{{ route('admin.users') }}" method="GET" class="mb-2 mb-md-0">
                        <div class="input-group me-3 border-radius-md" style="width: 250px;">
                            <span class="input-group-text text-body border-0 bg-gray-100">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-gray-100" placeholder="Cari email / nama...">
                        </div>
                    </form>
                    <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0 text-nowrap hover-scale">
                        <i class="fas fa-plus me-2"></i> Tambah User Baru
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 mt-3">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Informasi Akun</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role Akses</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl Bergabung</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="transition-base">
                                <td>
                                    <div class="d-flex px-3 py-1 align-items-center">
                                        <div>
                                            <div class="avatar avatar-sm me-3 rounded-circle shadow bg-gradient-info d-flex align-items-center justify-content-center">
                                                <span class="text-white font-weight-bold text-xs">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($user->role) {
                                            'admin' => 'bg-gradient-primary',
                                            'customer' => 'bg-gradient-info',
                                            'courier' => 'bg-gradient-warning',
                                            default => 'bg-gradient-secondary',
                                        };
                                    @endphp
                                    <span class="badge badge-sm {{ $badgeColor }} px-3 py-2 shadow-sm">{{ strtoupper($user->role) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="align-middle text-center">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-link text-info text-gradient px-3 mb-0">
        <i class="fas fa-pencil-alt me-2"></i>Edit
    </a>
    
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
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
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data pengguna ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-base { transition: all 0.2s ease-in-out; }
    tbody tr:hover { background-color: #f8f9fa !important; transform: scale(1.005); }
    .hover-text-info:hover i { color: #17c1e8 !important; }
    .hover-text-danger:hover i { color: #ea0606 !important; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }
</style>
@endsection