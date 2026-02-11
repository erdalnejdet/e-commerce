<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sipariş Geçmişim - PAULINE</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    @include('layouts.header')

    <section style="padding: 4rem 0; min-height: 60vh;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <h1 class="page-title" style="margin: 0;">Sipariş Geçmişim</h1>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="/" class="btn" style="padding: 0.5rem 1rem; background: var(--primary-color, #8b7355); color: white; text-decoration: none; border-radius: 5px;">
                        <i class="bi bi-house me-1"></i> Ana Sayfa
                    </a>
                </div>
            </div>

            <!-- Arama Kutusu -->
            <div class="order-search-box">
                <form method="GET" action="{{ route('orders.index') }}" class="search-form">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="search-input" 
                               placeholder="Sipariş numarası ile ara..." 
                               value="{{ $searchTerm ?? '' }}">
                        @if(!empty($searchTerm))
                            <a href="{{ route('orders.index') }}" class="clear-search" title="Temizle">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="search-btn" id="searchBtn" {{ empty($searchTerm) ? 'disabled' : '' }}>
                        <i class="bi bi-search"></i>
                        <span>Ara</span>
                    </button>
                </form>
            </div>

            @if($orders->count() > 0)
                @if(!empty($searchTerm))
                    <div class="search-results-info">
                        <i class="bi bi-info-circle"></i>
                        <span>"{{ $searchTerm }}" için <strong>{{ $orders->total() }}</strong> sonuç bulundu</span>
                    </div>
                @endif
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($orders as $order)
                        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                                        <h3 style="margin: 0; color: var(--primary-color, #8b7355);">
                                            {{ $order->order_number ?? 'ORD-' . $order->id }}
                                        </h3>
                                        <span class="status-badge status-{{ $order->order_status }}" style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                            {{ $order->status_label }}
                                        </span>
                                        @if($order->payment_status === 'paid')
                                            <span class="status-badge status-paid" style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                                <i class="bi bi-check-circle me-1"></i>Ödendi
                                            </span>
                                        @endif
                                    </div>
                                    <div style="color: #666; margin-bottom: 0.5rem;">
                                        <i class="bi bi-calendar"></i> {{ $order->created_at->format('d.m.Y H:i') }}
                                    </div>
                                    <div style="color: #666; margin-bottom: 0.5rem;">
                                        <i class="bi bi-box-seam"></i> {{ count($order->items) }} ürün
                                    </div>
                                    @if($order->statusHistory && $order->statusHistory->count() > 0)
                                        @php
                                            $latestStatus = $order->statusHistory->first();
                                        @endphp
                                        <div style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">
                                            <i class="bi bi-info-circle"></i> Son durum: {{ $latestStatus->status_label }}
                                            @if($latestStatus->created_at->diffInHours() < 24)
                                                <span style="color: #999;">({{ $latestStatus->created_at->diffForHumans() }})</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                        @foreach(array_slice($order->items, 0, 3) as $item)
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                <div>
                                                    <div style="font-size: 0.85rem; font-weight: 600;">{{ $item['name'] }}</div>
                                                    <div style="font-size: 0.75rem; color: #666;">{{ $item['quantity'] }} adet</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($order->items) > 3)
                                            <div style="display: flex; align-items: center; color: #666;">
                                                +{{ count($order->items) - 3 }} ürün daha
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.5rem; font-weight: 600; color: var(--primary-color, #8b7355); margin-bottom: 1rem;">
                                        ₺{{ number_format($order->total, 2) }}
                                    </div>
                                    <a href="{{ route('orders.show', $order->id) }}" style="padding: 0.5rem 1rem; background: var(--primary-color, #8b7355); color: white; text-decoration: none; border-radius: 5px; display: inline-block;">
                                        Detayları Gör
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($orders->hasPages())
                    <div style="margin-top: 2rem;">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    @if(!empty($searchTerm))
                        <i class="bi bi-search" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h3 style="color: #666; margin-bottom: 0.5rem;">Sonuç bulunamadı</h3>
                        <p style="color: #999; margin-bottom: 2rem;">"{{ $searchTerm }}" için sipariş bulunamadı. Farklı bir sipariş numarası deneyin.</p>
                        <a href="{{ route('orders.index') }}" style="padding: 0.75rem 2rem; background: var(--primary-color, #8b7355); color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                            Tüm Siparişleri Görüntüle
                        </a>
                    @else
                        <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h3 style="color: #666; margin-bottom: 0.5rem;">Henüz siparişiniz yok</h3>
                        <p style="color: #999; margin-bottom: 2rem;">İlk siparişinizi vermek için alışverişe başlayın!</p>
                        <a href="/" style="padding: 0.75rem 2rem; background: var(--primary-color, #8b7355); color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                            Alışverişe Başla
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    @include('layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.getElementById('searchBtn');
            
            if (searchInput && searchBtn) {
                // Sayfa yüklendiğinde kontrol et
                function checkInput() {
                    const value = searchInput.value.trim();
                    searchBtn.disabled = value === '';
                }
                
                // İlk kontrol
                checkInput();
                
                // Input değiştiğinde kontrol et
                searchInput.addEventListener('input', checkInput);
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter' && searchBtn.disabled) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>
