@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    {{-- 1. Load FontAwesome (CRITICAL FOR ICONS TO SHOW) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .form-label-custom { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #8898aa; margin-bottom: 0.5rem; display: block; }
        .form-control { border: 1px solid #e0e6ed; border-radius: 8px; }
        .bg-light-modern { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; }
        .total-display { font-size: 1.5rem; font-weight: 800; color: #344767; }
        .remove-btn { cursor: pointer; transition: color 0.2s; }
        .remove-btn:hover { color: #d63384 !important; }
    </style>

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white pt-4 pb-0">
                <h4 class="font-weight-bolder mb-0">Edit Purchase</h4>
            </div>
            
            <div class="card-body">
                <form id="purchase-form" action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Header --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label-custom">Note Number</label>
                            {{-- Readonly: Changing ID usually causes data consistency issues --}}
                            <input type="text" name="note_number" class="form-control" value="{{ $purchase->note_number }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Date</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Distributor</label>
                            <select name="distributor_id" class="form-control" required>
                                <option value="">Select...</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ $purchase->distributor_id == $distributor->id ? 'selected' : '' }}>
                                        {{ $distributor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="horizontal dark my-4">

                    {{-- Items --}}
                    <h6 class="mb-3 font-weight-bold">Items</h6>
                    <div id="items-container">
                        @foreach($purchase->details as $index => $detail)
                            <div class="row g-3 align-items-end mb-3 bg-light-modern p-3 item-row">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Product</label>
                                    <select name="products[{{ $index }}][serial_number]" class="form-control product-select" required>
                                        @foreach($products as $p)
                                            <option value="{{ $p->serial_number }}" {{ $detail->serial_number_product == $p->serial_number ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label-custom">Buy Price</label>
                                    <input type="number" name="products[{{ $index }}][purchase_price]" class="form-control price-input" value="{{ $detail->purchase_price }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label-custom">Margin (%)</label>
                                    <input type="number" name="products[{{ $index }}][selling_margin]" class="form-control" value="{{ $detail->selling_margin }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label-custom">Qty</label>
                                    <input type="number" name="products[{{ $index }}][purchase_amount]" class="form-control qty-input" value="{{ $detail->purchase_amount }}" required>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label-custom">Sub</label>
                                    <div class="font-weight-bold mt-2 subtotal-text">
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                                {{-- DELETE BUTTON --}}
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-link text-danger mb-0 remove-btn" title="Remove Item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="add-item-btn"><i class="fas fa-plus"></i> Add Item</button>
                    </div>

                    <div class="row justify-content-end mt-5">
                        <div class="col-md-4 text-end">
                            <h6 class="text-muted text-uppercase text-xs font-weight-bolder">Grand Total</h6>
                            <h3 class="total-display">Rp <span id="grand-total-display">{{ number_format($purchase->total_price, 0, ',', '.') }}</span></h3>
                            <input type="hidden" name="total_price" id="grand-total-input" value="{{ $purchase->total_price }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('purchases.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn bg-gradient-dark">Update Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Pass PHP Data to JS --}}
    <script>const products = @json($products);</script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const addBtn = document.getElementById('add-item-btn');
            const grandTotalDisplay = document.getElementById('grand-total-display');
            const grandTotalInput = document.getElementById('grand-total-input');
            
            // Calculate starting index based on existing items to avoid ID collisions
            let itemIndex = {{ count($purchase->details) }};

            // --- 1. Add Row Function ---
            function addRow() {
                const rowId = itemIndex;
                const html = `
                    <div class="row g-3 align-items-end mb-3 bg-light-modern p-3 item-row">
                        <div class="col-md-4">
                            <label class="form-label-custom">Product</label>
                            <select name="products[${rowId}][serial_number]" class="form-control product-select" required>
                                <option value="">Select Product...</option>
                                ${products.map(p => `<option value="${p.serial_number}">${p.name}</option>`).join('')}
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
                            <label class="form-label-custom">Sub</label>
                            <div class="font-weight-bold mt-2 subtotal-text">0</div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-link text-danger mb-0 remove-btn" title="Remove Item">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
                itemIndex++;
            }

            // --- 2. Event Listeners ---
            addBtn.addEventListener('click', addRow);

            // Listen for input changes to recalculate totals
            container.addEventListener('input', function(e) {
                if (e.target.matches('.price-input, .qty-input')) {
                    updateCalculations();
                }
            });

            // Listen for Delete Button Click
            container.addEventListener('click', function(e) {
                // Find the closest button with class 'remove-btn' (handles icon clicks too)
                const btn = e.target.closest('.remove-btn');
                if (btn) {
                    const row = btn.closest('.item-row');
                    
                    // Check if there is more than 1 item before deleting
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        updateCalculations();
                    } else {
                        // Optional: Alert the user they can't delete the last item
                        alert("You must have at least one item in the purchase.");
                    }
                }
            });

            // --- 3. Calculation Logic ---
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