<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Chocloud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>
                <p class="lead fs-4">Rp {{ number_format($product->price) }}</p>
                <p class="text-muted">{{ $product->description }}</p>
                <a href="{{ $product->shopee_link }}" class="btn btn-primary" target="_blank">Beli di Shopee</a>
                <a href="{{ $product->whatsapp_link }}" class="btn btn-success" target="_blank">Pesan via WhatsApp</a>
            </div>
        </div>

        <hr class="my-5">

        <div class="row">
            <div class="col-12">
                <h3>Kata Mereka tentang {{ $product->name }}</h3>

                @forelse ($approvedTestimonials as $testimonial)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $testimonial->customer_name }}</h5>

                            {{-- AWAL BLOK LOGIKA VIDEO --}}
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
                            {{-- AKHIR BLOK LOGIKA VIDEO --}}
                            
                        </div>
                    </div>
                @empty
                    <p>Belum ada testimoni untuk produk ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script async src="//www.instagram.com/embed.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    <script>
        window.onload = function() {
            if (typeof twttr !== 'undefined' && twttr.widgets) {
                twttr.widgets.load();
            }
        };
    </script>
</body>
</html>