@extends('layouts.admin')
@section('title', 'Tambah Produk Baru')

@section('content')
    <h1>Tambah Produk Baru</h1>

    {{-- Tampilkan pesan sukses jika ada --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tampilkan pesan error validasi umum jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="productForm" action="{{ route('admin.products.store') }}" method="POST"> {{-- Tanpa enctype="multipart/form-data" --}}
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</labe`l>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="shopee_link" class="form-label">Link Shopee</label>
            <input type="url" name="shopee_link" id="shopee_link" class="form-control @error('shopee_link') is-invalid @enderror" value="{{ old('shopee_link') }}" placeholder="https://shopee.co.id/...">
            @error('shopee_link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="whatsapp_link" class="form-label">Link WhatsApp</label>
            <input type="url" name="whatsapp_link" id="whatsapp_link" class="form-control @error('whatsapp_link') is-invalid @enderror" value="{{ old('whatsapp_link') }}" placeholder="https://wa.me/...">
            @error('whatsapp_link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="image_file" class="form-label">Gambar Produk</label>
            {{-- Input file akan digunakan untuk memilih file, bukan untuk dikirim langsung ke server Laravel --}}
            <input type="file" id="image_file" class="form-control @error('image') is-invalid @enderror">
            <small class="text-muted">Format: JPEG, PNG, JPG, WEBP. Ukuran maksimal: 2MB.</small>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            {{-- Input tersembunyi ini akan menyimpan URL gambar dari Cloudinary --}}
            <input type="hidden" name="image" id="image_url_hidden" required>
            {{-- Input tersembunyi untuk public_id Cloudinary --}}
            <input type="hidden" name="image_public_id" id="image_public_id_hidden">

            {{-- Tampilkan preview gambar dan indikator loading --}}
            <div class="mt-2">
                <img id="image_preview" src="" alt="Preview Gambar" style="max-width: 150px; display: none;">
                <div id="upload_progress" class="progress mt-2" style="display: none;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
        <button type="submit" id="submitButton" class="btn btn-primary" disabled>Simpan Produk</button>
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
            const uploadProgress = document.getElementById('upload_progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const submitButton = document.getElementById('submitButton');
            const productForm = document.getElementById('productForm');

            submitButton.disabled = true;

            imageFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                // --- DEBUGGING: LOGGING NILAI SEBELUM UPLOAD ---
                console.log('--- Memulai Proses Upload Cloudinary ---');
                console.log('Cloud Name yang digunakan:', cloudName);
                console.log('Upload Preset yang digunakan:', uploadPreset);
                console.log('File yang dipilih:', file.name, 'Ukuran:', file.size, 'bytes');
                // ----------------------------------------------

                progressBar.style.width = '0%';
                progressBar.textContent = '0%';
                uploadProgress.style.display = 'none';
                imagePreview.style.display = 'none';
                imagePreview.src = '';
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
                        // --- DEBUGGING: LOG DAN ALERT LEBIH DETAIL SAAT GAGAL ---
                        console.error('Cloudinary upload failed with status:', xhr.status, 'Response text:', xhr.responseText);
                        let errorMessage = 'Gagal mengunggah gambar. Status: ' + xhr.status + ' ' + xhr.statusText;
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse && errorResponse.error && errorResponse.error.message) {
                                errorMessage = 'Gagal mengunggah gambar: ' + errorResponse.error.message;
                            } else if (errorResponse && errorResponse.message) { // Beberapa API pakai 'message'
                                errorMessage = 'Gagal mengunggah gambar: ' + errorResponse.message;
                            }
                        } catch (e) {
                            console.error('Tidak dapat mengurai respon error Cloudinary:', e);
                            errorMessage += '. Respon tidak dapat dibaca atau formatnya salah.';
                        }
                        alert(errorMessage);
                        // --------------------------------------------------------

                        uploadProgress.style.display = 'none';
                        submitButton.disabled = true;
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error during Cloudinary upload.');
                    alert('Terjadi kesalahan jaringan saat mengunggah gambar.');
                    uploadProgress.style.display = 'none';
                    submitButton.disabled = true;
                };

                xhr.send(formData);
            });

            productForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Mencegah submit form bawaan

                // Pastikan gambar sudah diupload jika file dipilih
                if (imageFileInput.files.length > 0 && !imageUrlHiddenInput.value) {
                    alert('Mohon tunggu hingga gambar selesai diunggah.');
                    return; // Hentikan proses jika gambar belum siap
                }

                submitButton.disabled = true; // Nonaktifkan tombol saat submit dimulai

                // --- GANTI CARA MENGUMPULKAN DATA INI UNTUK MEMASTIKAN JSON MURNI ---
                const data = {
                    _token: document.querySelector('input[name="_token"]').value, // CSRF token
                    // name dan input lainnya diambil secara manual
                    name: document.getElementById('name').value,
                    price: document.getElementById('price').value,
                    description: document.getElementById('description').value, // Ambil langsung value dari textarea
                    shopee_link: document.getElementById('shopee_link').value,
                    whatsapp_link: document.getElementById('whatsapp_link').value,
                    image: document.getElementById('image_url_hidden').value,
                    image_public_id: document.getElementById('image_public_id_hidden').value
                };
                // ---------------------------------------------------------------------

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // Kirim sebagai JSON
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': data._token // Ambil CSRF token
                        },
                        body: JSON.stringify(data) // Kirim data sebagai JSON string
                    });

                    const responseData = await response.json();

                    if (response.ok) {
                        alert(responseData.message || 'Produk berhasil disimpan!');
                        window.location.href = "{{ route('admin.products.index') }}";
                    } else {
                        console.error('Server error during form submission:', responseData);
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
                    console.error('Network or unexpected error during form submission:', error);
                    alert('Terjadi kesalahan tidak terduga saat menyimpan produk.');
                } finally {
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endsection