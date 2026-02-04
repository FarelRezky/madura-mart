@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('distributor')
    {{-- Custom CSS for Modern UI --}}
    <style>
        :root {
            --primary-gradient: linear-gradient(310deg, #141727 0%, #3a416f 100%);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            --input-border: #e0e6ed;
            --input-focus: #344767;
        }

        /* Modern Card Container */
        .card-modern {
            border: none;
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            background: #fff;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        /* Left Side - Visual Stage */
        .preview-stage {
            background: #f8f9fa;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100%;
            padding: 3rem;
            overflow: hidden;
        }

        /* Subtle Pattern Background */
        .preview-stage::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.4;
            background-image: radial-gradient(#344767 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .image-wrapper {
            position: relative;
            z-index: 2;
            transition: transform 0.4s ease;
        }

        .image-wrapper:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .illustration-img {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            max-height: 400px;
            width: 100%;
            object-fit: cover;
        }

        /* Right Side - Form */
        .form-section {
            padding: 3.5rem 3rem;
        }

        /* Custom Inputs */
        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8392ab;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        .form-control-modern {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .form-control-modern:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(52, 71, 103, 0.1);
            outline: 0;
        }

        /* Input Group Icons */
        .input-group-text-modern {
            background: transparent;
            border: 1px solid var(--input-border);
            border-right: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            color: #8392ab;
            padding-left: 1.25rem;
        }
        
        .has-icon .form-control-modern {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .has-icon .input-group-text-modern + .form-control-modern:focus {
            border-left: 1px solid var(--input-focus);
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            color: #fff;
        }

        .btn-light-modern {
            background: #fff;
            color: #67748e;
            border: 1px solid #e9ecef;
        }

        .btn-light-modern:hover {
            background-color: #f8f9fa;
            color: #344767;
            border-color: #d1d7e1;
        }
    </style>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <div class="card card-modern">
                    <div class="row g-0">
                        
                        {{-- LEFT COLUMN: Visual Stage --}}
                        <div class="col-lg-5 d-none d-lg-flex preview-stage">
                            <div class="text-center w-100">
                                <div class="image-wrapper mb-4">
                                    <img src="{{ asset('images/makise.png') }}" 
                                         alt="Distributor Illustration" 
                                         class="illustration-img">
                                </div>
                                <h5 class="text-gradient text-dark font-weight-bolder mb-1">Partner Network</h5>
                                <p class="text-sm text-muted opacity-8">"Registering new reliable distributors for better reach."</p>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-handshake me-1"></i> New Partnership
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Form --}}
                        <div class="col-lg-7">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-5">
                                    <div>
                                        <h3 class="font-weight-bolder text-dark mb-0">{{ $title }}</h3>
                                        <p class="text-muted text-sm mb-0">Fill in the details to register a new distributor.</p>
                                    </div>
                                    <div class="icon icon-shape bg-gradient-dark shadow-md text-center border-radius-md">
                                        <i class="fas fa-truck text-white text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <form id="create-form" action="{{ route('distributors.store') }}" method="POST" novalidate>
                                    @csrf

                                    {{-- Row 1: Name & Phone --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group has-icon">
                                                <label>Distributor Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text input-group-text-modern">
                                                        <i class="fas fa-building text-xs"></i>
                                                    </span>
                                                    <input type="text" name="name" class="form-control form-control-modern" 
                                                           placeholder="e.g. PT. Jaya Abadi" required>
                                                    <div class="invalid-feedback">Name is required.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-icon">
                                                <label>Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text input-group-text-modern">
                                                        <i class="fas fa-phone text-xs"></i>
                                                    </span>
                                                    <input type="tel" name="phone_number" class="form-control form-control-modern" 
                                                           placeholder="e.g. 0812-3456-7890" required>
                                                    <div class="invalid-feedback">Phone number is required.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 2: Address --}}
                                    <div class="mb-5">
                                        <div class="form-group">
                                            <label>Full Address</label>
                                            <div class="input-group has-icon">
                                                <span class="input-group-text input-group-text-modern" style="border-bottom-left-radius: 0; height: auto;">
                                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                                </span>
                                                <textarea name="address" class="form-control form-control-modern" 
                                                          rows="4" placeholder="Enter complete office or warehouse address..." 
                                                          style="border-bottom-left-radius: 0; border-left: none;" required></textarea>
                                                <div class="invalid-feedback">Address is required.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Actions --}}
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" onclick="confirmCancel(event)" class="btn btn-modern btn-light-modern">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-modern btn-primary-modern" id="submit-btn">
                                            <i class="fas fa-save me-2"></i> Save Distributor
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer pt-5">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted">
                            © <script>document.write(new Date().getFullYear())</script>,
                            Inventory System made with <i class="fa fa-heart text-danger"></i> by
                            <a href="#" class="font-weight-bold text-dark">Creative Tim</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- SweetAlert & Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- Cancel Logic ---
        function confirmCancel(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Discard Changes?',
                text: "Any entered data will be lost.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#344767',
                cancelButtonColor: '#e9ecef',
                confirmButtonText: 'Yes, Discard',
                cancelButtonText: '<span style="color:#555">Keep Editing</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('distributors.index') }}";
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- Form Submission Logic ---
            const form = document.getElementById('create-form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Basic Client-Side Validation check
                    if (!form.checkValidity()) {
                        event.stopPropagation();
                        form.classList.add('was-validated');
                        return; // Stop if empty
                    }

                    Swal.fire({
                        title: 'Register Distributor?',
                        text: "Please verify the details before saving.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#344767',
                        cancelButtonColor: '#e9ecef',
                        confirmButtonText: 'Yes, Register',
                        cancelButtonText: '<span style="color:#555">Review</span>',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optional: Show loading state
                            const btn = document.getElementById('submit-btn');
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                            btn.disabled = true;
                            
                            this.submit();
                        }
                    });
                });
            }
        });
    </script>

    {{-- Duplicate Data Error --}}
    @if (session('duplikat'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Entry',
                    text: "{{ session('duplikat') }}",
                    confirmButtonColor: '#344767'
                });
            });
        </script>
    @endif
@endsection