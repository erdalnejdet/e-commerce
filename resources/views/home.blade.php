<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PAULINE - Özel tasarım pastalar ve tatlılar. Sevgiyle yapılmış, özenle sunulmuş.">
    <title>PAULINE - Pastane & Tatlı Atölyesi</title>
    
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.header')

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <img src="{{ $sections['hero_image'] ?? 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1920&q=80' }}" alt="Hero Cake" class="hero-image">
        <div class="hero-content">
            <h1>{{ $sections['hero_title'] ?? 'MAKE LIFE BEAUTIFUL' }}</h1>
            <p>{{ $sections['hero_subtitle'] ?? 'Özel günlerinizi unutulmaz kılacak, el yapımı pastalar ve tatlılar' }}</p>
            <button class="btn btn-hero">Ürünleri Keşfet</button>
        </div>
    </section>

    <!-- Top Picks Section -->
    <section id="products" class="top-picks-section">
        <div class="container">
            <div class="section-title">
                <h2>{{ $sections['top_picks_title'] ?? 'TOP PICKS' }}</h2>
                <p>{{ $sections['top_picks_subtitle'] ?? 'En çok tercih edilen özel tasarım pastalarımız' }}</p>
            </div>

            <div class="swiper top-picks-swiper">
                <div class="swiper-wrapper">
                    @forelse($products as $product)
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80' }}" alt="{{ $product->name }}">
                                    @if($product->badge)
                                        <span class="product-badge">{{ $product->badge }}</span>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                    <p>{{ $product->description ?? '' }}</p>
                                    <div class="product-price">₺{{ number_format($product->base_price, 0) }}</div>
                                    <button class="btn btn-add-cart" onclick="openProductModal({{ $product->id }})">Sepete Ekle</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product-info">
                                    <p>Henüz ürün eklenmemiş.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
                
                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Our Flavours Section -->
    <section id="flavours" class="flavours-section">
        <div class="container">
            <div class="section-title">
                <h2>{{ $sections['flavours_title'] ?? 'OUR FLAVOURS' }}</h2>
                <p>{{ $sections['flavours_subtitle'] ?? 'Benzersiz lezzet kombinasyonlarımızı keşfedin' }}</p>
            </div>

            <div class="flavour-grid">
                <!-- Flavour 1 - Large -->
                <div class="flavour-item flavour-large">
                    <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=1200&q=80" alt="Rainbow Cake">
                    <div class="flavour-overlay">
                        <h3>Gökkuşağı Pasta</h3>
                        <p>Renkli katmanlar ve vanilya kreması ile özel günlerinize renk katın</p>
                        <button class="btn btn-explore">Keşfet</button>
                    </div>
                </div>

                <!-- Flavour 2 -->
                <div class="flavour-item">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&q=80" alt="Chocolate Layers">
                    <div class="flavour-overlay">
                        <h3>Çikolata Katmanları</h3>
                        <p>Sütlü ve bitter çikolata uyumu</p>
                        <button class="btn btn-explore">Keşfet</button>
                    </div>
                </div>

                <!-- Flavour 3 -->
                <div class="flavour-item">
                    <img src="https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=800&q=80" alt="Berry Delight">
                    <div class="flavour-overlay">
                        <h3>Orman Meyveli</h3>
                        <p>Taze orman meyveleri ve hafif krema</p>
                        <button class="btn btn-explore">Keşfet</button>
                    </div>
                </div>

                <!-- Flavour 4 - Large -->
                <div class="flavour-item flavour-large">
                    <img src="https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=1200&q=80" alt="Luxury Cake">
                    <div class="flavour-overlay">
                        <h3>Lüks Tasarım</h3>
                        <p>Özel günleriniz için el yapımı fondant ve altın detaylar</p>
                        <button class="btn btn-explore">Keşfet</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="{{ $sections['about_image'] ?? 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80' }}" alt="Our Team">
                </div>
                <div class="about-text">
                    <h2>{{ $sections['about_title'] ?? 'WE MAKE CAKES ONLY WITH LOVE' }}</h2>
                    @if($sections['about_content_1'])
                        <p>{{ $sections['about_content_1'] }}</p>
                    @endif
                    @if($sections['about_content_2'])
                        <p>{{ $sections['about_content_2'] }}</p>
                    @endif
                    <button class="btn btn-learn-more">Daha Fazla Bilgi</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Pickup Location Section -->
    <section class="pickup-section py-5">
        <div class="container">
            <div class="section-title">
                <h2>PICKUP LOCATION</h2>
                <p>Mağazamızı ziyaret edin veya online sipariş verin</p>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="contact-info">
                        <h4><i class="bi bi-geo-alt"></i> Adres</h4>
                        <p>Nişantaşı, Teşvikiye Cad. No: 123<br>Şişli, İstanbul</p>
                        
                        <h4 class="mt-4"><i class="bi bi-clock"></i> Çalışma Saatleri</h4>
                        <p>Pazartesi - Cumartesi: 09:00 - 20:00<br>Pazar: 10:00 - 18:00</p>
                        
                        <h4 class="mt-4"><i class="bi bi-telephone"></i> İletişim</h4>
                        <p>Tel: +90 212 123 45 67<br>Email: info@pauline.com.tr</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="map-container" style="height: 400px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                        <p style="color: #999;">Harita buraya eklenecek</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        // Products data from database
        window.productsData = @json($productsData ?? []);
    </script>
</body>
</html>
