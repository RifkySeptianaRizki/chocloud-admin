@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
    <h1>Edit Produk: {{ $product->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productEditForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="shopee_link" class="form-label">Link Shopee</label>
            <input type="url" name="shopee_link" id="shopee_link" class="form-control @error('shopee_link') is-invalid @enderror" value="{{ old('shopee_link', $product->shopee_link) }}">
            @error('shopee_link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="whatsapp_link" class="form-label">Link WhatsApp</label>
            <input type="url" name="whatsapp_link" id="whatsapp_link" class="form-control @error('whatsapp_link') is-invalid @enderror" value="{{ old('whatsapp_link', $product->whatsapp_link) }}">
            @error('whatsapp_link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini</label>
            <div>
                <img id="image_preview" src="{{ $product->image ?? '' }}" alt="Preview Gambar" width="150" style="{{ $product->image ? 'display: block;' : 'display: none;' }}">
                @if(!$product->image)
                    <p id="no_image_text" style="{{ $product->image ? 'display: none;' : 'display: block;' }}">Tidak ada gambar.</p>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <label for="image_file" class="form-label">Ganti Gambar (Opsional)</label>
            {{-- --- KRUSIAL: PASTIKAN INPUT FILE INI TIDAK MEMILIKI ATRIBUT 'name' --- --}}
            <input type="file" id="image_file" class="form-control @error('image') is-invalid @enderror">
            {{-- ------------------------------------------------------------------- --}}
            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Format: JPEG, PNG, JPG, WEBP. Ukuran maksimal: 2MB.</small>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <input type="hidden" name="image" id="image_url_hidden" value="{{ old('image', $product->image) }}">
            <input type="hidden" name="image_public_id" id="image_public_id_hidden" value="{{ old('image_public_id', $product->image_public_id) }}">

            <div id="upload_progress" class="progress mt-2" style="display: none;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>
        <button type="submit" id="submitButton" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cloudName = document.querySelector('meta[name="cloudinary-cloud-name"]').content;
            const uploadPreset = "chocloud_unsigned_preset"; // <--- GANTI INI DENGAN NAMA UPLOAD PRESET ANDA

            const imageFileInput = document.getElementById('image_file');
            const imageUrlHiddenInput = document.getElementById('image_url_hidden');
            const imagePublicIdHiddenInput = document.getElementById('image_public_id_hidden');
            const imagePreview = document.getElementById('image_preview');
            const noImageText = document.getElementById('no_image_text');
            const uploadProgress = document.getElementById('upload_progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const submitButton = document.getElementById('submitButton');
            const productEditForm = document.getElementById('productEditForm');

            submitButton.disabled = !imageUrlHiddenInput.value;

            if (imageUrlHiddenInput.value) {
                imagePreview.src = imageUrlHiddenInput.value;
                imagePreview.style.display = 'block';
                if (noImageText) noImageText.style.display = 'none';
            } else {
                imagePreview.style.display = 'none';
                if (noImageText) noImageText.style.display = 'block';
            }


            imageFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) {
                    imageUrlHiddenInput.value = "";
                    imagePublicIdHiddenInput.value = "";
                    imagePreview.style.display = 'none';
                    if (noImageText) noImageText.style.display = 'block';
                    submitButton.disabled = true;
                    return;
                }

                progressBar.style.width = '0%';
                progressBar.textContent = '0%';
                uploadProgress.style.display = 'none';
                imagePreview.style.display = 'none';
                if (noImageText) noImageText.style.display = 'none';
                submitButton.disabled = true;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('upload_preset', uploadPreset);

                uploadProgress.style.display = 'block';

                const xhr = new XMLHttpRequest();
                xhr.open('POST', `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`);

                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = (e.loaded / e.total) * 100;
                        progressBar.style.width = percent.toFixed(0) + '%';
                        progressBar.textContent = percent.toFixed(0) + '%';
                    }
                });

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const data = JSON.parse(xhr.responseText);
                        imageUrlHiddenInput.value = data.secure_url;
                        imagePublicIdHiddenInput.value = data.public_id;
                        imagePreview.src = data.secure_url;
                        imagePreview.style.display = 'block';
                        uploadProgress.style.display = 'none';
                        submitButton.disabled = false;
                        alert('Gambar berhasil diunggah ke Cloudinary!');
                    } else {
                        console.error('Cloudinary upload failed with status:', xhr.status, 'Response text:', xhr.responseText);
                        let errorMessage = 'Gagal mengunggah gambar. Status: ' + xhr.status + ' ' + xhr.statusText;
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse && errorResponse.error && errorResponse.error.message) {
                                errorMessage = 'Gagal mengunggah gambar: ' + errorResponse.error.message;
                            } else if (errorResponse && errorResponse.message) {
                                errorMessage = 'Gagal mengunggah gambar: ' + errorResponse.message;
                            }
                        } catch (e) {
                            console.error('Tidak dapat mengurai respon error Cloudinary:', e);
                            errorMessage += '. Respon tidak dapat dibaca atau formatnya salah.';
                        }
                        alert(errorMessage);
                        uploadProgress.style.display = 'none';
                        submitButton.disabled = true;
                        imagePreview.src = imageUrlHiddenInput.value;
                        imagePreview.style.display = imageUrlHiddenInput.value ? 'block' : 'none';
                        if (noImageText) noImageText.style.display = imageUrlHiddenInput.value ? 'none' : 'block';
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error during Cloudinary upload.');
                    alert('Terjadi kesalahan jaringan saat mengunggah gambar.');
                    uploadProgress.style.display = 'none';
                    submitButton.disabled = true;
                    imagePreview.src = imageUrlHiddenInput.value;
                    imagePreview.style.display = imageUrlHiddenInput.value ? 'block' : 'none';
                    if (noImageText) noImageText.style.display = imageUrlHiddenInput.value ? 'none' : 'block';
                };

                xhr.send(formData);
            });

            productEditForm.addEventListener('submit', function(event) {
                if (imageFileInput.files.length > 0 && !imageUrlHiddenInput.value) {
                    alert('Mohon tunggu hingga gambar selesai diunggah.');
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection