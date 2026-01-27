<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sepetim - PAULINE">
    <title>Sepetim - PAULINE</title>
    
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.header')

    <!-- Cart Page -->
    <section class="cart-page">
        <div class="container">
            <h1 class="page-title">Sepetim</h1>

            @if(empty($cart))
                <div class="cart-empty text-center py-5">
                    <i class="bi bi-bag-x"></i>
                    <h3 class="mt-4">Sepetiniz Boş</h3>
                    <p>Henüz sepetinize ürün eklemediniz.</p>
                    <a href="/" class="btn btn-primary mt-3">Alışverişe Başla</a>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table cart-table">
                                <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Boyut</th>
                                        <th>Lezzet</th>
                                        <th>Fiyat</th>
                                        <th>Miktar</th>
                                        <th>Toplam</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr data-cart-id="{{ $id }}">
                                            <td>
                                                <div class="product-info">
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                                    <div class="info-text">
                                                        <h5>{{ $item['name'] }}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ strtoupper($item['size']) }}</td>
                                            <td>{{ $item['flavor_name'] ?? $item['flavor'] }}</td>
                                            <td>₺{{ number_format($item['price'], 2) }}</td>
                                            <td>
                                                <div class="quantity-controls">
                                                    <button onclick="updateQuantity('{{ $id }}', {{ $item['quantity'] - 1 }})">-</button>
                                                    <span>{{ $item['quantity'] }}</span>
                                                    <button onclick="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})">+</button>
                                                </div>
                                            </td>
                                            <td class="fw-bold">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="removeFromCart('{{ $id }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary-box">
                            <h4>Sipariş Özeti</h4>
                            
                            @php
                                $subtotal = 0;
                                foreach($cart as $item) {
                                    $subtotal += $item['price'] * $item['quantity'];
                                }
                                $tax = $subtotal * 0.18;
                                $total = $subtotal + $tax;
                            @endphp

                            <div class="summary-row">
                                <span>Ara Toplam:</span>
                                <span>₺{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>KDV (18%):</span>
                                <span>₺{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Kargo:</span>
                                <span class="text-success">Ücretsiz</span>
                            </div>
                            <div class="summary-row total">
                                <span>Toplam:</span>
                                <span class="amount">₺{{ number_format($total, 2) }}</span>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn btn-checkout">
                                <i class="bi bi-credit-card me-2"></i>
                                Ödemeye Geç
                            </a>

                            <div class="mt-3">
                                <a href="/" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Alışverişe Devam Et
                                </a>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-outline-danger w-100" onclick="clearCart()">
                                    <i class="bi bi-trash me-2"></i>
                                    Sepeti Temizle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('layouts.footer')

    <script>
        // Sepet güncelleme fonksiyonu (sayfa yenilemeden)
        async function updateQuantity(id, quantity) {
            if (quantity < 1) {
                removeFromCart(id);
                return;
            }

            const row = document.querySelector(`tr[data-cart-id="${id}"]`);
            const quantitySpan = row.querySelector('.quantity-controls span');
            const totalCell = row.querySelector('td:nth-child(6)');
            
            // Loading durumu göster
            quantitySpan.textContent = '...';

            try {
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id, quantity })
                });

                const data = await response.json();

                if (data.success) {
                    // Miktarı güncelle
                    quantitySpan.textContent = quantity;
                    
                    // Toplam fiyatı güncelle
                    const price = parseFloat(row.querySelector('td:nth-child(4)').textContent.replace('₺', '').replace(',', ''));
                    const newTotal = (price * quantity).toFixed(2);
                    totalCell.textContent = `₺${parseFloat(newTotal).toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
                    
                    // Sepet özetini güncelle
                    updateCartSummary();
                    
                    showToast('Başarılı!', 'Sepet güncellendi!', 'success');
                } else {
                    showToast('Hata!', data.message || 'Bir hata oluştu!', 'error');
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Hata!', 'Bir hata oluştu!', 'error');
                location.reload();
            }
        }

        // Sepetten ürün çıkarma
        async function removeFromCart(id) {
            if (!confirm('Bu ürünü sepetten çıkarmak istediğinize emin misiniz?')) {
                return;
            }

            const row = document.querySelector(`tr[data-cart-id="${id}"]`);
            row.style.opacity = '0.5';
            row.style.pointerEvents = 'none';

            try {
                const response = await fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();

                if (data.success) {
                    // Animasyon ile kaldır
                    row.style.transition = 'all 0.3s ease';
                    row.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        row.remove();
                        updateCartSummary();
                        
                        // Sepet güncellemesini localStorage'a kaydet (checkout sayfası için)
                        localStorage.setItem('cart_updated', Date.now().toString());
                        
                        // Eğer sepet boşsa sayfayı yenile
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload();
                        }
                    }, 300);
                    
                    showToast('Başarılı!', 'Ürün sepetten çıkarıldı!', 'success');
                    
                    // Sepet badge'ini güncelle
                    updateCartBadge();
                } else {
                    showToast('Hata!', data.message || 'Bir hata oluştu!', 'error');
                    row.style.opacity = '1';
                    row.style.pointerEvents = 'auto';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Hata!', 'Bir hata oluştu!', 'error');
                row.style.opacity = '1';
                row.style.pointerEvents = 'auto';
            }
        }

        // Sepeti temizle
        async function clearCart() {
            if (!confirm('Sepeti tamamen temizlemek istediğinize emin misiniz?')) {
                return;
            }

            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Başarılı!', 'Sepet temizlendi!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('Hata!', data.message || 'Bir hata oluştu!', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Hata!', 'Bir hata oluştu!', 'error');
            }
        }

        // Sepet özetini güncelle
        function updateCartSummary() {
            const rows = document.querySelectorAll('tbody tr');
            let subtotal = 0;
            
            rows.forEach(row => {
                const priceText = row.querySelector('td:nth-child(4)').textContent;
                const quantityText = row.querySelector('.quantity-controls span').textContent;
                const price = parseFloat(priceText.replace('₺', '').replace(',', ''));
                const quantity = parseInt(quantityText);
                subtotal += price * quantity;
            });
            
            const tax = subtotal * 0.18;
            const total = subtotal + tax;
            
            // Özeti güncelle
            document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = `₺${subtotal.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
            document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = `₺${tax.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
            document.querySelector('.summary-row.total .amount').textContent = `₺${total.toLocaleString('tr-TR', {minimumFractionDigits: 2})}`;
        }

        // Sepet badge'ini güncelle
        function updateCartBadge() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartLink = document.querySelector('a[href="/cart"]');
                    if (cartLink) {
                        let badge = cartLink.querySelector('.cart-badge');
                        if (data.count > 0) {
                            if (!badge) {
                                badge = document.createElement('span');
                                badge.className = 'cart-badge';
                                cartLink.appendChild(badge);
                            }
                            badge.textContent = data.count;
                        } else if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => console.error('Error updating cart badge:', error));
        }

        // Toast bildirimi göster
        function showToast(title, message, type = 'success') {
            const toastHtml = `
                <div class="toast custom-toast ${type}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="bi ${type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger'} me-2"></i>
                        <strong class="me-auto">${title}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">${message}</div>
                </div>
            `;

            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '9999';
                document.body.appendChild(container);
            }

            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = container.lastElementChild;
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 3000
            });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }
    </script>
</body>
</html>
