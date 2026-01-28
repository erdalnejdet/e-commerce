@extends('admin.layout')

@section('title', 'Sipariş Detayı')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0; color: var(--admin-dark);">Sipariş Detayı</h2>
        <a href="{{ route('admin.orders.index') }}" style="padding: 0.5rem 1rem; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
            <i class="bi bi-arrow-left"></i> Geri
        </a>
    </div>
    
    <!-- Sipariş Bilgileri -->
    <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <strong style="color: #666; font-size: 0.9rem;">Sipariş Numarası</strong>
                <div style="font-size: 1.2rem; font-weight: 600; color: var(--admin-primary);">{{ $order->order_number ?? 'ORD-' . $order->id }}</div>
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
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Müşteri Bilgileri</h3>
                <div style="display: grid; gap: 0.75rem;">
                    <div><strong>Ad Soyad:</strong> {{ $order->first_name }} {{ $order->last_name }}</div>
                    <div><strong>E-posta:</strong> {{ $order->email }}</div>
                    <div><strong>Telefon:</strong> {{ $order->phone }}</div>
                    @if($order->user)
                        <div><strong>Üyelik:</strong> <span style="color: var(--admin-primary);">Üye</span></div>
                    @else
                        <div><strong>Üyelik:</strong> <span style="color: #999;">Misafir</span></div>
                    @endif
                </div>
            </div>
            
            <!-- Teslimat Adresi -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Teslimat Adresi</h3>
                <div>
                    <div>{{ $order->address }}</div>
                    <div>{{ $order->district }}, {{ $order->city }}</div>
                    <div>Posta Kodu: {{ $order->postal_code }}</div>
                </div>
            </div>
            
            <!-- Sipariş Ürünleri -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Sipariş Ürünleri</h3>
                <div style="display: grid; gap: 1rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; margin-bottom: 0.5rem;">{{ $item['name'] }}</div>
                                <div style="font-size: 0.9rem; color: #666;">
                                    {{ strtoupper($item['size']) }} - {{ $item['flavor_name'] ?? $item['flavor'] }}
                                </div>
                                <div style="margin-top: 0.5rem;">
                                    <span>Adet: {{ $item['quantity'] }}</span>
                                    <span style="margin-left: 1rem; font-weight: 600;">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Durum Geçmişi -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Durum Geçmişi</h3>
                <div style="display: grid; gap: 1rem;">
                    @forelse($order->statusHistory as $history)
                        <div style="padding: 1rem; background: #f8f5f2; border-radius: 8px; border-left: 4px solid var(--admin-primary);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <strong>{{ $history->status }}</strong>
                                    @if($history->updatedBy)
                                        <div style="font-size: 0.85rem; color: #666;">{{ $history->updatedBy->name }} tarafından</div>
                                    @endif
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
            <!-- Durum Güncelleme -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Sipariş Durumu Güncelle</h3>
                <form id="statusForm" action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Yeni Durum</label>
                        <select name="status" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>İşleniyor</option>
                            <option value="preparing" {{ $order->order_status == 'preparing' ? 'selected' : '' }}>Hazırlanıyor</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Kargoya Verildi</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Not (Opsiyonel)</label>
                        <textarea name="notes" rows="3" placeholder="Durum değişikliği notu..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;"></textarea>
                    </div>
                    <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--admin-primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Durumu Güncelle
                    </button>
                </form>
            </div>
            
            <!-- Ödeme Durumu -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Ödeme Durumu</h3>
                <form id="paymentStatusForm" action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <select name="payment_status" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Ödendi</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Başarısız</option>
                        </select>
                    </div>
                    <button type="submit" style="width: 100%; padding: 0.75rem; background: var(--admin-secondary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Ödeme Durumunu Güncelle
                    </button>
                </form>
            </div>
            
            <!-- Sipariş Özeti -->
            <div style="background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid #e0e0e0;">
                <h3 style="margin: 0 0 1rem; color: var(--admin-dark);">Sipariş Özeti</h3>
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
                        <strong style="color: var(--admin-primary);">₺{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
                @if($order->notes)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                        <strong>Müşteri Notu:</strong>
                        <div style="margin-top: 0.5rem; color: #666;">{{ $order->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

<script>
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!confirm('Sipariş durumunu güncellemek istediğinize emin misiniz?')) {
            return;
        }
        this.submit();
    });
    
    document.getElementById('paymentStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!confirm('Ödeme durumunu güncellemek istediğinize emin misiniz?')) {
            return;
        }
        this.submit();
    });
</script>
@endsection
