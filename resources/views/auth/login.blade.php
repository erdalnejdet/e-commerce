<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap - PAULINE</title>
    @include('partials.assets')
</head>
<body>
    @include('layouts.header')

    <section style="padding: 4rem 0; min-height: 60vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h2 style="margin-bottom: 1.5rem; color: var(--primary-color, #8b7355); text-align: center;">Giriş Yap</h2>
                        
                        @if($errors->any())
                            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <ul style="margin: 0; padding-left: 1.5rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST" id="loginForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta <span style="color: red;">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Şifre <span style="color: red;">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required style="padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>

                            <div class="mb-3" style="display: flex; align-items: center; justify-content: space-between;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" name="remember">
                                    <span>Beni hatırla</span>
                                </label>
                            </div>

                            <button type="submit" class="btn" style="width: 100%; background: var(--primary-color, #8b7355); color: white; padding: 0.75rem; border: none; border-radius: 8px; font-weight: 600; margin-bottom: 1rem;">
                                Giriş Yap
                            </button>
                        </form>

                        <div style="text-align: center; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; color: #666;">
                                Hesabınız yok mu? 
                                <a href="{{ route('register') }}" style="color: var(--primary-color, #8b7355); text-decoration: none; font-weight: 600;">
                                    Üye Ol
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
