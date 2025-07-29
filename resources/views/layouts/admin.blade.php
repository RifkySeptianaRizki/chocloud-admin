<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Chocloud</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cloudinary-cloud-name" content="{{ env('CLOUDINARY_CLOUD_NAME') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(to top, #24243e, #302b63, #0f0c29);
            background-attachment: fixed;
            padding: 1rem; /* ⭐ BARU: Memberi jarak untuk bingkai */
        }

        /* ⭐ BARU: Bingkai utama aplikasi yang "mengambang" dan "glossy" */
.app-wrapper {
    width: 100%;
    height: calc(100vh - 2rem);
    border-radius: 15px;
    
    /* ⭐ Properti untuk efek Glassmorphism */
    background: rgba(18, 18, 18, 0.6); /* Warna dasar kaca lebih gelap */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    
    overflow: auto;
    display: flex;
    flex-direction: column;
}

        .navbar-glass {
            position: sticky; top: 0; z-index: 1030;
            background: rgba(18, 18, 18, 0.6); /* Sedikit lebih solid */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 0.75rem; 
            padding-bottom: 0.75rem;
        }
        .navbar-glass .navbar-brand { font-weight: 600; }

        /* ⭐ PERUBAHAN BESAR: Indikator garis bawah, bukan kapsul */
        .navbar-glass .nav-link {
            color: #ced4da;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            position: relative; /* Diperlukan untuk garis bawah */
            background: transparent !important; /* Hapus latar belakang kapsul */
        }
        .navbar-glass .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; /* Posisi garis di bawah teks */
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: #7e57c2;
            transition: width 0.3s ease-in-out;
        }
        .navbar-glass .nav-link:hover {
            color: #fff;
        }
        .navbar-glass .nav-link:hover::after,
        .navbar-glass .nav-link.active::after {
            width: 50%; /* Garis muncul saat hover atau aktif */
        }
        .navbar-glass .nav-link.active {
            color: #fff;
            font-weight: 500;
        }
        /* ⭐ AKHIR PERUBAHAN BESAR */
        
        .navbar-glass .dropdown-menu {
            background: rgba(30, 30, 45, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-glass .dropdown-item { color: #ced4da; }
        .navbar-glass .dropdown-item:hover { background-color: rgba(126, 87, 194, 0.2); color: #fff; }
        
        .offcanvas-glass {
            background: rgba(30, 30, 45, 0.85);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        .content-wrapper { padding: 2rem; flex-grow: 1; color: #e9ecef; }

        /* ⭐ BARU: Tombol dengan efek kaca (glossy) */
.btn-glass {
    background: rgba(126, 87, 194, 0.25); /* Warna dasar violet transparan */
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #f8f9fa;
    transition: all 0.3s ease;
}

.btn-glass:hover {
    background: rgba(126, 87, 194, 0.4); /* Background lebih terang saat hover */
    border-color: rgba(255, 255, 255, 0.3);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

/* ⭐ BARU: Tombol Aksi untuk Tabel */
.btn-action-edit {
    background-color: rgba(244, 201, 122, 0.15);
    border: 1px solid rgba(244, 201, 122, 0.3);
    color: #f4c97a;
    transition: all 0.2s ease-in-out;
}
.btn-action-edit:hover {
    background-color: rgba(244, 201, 122, 0.3);
    color: #ffde99;
    transform: scale(1.05);
}
.btn-action-delete {
    background-color: rgba(220, 53, 69, 0.15);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #dc3545;
    transition: all 0.2s ease-in-out;
}
.btn-action-delete:hover {
    background-color: rgba(220, 53, 69, 0.3);
    color: #f8d7da;
    transform: scale(1.05);
}

/* ⭐ BARU: Style untuk Form Controls di Tema Gelap */
.form-control, .form-select {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #e9ecef;
}
.form-control:focus, .form-select:focus {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #7e57c2;
    box-shadow: 0 0 0 0.25rem rgba(126, 87, 194, 0.25);
    color: #e9ecef;
}
.form-control::placeholder { color: #6c757d; }
.form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.2);
    opacity: 0.7;
}

/* ⭐ BARU: Style untuk Progress Bar */
.progress {
    background-color: rgba(255, 255, 255, 0.1);
}
.progress-bar {
    background-color: #7e57c2;
}

/* ⭐ BARU: Style untuk Tombol Sekunder */
.btn-secondary-custom {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ced4da;
}
.btn-secondary-custom:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    color: #fff;
}

/* ⭐ BARU: Style untuk Badge Status */
.badge-custom {
    padding: 0.4em 0.8em;
    font-weight: 500;
    border-radius: 50rem;
}
.badge-custom-success {
    background-color: rgba(25, 135, 84, 0.2);
    border: 1px solid rgba(25, 135, 84, 0.4);
    color: #7ee2b8;
}
.badge-custom-warning {
    background-color: rgba(255, 193, 7, 0.15);
    border: 1px solid rgba(255, 193, 7, 0.4);
    color: #ffca2c;
}
.btn-action-success {
    background-color: rgba(25, 135, 84, 0.15);
    border: 1px solid rgba(25, 135, 84, 0.3);
    color: #20c997;
    transition: all 0.2s ease-in-out;
}
.btn-action-success:hover {
    background-color: rgba(25, 135, 84, 0.3);
    color: #7ee2b8;
    transform: scale(1.05);
}

/* ⭐ BARU: Kartu untuk setiap Testimoni */
.testimonial-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%; /* Membuat semua kartu dalam satu baris sama tinggi */
}
.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 255, 255, 0.2);
}
.testimonial-card .card-header, 
.testimonial-card .card-footer {
    background: transparent;
    border-color: rgba(255, 255, 255, 0.1);
}
.testimonial-card .card-body {
    flex-grow: 1; /* Memastikan body kartu mengisi ruang yang tersedia */
}
.testimonial-card .customer-name {
    color: #fff;
    font-weight: 600;
}

/* ⭐ BARU: Avatar inisial nama */
.avatar-initial {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #7e57c2;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 12px;
}

/* ⭐ BARU: Kutipan testimoni yang elegan */
.testimonial-quote {
    position: relative;
    padding-left: 2.5rem;
    font-style: italic;
    color: #ced4da;
}
.testimonial-quote::before {
    content: '\201C'; /* Karakter kutip buka */
    position: absolute;
    left: 0;
    top: -0.5rem;
    font-size: 4rem;
    font-family: Georgia, serif;
    color: rgba(126, 87, 194, 0.2); /* Warna aksen transparan */
    line-height: 1;
}

/* ⭐ BARU: Kartu untuk setiap Produk */
.product-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    transition: all 0.3s ease;
    overflow: hidden; /* Agar gambar tidak keluar dari sudut rounded */
    display: flex;
    flex-direction: column;
    height: 100%;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 255, 255, 0.2);
}
.product-card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.product-card .card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.product-card .card-title {
    font-weight: 600;
    color: #fff;
}
.product-card .card-price {
    font-size: 1.25rem;
    font-weight: 500;
    color: #fff; /* Warna aksen ungu */
}
.product-card .card-actions {
    margin-top: auto; /* Mendorong tombol ke bagian bawah kartu */
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}


    </style>
</head>
<body>
    {{-- ⭐ BARU: Seluruh aplikasi dibungkus dalam .app-wrapper --}}
    <div class="app-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-glass">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-cloud me-2"></i>Chocloud Admin
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvas" aria-controls="mobileOffcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Manajemen Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}"><button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button></form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="content-wrapper">
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Menu untuk Mobile (Offcanvas) --}}
    <div class="offcanvas offcanvas-start offcanvas-glass text-white" tabindex="-1" id="mobileOffcanvas">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.products.index') }}">Manajemen Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.testimonials.index') }}">Manajemen Testimoni</a></li>
            </ul>
            <hr class="text-secondary">
            <form method="POST" action="{{ route('logout') }}"><button type="submit" class="btn btn-outline-danger w-100">Logout</button></form>
        </div>
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
    @stack('scripts')
</body>
</html>