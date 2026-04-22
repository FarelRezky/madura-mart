@extends('layout.master')

@section('title', 'Tambah Klien Bisnis - Madura Mart')

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
                        <h3 class="font-weight-bolder text-success text-gradient mb-0">
                            <i class="fas fa-building me-2"></i>Tambah Klien Bisnis
                        </h3>
                        <p class="mb-0 text-sm text-secondary">Masukkan data mitra bisnis atau pelanggan korporat ke dalam sistem.</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-sm bg-gradient-secondary mb-0 hover-scale mt-3 mt-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body px-4 pt-4 pb-4">
                <hr class="horizontal dark mt-0 mb-4">

                <div class="alert alert-light border-radius-md border border-light mb-4 shadow-sm d-flex align-items-center">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-success text-center me-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <span class="text-sm text-dark font-weight-bold">Hanya kolom <span class="text-danger">Nama Klien</span> yang wajib diisi. Kontak dan Alamat bisa disusul nanti jika belum tersedia.</span>
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

                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="name" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nama Perusahaan / Klien <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-briefcase text-success"></i></span>
                                    <input class="form-control focus-success border-start-0 ps-0" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: PT. Maju Mundur atau Toko Sejahtera" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="email" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Email Klien (Opsional)</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope text-success"></i></span>
                                    <input class="form-control focus-success border-start-0 ps-0" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="kontak@perusahaan.com">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="phone" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nomor HP / Telepon (Opsional)</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-phone-alt text-success"></i></span>
                                    <input class="form-control focus-success border-start-0 ps-0" type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-4">
                                <label for="address" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Alamat Lengkap (Opsional)</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white align-items-start pt-3"><i class="fas fa-map-marker-alt text-success"></i></span>
                                    <textarea class="form-control focus-success border-start-0 ps-0 pt-3" name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap perusahaan atau toko klien di sini...">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark mt-2 mb-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-success btn-lg mb-0 hover-scale px-5 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>Simpan Klien Baru
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
    
    /* Highlight gradient hijau saat input diklik */
    .focus-success:focus { 
        border-color: #82d616 !important; /* Warna hijau khas Soft UI */
        box-shadow: none !important; 
    }
    
    /* Hilangkan border kiri agar menyatu dengan icon */
    .input-group > .form-control:focus {
        border-left-color: transparent !important;
    }
    
    /* Perbaikan border saat menggunakan textarea */
    textarea.form-control {
        resize: vertical; /* Biar user bisa tarik besar-kecil kotaknya */
    }
</style>
@endsection