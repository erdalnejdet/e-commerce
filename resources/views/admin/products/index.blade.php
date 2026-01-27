@extends('admin.layout')

@section('title', 'Ürün Yönetimi')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0; color: var(--admin-dark);">Ürünler</h2>
        <a href="/admin/products/create" style="padding: 0.75rem 1.5rem; background: var(--admin-primary); color: white; text-decoration: none; border-radius: 8px;">
            <i class="bi bi-plus-circle"></i> Yeni Ürün
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 1rem; text-align: left;">ID</th>
                <th style="padding: 1rem; text-align: left;">Görsel</th>
                <th style="padding: 1rem; text-align: left;">Ürün Adı</th>
                <th style="padding: 1rem; text-align: left;">Fiyat</th>
                <th style="padding: 1rem; text-align: left;">Durum</th>
                <th style="padding: 1rem; text-align: left;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 1rem;">{{ $product->id }}</td>
                    <td style="padding: 1rem;">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image" style="color: #999;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <strong>{{ $product->name }}</strong>
                        @if($product->badge)
                            <span style="background: var(--admin-primary); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">{{ $product->badge }}</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">₺{{ number_format($product->base_price, 2) }}</td>
                    <td style="padding: 1rem;">
                        @if($product->is_active)
                            <span style="color: #28a745; font-weight: 600;">Aktif</span>
                        @else
                            <span style="color: #dc3545; font-weight: 600;">Pasif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <a href="/admin/products/{{ $product->id }}/edit" style="padding: 0.5rem 1rem; background: var(--admin-secondary); color: white; text-decoration: none; border-radius: 6px; margin-right: 0.5rem;">
                            <i class="bi bi-pencil"></i> Düzenle
                        </a>
                        <form action="/admin/products/{{ $product->id }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 0.5rem 1rem; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="bi bi-trash"></i> Sil
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: #999;">
                        Henüz ürün eklenmemiş.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
