<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sipariş Detayı - PAULINE</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    @include('layouts.header')

    <section style="padding: 4rem 0; min-height: 60vh;">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('orders.index') }}" style="color: var(--primary-color, #8b7355); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-arrow-left"></i> Sipariş Geçmişine Dön
                </a>
            </div>

            <h1 class="page-title" style="margin-bottom: 2rem;">Sipariş Detayı</h1>

            <!-- Sipariş Bilgileri -->
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <strong style="color: #666; font-size: 0.9rem;">Sipariş Numarası</strong>
                        <div style="font-size: 1.2rem; font-weight: 600; color: var(--primary-color, #8b7355);">{{ $order->order_number ?? 'ORD-' . $order->id }}</div>
                    </div>
                    <div>
                        <strong style="color: #666; font-size: 0.9rem;">Sipariş Tarihi</strong>
                        <div>{{ $order->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <div>
                        <strong style="color: #666; font-size: 0.9rem;">Sipariş Durumu</strong>
                        <div>
                            <span class="status-badge status-{{ $order->order_status }}" style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600;">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <strong style="color: #666; font-size: 0.9rem;">Ödeme Durumu</strong>
                        <div>
                            <span class="status-badge status-{{ $order->payment_status }}" style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600;">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Sol Taraf -->
                <div>
                    <!-- Müşteri Bilgileri -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Müşteri Bilgileri</h3>
                        <div style="display: grid; gap: 0.75rem;">
                            <div><strong>Ad Soyad:</strong> {{ $order->first_name }} {{ $order->last_name }}</div>
                            <div><strong>E-posta:</strong> {{ $order->email }}</div>
                            <div><strong>Telefon:</strong> {{ $order->phone }}</div>
                        </div>
                    </div>
                    
                    <!-- Teslimat Adresi -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Teslimat Adresi</h3>
                        <div>
                            <div>{{ $order->address }}</div>
                            <div>{{ $order->district }}, {{ $order->city }}</div>
                            <div>Posta Kodu: {{ $order->postal_code }}</div>
                        </div>
                    </div>
                    
                    <!-- Sipariş Ürünleri -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Sipariş Ürünleri</h3>
                        <div style="display: grid; gap: 1rem;">
                            @foreach($order->items as $item)
                                <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; margin-bottom: 0.5rem;">{{ $item['name'] }}</div>
                                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">
                                            {{ strtoupper($item['size']) }} - {{ $item['flavor_name'] ?? $item['flavor'] }}
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>Adet: {{ $item['quantity'] }}</span>
                                            <span style="font-weight: 600;">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Durum Geçmişi -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Sipariş Durumu Geçmişi</h3>
                        <div style="display: grid; gap: 1rem;">
                            @forelse($order->statusHistory as $history)
                                <div style="padding: 1rem; background: #f8f5f2; border-radius: 8px; border-left: 4px solid var(--primary-color, #8b7355);">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                        <div>
                                            <strong>{{ $history->status }}</strong>
                                        </div>
                                        <div style="font-size: 0.85rem; color: #666;">{{ $history->created_at->format('d.m.Y H:i') }}</div>
                                    </div>
                                    @if($history->notes)
                                        <div style="font-size: 0.9rem; color: #666;">{{ $history->notes }}</div>
                                    @endif
                                </div>
                            @empty
                                <div style="color: #666; text-align: center; padding: 1rem;">Henüz durum geçmişi yok.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Sağ Taraf -->
                <div>
                    <!-- Sipariş Özeti -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Sipariş Özeti</h3>
                        <div style="display: grid; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Ara Toplam:</span>
                                <strong>₺{{ number_format($order->subtotal, 2) }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>KDV (18%):</span>
                                <strong>₺{{ number_format($order->tax, 2) }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Kargo:</span>
                                <span style="color: #28a745;">Ücretsiz</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 2px solid #e0e0e0; font-size: 1.2rem;">
                                <span><strong>Toplam:</strong></span>
                                <strong style="color: var(--primary-color, #8b7355);">₺{{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ödeme Bilgileri -->
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 1rem; color: var(--primary-color, #8b7355);">Ödeme Bilgileri</h3>
                        <div>
                            <div style="margin-bottom: 0.75rem;">
                                <strong>Ödeme Yöntemi:</strong>
                                <div>
                                    @if($order->payment_method === 'cash_on_delivery')
                                        Kapıda Ödeme
                                    @else
                                        Kredi Kartı
                                    @endif
                                </div>
                            </div>
                            @if($order->notes)
                                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                                    <strong>Notlar:</strong>
                                    <div style="margin-top: 0.5rem; color: #666;">{{ $order->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <style>
        .status-badge {
            display: inline-block;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cfe2ff; color: #084298; }
        .status-preparing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-paid { background: #d1e7dd; color: #0f5132; }
        .status-failed { background: #f8d7da; color: #721c24; }
    </style>
</body>
</html>
