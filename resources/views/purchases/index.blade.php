@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { 
            --soft-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .card-modern { 
            border: none;
            border-radius: 20px;
            box-shadow: var(--soft-shadow);
            background: #fff;
            transition: transform 0.2s;
            overflow: hidden;
        }

        /* --- PEMBUNGKUS SCROLLBAR --- */
        .table-wrapper {
            width: 100%;
            overflow-x: auto !important; 
            -webkit-overflow-scrolling: touch;
            padding-bottom: 15px;
        }
        
        .table-wrapper::-webkit-scrollbar { height: 12px; }
        .table-wrapper::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .table-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; border: 2px solid #f1f5f9; }
        .table-wrapper::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* --- KUNCI MUTLAK ANTI DEMPET --- */
        .table-modern {
            width: max-content !important; 
            margin-bottom: 0;
            border-collapse: collapse;
        }
        
        .table-modern th, .table-modern td {
            white-space: nowrap !important; 
            vertical-align: middle;
            padding: 1.2rem 1.5rem;
            color: #495057;
            min-width: 140px !important; 
        }
        
        .table-modern th:first-child, .table-modern td:first-child { min-width: 80px !important; }
        
        .table-modern thead th {
            background-color: #f8f9fa;
            color: #8392ab;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-modern tbody tr { border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; }
        .table-modern tbody tr:hover { background-color: #f8fafc; }
        
        .empty-state { text-align: center; padding: 5rem 2rem !important; border-bottom: none !important; }
    </style>

    <div class="container-fluid py-4" style="min-height: 85vh;">
        @if (session('success'))
            <div class="alert alert-success text-white border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-modern mb-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 font-weight-bolder text-dark">{{ $title ?? 'Purchases Management' }}</h5>
                            <p class="text-sm text-muted mb-0">Track your incoming inventory and details.</p>
                        </div>
                        <a href="{{ route('purchases.create') }}" class="btn bg-gradient-dark btn-sm mb-0 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-plus me-2"></i>New Purchase
                        </a>
                    </div>

                    <div class="card-body px-0 pt-4 pb-2">
                        <div class="table-wrapper px-4">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Invoice Date</th>
                                        <th>Distributor</th>
                                        <th>Product Name</th>
                                        <th>Expired Date</th>
                                        <th>Buy Price</th>
                                        <th>Sell Price</th>
                                        <th>Margin</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Total Pay</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $index => $purchase)
                                        @if($purchase->details && $purchase->details->count() > 0)
                                            @foreach($purchase->details as $detail)
                                                <tr>
                                                    <td><span class="text-secondary text-sm">{{ $index + 1 }}</span></td>
                                                    <td class="font-weight-bold text-dark">{{ $purchase->note_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                                                    <td><span class="badge bg-light text-dark border">{{ $purchase->distributor->name ?? '-' }}</span></td>
                                                    <td class="font-weight-bold">{{ $detail->product->name ?? 'Unknown' }}</td>
                                                    <td>{{ $detail->expired_date ? \Carbon\Carbon::parse($detail->expired_date)->format('d/m/Y') : '-' }}</td>
                                                    <td>Rp {{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                                                    <td><span class="badge bg-info">{{ $detail->selling_margin }}%</span></td>
                                                    <td class="text-center">{{ $detail->purchase_amount }}</td>
                                                    <td class="font-weight-bold text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                    <td class="font-weight-bold text-success">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                                                    
                                                    <td class="text-center">
                                                        @php
                                                            $gambarProduk = $detail->product->picture ?? null;
                                                            $namaProduk = $detail->product->name ?? 'Gambar Produk';
                                                        @endphp

                                                        @if($gambarProduk)
                                                            <img src="{{ asset('images/products/' . $gambarProduk) }}" 
                                                                 width="45" height="45" class="rounded shadow-sm cursor-pointer" 
                                                                 onclick="showImage('{{ asset('images/products/' . $gambarProduk) }}', '{{ addslashes($namaProduk) }}')" 
                                                                 style="object-fit: cover;">
                                                        @else
                                                            <span class="badge bg-light text-secondary border">No Image</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-link text-warning p-0 me-3 mb-0" title="Edit">
                                                            <i class="fas fa-edit fs-5"></i>
                                                        </a>
                                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Invoice {{ $purchase->note_number }}?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0 mb-0" title="Delete">
                                                                <i class="fas fa-trash fs-5"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="14" class="empty-state">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                                                <h5 class="text-muted">Belum Ada Data Pembelian</h5>
                                                <p class="text-sm text-secondary">Silakan klik tombol <b>"New Purchase"</b> di pojok kanan atas untuk menambahkan data transaksi pertamamu.</p>
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
        function showImage(src, title) {
            // Cek jika modal sudah ada, hapus dulu biar tidak dobel
            const existingModal = document.getElementById('customImageModal');
            if(existingModal) {
                existingModal.remove();
            }

            // Buat elemen modal baru
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="modal fade show d-block" id="customImageModal" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1055; backdrop-filter: blur(3px);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                            
                            <div class="modal-header border-bottom py-3">
                                <h5 class="modal-title text-dark font-weight-bolder" style="font-size: 1.25rem;">${title}</h5>
                            </div>
                            
                            <div class="modal-body text-center p-4 bg-white">
                                <img src="${src}" class="img-fluid rounded" style="max-height: 50vh; object-fit: contain;">
                            </div>
                            
                            <div class="modal-footer border-top-0 d-flex justify-content-end pb-3 pt-0">
                                <button type="button" class="btn text-white mb-0 px-4" 
                                        onclick="document.getElementById('customImageModal').remove()" 
                                        style="background-color: #8392ab; border-radius: 6px; box-shadow: 0 4px 6px rgba(131, 146, 171, 0.2);">
                                    CLOSE
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            `;
            
            // Masukkan ke dalam HTML
            document.body.appendChild(modal.firstElementChild);
        }
    </script>
@endsection