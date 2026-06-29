@extends('admin.layout')

@section('title', 'Ürün Düzenle')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">Ürün Düzenle</h2>
    
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
    
    <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ürün Adı *</label>
                <input type="text" name="name" value="{{ $product->name }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Temel Fiyat (₺) *</label>
                <input type="number" name="base_price" step="0.01" value="{{ $product->base_price }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Açıklama</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ $product->description }}</textarea>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ana Görsel</label>
            @if($product->image)
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            <small style="color: #666; font-size: 0.85rem;">Yeni görsel yüklemek için seçin (Maksimum 5MB, Cloudinary'e yüklenir)</small>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ek Görseller</label>
            @if($product->images && count($product->images) > 0)
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                    @foreach($product->images as $img)
                        <img src="{{ $img }}" alt="Product Image" style="max-width: 100px; max-height: 100px; border-radius: 8px; border: 2px solid #e0e0e0;">
                    @endforeach
                </div>
            @endif
            <input type="file" name="images[]" accept="image/*" multiple style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            <small style="color: #666; font-size: 0.85rem;">Mevcut galeriye yeni görseller ekler</small>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Badge</label>
                <select name="badge" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="">Seçiniz</option>
                    <option value="Yeni" {{ $product->badge == 'Yeni' ? 'selected' : '' }}>Yeni</option>
                    <option value="Popüler" {{ $product->badge == 'Popüler' ? 'selected' : '' }}>Popüler</option>
                    <option value="Özel" {{ $product->badge == 'Özel' ? 'selected' : '' }}>Özel</option>
                    <option value="Trend" {{ $product->badge == 'Trend' ? 'selected' : '' }}>Trend</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Sıralama</label>
                <input type="number" name="sort_order" value="{{ $product->sort_order }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Boyutlar *</label>
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                @php
                    $selectedSizes = [];
                    if ($product->sizes && is_array($product->sizes)) {
                        $selectedSizes = array_column($product->sizes, 'id');
                    }
                @endphp
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid {{ in_array('s', $selectedSizes) ? 'var(--admin-primary)' : '#e0e0e0' }}; border-radius: 8px; background: {{ in_array('s', $selectedSizes) ? 'rgba(139, 115, 85, 0.05)' : 'transparent' }}; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="s" {{ in_array('s', $selectedSizes) ? 'checked' : '' }}>
                    <span>S size (1.5 kg - 5-6 kişilik)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid {{ in_array('m', $selectedSizes) ? 'var(--admin-primary)' : '#e0e0e0' }}; border-radius: 8px; background: {{ in_array('m', $selectedSizes) ? 'rgba(139, 115, 85, 0.05)' : 'transparent' }}; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="m" {{ in_array('m', $selectedSizes) ? 'checked' : '' }}>
                    <span>M size (2.5 kg - 9-10 kişilik)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid {{ in_array('l', $selectedSizes) ? 'var(--admin-primary)' : '#e0e0e0' }}; border-radius: 8px; background: {{ in_array('l', $selectedSizes) ? 'rgba(139, 115, 85, 0.05)' : 'transparent' }}; transition: all 0.3s;">
                    <input type="checkbox" name="sizes[]" value="l" {{ in_array('l', $selectedSizes) ? 'checked' : '' }}>
                    <span>L size (3.5 kg - 12-15 kişilik)</span>
                </label>
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 1rem; font-weight: 600;">Lezzetler</label>
            <div id="flavors-container">
                @if($product->flavors && count($product->flavors) > 0)
                    @foreach($product->flavors as $index => $flavor)
                        <div class="flavor-item" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; margin-bottom: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Lezzet Adı</label>
                                <input type="text" name="flavors[{{ $index }}][name]" value="{{ $flavor['name'] ?? '' }}" placeholder="Örn: Nutella" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Görsel</label>
                                @if(!empty($flavor['image']))
                                    <input type="hidden" name="flavors[{{ $index }}][existing_image]" value="{{ $flavor['image'] }}">
                                    <img src="{{ $flavor['image'] }}" alt="" style="max-width: 80px; max-height: 80px; border-radius: 8px; margin-bottom: 0.5rem; display: block;">
                                @endif
                                <input type="file" name="flavors[{{ $index }}][image]" accept="image/*" style="width: 100%; padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                            </div>
                            <div style="display: flex; align-items: flex-end;">
                                <button type="button" onclick="removeFlavor(this)" style="padding: 0.75rem 1rem; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flavor-item" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; margin-bottom: 1rem; padding: 1rem; background: #f8f5f2; border-radius: 8px;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Lezzet Adı</label>
                            <input type="text" name="flavors[0][name]" placeholder="Örn: Nutella" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Görsel</label>
                            <input type="file" name="flavors[0][image]" accept="image/*" style="width: 100%; padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                        </div>
                        <div style="display: flex; align-items: flex-end;">
                            <button type="button" onclick="removeFlavor(this)" style="padding: 0.75rem 1rem; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" onclick="addFlavor()" style="padding: 0.75rem 1.5rem; background: var(--admin-secondary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="bi bi-plus-circle"></i> Lezzet Ekle
            </button>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                <span style="font-weight: 600;">Aktif</span>
            </label>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="padding: 0.75rem 2rem; background: var(--admin-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Güncelle
            </button>
            <a href="/admin/products" style="padding: 0.75rem 2rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                İptal
            </a>
        </div>
    </form>
</div>

<script>
    let flavorIndex = {{ ($product->flavors && count($product->flavors) > 0) ? count($product->flavors) : 1 }};
    
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
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Görsel</label>
                <input type="file" name="flavors[${flavorIndex}][image]" accept="image/*" style="width: 100%; padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 8px;">
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
