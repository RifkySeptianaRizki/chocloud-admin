<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Chocloud</title>

    {{-- 💡 FIX #1: Tambahkan Meta Tag CSRF di sini --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="cloudinary-cloud-name" content="{{ env('CLOUDINARY_CLOUD_NAME') }}">
    
    <style>
        /* CSS Anda (tidak diubah) */
        .sidebar-desktop { position: fixed; top: 0; left: 0; bottom: 0; width: 250px; padding: 20px; background-color: #343a40; color: white; z-index: 1000; }
        .content { padding: 20px; }
        @media (min-width: 992px) { .content { margin-left: 250px; } .navbar-mobile { display: none; } }
        @media (max-width: 991.98px) { .sidebar-desktop { display: none; } body { padding-top: 56px; } }
    </style>
</head>
<body>
    {{-- Sidebar dan Navigasi Anda (tidak diubah) --}}
    <div class="sidebar-desktop">
        <h3>Chocloud Admin</h3>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.products.index') }}">Manajemen Produk</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a></li>
        </ul>
        <hr>
        <form method="POST" action="{{ route('logout') }}"><button type="submit" class="btn btn-danger">Logout</button></form>
    </div>
    <nav class="navbar navbar-dark bg-dark fixed-top navbar-mobile">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"><span class="navbar-toggler-icon"></span></button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Chocloud Admin</a>
        </div>
    </nav>
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.products.index') }}">Manajemen Produk</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a></li>
            </ul><hr>
            <form method="POST" action="{{ route('logout') }}"><button type="submit" class="btn btn-danger">Logout</button></form>
        </div>
    </div>

    {{-- Konten Utama (tidak diubah) --}}
    <div class="content">
        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script async src="//www.instagram.com/embed.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    <script>
        window.onload = function() {
            if (typeof twttr !== 'undefined' && twttr.widgets) { twttr.widgets.load(); }
        };
    </script>
    
    {{-- 💡 FIX #2: Tambahkan @stack('scripts') di sini --}}
    @stack('scripts')

</body>
</html>