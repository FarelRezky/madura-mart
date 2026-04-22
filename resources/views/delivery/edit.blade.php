@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header pb-0">
                    <h6>Edit Data Delivery</h6>
                </div>
                <div class="card-body">
                    {{-- Pastikan variabel $delivery dikirim dari fungsi edit() di Controller --}}
                    <form action="{{ route('delivery.update', $delivery->id ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="kode_resi" class="form-label">Kode Resi</label>
                            <input type="text" class="form-control @error('kode_resi') is-invalid @enderror" id="kode_resi" name="kode_resi" value="{{ old('kode_resi', $delivery->kode_resi ?? '') }}" required>
                            @error('kode_resi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="kurir" class="form-label">Kurir / Ekspedisi</label>
                            <input type="text" class="form-control @error('kurir') is-invalid @enderror" id="kurir" name="kurir" value="{{ old('kurir', $delivery->kurir ?? '') }}" required>
                            @error('kurir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="status" class="form-label">Status Pengiriman</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Diproses" {{ old('status', $delivery->status ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Dikirim" {{ old('status', $delivery->status ?? '') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="Selesai" {{ old('status', $delivery->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('delivery.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection