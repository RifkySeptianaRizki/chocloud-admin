@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="content-card">
    <div class="mb-4">
        <h1 class="mb-0">Edit Produk</h1>
        <p class="text-white-50">Perbarui detail untuk produk: <strong>{{ $product->name }}</strong></p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productEditForm" action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            {{-- Kolom Kiri: Detail Utama Produk --}}
            <div class="col-lg-7">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="mb-3">
    <label for="category_id" class="form-label">Kategori</label>
    <select name="category_id" id="category_id" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
                <div class="mb-3">
                    <label for="shopee_link" class="form-label">Link Shopee (Opsional)</label>
                    <input type="url" name="shopee_link" id="shopee_link" class="form-control" value="{{ old('shopee_link', $product->shopee_link) }}">
                </div>
                <div class="mb-3">
                    <label for="whatsapp_link" class="form-label">Link WhatsApp (Opsional)</label>
                    <input type="url" name="whatsapp_link" id="whatsapp_link" class="form-control" value="{{ old('whatsapp_link', $product->whatsapp_link) }}">
                </div>
            </div>

            {{-- Kolom Kanan: Upload Gambar --}}
            <div class="col-lg-5">
                <div class="mb-3">
                    <label class="form-label">Gambar Produk</label>
                    <div class="p-3 rounded" style="background-color: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.2);">
                        <div class="mb-2 text-center">
                            <img id="image_preview" src="{{ $product->image ?? '' }}" alt="Preview Gambar" class="img-fluid rounded" style="max-height: 200px; {{ $product->image ? '' : 'display: none;' }}">
                            <p id="no_image_text" class="text-white-50 mb-0" style="{{ $product->image ? 'display: none;' : '' }}">Tidak ada gambar.</p>
                        </div>
                        <label for="image_file" class="form-label mt-2">Ganti Gambar (Opsional)</label>
                        <input type="file" id="image_file" class="form-control">
                        <div class="form-text">Kosongkan jika tidak ingin mengubah gambar.</div>
                        
                        {{-- Elemen Tersembunyi untuk URL & Public ID Cloudinary --}}
                        <input type="hidden" name="image" id="image_url_hidden" value="{{ old('image', $product->image) }}">
                        <input type="hidden" name="image_public_id" id="image_public_id_hidden" value="{{ old('image_public_id', $product->image_public_id) }}">

                        <div id="upload_progress" class="progress mt-3" style="display: none; height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary-custom me-2">Batal</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

{{-- SCRIPT ASLI ANDA (TIDAK DIUBAH SAMA SEKALI) --}}
@push('scripts')
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

    // Cek kondisi awal gambar
    if (imageUrlHiddenInput.value) {
        imagePreview.src = imageUrlHiddenInput.value;
        imagePreview.style.display = 'block';
        noImageText.style.display = 'none';
    } else {
        imagePreview.style.display = 'none';
        noImageText.style.display = 'block';
    }

    imageFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        progressBar.style.width = '0%';
        uploadProgress.style.display = 'block';
        submitButton.disabled = true;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('upload_preset', uploadPreset);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`);

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = (e.loaded / e.total) * 100;
                progressBar.style.width = percent.toFixed(0) + '%';
            }
        });

        xhr.onload = function() {
            submitButton.disabled = false;
            uploadProgress.style.display = 'none';
            if (xhr.status >= 200 && xhr.status < 300) {
                const data = JSON.parse(xhr.responseText);
                imageUrlHiddenInput.value = data.secure_url;
                imagePublicIdHiddenInput.value = data.public_id;
                imagePreview.src = data.secure_url;
                imagePreview.style.display = 'block';
                noImageText.style.display = 'none';
            } else {
                console.error('Cloudinary upload failed:', xhr.responseText);
                alert('Gagal mengunggah gambar: ' + (JSON.parse(xhr.responseText).error.message || xhr.statusText));
            }
        };

        xhr.onerror = function() {
            submitButton.disabled = false;
            uploadProgress.style.display = 'none';
            alert('Terjadi kesalahan jaringan saat mengunggah gambar.');
        };

        xhr.send(formData);
    });

    productEditForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        if (imageFileInput.files.length > 0 && !imageUrlHiddenInput.value) {
            alert('Mohon tunggu hingga gambar selesai diunggah.');
            return;
        }

        submitButton.disabled = true;

        const data = {
            _token: document.querySelector('input[name="_token"]').value,
            _method: 'PATCH',
            name: document.getElementById('name').value,
            price: document.getElementById('price').value,
            description: document.getElementById('description').value,
            category_id: document.getElementById('category_id').value,
            shopee_link: document.getElementById('shopee_link').value,
            whatsapp_link: document.getElementById('whatsapp_link').value,
            image: document.getElementById('image_url_hidden').value,
            image_public_id: document.getElementById('image_public_id_hidden').value
        };

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            });

            const responseData = await response.json();

            if (response.ok) {
                alert(responseData.message || 'Perubahan berhasil disimpan!');
                window.location.href = "{{ route('admin.products.index') }}";
            } else {
                let errorMessage = 'Gagal menyimpan perubahan. ';
                if (responseData.message) errorMessage += responseData.message;
                if (responseData.errors) {
                    for (const field in responseData.errors) {
                        errorMessage += `\n${field}: ${responseData.errors[field].join(', ')}`;
                    }
                }
                alert(errorMessage);
            }
        } catch (error) {
            console.error('Error during form submission:', error);
            alert('Terjadi kesalahan tidak terduga saat menyimpan perubahan.');
        } finally {
            submitButton.disabled = false;
        }
    });
});
</script>
@endpush

@endsection