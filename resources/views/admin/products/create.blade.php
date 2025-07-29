@extends('layouts.admin')
@section('title', 'Tambah Produk Baru')

@section('content')
{{-- Dibungkus dengan .content-card untuk efek kaca --}}
<div class="content-card">
    <div class="mb-4">
        <h1 class="mb-0">Tambah Produk Baru</h1>
        <p class="text-white-50">Isi detail produk untuk menambahkannya ke toko Anda.</p>
    </div>

    {{-- Tampilkan pesan error validasi umum jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        
        <div class="row">
            {{-- Kolom Kiri: Detail Utama Produk --}}
            <div class="col-lg-7">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
    <label for="category_id" class="form-label">Kategori</label>
    <select name="category_id" id="category_id" class="form-control" required>
        <option value="" disabled selected>-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>

                <div class="mb-3">
                    <label for="shopee_link" class="form-label">Link Shopee (Opsional)</label>
                    <input type="url" name="shopee_link" id="shopee_link" class="form-control" value="{{ old('shopee_link') }}" placeholder="https://shopee.co.id/...">
                </div>
                <div class="mb-3">
                    <label for="whatsapp_link" class="form-label">Link WhatsApp (Opsional)</label>
                    <input type="url" name="whatsapp_link" id="whatsapp_link" class="form-control" value="{{ old('whatsapp_link') }}" placeholder="https://wa.me/...">
                </div>
            </div>

            {{-- Kolom Kanan: Upload Gambar --}}
            <div class="col-lg-5">
                <div class="mb-3">
                    <label class="form-label">Gambar Produk</label>
                    <div class="p-3 rounded" style="background-color: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.2);">
                        <div class="mb-2 text-center">
                            <img id="image_preview" src="" alt="Preview Gambar" class="img-fluid rounded" style="max-height: 200px; display: none;">
                        </div>
                        <label for="image_file" class="form-label mt-2">Pilih file gambar</label>
                        <input type="file" id="image_file" class="form-control">
                        <div class="form-text">Format: JPG, PNG, WEBP. Ukuran maks: 2MB.</div>
                        
                        {{-- Input tersembunyi (Hidden) --}}
                        <input type="hidden" name="image" id="image_url_hidden" required>
                        <input type="hidden" name="image_public_id" id="image_public_id_hidden">

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
            <button type="submit" id="submitButton" class="btn btn-primary" disabled>Simpan Produk</button>
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
    const uploadProgress = document.getElementById('upload_progress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const submitButton = document.getElementById('submitButton');
    const productForm = document.getElementById('productForm');

    submitButton.disabled = true;

    imageFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        progressBar.style.width = '0%';
        uploadProgress.style.display = 'block';
        imagePreview.style.display = 'none';
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
            uploadProgress.style.display = 'none';
            if (xhr.status >= 200 && xhr.status < 300) {
                const data = JSON.parse(xhr.responseText);
                imageUrlHiddenInput.value = data.secure_url;
                imagePublicIdHiddenInput.value = data.public_id;
                imagePreview.src = data.secure_url;
                imagePreview.style.display = 'block';
                submitButton.disabled = false;
            } else {
                console.error('Cloudinary upload failed:', xhr.responseText);
                alert('Gagal mengunggah gambar: ' + (JSON.parse(xhr.responseText).error.message || xhr.statusText));
                submitButton.disabled = true;
            }
        };

        xhr.onerror = function() {
            uploadProgress.style.display = 'none';
            alert('Terjadi kesalahan jaringan saat mengunggah gambar.');
            submitButton.disabled = true;
        };

        xhr.send(formData);
    });

    productForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        if (imageFileInput.files.length > 0 && !imageUrlHiddenInput.value) {
            alert('Mohon tunggu hingga gambar selesai diunggah.');
            return;
        }

        submitButton.disabled = true;

        const data = {
            _token: document.querySelector('input[name="_token"]').value,
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
                alert(responseData.message || 'Produk berhasil disimpan!');
                window.location.href = "{{ route('admin.products.index') }}";
            } else {
                let errorMessage = 'Gagal menyimpan produk. ';
                if (responseData.message) {
                    errorMessage += responseData.message;
                }
                if (responseData.errors) {
                    for (const field in responseData.errors) {
                        errorMessage += `\n${field}: ${responseData.errors[field].join(', ')}`;
                    }
                }
                alert(errorMessage);
            }
        } catch (error) {
            console.error('Error during form submission:', error);
            alert('Terjadi kesalahan tidak terduga saat menyimpan produk.');
        } finally {
            submitButton.disabled = false;
        }
    });
});
</script>
@endpush
@endsection