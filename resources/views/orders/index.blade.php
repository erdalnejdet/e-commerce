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
            <h1 class="page-title" style="margin-bottom: 2rem;">Sipariş Geçmişim</h1>

            @if($orders->count() > 0)
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($orders as $order)
                        <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                        <h3 style="margin: 0; color: var(--primary-color, #8b7355);">
                                            {{ $order->order_number ?? 'ORD-' . $order->id }}
                                        </h3>
                                        <span class="status-badge status-{{ $order->order_status }}" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>
                                    <div style="color: #666; margin-bottom: 0.5rem;">
                                        <i class="bi bi-calendar"></i> {{ $order->created_at->format('d.m.Y H:i') }}
                                    </div>
                                    <div style="color: #666; margin-bottom: 1rem;">
                                        <i class="bi bi-box-seam"></i> {{ count($order->items) }} ürün
                                    </div>
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
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3 style="color: #666; margin-bottom: 0.5rem;">Henüz siparişiniz yok</h3>
                    <p style="color: #999; margin-bottom: 2rem;">İlk siparişinizi vermek için alışverişe başlayın!</p>
                    <a href="/" style="padding: 0.75rem 2rem; background: var(--primary-color, #8b7355); color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                        Alışverişe Başla
                    </a>
                </div>
            @endif
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
    </style>
</body>
</html>
