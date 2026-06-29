<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sipariş Detayı - PAULINE</title>
    @include('partials.assets')
</head>
<body>
    @include('layouts.header')

    <section class="order-detail-page">
        <div class="container">
            <div class="order-detail-header">
                <a href="{{ route('orders.index') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Sipariş Geçmişine Dön
                </a>
                <h1 class="page-title">Sipariş Detayı</h1>
            </div>

            <!-- Sipariş Bilgileri -->
            <div class="order-info-card">
                <div class="order-info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-receipt"></i>
                            Sipariş Numarası
                        </div>
                        <div class="info-value order-number">{{ $order->order_number ?? 'ORD-' . $order->id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-calendar3"></i>
                            Sipariş Tarihi
                        </div>
                        <div class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-box-seam"></i>
                            Sipariş Durumu
                        </div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $order->order_status }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-credit-card"></i>
                            Ödeme Durumu
                        </div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $order->payment_status }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-detail-content">
                <!-- Sol Taraf -->
                <div class="order-detail-main">
                    <!-- Müşteri Bilgileri -->
                    <div class="detail-card">
                        <div class="card-header">
                            <i class="bi bi-person-circle"></i>
                            <h3>Müşteri Bilgileri</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <span class="info-label">Ad Soyad:</span>
                                <span class="info-text">{{ $order->first_name }} {{ $order->last_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">E-posta:</span>
                                <span class="info-text">{{ $order->email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Telefon:</span>
                                <span class="info-text">{{ $order->phone }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Teslimat Adresi -->
                    <div class="detail-card">
                        <div class="card-header">
                            <i class="bi bi-geo-alt-fill"></i>
                            <h3>Teslimat Adresi</h3>
                        </div>
                        <div class="card-body">
                            <div class="address-text">
                                <div>{{ $order->address }}</div>
                                <div>{{ $order->district }}, {{ $order->city }}</div>
                                <div class="postal-code">Posta Kodu: {{ $order->postal_code }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sipariş Ürünleri -->
                    <div class="detail-card">
                        <div class="card-header">
                            <i class="bi bi-bag-check"></i>
                            <h3>Sipariş Ürünleri</h3>
                        </div>
                        <div class="card-body">
                            <div class="order-items-list">
                                @foreach($order->items as $item)
                                    <div class="order-item-card">
                                        <div class="item-image-wrapper">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="item-image">
                                        </div>
                                        <div class="item-details-wrapper">
                                            <h4 class="item-name">{{ $item['name'] }}</h4>
                                            <div class="item-options">
                                                <span class="option-badge">{{ strtoupper($item['size']) }}</span>
                                                <span class="option-badge">{{ $item['flavor_name'] ?? $item['flavor'] }}</span>
                                            </div>
                                            <div class="item-footer">
                                                <span class="item-quantity">Adet: {{ $item['quantity'] }}</span>
                                                <span class="item-price">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Durum Geçmişi -->
                    <div class="detail-card">
                        <div class="card-header">
                            <i class="bi bi-clock-history"></i>
                            <h3>Sipariş Durumu Geçmişi</h3>
                        </div>
                        <div class="card-body">
                            <div class="status-timeline">
                                @forelse($order->statusHistory as $index => $history)
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Beklemede',
                                            'processing' => 'İşleniyor',
                                            'preparing' => 'Hazırlanıyor',
                                            'shipped' => 'Kargoya Verildi',
                                            'delivered' => 'Teslim Edildi',
                                            'cancelled' => 'İptal Edildi',
                                        ];
                                        $statusLabel = $statusLabels[$history->status] ?? $history->status;
                                        $isLast = $index === $order->statusHistory->count() - 1;
                                    @endphp
                                    <div class="timeline-item {{ $isLast ? 'active' : '' }}">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <span class="status-badge status-{{ $history->status }}">
                                                    {{ $statusLabel }}
                                                </span>
                                                <span class="timeline-date">{{ $history->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            @if($history->notes)
                                                <div class="timeline-notes">{{ $history->notes }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">Henüz durum geçmişi yok.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sağ Taraf -->
                <div class="order-detail-sidebar">
                    <!-- Sipariş Özeti -->
                    <div class="summary-card">
                        <div class="card-header">
                            <i class="bi bi-receipt-cutoff"></i>
                            <h3>Sipariş Özeti</h3>
                        </div>
                        <div class="card-body">
                            <div class="summary-row">
                                <span>Ara Toplam:</span>
                                <strong>₺{{ number_format($order->subtotal, 2) }}</strong>
                            </div>
                            <div class="summary-row">
                                <span>KDV (18%):</span>
                                <strong>₺{{ number_format($order->tax, 2) }}</strong>
                            </div>
                            <div class="summary-row">
                                <span>Kargo:</span>
                                <span class="free-shipping">Ücretsiz</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Toplam:</span>
                                <strong class="total-amount">₺{{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ödeme Bilgileri -->
                    <div class="summary-card">
                        <div class="card-header">
                            <i class="bi bi-credit-card-2-front"></i>
                            <h3>Ödeme Bilgileri</h3>
                        </div>
                        <div class="card-body">
                            <div class="payment-info">
                                <div class="info-row">
                                    <span class="info-label">Ödeme Yöntemi:</span>
                                    <span class="info-text">
                                        @if($order->payment_method === 'cash_on_delivery')
                                            <i class="bi bi-cash-coin"></i> Kapıda Ödeme
                                        @else
                                            <i class="bi bi-credit-card"></i> Kredi Kartı
                                        @endif
                                    </span>
                                </div>
                                @if($order->notes)
                                    <div class="order-notes">
                                        <strong>Notlar:</strong>
                                        <p>{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
