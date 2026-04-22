@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    <style>
        .form-label-custom { font-size: 0.70rem; font-weight: 700; text-transform: uppercase; color: #8898aa; margin-bottom: 0.5rem; display: block; }
        .form-control { border: 1px solid #e0e6ed; border-radius: 8px; font-size: 0.85rem; }
        .form-control:focus { border-color: #5e72e4; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1); }
        .total-display { font-size: 1.5rem; font-weight: 800; color: #344767; }
        
        /* --- SCROLLING TABEL CREATE --- */
        .table-create-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 10px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
        }
        .table-create-wrapper::-webkit-scrollbar { height: 8px; }
        .table-create-wrapper::-webkit-scrollbar-track { background: #f8f9fa; border-radius: 8px; }
        .table-create-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        
        .table-create {
            width: max-content !important;
            min-width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .table-create th {
            background-color: #f8f9fa;
            color: #8392ab;
            font-size: 0.70rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-create td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .input-group-text { border: 1px solid #e0e6ed; background-color: #f8f9fa; color: #8898aa; font-weight: 600; font-size: 0.85rem;}
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white pt-4 pb-0">
                        <h4 class="font-weight-bolder mb-0">Create New Purchase</h4>
                        <p class="text-sm text-muted">Record a new transaction.</p>
                    </div>
                    
                    <div class="card-body">
                        <form id="purchase-form" action="{{ route('purchases.store') }}" method="POST">
                            @csrf

                            {{-- Header Section --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Note Number</label>
                                    <input type="text" name="note_number" class="form-control" placeholder="PN-2026-XXX" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Date</label>
                                    <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Distributor</label>
                                    <select name="distributor_id" class="form-control" required>
                                        <option value="">Select Distributor...</option>
                                        @foreach($distributors ?? [] as $distributor)
                                            <option value="{{ $distributor->id ?? '' }}">{{ $distributor->name ?? 'Tanpa Nama' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="horizontal dark my-4">

                            {{-- Items Section (Sekarang Pakai Tabel Bawaan Scroll) --}}
                            <h6 class="mb-3 font-weight-bold">Purchase Items</h6>
                            
                            <div class="table-create-wrapper">
                                <table class="table-create">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 250px;">Product Name</th>
                                            <th style="min-width: 160px;">Expired Date</th>
                                            <th style="min-width: 180px;">Buy Price</th>
                                            <th style="min-width: 180px;">Sell Price</th>
                                            <th style="min-width: 130px;">Margin</th>
                                            <th style="min-width: 100px;">Qty</th>
                                            <th style="min-width: 150px;">Subtotal</th>
                                            <th style="min-width: 80px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-container">
                                        {{-- Baris JS akan masuk ke sini --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-dark btn-sm" id="add-item-btn">
                                    <i class="fas fa-plus me-1"></i> Add Another Item
                                </button>
                            </div>

                            <div class="row justify-content-end mt-5">
                                <div class="col-md-4 text-end">
                                    <h6 class="text-muted text-uppercase text-xs font-weight-bolder">Grand Total</h6>
                                    <h3 class="total-display text-success">Rp <span id="grand-total-display">0</span></h3>
                                    <input type="hidden" name="total_price" id="grand-total-input" value="0">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('purchases.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn bg-gradient-dark">Save Purchase</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const products = @json($products ?? []);
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const addBtn = document.getElementById('add-item-btn');
            const grandTotalDisplay = document.getElementById('grand-total-display');
            const grandTotalInput = document.getElementById('grand-total-input');
            let itemIndex = 0;

            function addRow() {
                const rowId = itemIndex;
                const html = `
                    <tr class="item-row" id="row-${rowId}">
                        <td>
                            <select name="products[${rowId}][serial_number]" class="form-control product-select" required>
                                <option value="">Select Product...</option>
                                ${products.map(p => `<option value="${p.serial_number}">${p.name}</option>`).join('')}
                            </select>
                        </td>
                        <td>
                            <input type="date" name="products[${rowId}][expired_date]" class="form-control" required>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2">Rp</span>
                                <input type="number" name="products[${rowId}][purchase_price]" class="form-control price-input ps-2" min="0" required placeholder="0">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2">Rp</span>
                                <input type="number" name="products[${rowId}][selling_price]" class="form-control sell-price-input ps-2" readonly style="background-color: #e9ecef;" placeholder="0">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="number" name="products[${rowId}][selling_margin]" class="form-control margin-input pe-1" min="0" value="10" required>
                                <span class="input-group-text px-2">%</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="products[${rowId}][purchase_amount]" class="form-control qty-input" min="1" value="1" required>
                        </td>
                        <td>
                            <div class="font-weight-bold mt-2 subtotal-text text-primary">Rp 0</div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link text-danger mb-0 remove-btn p-0"><i class="fas fa-trash fs-5"></i></button>
                        </td>
                    </tr>
                `;
                container.insertAdjacentHTML('beforeend', html);
                itemIndex++;
            }

            // Memunculkan baris pertama saat halaman di-load
            addRow();

            // Tambah baris baru saat tombol diklik
            addBtn.addEventListener('click', addRow);

            // Deteksi perubahan input pada Buy Price, Qty, dan Margin
            container.addEventListener('input', function(e) {
                if (e.target.matches('.price-input, .qty-input, .margin-input')) {
                    updateCalculations();
                }
            });

            // Hapus baris jika tombol tong sampah diklik
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-btn')) {
                    const row = e.target.closest('.item-row');
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        updateCalculations();
                    } else {
                        alert("Harus ada minimal 1 barang!");
                    }
                }
            });

            function updateCalculations() {
                let grandTotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const margin = parseFloat(row.querySelector('.margin-input').value) || 0;
                    
                    // Hitung Sell Price = Buy Price + (Buy Price * (Margin / 100))
                    const sellPrice = price + (price * (margin / 100));
                    row.querySelector('.sell-price-input').value = sellPrice; // Menyimpan nilai desimal murni ke input

                    // Hitung Subtotal = Buy Price * Qty
                    const subtotal = price * qty;
                    row.querySelector('.subtotal-text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                    
                    grandTotal += subtotal;
                });

                grandTotalDisplay.textContent = new Intl.NumberFormat('id-ID').format(grandTotal);
                grandTotalInput.value = grandTotal; // Kirim nilai murni grand total ke database
            }
        });
    </script>
@endsection