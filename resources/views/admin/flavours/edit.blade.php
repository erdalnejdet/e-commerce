@extends('admin.layout')

@section('title', 'Lezzet Düzenle')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">Lezzet Düzenle</h2>
    
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
    
    <form action="/admin/flavours/{{ $flavour->id }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Başlık *</label>
                <input type="text" name="title" required value="{{ old('title', $flavour->title) }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Col Size *</label>
                <select name="col_size" required style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                    <option value="1" {{ old('col_size', $flavour->col_size) == '1' ? 'selected' : '' }}>1 Kolon (Küçük)</option>
                    <option value="2" {{ old('col_size', $flavour->col_size) == '2' ? 'selected' : '' }}>2 Kolon (Orta)</option>
                    <option value="3" {{ old('col_size', $flavour->col_size) == '3' ? 'selected' : '' }}>3 Kolon (Büyük)</option>
                    <option value="4" {{ old('col_size', $flavour->col_size) == '4' ? 'selected' : '' }}>4 Kolon (Çok Büyük)</option>
                </select>
                <small style="color: #666; font-size: 0.85rem;">Grid yapısında kaç kolon kaplayacağını belirler</small>
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Açıklama</label>
            <textarea name="description" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ old('description', $flavour->description) }}</textarea>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Görsel URL *</label>
            <input type="url" name="image" required value="{{ old('image', $flavour->image) }}" placeholder="https://images.unsplash.com/..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            @if($flavour->image)
                <div style="margin-top: 0.5rem;">
                    <img src="{{ $flavour->image }}" alt="Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            @endif
            <small style="color: #666; font-size: 0.85rem;">Görselin tam URL adresini girin</small>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Link URL</label>
                <input type="url" name="link" value="{{ old('link', $flavour->link) }}" placeholder="https://..." style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                <small style="color: #666; font-size: 0.85rem;">Tıklanınca gidilecek sayfa</small>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Link Metni</label>
                <input type="text" name="link_text" value="{{ old('link_text', $flavour->link_text) }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Sıralama</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $flavour->sort_order) }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            
            <div style="display: flex; align-items: flex-end;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.75rem 1rem; border: 2px solid #e0e0e0; border-radius: 8px; width: 100%;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $flavour->is_active) ? 'checked' : '' }}>
                    <span>Aktif</span>
                </label>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" style="padding: 0.75rem 2rem; background: var(--admin-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Güncelle
            </button>
            <a href="/admin/flavours" style="padding: 0.75rem 2rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                İptal
            </a>
        </div>
    </form>
</div>
@endsection
