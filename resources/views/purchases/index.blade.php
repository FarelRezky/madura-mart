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
                                                        <button type="button" class="btn btn-link text-warning p-0 me-3 mb-0" title="Edit" 
                                                                onclick="requirePasswordForEdit('{{ route('purchases.edit', $purchase->id) }}')">
                                                            <i class="fas fa-edit fs-5"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-link text-danger p-0 mb-0" title="Delete" 
                                                                onclick="requirePasswordForDelete('{{ $purchase->id }}')">
                                                            <i class="fas fa-trash fs-5"></i>
                                                        </button>

                                                        <form id="delete-form-{{ $purchase->id }}" action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-none">
                                                            @csrf @method('DELETE')
                                                            <input type="hidden" name="password" id="delete-password-{{ $purchase->id }}">
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

        // Fungsi mengecek password ke backend via AJAX
        function verifyBossPassword(password) {
            return fetch('{{ route('purchases.verify-password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // <-- Tambahan penting agar Laravel merespon JSON
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: password })
            })
            .then(async response => {
                if (!response.ok) {
                    console.error("Server merespon dengan status:", response.status);
                    return { success: false, message: 'Terjadi error di server (Status: ' + response.status + ')' };
                }
                return response.json(); // Parsing JSON dari controller
            })
            .catch(error => {
                console.error("Detail Error Fetch:", error);
                return { success: false, message: 'Gagal terhubung ke server! Cek console.' };
            });
        }

        // Alur Modal untuk Edit
        function requirePasswordForEdit(editUrl) {
            Swal.fire({
                title: 'Security Check',
                html: '<span style="font-size: 0.9em; color: #6c757d;">Please enter the <b>Administrator</b> password to edit this record.</span>',
                input: 'password',
                inputPlaceholder: 'Enter password',
                icon: 'warning',
                confirmButtonColor: '#e91e63',
                cancelButtonColor: '#adb5bd',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Verify',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    if (!password) { 
                        Swal.showValidationMessage('Password is required!'); 
                        return false;
                    }
                    return verifyBossPassword(password).then(data => {
                        if (!data.success) {
                            Swal.showValidationMessage(data.message || 'Password salah!');
                            return false;
                        }
                        return password;
                    }).catch(error => {
                        Swal.showValidationMessage('Server error! Please try again.');
                        return false;
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Verified!',
                        text: 'Redirecting to edit form...',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = editUrl; 
                    });
                }
            });
        }

        // Alur Modal untuk Hapus
        function requirePasswordForDelete(id) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                html: '<span style="font-size: 0.9em; color: #6c757d;">You are about to delete this record. This action <b>cannot be undone</b>.</span>',
                showCancelButton: true,
                confirmButtonColor: '#f5365c', 
                cancelButtonColor: '#adb5bd',
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Security Check',
                        html: '<span style="font-size: 0.9em; color: #6c757d;">Please enter the <b>Administrator</b> password to authorize deletion.</span>',
                        input: 'password',
                        inputPlaceholder: 'Enter password',
                        icon: 'error',
                        confirmButtonColor: '#f5365c',
                        cancelButtonColor: '#adb5bd',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Authorize',
                        cancelButtonText: 'Cancel',
                        showLoaderOnConfirm: true,
                        preConfirm: (password) => {
                            if (!password) { 
                                Swal.showValidationMessage('Password is required!'); 
                                return false;
                            }
                            return verifyBossPassword(password).then(data => {
                                if (!data.success) {
                                    Swal.showValidationMessage(data.message || 'Password salah!');
                                    return false;
                                }
                                return password;
                            }).catch(error => {
                                Swal.showValidationMessage('Server error! Please try again.');
                                return false;
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((pwdResult) => {
                        if (pwdResult.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Authorized!',
                                text: 'Deleting record...',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                document.getElementById('delete-password-' + id).value = pwdResult.value;
                                document.getElementById('delete-form-' + id).submit();
                            });
                        }
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection