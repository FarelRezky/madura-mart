@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('products')
    <style>
        :root {
            --primary-gradient: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        /* Modern Card Container */
        .card-modern {
            border: none;
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            background: #fff;
            overflow: hidden;
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
            padding: 2rem;
            transition: all 0.5s ease;
        }

        .preview-stage::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%235e72e4' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .image-wrapper {
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .image-wrapper:hover {
            transform: translateY(-5px);
        }

        .preview-img-style {
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            max-height: 400px;
            width: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        /* Right Side - Form */
        .form-section {
            padding: 3rem 2.5rem;
        }

        /* Custom Input Styling */
        .form-control {
            border: 1px solid #e0e6ed;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #5e72e4;
            box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #fd5c70;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23fd5c70'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23fd5c70' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #fd5c70;
        }

        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8898aa;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Upload Area */
        .upload-area {
            border: 2px dashed #e0e6ed;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fcfcfc;
        }

        .upload-area:hover, .upload-area.drag-over {
            border-color: #5e72e4;
            background: rgba(94, 114, 228, 0.02);
            transform: scale(1.01);
        }

        .upload-icon {
            color: #5e72e4;
            font-size: 2rem;
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }

        .upload-area:hover .upload-icon {
            transform: translateY(-5px);
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            letter-spacing: 0.025em;
            transition: all 0.3s;
        }

        .btn-modern-primary {
            background: #344767;
            color: white;
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-modern-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            background: #2b3c58;
            color: white;
        }

        .btn-modern-light {
            background: #fff;
            color: #67748e;
            border: 1px solid #e0e6ed;
        }

        .btn-modern-light:hover {
            background: #f8f9fa;
            color: #344767;
        }
    </style>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <div class="card card-modern">
                    <div class="row g-0">
                        {{-- LEFT SIDE: Visual Preview Stage --}}
                        <div class="col-lg-5 d-none d-lg-flex preview-stage">
                            <div class="text-center w-100 px-4">
                                <h4 class="mb-4 text-gradient text-primary font-weight-bolder">Product Preview</h4>
                                
                                <div class="image-wrapper" id="preview-container">
                                    {{-- Default Placeholder --}}
                                    <div id="default-view">
                                        <img src="{{ asset('images/makise.png') }}" 
                                             alt="Default Illustration" 
                                             class="preview-img-style">
                                        <p class="text-muted mt-3 fst-italic text-sm">"Makise Kurisu edition"</p>
                                    </div>

                                    {{-- Dynamic Preview --}}
                                    <div id="dynamic-view" style="display: none;">
                                        <img id="preview-img-main" src="" alt="Preview" class="preview-img-style">
                                        <div class="mt-3">
                                            <span class="badge bg-gradient-success mb-2">New Upload</span>
                                            <p id="preview-filename" class="text-sm font-weight-bold text-dark mb-0"></p>
                                            <p id="preview-size" class="text-xs text-muted"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT SIDE: Form --}}
                        <div class="col-lg-7">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="font-weight-bolder mb-0 text-dark">Create New Product</h4>
                                    <span class="badge bg-light text-dark border">Inventory System</span>
                                </div>

                                <form id="create-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Section 1: Basic Info --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label-custom">Serial Number</label>
                                                {{-- Added 'is-invalid' logic for backend validation display --}}
                                                <input type="text" name="serial_number" 
                                                       class="form-control @error('serial_number') is-invalid @enderror" 
                                                       value="{{ old('serial_number') }}"
                                                       placeholder="e.g. SN-2026-001" required>
                                                @error('serial_number')
                                                    <div class="invalid-feedback" style="display: block;">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label-custom">Product Name</label>
                                                <input type="text" name="name" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       value="{{ old('name') }}"
                                                       placeholder="e.g. Future Gadget 204" required>
                                                @error('name')
                                                    <div class="invalid-feedback" style="display: block;">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Section 2: Details --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label-custom">Category / Type</label>
                                                <input type="text" name="type" 
                                                       class="form-control @error('type') is-invalid @enderror" 
                                                       value="{{ old('type') }}"
                                                       placeholder="e.g. Electronics" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label-custom">Expiration Date</label>
                                                <input type="date" name="expiration_date" 
                                                       class="form-control"
                                                       value="{{ old('expiration_date') }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Section 3: Pricing & Stock --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Price (IDR)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                                <input type="number" name="price" 
                                                       class="form-control border-start-0 ps-0" 
                                                       value="{{ old('price') }}"
                                                       placeholder="0.00" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label-custom">Initial Stock</label>
                                                <input type="number" name="stock" 
                                                       class="form-control" 
                                                       value="{{ old('stock') }}"
                                                       placeholder="0" min="0">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Section 4: File Upload --}}
                                    <div class="mb-4">
                                        <label class="form-label-custom">Product Image</label>
                                        <div id="upload-area" class="upload-area">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <h6 class="font-weight-bold mb-1">Click to upload or drag & drop</h6>
                                            <p class="text-xs text-muted mb-0">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                                            <input type="file" id="picture-input" name="picture" class="d-none" accept="image/*">
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    {{-- Actions --}}
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" onclick="confirmCancel(event)" class="btn btn-modern btn-modern-light">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-modern btn-modern-primary" id="submit-btn">
                                            <i class="fas fa-save me-2"></i> Save Product
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
    
    {{-- ALERT LOGIC: Check for Laravel Validation Errors --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorHtml = '<ul style="text-align: left;">';
                @foreach ($errors->all() as $error)
                    errorHtml += '<li>{{ $error }}</li>';
                @endforeach
                errorHtml += '</ul>';

                Swal.fire({
                    title: 'Validation Error!',
                    html: errorHtml,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#344767'
                });
            });
        </script>
    @endif

    <script>
        // --- Cancel Confirmation ---
        function confirmCancel(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Discard Changes?',
                text: "Any unsaved data will be lost.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#344767',
                cancelButtonColor: '#e9ecef',
                confirmButtonText: 'Yes, Discard',
                cancelButtonText: '<span style="color:#555">Keep Editing</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('products.index') }}";
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- Form Submission ---
            const form = document.getElementById('create-form');
            form.addEventListener('submit', function(event) {
                // We perform a basic check before alerting, but full validation happens on backend
                event.preventDefault();
                const formElement = this; // Capture the form reference
                Swal.fire({
                    title: 'Create Product?',
                    text: "Please confirm the details are correct.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#344767',
                    cancelButtonColor: '#e9ecef',
                    confirmButtonText: 'Yes, Create',
                    cancelButtonText: '<span style="color:#555">Cancel</span>',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        formElement.submit();
                    }
                });
            });

            // --- Enhanced File Upload Logic ---
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('picture-input');
            const defaultView = document.getElementById('default-view');
            const dynamicView = document.getElementById('dynamic-view');
            const previewImgMain = document.getElementById('preview-img-main');
            const previewFilename = document.getElementById('preview-filename');
            const previewSize = document.getElementById('preview-size');

            // Trigger file input click
            uploadArea.addEventListener('click', () => fileInput.click());

            // Handle File Selection
            fileInput.addEventListener('change', handleFileSelect);

            // Drag & Drop Events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => uploadArea.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('drag-over'), false);
            });

            uploadArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect();
                }
            });

            function handleFileSelect() {
                const file = fileInput.files[0];
                if (file) {
                    // Validation
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        Swal.fire('Invalid File', 'Please select a valid image file.', 'error');
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('File Too Large', 'Image must be less than 2MB.', 'warning');
                        return;
                    }

                    // Update Visuals
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImgMain.src = e.target.result;
                        previewFilename.textContent = file.name;
                        previewSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                        
                        // Switch Views
                        defaultView.style.display = 'none';
                        dynamicView.style.display = 'block';
                        
                        // Highlight Upload Area
                        uploadArea.style.borderColor = '#2dce89';
                        uploadArea.style.background = 'rgba(45, 206, 137, 0.05)';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endsection