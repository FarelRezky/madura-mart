@extends('layout.master')

@section('title', 'Edit User - Madura Mart')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-9 m-auto mt-4">
        <div class="card shadow-lg border-0 mb-4 rounded-4">
            
            <div class="card-header pb-0 text-left bg-transparent mt-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="font-weight-bolder text-info text-gradient mb-0">Edit Profil Pengguna</h3>
                        <p class="mb-0 text-sm text-secondary">Perbarui informasi detail dan hak akses untuk akun ini.</p>
                    </div>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm bg-gradient-secondary mb-0 hover-scale mt-3 mt-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <div class="row mb-4 align-items-center bg-gray-100 py-3 mx-0 rounded-3">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative bg-gradient-info d-flex align-items-center justify-content-center shadow-sm">
                            <span class="text-white font-weight-bold text-xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-0">{{ $user->name }}</h5>
                            <p class="mb-0 text-sm text-secondary mt-1">
                                <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                            </p>
                            <span class="badge badge-sm bg-gradient-dark mt-2 shadow-sm px-3 py-1">{{ strtoupper($user->role) }}</span>
                        </div>
                    </div>
                </div>

                <hr class="horizontal dark mt-0 mb-4">

                @if ($errors->any())
                    <div class="alert alert-danger text-white text-sm rounded-3 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="name" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nama Lengkap</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-user text-info"></i></span>
                                    <input class="form-control focus-info border-start-0 ps-0" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Alamat Email</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope text-info"></i></span>
                                    <input class="form-control focus-info border-start-0 ps-0" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="role" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Role / Hak Akses</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-user-shield text-info"></i></span>
                                    <select class="form-control focus-info border-start-0 ps-0" name="role" id="role" required>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="courier" {{ old('role', $user->role) == 'courier' ? 'selected' : '' }}>Courier / Kurir</option>
                                        <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="password" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">
                                    Password Baru <span class="text-danger text-xxs text-lowercase fw-normal">(Kosongkan jika tidak diubah)</span>
                                </label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-lock text-info"></i></span>
                                    <input class="form-control focus-info border-start-0 ps-0" type="password" name="password" id="password" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark mt-2 mb-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-info btn-lg mb-0 hover-scale px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animasi Tombol */
    .hover-scale { transition: transform 0.2s ease-in-out; }
    .hover-scale:hover { transform: translateY(-3px); box-shadow: 0 7px 14px rgba(0,0,0,0.1) !important; }
    
    /* Highlight biru saat input diklik */
    .focus-info:focus { 
        border-color: #17c1e8 !important; 
        box-shadow: none !important; 
    }
    
    /* Hilangkan border kiri agar menyatu dengan icon */
    .input-group > .form-control:focus {
        border-left-color: transparent !important;
    }
</style>
@endsection