<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Üye Ol - PAULINE</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    @include('layouts.header')

    <section style="padding: 4rem 0; min-height: 60vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h2 style="margin-bottom: 1.5rem; color: var(--primary-color, #8b7355); text-align: center;">Üye Ol</h2>
                        
                        @if($errors->any())
                            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <ul style="margin: 0; padding-left: 1.5rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST" id="registerForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Ad Soyad <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta <span style="color: red;">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="05XX XXX XX XX" style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Şifre <span style="color: red;">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                                <small style="color: #666; font-size: 0.85rem;">En az 6 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Şifre Tekrar <span style="color: red;">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <button type="submit" class="btn" style="width: 100%; background: var(--primary-color, #8b7355); color: white; padding: 0.75rem; border: none; border-radius: 8px; font-weight: 600; margin-bottom: 1rem;">
                                Üye Ol
                            </button>
                        </form>

                        <div style="text-align: center; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; color: #666;">
                                Zaten üye misiniz? 
                                <a href="{{ route('login') }}" style="color: var(--primary-color, #8b7355); text-decoration: none; font-weight: 600;">
                                    Giriş Yap
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        // Telefon numarası formatı
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0 && value[0] !== '0') {
                value = '0' + value;
            }
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
