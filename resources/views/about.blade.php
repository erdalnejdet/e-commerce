<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PAULINE - Hakkımızda. Sevgiyle yapılmış, özenle sunulmuş pastalar ve tatlılar.">
    <title>Hakkımızda - PAULINE</title>
    
    @include('partials.assets')
</head>
<body>
    @include('layouts.header')

    <!-- Sub Header / Hero -->
    <section class="about-hero">
        <div class="about-hero-overlay"></div>
        <div class="container">
            <div class="about-hero-content">
                <h1>Hakkımızda</h1>
                <p>{{ $sections['title'] ?? 'WE MAKE CAKES ONLY WITH LOVE' }}</p>
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
                    <li class="breadcrumb-item active" aria-current="page">Hakkımızda</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-page-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                    <div class="about-image-wrapper">
                        <img src="{{ $sections['image'] ?? 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80' }}" alt="Hakkımızda" class="about-page-image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="about-page-content">
                        <h2>{{ $sections['title'] ?? 'WE MAKE CAKES ONLY WITH LOVE' }}</h2>
                        @if($sections['content_1'])
                            <p class="lead">{{ $sections['content_1'] }}</p>
                        @endif
                        @if($sections['content_2'])
                            <p>{{ $sections['content_2'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="row mt-5 pt-5">
                <div class="col-12">
                    <h3 class="section-subtitle text-center mb-5">Değerlerimiz</h3>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h4>Sevgi</h4>
                        <p>Her pastayı sevgiyle ve özenle hazırlıyoruz. Müşterilerimizin mutluluğu bizim için en önemli değerdir.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h4>Kalite</h4>
                        <p>En kaliteli malzemeleri kullanarak, geleneksel tarifleri modern dokunuşlarla birleştiriyoruz.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h4>Yenilik</h4>
                        <p>Sürekli kendimizi geliştiriyor, yeni lezzetler ve tasarımlar sunarak müşterilerimizi şaşırtıyoruz.</p>
                    </div>
                </div>
            </div>

            <!-- Story Section -->
            <div class="row mt-5 pt-5">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="story-section">
                        <h3 class="section-subtitle text-center mb-4">Hikayemiz</h3>
                        <p class="text-center">
                            PAULINE olarak, 2010 yılından beri İstanbul'da hizmet veriyoruz. Başlangıçta küçük bir atölye olarak başladığımız yolculuğumuzda, 
                            müşterilerimizin güveni ve desteği sayesinde bugünlere geldik. Her geçen gün daha iyiye ulaşmak için çalışıyor, 
                            özel günlerinizi unutulmaz kılmak için var gücümüzle çalışıyoruz.
                        </p>
                        <p class="text-center">
                            Ekibimiz, deneyimli pastacılar ve yaratıcı tasarımcılardan oluşuyor. Her biri kendi alanında uzman olan ekibimiz, 
                            müşterilerimizin hayallerini gerçeğe dönüştürmek için burada. Siz de bu ailenin bir parçası olmak isterseniz, 
                            bizimle iletişime geçmekten çekinmeyin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
