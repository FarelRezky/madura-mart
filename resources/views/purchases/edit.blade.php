@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('purchase')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .form-label-custom { font-size: 0.70rem; font-weight: 700; text-transform: uppercase; color: #8898aa; margin-bottom: 0.5rem; display: block; }
        .form-control { border: 1px solid #e0e6ed; border-radius: 8px; font-size: 0.85rem; }
        .total-display { font-size: 1.5rem; font-weight: 800; color: #344767; }
        
        /* --- SCROLLING TABLE EDIT --- */
        .table-edit-wrapper {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding-bottom: 10px;
        }
        .table-edit-wrapper::-webkit-scrollbar { height: 8px; }
        .table-edit-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

        .table-create {
            width: max-content !important;
            min-width: 100%;
            border-collapse: collapse;
        }
        .table-create th {
            background-color: #f8f9fa;
            color: #8392ab;
            font-size: 0.70rem;
            font-weight: 700;
            padding: 1rem;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-create td { padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .input-group-text { border: 1px solid #e0e6ed; background-color: #f8f9fa; color: #8898aa; font-weight: 600; font-size: 0.85rem;}
    </style>

    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white pt-4 pb-0">
                <h4 class="font-weight-bolder mb-0">Edit Purchase</h4>
                <p class="text-sm text-muted">Update existing transaction record.</p>
            </div>
            
            <div class="card-body">
                <form id="purchase-form" action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Header Section --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label-custom">Note Number</label>
                            <input type="text" name="note_number" class="form-control bg-light" value="{{ $purchase->note_number }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Date</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Distributor</label>
                            <select name="distributor_id" class="form-control" required>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" {{ $purchase->distributor_id == $distributor->id ? 'selected' : '' }}>
                                        {{ $distributor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="horizontal dark my-4">

                    {{-- Items Section (Table Scroll) --}}
                    <h6 class="mb-3 font-weight-bold">Purchase Items</h6>
                    <div class="table-edit-wrapper">
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
                                @foreach($purchase->details as $index => $detail)
                                <tr class="item-row" id="row-{{ $index }}">
                                    <td>
                                        <select name="products[{{ $index }}][serial_number]" class="form-control product-select" required>
                                            @foreach($products as $p)
                                                <option value="{{ $p->serial_number }}" {{ $detail->serial_number_product == $p->serial_number ? 'selected' : '' }}>
                                                    {{ $p->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="products[{{ $index }}][expired_date]" class="form-control" value="{{ $detail->expired_date ? \Carbon\Carbon::parse($detail->expired_date)->format('Y-m-d') : '' }}" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text px-2">Rp</span>
                                            <input type="number" name="products[{{ $index }}][purchase_price]" class="form-control price-input" value="{{ $detail->purchase_price }}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text px-2">Rp</span>
                                            <input type="number" name="products[{{ $index }}][selling_price]" class="form-control sell-price-input" value="{{ $detail->selling_price }}" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="products[{{ $index }}][selling_margin]" class="form-control margin-input" value="{{ $detail->selling_margin }}" required>
                                            <span class="input-group-text px-2">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="products[{{ $index }}][purchase_amount]" class="form-control qty-input" value="{{ $detail->purchase_amount }}" required>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold mt-2 subtotal-text text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-danger mb-0 remove-btn p-0"><i class="fas fa-trash fs-5"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-dark btn-sm" id="add-item-btn"><i class="fas fa-plus me-1"></i> Add Item</button>
                    </div>

                    <div class="row justify-content-end mt-5">
                        <div class="col-md-4 text-end">
                            <h6 class="text-muted text-uppercase text-xs font-weight-bolder">Grand Total</h6>
                            <h3 class="total-display text-success">Rp <span id="grand-total-display">{{ number_format($purchase->total_price, 0, ',', '.') }}</span></h3>
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

    <script>
        const products = @json($products);
        let itemIndex = {{ count($purchase->details) }};

        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const rowId = itemIndex++;
            const html = `
                <tr class="item-row">
                    <td>
                        <select name="products[${rowId}][serial_number]" class="form-control product-select" required>
                            <option value="">Select Product...</option>
                            ${products.map(p => `<option value="${p.serial_number}">${p.name}</option>`).join('')}
                        </select>
                    </td>
                    <td><input type="date" name="products[${rowId}][expired_date]" class="form-control" required></td>
                    <td><div class="input-group"><span class="input-group-text px-2">Rp</span><input type="number" name="products[${rowId}][purchase_price]" class="form-control price-input" required></div></td>
                    <td><div class="input-group"><span class="input-group-text px-2">Rp</span><input type="number" name="products[${rowId}][selling_price]" class="form-control sell-price-input" readonly style="background-color: #e9ecef;"></div></td>
                    <td><div class="input-group"><input type="number" name="products[${rowId}][selling_margin]" class="form-control margin-input" value="10" required><span class="input-group-text px-2">%</span></div></td>
                    <td><input type="number" name="products[${rowId}][purchase_amount]" class="form-control qty-input" value="1" required></td>
                    <td><div class="font-weight-bold mt-2 subtotal-text text-primary">Rp 0</div></td>
                    <td class="text-center"><button type="button" class="btn btn-link text-danger mb-0 remove-btn p-0"><i class="fas fa-trash fs-5"></i></button></td>
                </tr>`;
            container.insertAdjacentHTML('beforeend', html);
        });

        // Delegate input & click events
        const container = document.getElementById('items-container');
        container.addEventListener('input', function(e) {
            if (e.target.matches('.price-input, .qty-input, .margin-input')) updateCalculations();
        });
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-btn')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                    updateCalculations();
                } else {
                    alert("Minimal harus ada 1 barang!");
                }
            }
        });

        function updateCalculations() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const margin = parseFloat(row.querySelector('.margin-input').value) || 0;
                
                const sellPrice = price + (price * (margin / 100));
                const subtotal = price * qty;
                
                row.querySelector('.sell-price-input').value = Math.round(sellPrice);
                row.querySelector('.subtotal-text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                grandTotal += subtotal;
            });
            document.getElementById('grand-total-display').textContent = new Intl.NumberFormat('id-ID').format(grandTotal);
            document.getElementById('grand-total-input').value = grandTotal;
        }
    </script>
@endsection