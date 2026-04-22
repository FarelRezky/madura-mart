@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

{{-- 
  CATATAN: Karena @section('purchase') kamu zonk, 
  kita bungkus kontennya di section 'content'. 
  Kalau 'content' masih zonk, ganti tulisan 'content' di bawah ini menjadi 'main'.
--}}
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Kita beri offset/margin kiri 250px agar tidak tertutup sidebar --}}
        <div class="col-12 col-md-8 mx-auto" style="margin-left: 15% !important;">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; background: white; margin-top: 30px;">
                <div class="card-header bg-white pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(310deg, #cb0c9f 0%, #cb0c9f 100%);">
                            <i class="fas fa-truck text-white opacity-10"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bolder mb-0">Tambah Data Delivery</h5>
                            <p class="text-sm mb-0">Input resi baru ke sistem Madura Mart</p>
                        </div>
                    </div>
                </div>
                <hr class="horizontal dark mt-3 mb-0">
                <div class="card-body">
                    <form action="{{ route('delivery.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-control-label text-uppercase text-xs font-weight-bolder opacity-7">Kode Resi</label>
                                    <input type="text" class="form-control @error('kode_resi') is-invalid @enderror" name="kode_resi" value="{{ old('kode_resi') }}" placeholder="Masukkan nomor resi..." required style="border-radius: 10px; padding: 12px; border: 1px solid #d2d6da;">
                                    @error('kode_resi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label text-uppercase text-xs font-weight-bolder opacity-7">Kurir / Ekspedisi</label>
                                    <input type="text" class="form-control @error('kurir') is-invalid @enderror" name="kurir" value="{{ old('kurir') }}" placeholder="JNE, J&T, SiCepat..." required style="border-radius: 10px; padding: 12px; border: 1px solid #d2d6da;">
                                    @error('kurir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label text-uppercase text-xs font-weight-bolder opacity-7">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" required style="border-radius: 10px; padding: 12px; border: 1px solid #d2d6da;">
                                        <option value="Diproses">Diproses</option>
                                        <option value="Dikirim">Dikirim</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('delivery.index') }}" class="btn btn-outline-secondary border-radius-md px-4 mb-0">Batal</a>
                            <button type="submit" class="btn bg-gradient-primary border-radius-md px-4 mb-0" style="background: linear-gradient(310deg, #cb0c9f 0%, #cb0c9f 100%); color: white;">
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