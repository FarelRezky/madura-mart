@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --soft-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --primary-color: #cb0c9f;
        }
        
        /* Receipt Styling */
        .receipt-container {
            background: #fff;
            padding: 25px;
            box-shadow: var(--soft-shadow);
            border-radius: 8px;
            position: relative;
            min-height: 400px;
            font-family: 'Courier New', Courier, monospace;
            border: 1px solid #f1f5f9;
        }
        .receipt-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 10px;
            background: linear-gradient(45deg, transparent 33.333%, #fff 33.333%, #fff 66.666%, transparent 66.666%),
                        linear-gradient(-45deg, transparent 33.333%, #fff 33.333%, #fff 66.666%, transparent 66.666%);
            background-size: 20px 40px;
            transform: translateY(-10px);
        }
        .receipt-header { text-align: center; border-bottom: 1px dashed #cbd5e1; padding-bottom: 15px; margin-bottom: 15px; }
        .receipt-line { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #334155;}
        .receipt-total { border-top: 2px dashed #334155; margin-top: 15px; padding-top: 12px; font-weight: bold; font-size: 1.2rem; color: #0f172a;}
        
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(45, 206, 137, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(45, 206, 137, 0); }
            100% { box-shadow: 0 0 0 0 rgba(45, 206, 137, 0); }
        }
        .stock-good { animation: pulse-green 2s infinite; border-radius: 50%; display: inline-block; width: 10px; height: 10px; background: #2dce89; margin-right: 8px; }
        
        .price-big { font-size: 3rem; font-weight: 800; letter-spacing: -1px; line-height: 1; text-shadow: 2px 2px 8px rgba(0,0,0,0.3); }
        
        /* Table & Form Customization */
        .table th { border-bottom: 2px solid #f1f5f9; letter-spacing: 0.5px; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #cbd5e1; padding: 0.6rem 0.8rem; font-size: 0.9rem;}
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(203, 12, 159, 0.2); }
        .input-group-text { border: 1px solid #cbd5e1; background-color: #f8fafc; font-weight: 600; color: #64748b;}
        
        /* Smooth Readonly */
        input[readonly] { background-color: #f8fafc !important; color: #64748b; font-weight: 600; cursor: not-allowed; }

        @media print {
            body * { visibility: hidden; }
            .receipt-container, .receipt-container * { visibility: visible; }
            .receipt-container { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
        
        .print-btn { position: absolute; top: 15px; right: 15px; background: #f1f5f9; border: none; border-radius: 50%; width: 35px; height: 35px; color: #475569; transition: all 0.2s; }
        .print-btn:hover { background: var(--primary-color); color: #fff; transform: scale(1.1); }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <div class="container-fluid py-4">
        <div class="row animate__animated animate__fadeInDown mb-4">
            <div class="col-12">
                <div class="card bg-gradient-dark border-0 shadow-lg overflow-hidden position-relative" style="border-radius: 20px;">
                    <div class="card-body p-4 p-md-5 position-relative">
                        <div class="row align-items-center">
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-gradient-primary me-3 shadow-sm px-3 py-2">LIVE POS</span>
                                    <h6 class="text-white opacity-8 mb-0 font-weight-normal text-uppercase tracking-widest" style="font-size: 0.75rem;">Terminal Kasir</h6>
                                </div>
                                <h1 class="text-white font-weight-bolder mb-2" id="detail_product_name" style="font-size: 2.5rem;">Siap Melayani</h1>
                                <div class="d-flex align-items-center mt-2">
                                    <div id="stock_indicator" class="me-3 d-none d-flex align-items-center">
                                        <span class="stock-good"></span>
                                        <span class="text-white text-sm" id="stock_label">Siap Diproses</span>
                                    </div>
                                    <span class="text-white opacity-8 text-sm mb-0" id="detail_calculation">Pilih produk untuk memulai transaksi</span>
                                </div>
                            </div>
                            <div class="col-lg-5 text-lg-end">
                                <p class="text-white opacity-8 mb-1 text-xs text-uppercase font-weight-bold">Total Pembayaran</p>
                                <h1 class="text-white price-big mb-0" id="large_total">Rp 0</h1>
                            </div>
                        </div>
                    </div>
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('sales.store')}}" method="POST" id="form-sale">
            @csrf
            <div class="row">
                <div class="col-lg-8 animate__animated animate__fadeInLeft">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pb-0 pt-4 px-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-md me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-shopping-cart text-white opacity-10"></i>
                                </div>
                                <h6 class="font-weight-bolder mb-0 text-dark">Detail Transaksi</h6>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-control-label text-uppercase text-xs font-weight-bolder text-secondary">Nomor Struk</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        <input type="text" class="form-control" id="sale_number" name="sale_number" value="{{ $next_number }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label text-uppercase text-xs font-weight-bolder text-secondary">Tanggal Penjualan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0 px-4">
                                <table class="table align-items-center mb-0" id="sale-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2" width="35%">Pilih Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2" width="20%">Harga Satuan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2" width="15%">Qty</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2" width="20%">Subtotal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2" width="10%">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-container">
                                        <tr class="item-row">
                                            <td class="p-2 border-0">
                                                <select name="products[0][serial]" class="form-select product-select fw-bold text-dark" required onchange="updatePrice(this)">
                                                    <option value="" disabled selected>Pilih Produk...</option>
                                                    @foreach($products as $p)
                                                        {{-- Menggunakan Fallback (??) agar aman dari perbedaan nama kolom database --}}
                                                        <option value="{{ $p->serial_number }}" 
                                                                data-price="{{ $p->selling_price ?? $p->harga_jual }}" 
                                                                data-name="{{ $p->name ?? $p->nama_barang }}">
                                                            {{ $p->name ?? $p->nama_barang }} (Stok: {{ $p->stock ?? $p->stok }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-2 border-0">
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0">Rp</span>
                                                    <input type="number" name="products[0][price]" class="form-control price-input border-start-0 ps-0 text-dark" placeholder="0" required oninput="calcGrandTotal()">
                                                </div>
                                            </td>
                                            <td class="p-2 border-0">
                                                <input type="number" name="products[0][qty]" class="form-control qty-input text-center text-dark" value="1" min="1" required oninput="calcGrandTotal()">
                                            </td>
                                            <td class="p-2 border-0">
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0">Rp</span>
                                                    <input type="text" class="form-control subtotal-input border-start-0 ps-0 text-primary fw-bold" readonly placeholder="0">
                                                </div>
                                            </td>
                                            <td class="text-center p-2 border-0">
                                                <button type="button" class="btn btn-link text-danger mb-0 px-2 shadow-none" onclick="removeRow(this)">
                                                    <i class="fas fa-times-circle fs-5"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="px-4 py-3 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-dark mb-0 shadow-sm" onclick="addRow()" style="border-radius: 6px;">
                                    <i class="fas fa-plus me-1"></i> Tambah Produk
                                </button>
                                <input type="hidden" name="total_price" id="grand-total-input" value="0">
                            </div>

                            <div class="row mt-3 px-4">
                                <div class="col-12 text-end pb-4 border-top pt-4">
                                    <a href="{{ route('sales.index')}}" class="btn btn-light mb-0 me-2 shadow-sm text-dark">Batal</a>
                                    <button type="button" id="btn-simpan" class="btn bg-gradient-primary mb-0 shadow-sm px-4">
                                        <i class="fas fa-check-circle me-2"></i> Proses Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 animate__animated animate__fadeInRight">
                    <div class="receipt-container mb-4" id="printable_receipt">
                        <button type="button" class="print-btn no-print d-flex align-items-center justify-content-center" onclick="window.print()" title="Cetak Struk">
                            <i class="fas fa-print"></i>
                        </button>
                        <div class="receipt-header">
                            <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: 1px;">MADURA MART</h5>
                            <p class="text-xs mb-0 text-secondary">Premium Grocery & Goods</p>
                            <p class="text-xxs text-secondary">Jl. Raya Madura No. 123</p>
                        </div>
                        <div class="receipt-body" style="min-height: 150px;">
                            <div class="receipt-line text-xxs text-secondary mb-4 pb-2" style="border-bottom: 1px solid #f1f5f9;">
                                <span>Ref: <span id="r_ref">{{ $next_number }}</span></span>
                                <span>Tgl: {{ date('d/m/Y') }}</span>
                            </div>
                            <div id="receipt_items">
                                <p class="text-center text-xs text-secondary my-5" style="font-style: italic;">Struk kosong. Silakan pilih produk...</p>
                            </div>
                        </div>
                        <div class="receipt-footer mt-4">
                            <div class="receipt-total d-flex justify-content-between">
                                <span>TOTAL</span>
                                <span id="r_total">Rp 0</span>
                            </div>
                            <div class="text-center mt-5">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=MADURA+MART" id="receipt_qr" alt="QR" class="opacity-8" style="width: 70px; height: 70px; border-radius: 5px;">
                                <p class="text-xxs text-secondary mt-3 fw-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = 1;

        // Format Rupiah (Format ke string dengan titik)
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }
        
        // Format angka polos (hanya pemisah ribuan untuk di dalam input subtotal agar rapi)
        function formatAngka(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Tambah Baris Produk
        function addRow() {
            let container = document.getElementById('items-container');
            let html = `
                <tr class="item-row">
                    <td class="p-2 border-0">
                        <select name="products[${itemIndex}][serial]" class="form-select product-select fw-bold text-dark" required onchange="updatePrice(this)">
                            <option value="" disabled selected>Pilih Produk...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->serial_number }}" data-price="{{ $p->selling_price ?? $p->harga_jual }}" data-name="{{ $p->name ?? $p->nama_barang }}">
                                    {{ $p->name ?? $p->nama_barang }} (Stok: {{ $p->stock ?? $p->stok }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-2 border-0">
                        <div class="input-group">
                            <span class="input-group-text border-end-0">Rp</span>
                            <input type="number" name="products[${itemIndex}][price]" class="form-control price-input border-start-0 ps-0 text-dark" placeholder="0" required oninput="calcGrandTotal()">
                        </div>
                    </td>
                    <td class="p-2 border-0">
                        <input type="number" name="products[${itemIndex}][qty]" class="form-control qty-input text-center text-dark" value="1" min="1" required oninput="calcGrandTotal()">
                    </td>
                    <td class="p-2 border-0">
                        <div class="input-group">
                            <span class="input-group-text border-end-0">Rp</span>
                            <input type="text" class="form-control subtotal-input border-start-0 ps-0 text-primary fw-bold" readonly placeholder="0">
                        </div>
                    </td>
                    <td class="text-center p-2 border-0">
                        <button type="button" class="btn btn-link text-danger mb-0 px-2 shadow-none" onclick="removeRow(this)">
                            <i class="fas fa-times-circle fs-5"></i>
                        </button>
                    </td>
                </tr>`;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }

        // Update Harga saat Dropdown dipilih
        function updatePrice(selectElement) {
            let price = selectElement.options[selectElement.selectedIndex].getAttribute('data-price');
            let row = selectElement.closest('tr');
            row.querySelector('.price-input').value = price;
            calcGrandTotal();
        }

        // Hitung Keseluruhan & Update UI
        function calcGrandTotal() {
            let total = 0;
            let receiptHtml = '';
            let itemCount = 0;

            document.querySelectorAll('.item-row').forEach((row) => {
                let select = row.querySelector('.product-select');
                let price = parseFloat(row.querySelector('.price-input').value) || 0;
                let qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                let sub = price * qty;
                
                // Tampilkan angka format rapi di kolom subtotal
                row.querySelector('.subtotal-input').value = formatAngka(sub);
                total += sub;

                // Bangun HTML untuk Struk
                if (select.value && qty > 0) {
                    let productName = select.options[select.selectedIndex].getAttribute('data-name');
                    receiptHtml += `
                        <div class="receipt-line">
                            <span style="flex: 2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding-right: 10px;">${productName}</span>
                            <span style="flex: 0.5; text-align: center;">x${qty}</span>
                            <span style="flex: 1.5; text-align: right;">${formatAngka(sub)}</span>
                        </div>
                        <div class="text-xxs text-secondary text-end mb-2" style="font-style: italic;">@ ${formatAngka(price)}</div>
                    `;
                    itemCount++;
                }
            });

            // Update Input Form Hidden
            document.getElementById('grand-total-input').value = total;
            
            // Update Layar Besar
            let largeTotal = document.getElementById('large_total');
            largeTotal.innerText = formatRupiah(total);
            
            // Re-trigger animation
            largeTotal.classList.remove('animate__animated', 'animate__pulse');
            void largeTotal.offsetWidth;
            largeTotal.classList.add('animate__animated', 'animate__pulse');

            // Update Detail Information (Kiri Atas)
            if (itemCount > 0) {
                document.getElementById('detail_product_name').innerText = itemCount + " Produk Terpilih";
                document.getElementById('detail_calculation').innerText = "Menunggu Proses Pembayaran...";
                document.getElementById('stock_indicator').classList.remove('d-none');
                document.getElementById('receipt_items').innerHTML = receiptHtml;
            } else {
                document.getElementById('detail_product_name').innerText = "Siap Melayani";
                document.getElementById('detail_calculation').innerText = "Pilih produk untuk memulai transaksi";
                document.getElementById('stock_indicator').classList.add('d-none');
                document.getElementById('receipt_items').innerHTML = '<p class="text-center text-xs text-secondary my-5" style="font-style: italic;">Struk kosong. Silakan pilih produk...</p>';
            }

            // Update Total Struk
            document.getElementById('r_total').innerText = formatRupiah(total);

            // Update Dynamic QR Code
            let noStruk = document.getElementById('sale_number').value;
            let qrData = `MADURA MART | Ref: ${noStruk} | Total: ${formatRupiah(total)}`;
            document.getElementById('receipt_qr').src = `https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(qrData)}`;
        }

        // Hapus Baris
        function removeRow(btn) {
            if(document.querySelectorAll('.item-row').length > 1) {
                btn.closest('tr').remove();
                calcGrandTotal();
            } else {
                swal("Perhatian!", "Minimal harus ada 1 produk dalam transaksi!", "warning");
            }
        }

        // Submit Animation & SweetAlert
        document.getElementById('btn-simpan').addEventListener('click', function(e) {
            e.preventDefault();
            
            let total = document.getElementById('grand-total-input').value;
            let firstSelect = document.querySelector('.product-select').value;
            
            if (!firstSelect || total <= 0) {
                swal("Tidak Valid", "Pilih produk dan pastikan jumlah pesanan benar!", "error");
                return;
            }

            swal({
                title: "Konfirmasi Pembayaran",
                text: "Total Tagihan: " + formatRupiah(total) + "\nLanjutkan proses transaksi?",
                icon: "info",
                buttons: ["Periksa Kembali", "Proses Bayar"],
                dangerMode: false,
            })
            .then((willProcess) => {
                if (willProcess) {
                    // Animasi Confetti
                    confetti({
                        particleCount: 150,
                        spread: 70,
                        origin: { y: 0.6 },
                        colors: ['#cb0c9f', '#f5365c', '#2dce89']
                    });
                    
                    // Ganti teks tombol
                    let btn = document.getElementById('btn-simpan');
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                    btn.classList.add('disabled');
                    
                    // Delay submit agar user bisa melihat animasi
                    setTimeout(() => {
                        document.getElementById('form-sale').submit();
                    }, 1500);
                }
            });
        });
    </script>
@endsection