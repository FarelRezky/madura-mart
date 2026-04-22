@extends('layout.master')

@section('title', 'Edit Klien Bisnis - Madura Mart')

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
                            <i class="fas fa-edit me-2"></i>Edit Klien: {{ $client->name }}
                        </h3>
                        <p class="mb-0 text-sm text-secondary">Perbarui informasi kontak atau alamat mitra bisnis ini.</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-sm bg-gradient-secondary mb-0 hover-scale mt-3 mt-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body px-4 pt-4 pb-4">
                
                <div class="row mb-4 align-items-center bg-gray-100 py-3 mx-0 rounded-3">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative bg-gradient-success d-flex align-items-center justify-content-center shadow-sm">
                            <span class="text-white font-weight-bold text-xl">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-0">{{ $client->name }}</h5>
                            <p class="mb-0 text-sm text-secondary mt-1">
                                <i class="fas fa-envelope me-1"></i> {{ $client->email ?? 'Belum ada email' }}
                            </p>
                            <p class="mb-0 text-sm text-secondary">
                                <i class="fas fa-phone-alt me-1"></i> {{ $client->phone ?? 'Belum ada kontak HP' }}
                            </p>
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

                <form action="{{ route('clients.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="name" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nama Perusahaan / Klien <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-briefcase text-success"></i></span>
                                    <input class="form-control focus-success border-start-0 ps-0" type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required>
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
                                    <input class="form-control focus-success border-start-0 ps-0" type="email" name="email" id="email" value="{{ old('email', $client->email) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="phone" class="form-control-label text-xs font-weight-bolder text-uppercase opacity-8">Nomor HP / Telepon (Opsional)</label>
                                <div class="input-group shadow-sm border-radius-md">
                                    <span class="input-group-text bg-white"><i class="fas fa-phone-alt text-success"></i></span>
                                    <input class="form-control focus-success border-start-0 ps-0" type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}">
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
                                    <textarea class="form-control focus-success border-start-0 ps-0 pt-3" name="address" id="address" rows="3">{{ old('address', $client->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark mt-2 mb-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-success btn-lg mb-0 hover-scale px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i>Update Data Klien
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
        border-color: #82d616 !important; 
        box-shadow: none !important; 
    }
    
    /* Hilangkan border kiri agar menyatu dengan icon */
    .input-group > .form-control:focus {
        border-left-color: transparent !important;
    }
    
    /* Perbaikan border saat menggunakan textarea */
    textarea.form-control {
        resize: vertical;
    }
</style>
@endsection