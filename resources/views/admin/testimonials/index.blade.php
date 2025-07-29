@extends('layouts.admin')
@section('title', 'Manajemen Testimoni')

@section('content')
<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Manajemen Testimoni</h1>
            <p class="text-white-50">Setujui, hapus, atau tambahkan testimoni yang masuk.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-glass btn-sm mt-4">
            <i class="fas fa-plus me-2"></i>Tambah Testimoni
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        @forelse ($testimonials as $testimonial)
            <div class="col-lg-6 mb-4">
                {{-- Struktur kartu baru yang lebih rapi --}}
                <div class="testimonial-card">
                    <div class="card-body d-flex flex-column">
                        {{-- BAGIAN ATAS: Info Pelanggan & Produk --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-initial">
                                {{ strtoupper(substr($testimonial->customer_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="customer-name">{{ $testimonial->customer_name }}</div>
                                <small class="text-muted">Mengulas produk: <strong>{{ $testimonial->product->name ?? 'N/A' }}</strong></small>
                            </div>
                        </div>

                        {{-- BAGIAN TENGAH: Isi Testimoni (Video & Teks) --}}
                        <div>
                            @if ($testimonial->video_url)
                                @php
                                    $url = $testimonial->video_url;
                                    $embedHtml = '';
                                    if (Str::contains($url, ['youtube.com', 'youtu.be'])) {
                                        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                                        if (preg_match($pattern, $url, $matches)) {
                                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                            $embedHtml = '<div class="ratio ratio-16x9 mb-3 rounded overflow-hidden"><iframe src="' . $embedUrl . '" title="Testimoni Video YouTube" allowfullscreen></iframe></div>';
                                        }
                                    } 
                                    elseif (Str::contains($url, 'instagram.com')) {
                                        $embedHtml = '<div class="mb-3"><blockquote class="instagram-media" data-instgrm-permalink="' . $url . '" data-instgrm-version="14" style="max-width:320px; min-width:320px; margin: auto;"></blockquote></div>';
                                    }
                                    // ... logika untuk Twitter & TikTok tetap sama ...
                                @endphp
                                {!! $embedHtml !!}
                            @endif
                            <blockquote class="testimonial-quote">"{{ $testimonial->content }}"</blockquote>
                        </div>
                        
                        {{-- BAGIAN BAWAH: Status & Tombol Aksi --}}
                        <div class="mt-auto pt-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 1rem;">
                            <div>
                                @if($testimonial->status == 'approved')
                                    <span class="badge-custom badge-custom-success">Disetujui</span>
                                @else
                                    <span class="badge-custom badge-custom-warning">Pending</span>
                                @endif
                            </div>
                            <div>
                                @if($testimonial->status == 'pending')
                                    <button class="btn btn-sm btn-action-success btn-action" 
                                            data-url="{{ route('admin.testimonials.approve', $testimonial->id) }}"
                                            data-method="PATCH">
                                        Approve
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-action-delete btn-action"
                                        data-url="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                                        data-method="DELETE"
                                        data-confirm="Yakin ingin menghapus testimoni ini?">
                                    Hapus
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <p class="mb-0">Belum ada testimoni.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
{{-- Script asli Anda tidak diubah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (typeof twttr !== 'undefined') twttr.widgets.load();
    if (typeof instgrm !== 'undefined') instgrm.Embeds.process();
    document.body.addEventListener('click', async function(e) {
        if (e.target && e.target.classList.contains('btn-action')) {
            e.preventDefault();
            const actionButton = e.target;
            const url = actionButton.dataset.url;
            const method = actionButton.dataset.method;
            const confirmation = actionButton.dataset.confirm;
            if (confirmation && !confirm(confirmation)) {
                return;
            }
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: method })
                });
                const result = await response.json();
                if (response.ok) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Aksi gagal dilakukan.'));
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            }
        }
    });
});
</script>
@endpush