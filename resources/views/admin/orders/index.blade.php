@extends('admin.layout')

@section('title', 'Siparişler')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">Siparişler</h2>
    
    <div style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <select id="statusFilter" style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                <option value="">Tüm Durumlar</option>
                <option value="pending">Beklemede</option>
                <option value="processing">İşleniyor</option>
                <option value="preparing">Hazırlanıyor</option>
                <option value="shipped">Kargoya Verildi</option>
                <option value="delivered">Teslim Edildi</option>
                <option value="cancelled">İptal Edildi</option>
            </select>
            <input type="text" id="searchInput" placeholder="Sipariş No, Müşteri Adı..." style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 8px; flex: 1; min-width: 200px;">
        </div>
    </div>
    
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f5f2; border-bottom: 2px solid #e0e0e0;">
                    <th style="padding: 1rem; text-align: left;">Sipariş No</th>
                    <th style="padding: 1rem; text-align: left;">Müşteri</th>
                    <th style="padding: 1rem; text-align: left;">Tarih</th>
                    <th style="padding: 1rem; text-align: left;">Tutar</th>
                    <th style="padding: 1rem; text-align: left;">Durum</th>
                    <th style="padding: 1rem; text-align: left;">Ödeme</th>
                    <th style="padding: 1rem; text-align: center;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #e0e0e0;">
                        <td style="padding: 1rem;">
                            <strong>{{ $order->order_number ?? 'ORD-' . $order->id }}</strong>
                        </td>
                        <td style="padding: 1rem;">
                            <div>{{ $order->first_name }} {{ $order->last_name }}</div>
                            <small style="color: #666;">{{ $order->email }}</small>
                            @if($order->user)
                                <div><small style="color: #8b7355;">Üye</small></div>
                            @else
                                <div><small style="color: #999;">Misafir</small></div>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td style="padding: 1rem;">
                            <strong>₺{{ number_format($order->total, 2) }}</strong>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="status-badge status-{{ $order->order_status }}" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="status-badge status-{{ $order->payment_status }}" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm" style="background: var(--admin-primary); color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; font-size: 0.85rem;">
                                Detay
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #666;">
                            Henüz sipariş bulunmamaktadır.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div style="margin-top: 2rem;">
            {{ $orders->links() }}
        </div>
    @endif
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
@endsection
