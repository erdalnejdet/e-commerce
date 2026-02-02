@extends('admin.layout')

@section('title', 'Yeni Ürün Ekle')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">Yeni Ürün Ekle</h2>
    
    @if($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <strong>Hata!</strong> Lütfen formu kontrol edin:
            <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="/admin/products" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ürün Adı *</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Temel Fiyat (₺) *</label>
                <input type="number" name="base_price" step="0.01" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Açıklama</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;"></textarea>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ana Görsel *</label>
            <input type="file" name="image" accept="image/*" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            <small style="color: #666; font-size: 0.85rem;">Maksimum 2MB, JPG, PNG, GIF veya WebP formatında</small>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ek Görseller</label>
            <input type="file" name="images[]" accept="image/*" multiple style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            <small style="color: #666; font-size: 0.85rem;">Birden fazla görsel seçebilirsiniz</small>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Badge</label>
                <select name="badge" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="">Seçiniz</option>
                    <option value="Yeni">Yeni</option>
                    <option value="Popüler">Popüler</option>
                    <option value="Özel">Özel</option>
                    <option value="Trend">Trend</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Sıralama</label>
                <input type="number" name="sort_order" value="0" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Boyutlar *</label>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="s">
                    <span>S size (1.5 kg - 5-6 kişilik)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="m">
                    <span>M size (2.5 kg - 9-10 kişilik)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 8px; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="l">
                    <span>L size (3.5 kg - 12-15 kişilik)</span>
                </label>
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Lezzetler</label>
            <div id="flavors-container">
                <div class="flavor-item" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; margin-bottom: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Lezzet Adı</label>
                        <input type="text" name="flavors[0][name]" placeholder="Örn: Nutella" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Görsel URL</label>
                        <input type="url" name="flavors[0][image]" placeholder="https://..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    </div>
                    <div style="display: flex; align-items: flex-end;">
                        <button type="button" onclick="removeFlavor(this)" style="padding: 0.75rem 1rem; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addFlavor()" style="padding: 0.75rem 1.5rem; background: var(--admin-secondary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="bi bi-plus-circle"></i> Lezzet Ekle
            </button>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" checked>
                <span style="font-weight: 600;">Aktif</span>
            </label>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="padding: 0.75rem 2rem; background: var(--admin-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Kaydet
            </button>
            <a href="/admin/products" style="padding: 0.75rem 2rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                İptal
            </a>
        </div>
    </form>
</div>

<script>
    let flavorIndex = 1;
    
    function addFlavor() {
        const container = document.getElementById('flavors-container');
        const newFlavor = document.createElement('div');
        newFlavor.className = 'flavor-item';
        newFlavor.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; margin-bottom: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;';
        newFlavor.innerHTML = `
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Lezzet Adı</label>
                <input type="text" name="flavors[${flavorIndex}][name]" placeholder="Örn: Nutella" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Görsel URL</label>
                <input type="url" name="flavors[${flavorIndex}][image]" placeholder="https://..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="button" onclick="removeFlavor(this)" style="padding: 0.75rem 1rem; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newFlavor);
        flavorIndex++;
    }
    
    function removeFlavor(button) {
        const container = document.getElementById('flavors-container');
        if (container.children.length > 1) {
            button.closest('.flavor-item').remove();
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Uyarı!',
                    text: 'En az bir lezzet eklemelisiniz!'
                });
            } else {
                alert('En az bir lezzet eklemelisiniz!');
            }
        }
    }
    
    // Checkbox hover effect
    document.querySelectorAll('input[type="checkbox"][name="sizes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.style.borderColor = 'var(--admin-primary)';
                label.style.background = 'rgba(139, 115, 85, 0.05)';
            } else {
                label.style.borderColor = '#e0e0e0';
                label.style.background = 'transparent';
            }
        });
    });
</script>

<style>
    .flavor-item {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
