@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('distributor')
    {{-- Ensure Icons Load --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Custom CSS for Premium Look --}}
    <style>
        :root {
            --soft-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --primary-gradient: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
        }

        .bg-gray-100 { background-color: #f8f9fa !important; }
        
        /* Modern Card */
        .card-modern {
            border: none;
            border-radius: 24px;
            box-shadow: var(--soft-shadow);
            background: #fff;
            transition: transform 0.2s;
            overflow: hidden;
        }

        /* Table Styling */
        .table-modern thead th {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #8392ab;
            border-bottom: 1px solid #edf2f7;
            padding: 1.5rem 1rem;
            background-color: #f8f9fa;
            font-weight: 700;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease-in-out;
            border-bottom: 1px solid #f1f1f1;
        }

        .table-modern tbody tr:hover {
            background-color: #fafbfc;
            transform: scale(1.002);
            box-shadow: var(--hover-shadow);
            z-index: 10;
            position: relative;
        }

        .table-modern td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: #495057;
            font-size: 0.9rem;
        }

        /* Avatar / Icon Styling */
        .avatar-initial {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 6px rgba(33, 82, 255, 0.25);
        }

        /* Action Buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
            background: transparent;
            color: #8392ab;
        }

        .btn-action:hover {
            background-color: #f0f2f5;
            transform: translateY(-2px);
        }

        .btn-action.edit:hover { color: #fb6340; background-color: rgba(251, 99, 64, 0.1); }
        .btn-action.delete:hover { color: #f5365c; background-color: rgba(245, 54, 92, 0.1); }

        /* Floating Alert */
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 320px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>

    <div class="container-fluid py-4" style="min-height: 85vh;">
        
        {{-- Floating Alerts --}}
        @if (session('success'))
            <div class="alert alert-success floating-alert text-white d-flex align-items-center" role="alert" style="background: linear-gradient(310deg, #2dce89 0%, #2dcecc 100%);">
                <span class="alert-icon me-3"><i class="fas fa-check-circle fa-lg"></i></span>
                <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger floating-alert text-white d-flex align-items-center" role="alert" style="background: linear-gradient(310deg, #f5365c 0%, #f56036 100%);">
                <span class="alert-icon me-3"><i class="fas fa-exclamation-circle fa-lg"></i></span>
                <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-modern mb-4">
                    {{-- Header Section --}}
                    <div class="card-header pb-0 bg-white border-0 pt-4 px-4">
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 font-weight-bolder text-dark">{{ $title }} Management</h5>
                                <p class="text-sm text-muted mb-0">
                                    View and manage your verified distributors.
                                </p>
                            </div>
                            <div class="mt-3 mt-lg-0 text-end">
                                <a href="{{ route('distributors.create') }}" class="btn bg-gradient-dark btn-sm mb-0 shadow-lg px-4 py-2" id="btn-add-distributor" style="border-radius: 8px;">
                                    <i class="fas fa-plus me-2"></i>Add New Distributor
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Table Section --}}
                    <div class="card-body px-0 pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-modern align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center opacity-7" style="width: 5%">No.</th>
                                        <th class="ps-4 opacity-7" style="width: 25%">Distributor Name</th>
                                        <th class="ps-2 opacity-7" style="width: 35%">Address</th>
                                        <th class="text-center opacity-7" style="width: 20%">Contact</th>
                                        <th class="text-center opacity-7" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($distributors as $no => $item)
                                        <tr>
                                            {{-- 1. Number --}}
                                            <td class="text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $no + 1 }}</span>
                                            </td>

                                            {{-- 2. Name & Avatar --}}
                                            <td>
                                                <div class="d-flex px-3 py-1 align-items-center">
                                                    <div class="avatar-initial me-3">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $item->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">ID: #DST-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- 3. Address --}}
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 text-secondary text-wrap" style="max-width: 350px; line-height: 1.5;">
                                                    <i class="fas fa-map-marker-alt me-1 text-xs"></i> {{ $item->address }}
                                                </p>
                                            </td>

                                            {{-- 4. Phone Number --}}
                                            <td class="align-middle text-center">
                                                <span class="badge bg-gradient-secondary badge-sm" style="font-weight: 500; letter-spacing: 0.5px;">
                                                    <i class="fas fa-phone me-1"></i> {{ $item->phone_number }}
                                                </span>
                                            </td>

                                            {{-- 5. Actions --}}
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('distributors.edit', $item->id) }}" 
                                                       class="btn-action edit confirm-edit" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-title="Edit Distributor">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>

                                                    <form action="{{ route('distributors.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="btn-action delete btn-delete" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-title="Delete Distributor">
                                                            <i class="fas fa-trash-alt text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Empty State --}}
                        @if(count($distributors) == 0)
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ni ni-delivery-fast text-secondary opacity-3" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-secondary font-weight-normal">No distributors found</h6>
                                <a href="{{ route('distributors.create') }}" class="btn btn-link text-primary text-gradient" id="btn-add-distributor-empty">
                                    Register your first distributor
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="footer pt-3 mt-auto">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>document.write(new Date().getFullYear())</script>,
                            Inventory System made with <i class="fa fa-heart text-danger"></i> by
                            <a href="#" class="font-weight-bold" target="_blank">Creative Tim</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- Script Section --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto hide floating alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.floating-alert');
                alerts.forEach(function(alert) {
                    alert.classList.remove('show');
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500); 
                });
            }, 5000);

            // SweetAlert Configuration
            const swalCustom = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-dark mx-2',
                    cancelButton: 'btn btn-light mx-2'
                },
                buttonsStyling: false
            });

            // Delete Logic
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');
                    swalCustom.fire({
                        title: 'Are you sure?',
                        text: "This distributor record will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Edit Logic
            document.querySelectorAll('.confirm-edit').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    swalCustom.fire({
                        title: 'Edit Distributor?',
                        text: "You will be redirected to the editing form.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Edit',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            // Add Logic
            const addBtns = document.querySelectorAll('#btn-add-distributor, #btn-add-distributor-empty');
            addBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    swalCustom.fire({
                        title: 'New Distributor',
                        text: "Register a new distributor partner?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Register',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
@endsection