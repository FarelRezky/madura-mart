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
            --soft-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.04);
            --hover-shadow: 0 14px 24px -3px rgba(0, 0, 0, 0.08);
            --primary-gradient: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
            --text-main: #334155;
            --text-muted: #64748b;
        }

        body {
            background-color: #f8fafc;
        }
        
        /* Modern Card */
        .card-modern {
            border: none;
            border-radius: 20px;
            box-shadow: var(--soft-shadow);
            background: #fff;
            overflow: hidden;
        }

        /* Table Styling */
        .table-modern thead th {
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 2px solid #f1f5f9;
            padding: 1.2rem 1rem;
            background-color: #fff;
            font-weight: 700;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f8fafc;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
            border-radius: 12px;
        }

        .table-modern td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: var(--text-main);
            font-size: 0.9rem;
        }

        /* Avatar / Icon Styling */
        .avatar-initial {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            box-shadow: 0 8px 16px rgba(0, 97, 255, 0.2);
        }

        /* Call to Action Button */
        .btn-primary-custom {
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 97, 255, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 97, 255, 0.4);
            color: #fff;
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

        /* Soft Edit Button */
        .btn-action.edit { 
            color: #0ea5e9; 
            background-color: #e0f2fe; 
        }
        .btn-action.edit:hover { 
            background-color: #bae6fd; 
            transform: translateY(-2px); 
        }

        /* Soft Delete Button */
        .btn-action.delete { 
            color: #ef4444; 
            background-color: #fee2e2; 
        }
        .btn-action.delete:hover { 
            background-color: #fecaca; 
            transform: translateY(-2px); 
        }

        /* Soft Badge */
        .badge-soft {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Address Styling */
        .address-text {
            color: var(--text-muted);
            line-height: 1.6;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .address-icon {
            color: #cbd5e1;
            margin-top: 3px;
        }

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
            <div class="alert alert-success floating-alert text-white d-flex align-items-center" role="alert" style="background: #10b981;">
                <span class="alert-icon me-3"><i class="fas fa-check-circle fa-lg"></i></span>
                <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger floating-alert text-white d-flex align-items-center" role="alert" style="background: #ef4444;">
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
                                <h5 class="mb-1 font-weight-bolder" style="color: var(--text-main);">{{ $title }} Management</h5>
                                <p class="text-sm mb-0" style="color: var(--text-muted);">
                                    View and manage your verified distributors.
                                </p>
                            </div>
                            <div class="mt-3 mt-lg-0 text-end">
                                <a href="{{ route('distributors.create') }}" class="btn btn-primary-custom btn-sm mb-0 px-4 py-2" id="btn-add-distributor">
                                    <i class="fas fa-plus me-2"></i>Add New Distributor
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Table Section --}}
                    <div class="card-body px-0 pt-4 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-modern align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">No.</th>
                                        <th class="ps-4" style="width: 25%">Distributor Name</th>
                                        <th class="ps-3" style="width: 35%">Address</th>
                                        <th class="text-center" style="width: 20%">Contact</th>
                                        <th class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($distributors as $no => $item)
                                        <tr>
                                            {{-- 1. Number --}}
                                            <td class="text-center">
                                                <span class="text-sm font-weight-bold" style="color: var(--text-muted);">{{ $no + 1 }}</span>
                                            </td>

                                            {{-- 2. Name & Avatar --}}
                                            <td>
                                                <div class="d-flex px-3 py-1 align-items-center">
                                                    <div class="avatar-initial me-3">
                                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-1 text-sm font-weight-bold" style="color: var(--text-main);">{{ $item->name }}</h6>
                                                        <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.7rem; width: fit-content; padding: 0.3em 0.6em;">
                                                            #DST-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- 3. Address --}}
                                            <td>
                                                <div class="address-text text-sm font-weight-normal text-wrap" style="max-width: 350px;">
                                                    <i class="fas fa-map-marker-alt address-icon"></i> 
                                                    <span>{{ $item->address }}</span>
                                                </div>
                                            </td>

                                            {{-- 4. Phone Number --}}
                                            <td class="align-middle text-center">
                                                <span class="badge-soft text-xs">
                                                    <i class="fas fa-phone-alt me-2" style="color: #94a3b8;"></i>{{ $item->phone_number }}
                                                </span>
                                            </td>

                                            {{-- 5. Actions --}}
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('distributors.edit', $item->id) }}" 
                                                       class="btn-action edit confirm-edit" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-title="Edit Distributor">
                                                        <i class="fas fa-pen text-sm"></i>
                                                    </a>

                                                    <form action="{{ route('distributors.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="btn-action delete btn-delete" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-title="Delete Distributor">
                                                            <i class="fas fa-trash text-sm"></i>
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
                                    <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <i class="fas fa-box-open text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <h6 class="text-secondary font-weight-normal mb-1">No distributors found</h6>
                                <p class="text-sm text-muted mb-3">Your distributor list is currently empty.</p>
                                <a href="{{ route('distributors.create') }}" class="btn btn-primary-custom btn-sm" id="btn-add-distributor-empty">
                                    Register First Distributor
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
                            Inventory System made with <i class="fa fa-heart text-danger mx-1"></i> by
                            <a href="#" class="font-weight-bold text-primary" target="_blank">Creative Tim</a>
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
                    confirmButton: 'btn btn-primary-custom mx-2',
                    cancelButton: 'btn btn-light mx-2'
                },
                buttonsStyling: false
            });

            // Delete Logic
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This distributor record will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-danger mx-2', // Override to red for delete
                            cancelButton: 'btn btn-light mx-2'
                        },
                        buttonsStyling: false
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