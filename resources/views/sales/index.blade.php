@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<style>
    :root {
        --soft-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.04);
        --hover-shadow: 0 14px 24px -3px rgba(0, 0, 0, 0.08);
        --primary-gradient: linear-gradient(135deg, #cb0c9f 0%, #f5365c 100%);
        --text-main: #334155;
        --text-muted: #64748b;
    }

    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: var(--soft-shadow);
        background: #fff;
        margin-top: 20px;
    }

    /* Modern Table Styling */
    .table-wrapper { width: 100%; overflow-x: auto; padding-bottom: 15px; }
    .table-wrapper::-webkit-scrollbar { height: 8px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

    .table-modern { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-modern thead th {
        font-size: 0.75rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        border-bottom: 2px solid #f1f5f9;
        padding: 1.2rem 1.5rem;
        background-color: #fff;
        font-weight: 700;
        white-space: nowrap;
    }
    
    .table-modern tbody tr { transition: all 0.3s ease; border-bottom: 1px solid #f8fafc; }
    .table-modern tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
        border-radius: 12px;
    }
    
    .table-modern td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: var(--text-main);
        font-size: 0.9rem;
    }

    /* Action Buttons */
    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action.edit { color: #0ea5e9; background-color: #e0f2fe; }
    .btn-action.edit:hover { background-color: #bae6fd; transform: translateY(-2px); }

    .btn-action.delete { color: #ef4444; background-color: #fee2e2; }
    .btn-action.delete:hover { background-color: #fecaca; transform: translateY(-2px); }

    /* Product Badge */
    .product-badge {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 4px;
        font-size: 0.75rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid py-4">

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 font-weight-bolder text-dark">Data Penjualan</h5>
                        <p class="text-sm text-muted mb-0">Manajemen Transaksi & Riwayat Pendapatan</p>
                    </div>
                    <a href="{{ route('sales.create') }}" class="btn text-white btn-sm mb-0 shadow-lg px-4 py-2" style="background: var(--primary-gradient); border-radius: 8px;">
                        <i class="fas fa-plus me-2"></i>Tambah Transaksi
                    </a>
                </div>

                <div class="card-body px-0 pt-4 pb-2">
                    <div class="table-wrapper px-4">
                        <table class="table table-modern align-items-center">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th style="width: 20%">Info Transaksi</th>
                                    <th style="width: 35%">Produk Terjual</th>
                                    <th style="width: 20%">Total Pendapatan</th>
                                    <th class="text-center" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $final_data = $sales ?? []; @endphp

                                @forelse($final_data as $index => $item)
                                    <tr>
                                        <td class="text-center text-sm fw-bold text-muted">{{ $index + 1 }}</td>
                                        
                                        <td>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-sm fw-bold text-dark">{{ $item->sale_number }}</h6>
                                                <span class="text-xs text-muted"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($item->sale_date)->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 300px;">
                                                {{-- Cek apakah transaksi memiliki relasi ke detail --}}
                                                @if(isset($item->details) && $item->details->count() > 0)
                                                    @foreach($item->details as $detail)
                                                        <span class="product-badge">
                                                            {{-- Ambil nama produk dari relasi, jika gagal tampilkan serial numbernya --}}
                                                            {{ $detail->product->name ?? $detail->product->nama_barang ?? $detail->product_serial }} 
                                                            <span class="text-primary fw-bold ms-1">x{{ $detail->qty }}</span>
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-xs text-muted" style="font-style: italic;">Tidak ada rincian produk</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-gradient-success px-3 py-2" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('sales.edit', $item->id) }}" 
                                                   class="btn-action edit" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Edit Transaksi">
                                                    <i class="fas fa-pen text-sm"></i>
                                                </a>

                                                <form action="{{ route('sales.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn-action delete btn-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Transaksi">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                                    <i class="fas fa-receipt text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                                <h6 class="text-dark fw-bold mb-1">Belum Ada Transaksi</h6>
                                                <p class="text-muted text-sm mb-3">Transaksi penjualan yang Anda buat akan muncul di sini.</p>
                                                <a href="{{ route('sales.create') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Buat Transaksi Pertama</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Tooltip Bootstrap (Hover Text)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Logika SweetAlert untuk Hapus Data
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Data transaksi akan dihapus permanen dan stok produk akan dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#cbd5e1',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'shadow-sm',
                        cancelButton: 'shadow-sm text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection