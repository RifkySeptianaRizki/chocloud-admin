@extends('layouts.admin')
@section('title', 'Manajemen Testimoni')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>Manajemen Testimoni</h1>
            <p class="mb-0">Setujui, hapus, atau tambahkan testimoni yang masuk.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Tambah Testimoni Baru</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th style="width: 20%;">Nama Pelanggan</th>
                <th>Isi Testimoni</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 20%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
    @forelse ($testimonials as $testimonial)
        <tr>
            <td>{{ $testimonial->customer_name }}</td>
            <td>
                {{-- ⭐ FIX #1: Logika Video URL Disederhanakan --}}
                @if ($testimonial->video_url)
                    @php
                        $url = $testimonial->video_url;
                        $embedHtml = '';

                        // Logika untuk YouTube (lebih andal)
                        if (Str::contains($url, ['youtube.com', 'youtu.be/'])) {
                            $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                            if (preg_match($pattern, $url, $matches)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                $embedHtml = '<div class="ratio ratio-16x9 mb-2"><iframe src="' . $embedUrl . '" title="Testimoni Video YouTube" allowfullscreen></iframe></div>';
                            }
                        } 
                        // Logika untuk Instagram
                        elseif (Str::contains($url, 'instagram.com')) {
                            $embedHtml = '<blockquote class="instagram-media" data-instgrm-permalink="' . $url . '" data-instgrm-version="14" style="max-width:320px; min-width:320px;"></blockquote>';
                        }
                        // Logika untuk Twitter/X
                        elseif (Str::contains($url, ['twitter.com', 'x.com'])) {
                            $embedHtml = '<blockquote class="twitter-tweet"><a href="' . $url . '"></a></blockquote>';
                        }
                        // Logika untuk TikTok
                        elseif (Str::contains($url, 'tiktok.com')) {
                            $videoId = basename(parse_url($url, PHP_URL_PATH));
                            $embedHtml = '<blockquote class="tiktok-embed" data-video-id="' . $videoId . '" style="max-width: 325px; min-width: 325px;"><section></section></blockquote>';
                        }
                    @endphp
                    
                    {!! $embedHtml !!}
                    <p class="card-text fst-italic mt-2">"{{ $testimonial->content }}"</p>
                @else
                    <p class="card-text fst-italic">"{{ $testimonial->content }}"</p>
                @endif
                {{-- AKHIR BLOK LOGIKA VIDEO --}}

                <br>
                <small class="text-muted">Untuk Produk: <strong>{{ $testimonial->product->name ?? 'N/A' }}</strong></small>
            </td>
            <td>
                @if($testimonial->status == 'approved')
                    <span class="badge bg-success">Disetujui</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </td>
            <td>
                {{-- ⭐ FIX #2: Ganti Form dengan Button untuk AJAX --}}
                @if($testimonial->status == 'pending')
                    <button class="btn btn-sm btn-success btn-action" 
                            data-url="{{ route('admin.testimonials.approve', $testimonial->id) }}"
                            data-method="PATCH">
                        Approve
                    </button>
                @endif
                <button class="btn btn-sm btn-danger btn-action"
                        data-url="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                        data-method="DELETE"
                        data-confirm="Yakin ingin menghapus testimoni ini?">
                    Hapus
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">Belum ada testimoni.</td>
        </tr>
    @endforelse
</tbody>
    </table>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Script untuk memuat embed social media
    if (typeof twttr !== 'undefined') twttr.widgets.load();
    if (typeof instgrm !== 'undefined') instgrm.Embeds.process();
    // TikTok biasanya memproses otomatis

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
                    method: 'POST', // Selalu POST untuk method spoofing
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: method // Method spoofing (PATCH atau DELETE)
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    alert(result.message);
                    window.location.reload(); // Muat ulang halaman untuk melihat perubahan
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