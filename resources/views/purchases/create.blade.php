@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    <style>
        .form-label-custom { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #8898aa; margin-bottom: 0.5rem; display: block; }
        .form-control { border: 1px solid #e0e6ed; border-radius: 8px; }
        .form-control:focus { border-color: #5e72e4; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1); }
        .bg-light-modern { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; }
        .total-display { font-size: 1.5rem; font-weight: 800; color: #344767; }
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
                                        @foreach($distributors as $distributor)
                                            <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="horizontal dark my-4">

                            {{-- Items Section --}}
                            <h6 class="mb-3 font-weight-bold">Purchase Items</h6>
                            <div id="items-container">
                                {{-- Rows inserted by JS --}}
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-dark btn-sm" id="add-item-btn">
                                    <i class="fas fa-plus me-1"></i> Add Another Item
                                </button>
                            </div>

                            <div class="row justify-content-end mt-5">
                                <div class="col-md-4 text-end">
                                    <h6 class="text-muted text-uppercase text-xs font-weight-bolder">Grand Total</h6>
                                    <h3 class="total-display">Rp <span id="grand-total-display">0</span></h3>
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
        const products = @json($products);
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
                    <div class="row g-3 align-items-end mb-3 bg-light-modern p-3 item-row" id="row-${rowId}">
                        <div class="col-md-4">
                            <label class="form-label-custom">Product</label>
                            <select name="products[${rowId}][serial_number]" class="form-control product-select" required>
                                <option value="">Select Product...</option>
                                ${products.map(p => `<option value="${p.serial_number}">${p.name} (${p.serial_number})</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-custom">Buy Price</label>
                            <input type="number" name="products[${rowId}][purchase_price]" class="form-control price-input" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-custom">Margin (%)</label>
                            <input type="number" name="products[${rowId}][selling_margin]" class="form-control" min="0" value="10" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-custom">Qty</label>
                            <input type="number" name="products[${rowId}][purchase_amount]" class="form-control qty-input" min="1" value="1" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label-custom">Subtotal</label>
                            <div class="font-weight-bold mt-2 subtotal-text">0</div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-link text-danger mb-0 remove-btn"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
                itemIndex++;
            }

            // Initial Row
            addRow();

            addBtn.addEventListener('click', addRow);

            container.addEventListener('input', function(e) {
                if (e.target.matches('.price-input, .qty-input')) {
                    updateCalculations();
                }
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-btn')) {
                    const row = e.target.closest('.item-row');
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        updateCalculations();
                    }
                }
            });

            function updateCalculations() {
                let grandTotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const subtotal = price * qty;
                    
                    row.querySelector('.subtotal-text').textContent = new Intl.NumberFormat('id-ID').format(subtotal);
                    grandTotal += subtotal;
                });

                grandTotalDisplay.textContent = new Intl.NumberFormat('id-ID').format(grandTotal);
                grandTotalInput.value = grandTotal;
            }
        });
    </script>
@endsection