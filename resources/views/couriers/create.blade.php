@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<style>
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); background: #fff; margin-top: 20px; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #d2d6da; padding: 0.6rem 0.75rem; }
    .form-control:focus, .form-select:focus { border-color: #cb0c9f; box-shadow: 0 0 0 2px rgba(203, 12, 159, 0.2); }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-modern mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 font-weight-bolder">Tambah Kurir Baru</h5>
                    <p class="text-sm text-muted mb-0">Daftarkan kurir atau armada baru ke sistem.</p>
                </div>
                <div class="card-body px-4 pt-4 pb-4">
                    <form action="{{ route('couriers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-secondary">Nama Kurir / Ekspedisi <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: JNE / Pak Budi" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-xs font-weight-bold text-uppercase text-secondary">No. HP / Telepon</label>
                                <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789">
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="text-xs font-weight-bold text-uppercase text-secondary">Plat Nomor Kendaraan</label>
                                <input type="text" name="vehicle_number" class="form-control" placeholder="Contoh: M 1234 AB">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs font-weight-bold text-uppercase text-secondary">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>

                        <div class="text-end border-top pt-3">
                            <a href="{{ route('couriers.index') }}" class="btn btn-light mb-0 me-2 shadow-sm">Batal</a>
                            <button type="submit" class="btn bg-gradient-primary mb-0 shadow-sm" style="background-image: linear-gradient(310deg, #cb0c9f 0%, #cb0c9f 100%); color: white;">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection