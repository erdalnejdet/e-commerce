<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sipariş Onayı - PAULINE">
    <title>Sipariş Onayı - PAULINE</title>
    
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.header')

    <!-- Success Page -->
    <section class="checkout-success-page">
        <div class="container">
            <div class="success-content">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                
                <h1 class="success-title">Siparişiniz Alındı!</h1>
                <p class="success-message">
                    Siparişiniz başarıyla oluşturuldu. En kısa sürede hazırlanıp size ulaştırılacaktır.
                </p>

                <div class="order-details">
                    <div class="detail-card">
                        <h3 class="card-title">
                            <i class="bi bi-person me-2"></i>
                            Müşteri Bilgileri
                        </h3>
                        <div class="card-content">
                            <p><strong>Ad Soyad:</strong> {{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</p>
                            <p><strong>E-posta:</strong> {{ $order['customer']['email'] }}</p>
                            <p><strong>Telefon:</strong> {{ $order['customer']['phone'] }}</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <h3 class="card-title">
                            <i class="bi bi-geo-alt me-2"></i>
                            Teslimat Adresi
                        </h3>
                        <div class="card-content">
                            <p>{{ $order['shipping']['address'] }}</p>
                            <p>{{ $order['shipping']['district'] }}, {{ $order['shipping']['city'] }}</p>
                            <p>Posta Kodu: {{ $order['shipping']['postal_code'] }}</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <h3 class="card-title">
                            <i class="bi bi-credit-card me-2"></i>
                            Ödeme Bilgileri
                        </h3>
                        <div class="card-content">
                            <p><strong>Ödeme Yöntemi:</strong> 
                                @if($order['payment_method'] === 'cash_on_delivery')
                                    Kapıda Ödeme
                                @else
                                    Kredi Kartı
                                @endif
                            </p>
                            @if($order['notes'])
                                <p><strong>Notlar:</strong> {{ $order['notes'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="order-items-summary">
                    <h3>Sipariş Detayları</h3>
                    <div class="items-list">
                        @foreach($order['cart'] as $item)
                            <div class="item-row">
                                <div class="item-info">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="item-img">
                                    <div class="item-text">
                                        <div class="item-name">{{ $item['name'] }}</div>
                                        <div class="item-options">
                                            {{ strtoupper($item['size']) }} - {{ $item['flavor_name'] ?? $item['flavor'] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="item-quantity">{{ $item['quantity'] }} Adet</div>
                                <div class="item-price">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    @php
                        $subtotal = 0;
                        foreach($order['cart'] as $item) {
                            $subtotal += $item['price'] * $item['quantity'];
                        }
                        $tax = $subtotal * 0.18;
                        $total = $subtotal + $tax;
                    @endphp
                    
                    <div class="order-totals">
                        <div class="total-row">
                            <span>Ara Toplam:</span>
                            <span>₺{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>KDV (18%):</span>
                            <span>₺{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Kargo:</span>
                            <span class="text-success">Ücretsiz</span>
                        </div>
                        <div class="total-row total">
                            <span>Toplam:</span>
                            <span class="amount">₺{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="success-actions">
                    <a href="/" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>
                        Ana Sayfaya Dön
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>
                        Yazdır
                    </button>
                </div>

                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <p>
                        Sipariş durumunuz hakkında bilgi almak için <strong>{{ $order['customer']['phone'] }}</strong> 
                        numaralı telefonunuzu kullanabilirsiniz.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
