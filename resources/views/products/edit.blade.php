@extends('layout.master')

@section('menu')
    @include('layout.menu')
@endsection

@section('products')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>{{ $title }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">

                            <div class="col-md-5 text-center mb-4 mb-md-0">
                                <div class="position-relative">
                                    <img src="{{ asset('images/makise.png') }}"
                                         alt="Illustration"
                                         class="img-fluid border-radius-lg shadow-sm"
                                         style="max-height: 450px; width: 100%; object-fit: cover;">

                                    <div class="mt-3 text-sm text-muted">
                                        <em>Makise Kurisu 🎶</em>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="p-3">
                                    <form id="edit-form" role="form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <label>Serial Number</label>
                                        <div class="mb-3">
                                            <input type="text" name="serial_number" class="form-control"
                                                placeholder="Input Product Serial Number" aria-label="SerialNumber" value="{{ $product->serial_number }}" required>
                                        </div>

                                        <label>Name</label>
                                        <div class="mb-3">
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Input Product Name" aria-label="Name" value="{{ $product->name }}" required>
                                        </div>

                                        <label>Type</label>
                                        <div class="mb-3">
                                            <input type="text" name="type" class="form-control"
                                                placeholder="Input Product Type" aria-label="Type" value="{{ $product->type }}" required>
                                        </div>

                                        <label>Expiration Date</label>
                                        <div class="mb-3">
                                            <input type="date" name="expiration_date" class="form-control"
                                                aria-label="ExpirationDate" value="{{ $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '' }}">
                                        </div>

                                        <label>Price</label>
                                        <div class="mb-3">
                                            <input type="number" name="price" class="form-control"
                                                placeholder="Input Product Price" aria-label="Price" min="0" step="0.01" value="{{ $product->price }}">
                                        </div>

                                        <label>Stock</label>
                                        <div class="mb-3">
                                            <input type="number" name="stock" class="form-control"
                                                placeholder="Input Product Stock" aria-label="Stock" min="0" value="{{ $product->stock }}">
                                        </div>

                                        <label>Picture</label>
                                        <div class="mb-3">
                                            @if($product->picture)
                                                <div class="mb-3">
                                                    <img id="current-image" src="{{ asset('images/photos/' . $product->picture) }}" alt="Current Picture" class="img-fluid border-radius-lg shadow-sm" style="max-height: 200px;">
                                                    <p class="text-sm text-muted mt-1">Current image</p>
                                                </div>
                                            @endif
                                            <div id="upload-area" class="border border-dashed border-secondary rounded p-4 text-center cursor-pointer" style="cursor: pointer;">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
                                                <p class="mb-1">Click to upload or drag and drop new image</p>
                                                <p class="text-sm text-muted">JPEG, PNG, JPG, GIF (max 2MB)</p>
                                                <p id="file-name" class="text-sm font-weight-bold text-primary mt-2" style="display: none;"></p>
                                            </div>
                                            <input type="file" id="picture-input" name="picture" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif" aria-label="Picture">
                                            <div id="image-preview" class="mt-3" style="display: none;">
                                                <img id="preview-img" src="" alt="New Preview" class="img-fluid border-radius-lg shadow-sm" style="max-height: 200px;">
                                                <p class="text-sm text-success mt-1">New image preview</p>
                                            </div>
                                            <small class="text-muted">Upload new product image (JPEG, PNG, JPG, GIF, max 2MB). Leave empty to keep current image.</small>
                                        </div>

                                        <div class="text-end">
                                            <a href="{{ route('products.index') }}"
                                                class="btn bg-gradient-light mt-4 mb-0">Cancel</a>

                                            <button type="submit" class="btn bg-gradient-dark mt-4 mb-0">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer pt-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>document.write(new Date().getFullYear())</script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                            for a better web.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Update Data?',
                        text: "Pastikan data yang diupdate sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#344767',
                        cancelButtonColor: '#82d616',
                        confirmButtonText: 'Ya, Update!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }

            // File upload interactivity
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('picture-input');
            const fileName = document.getElementById('file-name');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            // Click to open file dialog
            uploadArea.addEventListener('click', () => {
                fileInput.click();
            });

            // File selection
            fileInput.addEventListener('change', handleFileSelect);

            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('bg-light');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('bg-light');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('bg-light');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect();
                }
            });

            function handleFileSelect() {
                const file = fileInput.files[0];
                if (file) {
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            title: 'Invalid File Type',
                            text: 'Please select a JPEG, PNG, JPG, or GIF image.',
                            icon: 'error'
                        });
                        fileInput.value = '';
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            title: 'File Too Large',
                            text: 'Please select an image smaller than 2MB.',
                            icon: 'error'
                        });
                        fileInput.value = '';
                        return;
                    }

                    // Show file name
                    fileName.textContent = file.name;
                    fileName.style.display = 'block';

                    // Show image preview
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    fileName.style.display = 'none';
                    imagePreview.style.display = 'none';
                }
            }
        });
    </script>
@endsection
