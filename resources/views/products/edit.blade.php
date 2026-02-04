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
        .card-modern { border: none; border-radius: 24px; box-shadow: var(--glass-shadow); background: #fff; overflow: hidden; }
        .preview-stage { background: #f8f9fa; padding: 2rem; min-height: 500px; display: flex; align-items: center; justify-content: center;}
        .preview-img-style { border-radius: 16px; box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1); max-height: 400px; width: 100%; object-fit: cover; transition: all 0.3s ease; }
        .form-section { padding: 3rem 2.5rem; }
        .form-control { border: 1px solid #e0e6ed; padding: 0.75rem 1rem; border-radius: 8px; }
        .form-control:focus { border-color: #5e72e4; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1); }
        .form-label-custom { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #8898aa; margin-bottom: 0.5rem; display: block; }
        .upload-area { border: 2px dashed #e0e6ed; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; background: #fcfcfc; transition: all 0.3s; }
        .upload-area:hover { border-color: #5e72e4; background: #f4f6fe; }
        .btn-modern { padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 8px; }
        .btn-primary-modern { background: #344767; color: white; border: none; }
        .btn-light-modern { background: #fff; color: #67748e; border: 1px solid #e0e6ed; }
        .btn-danger-modern { background: #f5365c; color: white; border: none; padding: 0.5rem 1rem; font-size: 0.8rem;}
    </style>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <div class="card card-modern">
                    <div class="row g-0">
                        {{-- LEFT: Preview Stage --}}
                        <div class="col-lg-5 d-none d-lg-flex preview-stage">
                            <div class="text-center w-100 px-4">
                                <h4 class="mb-4 text-primary font-weight-bolder">Product Preview</h4>
                                <div id="preview-container" class="position-relative">
                                    
                                    {{-- Image Container --}}
                                    <div id="image-display-area">
                                        @if($product->picture)
                                            <img id="preview-img-main" 
                                                 src="{{ asset('images/products/' . $product->picture) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="preview-img-style"
                                                 onerror="this.src='https://via.placeholder.com/400x300?text=Image+Not+Found'">
                                        @else
                                            <img id="preview-img-main" 
                                                 src="{{ asset('images/makise.png') }}" 
                                                 alt="Default" 
                                                 class="preview-img-style">
                                        @endif
                                    </div>

                                    {{-- Deleted State Message (Hidden by default) --}}
                                    <div id="deleted-state-msg" class="text-center p-5 border rounded bg-light" style="display: none;">
                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                        <h6 class="text-danger">Image will be removed</h6>
                                        <p class="text-sm text-muted">Click Update to confirm.</p>
                                    </div>
                                    
                                    {{-- Badges and Actions --}}
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <span id="preview-badge" class="badge {{ $product->picture ? 'bg-secondary' : 'bg-light text-dark' }} mb-2">
                                                {{ $product->picture ? 'Current Image' : 'No Image' }}
                                            </span>
                                            <p id="preview-filename" class="text-sm font-weight-bold text-dark mb-0 text-truncate" style="max-width: 200px;">
                                                {{ $product->picture ?? '' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Delete Button (Only show if image exists) --}}
                                        @if($product->picture)
                                        <button type="button" id="btn-remove-image" class="btn btn-danger-modern shadow-sm">
                                            <i class="fas fa-times me-1"></i> Remove
                                        </button>
                                        @endif
                                        
                                        {{-- Undo Delete Button (Hidden) --}}
                                        <button type="button" id="btn-undo-remove" class="btn btn-light-modern shadow-sm" style="display: none;">
                                            <i class="fas fa-undo me-1"></i> Undo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: Form --}}
                        <div class="col-lg-7">
                            <div class="form-section">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="font-weight-bolder mb-0 text-dark">Edit Product</h4>
                                </div>

                                <form id="edit-form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Hidden Input to signal deletion --}}
                                    <input type="hidden" name="delete_picture" id="delete-picture-input" value="0">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Serial Number</label>
                                            <input type="text" name="serial_number" class="form-control" value="{{ $product->serial_number }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Product Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Category</label>
                                            <input type="text" name="type" class="form-control" value="{{ $product->type }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Expiration Date</label>
                                            <input type="date" name="expiration_date" class="form-control" value="{{ $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Price (IDR)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                                <input type="number" name="price" class="form-control border-start-0 ps-0" value="{{ $product->price }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Stock</label>
                                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label-custom">Change Product Image</label>
                                        <div id="upload-area" class="upload-area">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
                                            <h6 class="font-weight-bold mb-1">Click to change or drag & drop</h6>
                                            <p class="text-xs text-muted mb-0">MAX. 2MB (JPG, PNG, GIF)</p>
                                            <input type="file" id="picture-input" name="picture" class="d-none" accept="image/*">
                                        </div>
                                    </div>

                                    <hr class="horizontal dark my-4">

                                    <div class="d-flex justify-content-end gap-3">
                                        <a href="{{ route('products.index') }}" class="btn btn-modern btn-light-modern">Cancel</a>
                                        <button type="submit" class="btn btn-modern btn-primary-modern">Update Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-form');
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('picture-input');
            const previewImgMain = document.getElementById('preview-img-main');
            const previewFilename = document.getElementById('preview-filename');
            const previewBadge = document.getElementById('preview-badge');
            const btnRemoveImage = document.getElementById('btn-remove-image');
            const btnUndoRemove = document.getElementById('btn-undo-remove');
            const deleteInput = document.getElementById('delete-picture-input');
            const imageDisplayArea = document.getElementById('image-display-area');
            const deletedStateMsg = document.getElementById('deleted-state-msg');

            const originalImageSrc = previewImgMain.src;
            const originalFilename = previewFilename.textContent;

            // --- Submit Confirmation ---
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Update Product?',
                    text: "Confirm changes to database.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#344767',
                    confirmButtonText: 'Yes, Update'
                }).then((result) => { if (result.isConfirmed) this.submit(); });
            });

            // --- Remove Image Logic ---
            if (btnRemoveImage) {
                btnRemoveImage.addEventListener('click', function() {
                    // 1. Set hidden input to 1
                    deleteInput.value = '1';
                    // 2. Clear file input if any
                    fileInput.value = '';
                    // 3. Update UI
                    imageDisplayArea.style.display = 'none';
                    deletedStateMsg.style.display = 'block';
                    btnRemoveImage.style.display = 'none';
                    btnUndoRemove.style.display = 'inline-block';
                    previewBadge.textContent = "Pending Deletion";
                    previewBadge.className = "badge bg-danger mb-2";
                    previewFilename.textContent = "";
                });
            }

            // --- Undo Remove Logic ---
            btnUndoRemove.addEventListener('click', function() {
                // 1. Reset hidden input
                deleteInput.value = '0';
                // 2. Restore UI
                imageDisplayArea.style.display = 'block';
                deletedStateMsg.style.display = 'none';
                btnUndoRemove.style.display = 'none';
                if(btnRemoveImage) btnRemoveImage.style.display = 'inline-block';
                
                previewImgMain.src = originalImageSrc;
                previewBadge.textContent = "Current Image";
                previewBadge.className = "badge bg-secondary mb-2";
                previewFilename.textContent = originalFilename;
            });

            // --- File Upload Logic ---
            uploadArea.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // If uploading new file, cancel deletion flag
                    deleteInput.value = '0';
                    imageDisplayArea.style.display = 'block';
                    deletedStateMsg.style.display = 'none';
                    btnUndoRemove.style.display = 'none';
                    if(btnRemoveImage) btnRemoveImage.style.display = 'inline-block';

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImgMain.src = e.target.result;
                        previewFilename.textContent = file.name;
                        previewBadge.textContent = "New Image Selected";
                        previewBadge.className = "badge bg-success mb-2";
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection