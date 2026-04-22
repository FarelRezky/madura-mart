@extends('layout.master')

@section('title', 'Tambah User - Madura Mart')

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
                        <h3 class="font-weight-bolder text-primary text-gradient mb-0">
                            <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                        </h3>
                        <p class="mb-0 text-sm text-secondary">Isi formulir di bawah ini untuk mendaftarkan akun ke dalam sistem.</p>
                    </div>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm bg-gradient-secondary mb-0 hover-scale mt-3 mt-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <hr class="horizontal dark mt-0 mb-4">

                <div class="alert alert-light border-radius-md border border-light mb-4 shadow-sm d-flex align-items-center">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-primary text-center me-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-info text-white"></i>
                    </div>
                    <span class="text-sm text-dark font-weight-bold">Pastikan alamat email yang didaftarkan aktif dan belum pernah digunakan sebelumnya. Default password minimal 8 karakter.</span>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger text-white text-sm rounded-3 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="name" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-user text-primary"></i></span>
                                    <input class="form-control focus-primary border-start-0 ps-0" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Farel Rezky" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope text-primary"></i></span>
                                    <input class="form-control focus-primary border-start-0 ps-0" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="role" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Role / Hak Akses <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-user-shield text-primary"></i></span>
                                    <select class="form-control focus-primary border-start-0 ps-0" name="role" id="role" required>
                                        <option value="" disabled selected>-- Pilih Hak Akses --</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="courier" {{ old('role') == 'courier' ? 'selected' : '' }}>Courier / Kurir</option>
                                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="password" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Password <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-lock text-primary"></i></span>
                                    <input class="form-control focus-primary border-start-0 ps-0" type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark mt-2 mb-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-primary btn-lg mb-0 hover-scale px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan User Baru
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
    
    /* Highlight gradient template saat input diklik */
    .focus-primary:focus { 
        border-color: #cb0c9f !important; 
        box-shadow: none !important; 
    }
    
    /* Hilangkan border kiri agar menyatu dengan icon */
    .input-group > .form-control:focus {
        border-left-color: transparent !important;
    }
</style>
@endsection