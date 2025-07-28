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
                {{-- AWAL DARI BLOK LOGIKA VIDEO --}}
                @if ($testimonial->video_url) {{-- <-- IF LUAR (1) DIBUKA --}}
                    @php
                        $videoUrl = $testimonial->video_url;
                    @endphp

                    @if (Illuminate\Support\Str::contains($videoUrl, ['youtube.com', 'youtu.be/'])) {{-- <-- IF DALAM (2) DIBUKA --}}
                        @php
                            if (str_contains($videoUrl, 'watch?v=')) {
                                $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                            }
                            if (str_contains($videoUrl, 'youtu.be/')) {
                                $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                            }
                            $embedUrl = strtok($embedUrl, '?');
                        @endphp
                        <div class="ratio ratio-16x9 mb-2">
                            <iframe src="{{ $embedUrl }}" title="Testimoni Video YouTube" allowfullscreen></iframe>
                        </div>

                    @elseif (Illuminate\Support\Str::contains($videoUrl, 'instagram.com'))
                        <blockquote class="instagram-media" data-instgrm-permalink="{{ $videoUrl }}" data-instgrm-version="14" style="max-width:320px; min-width:320px;"></blockquote>

                    @elseif (Illuminate\Support\Str::contains($videoUrl, ['twitter.com', 'x.com']))
                        <blockquote class="twitter-tweet"><a href="{{ $videoUrl }}"></a></blockquote>

                    @elseif (Illuminate\Support\Str::contains($videoUrl, 'tiktok.com'))
                        <blockquote class="tiktok-embed" data-video-id="{{ basename(parse_url($videoUrl, PHP_URL_PATH)) }}" style="max-width: 325px; min-width: 325px;"> <section></section> </blockquote>
                    
                    @endif {{-- <-- IF DALAM (2) DITUTUP --}}

                    <p class="card-text fst-italic mt-2">"{{ $testimonial->content }}"</p>

                @else {{-- <-- ELSE UNTUK IF LUAR (1) --}}
                    <p class="card-text fst-italic">"{{ $testimonial->content }}"</p>

                @endif {{-- <-- IF LUAR (1) DITUTUP (INI YANG KEMUNGKINAN HILANG) --}}
                {{-- AKHIR DARI BLOK LOGIKA VIDEO --}}

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
                @if($testimonial->status == 'pending')
                    <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                @endif

                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
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