<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PAULINE - İletişim. Bize ulaşın, sorularınızı sorun.">
    <title>İletişim - PAULINE</title>
    
    @include('partials.assets')
</head>
<body>
    @include('layouts.header')

    <!-- Sub Header / Hero -->
    <section class="contact-hero">
        <div class="contact-hero-overlay"></div>
        <div class="container">
            <div class="contact-hero-content">
                <h1>İletişim</h1>
                <p>Bizimle iletişime geçin, sorularınızı sorun veya özel sipariş talebinizi iletin.</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Ana Sayfa
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">İletişim</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">

            <div class="row">
                <div class="col-lg-4 col-md-12 contact-info">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <h3>Adres</h3>
                                <p>Nişantaşı, İstanbul<br>Türkiye</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <h3>Telefon</h3>
                                <p><a href="tel:+902121234567">+90 212 123 45 67</a></p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <h3>E-posta</h3>
                                <p><a href="mailto:info@pauline.com.tr">info@pauline.com.tr</a></p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <h3>Çalışma Saatleri</h3>
                                <p>Pazartesi - Cumartesi<br>09:00 - 20:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12 contact-form-wrapper">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name">Ad Soyad <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">E-posta <span class="required">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>

                        <div class="form-group">
                            <label for="subject">Konu <span class="required">*</span></label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Mesajınız <span class="required">*</span></label>
                            <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send"></i>
                            Mesaj Gönder
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="map-section">
                <h2 class="map-title">Bizi Ziyaret Edin</h2>
                <div class="map-wrapper">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3008.963369833927!2d28.985655315384!3d41.040215979297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab9e7a7777c43%3A0x4c76cf3dcc8b3b7c!2sNi%C5%9Fanta%C5%9F%C4%B1%2C%20%C5%9Ei%C5%9Fli%2F%C4%B0stanbul!5e0!3m2!1str!2str!4v1234567890123!5m2!1str!2str" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="contact-map">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
