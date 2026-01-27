@extends('admin.layout')

@section('title', 'Sayfa İçerikleri')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">Sayfa İçerikleri</h2>
    
    <div style="display: grid; gap: 1rem;">
        <a href="/admin/pages/home/edit" style="padding: 1.5rem; background: #f8f5f2; border: 2px solid var(--admin-primary); border-radius: 12px; text-decoration: none; color: var(--admin-dark); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0 0 0.5rem; color: var(--admin-primary);">Ana Sayfa</h3>
                <p style="margin: 0; color: #666;">Hero bölümü, ürünler, lezzetler ve diğer içerikleri düzenleyin</p>
            </div>
            <i class="bi bi-chevron-right" style="font-size: 1.5rem; color: var(--admin-primary);"></i>
        </a>
    </div>
</div>
@endsection
