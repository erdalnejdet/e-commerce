@extends('admin.layout')

@section('title', 'Sayfa İçeriklerini Düzenle')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">{{ ucfirst($page) }} Sayfası İçeriklerini Düzenle</h2>

    @if($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <strong>Hata!</strong>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="/admin/pages/{{ $page }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Hero Bölümü</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Başlık</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $sections->firstWhere('section_key', 'hero_title')?->content ?? 'MAKE LIFE BEAUTIFUL') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Alt Başlık</label>
                <textarea name="hero_subtitle" rows="2" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ old('hero_subtitle', $sections->firstWhere('section_key', 'hero_subtitle')?->content ?? 'Özel günlerinizi unutulmaz kılacak, el yapımı pastalar ve tatlılar') }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Hero Görsel</label>
                @php $heroImage = old('hero_image_existing', $sections->firstWhere('section_key', 'hero_image')?->content); @endphp
                @if($heroImage)
                    <input type="hidden" name="hero_image_existing" value="{{ $heroImage }}">
                    <img src="{{ $heroImage }}" alt="Hero" style="max-width: 240px; max-height: 140px; border-radius: 8px; margin-bottom: 0.75rem; display: block; object-fit: cover;">
                @endif
                <input type="file" name="hero_image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                <small style="color: #666; font-size: 0.85rem;">Cloudinary'e yüklenir. Yeni dosya seçmezseniz mevcut görsel korunur.</small>
            </div>
        </div>
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Bölüm Başlıkları</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Top Picks Başlık</label>
                <input type="text" name="top_picks_title" value="{{ old('top_picks_title', $sections->firstWhere('section_key', 'top_picks_title')?->content ?? 'TOP PICKS') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Top Picks Alt Başlık</label>
                <input type="text" name="top_picks_subtitle" value="{{ old('top_picks_subtitle', $sections->firstWhere('section_key', 'top_picks_subtitle')?->content ?? 'En çok tercih edilen özel tasarım pastalarımız') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Lezzetler Başlık</label>
                <input type="text" name="flavours_title" value="{{ old('flavours_title', $sections->firstWhere('section_key', 'flavours_title')?->content ?? 'OUR FLAVOURS') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Lezzetler Alt Başlık</label>
                <input type="text" name="flavours_subtitle" value="{{ old('flavours_subtitle', $sections->firstWhere('section_key', 'flavours_subtitle')?->content ?? 'Benzersiz lezzet kombinasyonlarımızı keşfedin') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Hakkımızda Bölümü</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Başlık</label>
                <input type="text" name="about_title" value="{{ old('about_title', $sections->firstWhere('section_key', 'about_title')?->content ?? 'WE MAKE CAKES ONLY WITH LOVE') }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">İçerik (Paragraf 1)</label>
                <textarea name="about_content_1" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ old('about_content_1', $sections->firstWhere('section_key', 'about_content_1')?->content ?? '') }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">İçerik (Paragraf 2)</label>
                <textarea name="about_content_2" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ old('about_content_2', $sections->firstWhere('section_key', 'about_content_2')?->content ?? '') }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Hakkımızda Görsel</label>
                @php $aboutImage = old('about_image_existing', $sections->firstWhere('section_key', 'about_image')?->content); @endphp
                @if($aboutImage)
                    <input type="hidden" name="about_image_existing" value="{{ $aboutImage }}">
                    <img src="{{ $aboutImage }}" alt="About" style="max-width: 240px; max-height: 140px; border-radius: 8px; margin-bottom: 0.75rem; display: block; object-fit: cover;">
                @endif
                <input type="file" name="about_image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
                <small style="color: #666; font-size: 0.85rem;">Cloudinary'e yüklenir. Yeni dosya seçmezseniz mevcut görsel korunur.</small>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" style="padding: 0.75rem 2rem; background: var(--admin-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                Kaydet
            </button>
            <a href="/admin/pages" style="padding: 0.75rem 2rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                İptal
            </a>
        </div>
    </form>
</div>
@endsection
