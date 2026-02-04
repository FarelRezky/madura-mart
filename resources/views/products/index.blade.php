@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('products')
    {{-- 1. Load FontAwesome (Crucial for Icons) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- 2. Custom CSS for Modern UI --}}
    <style>
        :root {
            --soft-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .bg-gray-100 { background-color: #f8f9fa !important; }
        
        /* Modern Card */
        .card-modern {
            border: none;
            border-radius: 20px;
            box-shadow: var(--soft-shadow);
            background: #fff;
            transition: transform 0.2s;
            overflow: hidden;
        }

        /* Table Styling */
        .table-modern thead th {
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #8392ab;
            border-bottom: 1px solid #edf2f7;
            padding: 1.5rem 1rem;
            background-color: #f8f9fa;
        }

        .table-modern td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f1;
        }

        /* IMAGE CONTAINER - Updated with pointer for modal trigger */
        .product-image-container {
            width: 80px; 
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer; /* Indicates clickable */
            transition: transform 0.2s;
        }

        .product-image-container:hover {
            transform: scale(1.05);
            border-color: #5e72e4;
        }

        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Badge Styling */
        .badge-soft {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .badge-soft-success { background-color: #e6fffa; color: #2dce89; }
        .badge-soft-warning { background-color: #fffaf0; color: #fc8181; }
        .badge-soft-danger { background-color: #fff5f5; color: #f5365c; }
        .badge-soft-secondary { background-color: #e2e8f0; color: #4a5568; }

        /* Action Buttons */
        .btn-link-custom {
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-link-custom:hover { transform: translateY(-1px); }
    </style>

    <div class="container-fluid py-4" style="min-height: 85vh;">
        
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success text-white mb-4 border-0 shadow-sm" role="alert">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white mb-4 border-0 shadow-sm" role="alert">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-modern mb-4">
                    {{-- Header --}}
                    <div class="card-header pb-0 bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 font-weight-bolder text-dark">{{ $title }} Management</h5>
                            <p class="text-sm text-muted mb-0">Manage your inventory items securely.</p>
                        </div>
                        <a href="{{ route('products.create') }}" class="btn bg-gradient-dark btn-sm mb-0 shadow-lg" style="border-radius: 8px;">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="card-body px-0 pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-modern align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center opacity-7">No.</th>
                                        <th class="text-center opacity-7">Photo</th>
                                        <th class="ps-2 opacity-7">Product Details</th>
                                        <th class="text-center opacity-7">Category</th>
                                        <th class="text-center opacity-7">Price & Expiry</th>
                                        <th class="text-center opacity-7">Status</th>
                                        <th class="text-center opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $no => $item)
                                        <tr>
                                            {{-- 1. Number --}}
                                            <td class="text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $no + 1 }}</span>
                                            </td>

                                            {{-- 2. PHOTO WITH MODAL TRIGGER --}}
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center">
                                                    {{-- The Trigger --}}
                                                    <div class="product-image-container" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $item->id }}">
                                                        @if($item->picture)
                                                            <img src="{{ asset('images/products/' . $item->picture) }}" 
                                                                 alt="{{ $item->name }}"
                                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text=No+Img';">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light">
                                                                <i class="fas fa-image text-secondary opacity-5 fa-2x"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- THE MODAL --}}
                                                    <div class="modal fade" id="staticBackdrop{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel{{ $item->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">{{ $item->name }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center p-4">
                                                                    @if($item->picture)
                                                                        <img src="{{ asset('images/products/' . $item->picture) }}" 
                                                                             alt="{{ $item->name }}" 
                                                                             class="img-fluid rounded shadow-sm" 
                                                                             style="max-height: 400px;">
                                                                    @else
                                                                        <div class="py-5 text-muted">
                                                                            <i class="fas fa-image fa-3x mb-3"></i>
                                                                            <p>No image available</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal --}}
                                                </div>
                                            </td>

                                            {{-- 3. Name & Serial --}}
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $item->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">SN: <span class="font-weight-bold">{{ $item->serial_number }}</span></p>
                                                </div>
                                            </td>

                                            {{-- 4. Category --}}
                                            <td class="align-middle text-center">
                                                <span class="badge badge-soft badge-soft-secondary">{{ $item->type }}</span>
                                            </td>

                                            {{-- 5. Price --}}
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column">
                                                    <span class="text-sm font-weight-bold text-dark">
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-xs text-muted">
                                                        Exp: {{ $item->expiration_date ? $item->expiration_date->format('d M Y') : '-' }}
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- 6. Stock --}}
                                            <td class="align-middle text-center">
                                                @php
                                                    $stockClass = $item->stock > 10 ? 'badge-soft-success' : ($item->stock > 0 ? 'badge-soft-warning' : 'badge-soft-danger');
                                                    $stockText = $item->stock > 10 ? 'In Stock' : ($item->stock > 0 ? 'Low Stock' : 'Out of Stock');
                                                @endphp
                                                <span class="badge badge-soft {{ $stockClass }} mb-1">{{ $stockText }}</span>
                                                <div class="text-xs text-secondary font-weight-bold">{{ $item->stock }} Units</div>
                                            </td>

                                            {{-- 7. Actions --}}
                                            <td class="align-middle text-center">
                                                <a href="{{ route('products.edit', $item->id) }}" class="btn-link-custom text-dark me-3 confirm-edit">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                
                                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-link btn-link-custom text-danger p-0 m-0 btn-delete">
                                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Empty State --}}
                        @if(count($datas) == 0)
                            <div class="text-center py-5">
                                <h6 class="text-secondary">No products found.</h6>
                                <a href="{{ route('products.create') }}" class="btn btn-link text-primary">Create your first product</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer pt-3 mt-auto">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>document.write(new Date().getFullYear())</script>,
                            Inventory System made with <i class="fa fa-heart text-danger"></i> by Creative Tim
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- SweetAlert Logic --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete Confirmation
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This item will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#344767',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // Edit Confirmation
            document.querySelectorAll('.confirm-edit').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    Swal.fire({
                        title: 'Edit Product?',
                        text: "You will be redirected to the edit page.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#344767',
                        confirmButtonText: 'Yes, Edit'
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = url;
                    });
                });
            });
        });
    </script>
@endsection