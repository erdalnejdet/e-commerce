@extends('admin.layout')

@section('title', 'Lezzetler Yönetimi')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0; color: var(--admin-dark);">Anasayfa Lezzetler</h2>
        <a href="/admin/flavours/create" style="padding: 0.75rem 1.5rem; background: var(--admin-primary); color: white; text-decoration: none; border-radius: 8px;">
            <i class="bi bi-plus-circle"></i> Yeni Lezzet
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 1rem; text-align: left;">ID</th>
                <th style="padding: 1rem; text-align: left;">Görsel</th>
                <th style="padding: 1rem; text-align: left;">Başlık</th>
                <th style="padding: 1rem; text-align: left;">Col Size</th>
                <th style="padding: 1rem; text-align: left;">Sıra</th>
                <th style="padding: 1rem; text-align: left;">Durum</th>
                <th style="padding: 1rem; text-align: left;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($flavours as $flavour)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 1rem;">{{ $flavour->id }}</td>
                    <td style="padding: 1rem;">
                        @if($flavour->image)
                            <img src="{{ $flavour->image }}" alt="{{ $flavour->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image" style="color: #999;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <strong>{{ $flavour->title }}</strong>
                        @if($flavour->description)
                            <br><small style="color: #666;">{{ Str::limit($flavour->description, 50) }}</small>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <span style="background: var(--admin-primary); color: white; padding: 0.3rem 0.8rem; border-radius: 4px; font-weight: 600;">{{ $flavour->col_size }}</span>
                    </td>
                    <td style="padding: 1rem;">{{ $flavour->sort_order }}</td>
                    <td style="padding: 1rem;">
                        @if($flavour->is_active)
                            <span style="color: #28a745; font-weight: 600;">Aktif</span>
                        @else
                            <span style="color: #dc3545; font-weight: 600;">Pasif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <a href="/admin/flavours/{{ $flavour->id }}/edit" style="padding: 0.5rem 1rem; background: var(--admin-secondary); color: white; text-decoration: none; border-radius: 6px; margin-right: 0.5rem;">
                            <i class="bi bi-pencil"></i> Düzenle
                        </a>
                        <form action="/admin/flavours/{{ $flavour->id }}" method="POST" style="display: inline;" class="delete-form" data-message="Bu lezzeti silmek istediğinize emin misiniz?">
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
                    <td colspan="7" style="padding: 2rem; text-align: center; color: #999;">
                        Henüz lezzet eklenmemiş.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded');
        return;
    }

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-message') || 'Bu işlemi yapmak istediğinize emin misiniz?';
            
            const result = await Swal.fire({
                title: 'Emin misiniz?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'İptal'
            });

            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
