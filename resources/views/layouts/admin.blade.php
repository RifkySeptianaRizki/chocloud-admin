<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Chocloud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- --- TAMBAHKAN META TAG INI UNTUK CLOUDINARY CLOUD NAME --- --}}
    <meta name="cloudinary-cloud-name" content="{{ env('CLOUDINARY_CLOUD_NAME') }}">
    {{-- -------------------------------------------------------- --}}
    
    {{-- CSS Kustom untuk Layout Hybrid Responsif --}}
    <style>
        /* Sidebar permanen untuk layar besar (desktop) */
        .sidebar-desktop {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            padding: 20px;
            background-color: #343a40;
            color: white;
            z-index: 1000;
        }

        /* Konten utama */
        .content {
            padding: 20px;
        }
        
        /* Media query HANYA untuk layar besar (lg -> 992px ke atas) */
        @media (min-width: 992px) {
            .content {
                margin-left: 250px;
            }
            .navbar-mobile {
                display: none;
            }
        }
        
        /* Media query HANYA untuk layar kecil (di bawah 992px) */
        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none;
            }
            body {
                padding-top: 56px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-desktop">
        <h3>Chocloud Admin</h3>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('admin.products.index') }}">Manajemen Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a>
            </li>
        </ul>
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
    <nav class="navbar navbar-dark bg-dark fixed-top navbar-mobile">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Chocloud Admin</a>
        </div>
    </nav>
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            {{-- Isinya sama dengan sidebar desktop --}}
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.products.index') }}">Manajemen Produk</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a></li>
            </ul>
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
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
            if (typeof twttr !== 'undefined' && twttr.widgets) {
                twttr.widgets.load();
            }
        };
    </script>
</body>
</html>