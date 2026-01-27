@extends('admin.layout')

@section('title', 'Sayfa İçeriklerini Düzenle')

@section('content')
<div class="admin-card">
    <h2 style="margin: 0 0 2rem; color: var(--admin-dark);">{{ ucfirst($page) }} Sayfası İçeriklerini Düzenle</h2>
    
    <form action="/admin/pages/{{ $page }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Hero Bölümü</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Başlık</label>
                <input type="text" name="hero_title" value="{{ $sections->firstWhere('section_key', 'hero_title')?->content ?? 'MAKE LIFE BEAUTIFUL' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Alt Başlık</label>
                <textarea name="hero_subtitle" rows="2" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ $sections->firstWhere('section_key', 'hero_subtitle')?->content ?? 'Özel günlerinizi unutulmaz kılacak, el yapımı pastalar ve tatlılar' }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Hero Görsel URL</label>
                <input type="url" name="hero_image" value="{{ $sections->firstWhere('section_key', 'hero_image')?->content ?? '' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Bölüm Başlıkları</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Top Picks Başlık</label>
                <input type="text" name="top_picks_title" value="{{ $sections->firstWhere('section_key', 'top_picks_title')?->content ?? 'TOP PICKS' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Top Picks Alt Başlık</label>
                <input type="text" name="top_picks_subtitle" value="{{ $sections->firstWhere('section_key', 'top_picks_subtitle')?->content ?? 'En çok tercih edilen özel tasarım pastalarımız' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Lezzetler Başlık</label>
                <input type="text" name="flavours_title" value="{{ $sections->firstWhere('section_key', 'flavours_title')?->content ?? 'OUR FLAVOURS' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Lezzetler Alt Başlık</label>
                <input type="text" name="flavours_subtitle" value="{{ $sections->firstWhere('section_key', 'flavours_subtitle')?->content ?? 'Benzersiz lezzet kombinasyonlarımızı keşfedin' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
        </div>
        
        <h3 style="margin: 2rem 0 1rem; color: var(--admin-primary);">Hakkımızda Bölümü</h3>
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Başlık</label>
                <input type="text" name="about_title" value="{{ $sections->firstWhere('section_key', 'about_title')?->content ?? 'WE MAKE CAKES ONLY WITH LOVE' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">İçerik (Paragraf 1)</label>
                <textarea name="about_content_1" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ $sections->firstWhere('section_key', 'about_content_1')?->content ?? '' }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">İçerik (Paragraf 2)</label>
                <textarea name="about_content_2" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">{{ $sections->firstWhere('section_key', 'about_content_2')?->content ?? '' }}</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Görsel URL</label>
                <input type="url" name="about_image" value="{{ $sections->firstWhere('section_key', 'about_image')?->content ?? '' }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e0e0e0; border-radius: 8px;">
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
