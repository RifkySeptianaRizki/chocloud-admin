<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chocloud Admin</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(to top, #24243e, #302b63, #0f0c29);
            color: #f8f9fa;
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            animation: fadeInCard 0.8s ease-in-out forwards;
        }

        @keyframes fadeInCard {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card .card-body {
            padding: 3rem !important;
        }
        
        .brand-icon {
            font-size: 3rem;
            color: #7e57c2; /* ⭐ Diubah ke Violet */
            margin-bottom: 1rem;
        }
        
        h2 {
            color: #fff;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #7e57c2; /* ⭐ Diubah ke Violet */
            box-shadow: 0 0 0 0.25rem rgba(126, 87, 194, 0.4); /* ⭐ Diubah ke Violet */
            color: #fff;
        }
        
        .form-label, .form-check-label {
             color: #ced4da;
        }
        
        .btn-primary {
            background-color: #7e57c2; /* ⭐ Diubah ke Violet */
            border-color: #7e57c2; /* ⭐ Diubah ke Violet */
            color: #ffffff; /* ⭐ Diubah ke Putih */
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #9575cd; /* ⭐ Diubah ke Violet lebih terang */
            border-color: #9575cd; /* ⭐ Diubah ke Violet lebih terang */
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .link-fancy {
            color: #9575cd; /* ⭐ Diubah ke Violet lebih terang */
            text-decoration: none;
            position: relative;
            padding-bottom: 3px;
        }
        
        .link-fancy::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: 0;
            left: 0;
            background-color: #b39ddb; /* ⭐ Diubah ke Violet paling terang */
            transition: width 0.3s ease;
        }

        .link-fancy:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row min-vh-100 d-flex justify-content-center align-items-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-cloud brand-icon"></i>
                            <h2>Chocloud Admin</h2>
                        </div>

                        <x-auth-session-status class="mb-4 alert alert-success" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
                            </div>

                            <div class="mb-4 form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Log in') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                @if (Route::has('register'))
                                    <a class="small link-fancy" href="{{ route('register') }}">
                                        Belum punya akun? Daftar
                                    </a>
                                 @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>