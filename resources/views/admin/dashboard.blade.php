@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="admin-card">
        <h3 style="margin: 0 0 1rem; color: var(--admin-primary);">
            <i class="bi bi-box-seam"></i> Toplam Ürün
        </h3>
        <p style="font-size: 2rem; margin: 0; font-weight: 700;" id="totalProducts">0</p>
    </div>
    
    <div class="admin-card">
        <h3 style="margin: 0 0 1rem; color: var(--admin-primary);">
            <i class="bi bi-check-circle"></i> Aktif Ürünler
        </h3>
        <p style="font-size: 2rem; margin: 0; font-weight: 700;" id="activeProducts">0</p>
    </div>
    
    <div class="admin-card">
        <h3 style="margin: 0 0 1rem; color: var(--admin-primary);">
            <i class="bi bi-file-text"></i> Sayfa Bölümleri
        </h3>
        <p style="font-size: 2rem; margin: 0; font-weight: 700;" id="totalSections">0</p>
    </div>
    
    <div class="admin-card">
        <h3 style="margin: 0 0 1rem; color: var(--admin-primary);">
            <i class="bi bi-cart-check"></i> Toplam Sipariş
        </h3>
        <p style="font-size: 2rem; margin: 0; font-weight: 700;" id="totalOrders">0</p>
    </div>
    
    <div class="admin-card">
        <h3 style="margin: 0 0 1rem; color: var(--admin-primary);">
            <i class="bi bi-hourglass-split"></i> Bekleyen Siparişler
        </h3>
        <p style="font-size: 2rem; margin: 0; font-weight: 700;" id="pendingOrders">0</p>
    </div>
</div>

<div class="admin-card">
    <h2 style="margin: 0 0 1.5rem; color: var(--admin-dark);">Hızlı İşlemler</h2>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="/admin/products/create" style="padding: 0.75rem 1.5rem; background: var(--admin-primary); color: white; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-plus-circle"></i> Yeni Ürün Ekle
        </a>
        <a href="/admin/pages/home/edit" style="padding: 0.75rem 1.5rem; background: var(--admin-secondary); color: white; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-pencil"></i> Ana Sayfa Düzenle
        </a>
        <a href="/admin/orders" style="padding: 0.75rem 1.5rem; background: #28a745; color: white; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-cart-check"></i> Siparişleri Görüntüle
        </a>
    </div>
</div>

<script>
    // Load statistics
    async function loadStats() {
        try {
            const token = localStorage.getItem('admin_token');
            
            // Load products count
            const productsRes = await fetch('/api/admin/products', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });
            if (productsRes.ok) {
                const products = await productsRes.json();
                document.getElementById('totalProducts').textContent = products.data?.length || 0;
                document.getElementById('activeProducts').textContent = products.data?.filter(p => p.is_active).length || 0;
            }
            
            // Load sections count
            const sectionsRes = await fetch('/api/admin/pages', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });
            if (sectionsRes.ok) {
                const sections = await sectionsRes.json();
                document.getElementById('totalSections').textContent = sections.data?.length || 0;
            }
            
            // Load orders count
            const ordersRes = await fetch('/api/admin/orders', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });
            if (ordersRes.ok) {
                const orders = await ordersRes.json();
                const ordersData = orders.data?.data || orders.data || [];
                document.getElementById('totalOrders').textContent = ordersData.length || 0;
                document.getElementById('pendingOrders').textContent = ordersData.filter(o => o.order_status === 'pending' || o.order_status === 'processing' || o.order_status === 'preparing').length || 0;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }
    
    loadStats();
</script>
@endsection
