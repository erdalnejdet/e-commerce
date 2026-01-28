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
                                        <tr data-cart-id="{{ $id }}" 
                                            data-price="{{ $item['price'] }}" 
                                            data-quantity="{{ $item['quantity'] }}">
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
                                            <td class="item-price">₺{{ number_format($item['price'], 2) }}</td>
                                            <td>
                                                <div class="quantity-controls">
                                                    <button type="button" class="btn-quantity-decrease" data-cart-id="{{ $id }}">-</button>
                                                    <span class="item-quantity">{{ $item['quantity'] }}</span>
                                                    <button type="button" class="btn-quantity-increase" data-cart-id="{{ $id }}">+</button>
                                                </div>
                                            </td>
                                            <td class="fw-bold item-total">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
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
        // Sayfa yüklendiğinde buton event listener'larını ekle
        document.addEventListener('DOMContentLoaded', function() {
            // Artırma butonları
            document.querySelectorAll('.btn-quantity-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-cart-id');
                    const row = document.querySelector(`tr[data-cart-id="${id}"]`);
                    const quantitySpan = row.querySelector('.item-quantity');
                    const currentQuantity = parseInt(quantitySpan.textContent) || parseInt(row.getAttribute('data-quantity')) || 1;
                    updateQuantity(id, currentQuantity + 1);
                });
            });
            
            // Azaltma butonları
            document.querySelectorAll('.btn-quantity-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-cart-id');
                    const row = document.querySelector(`tr[data-cart-id="${id}"]`);
                    const quantitySpan = row.querySelector('.item-quantity');
                    const currentQuantity = parseInt(quantitySpan.textContent) || parseInt(row.getAttribute('data-quantity')) || 1;
                    updateQuantity(id, currentQuantity - 1);
                });
            });
        });
        
        // Sepet güncelleme fonksiyonu (sayfa yenilemeden)
        async function updateQuantity(id, quantity) {
            if (quantity < 1) {
                removeFromCart(id);
                return;
            }

            const row = document.querySelector(`tr[data-cart-id="${id}"]`);
            if (!row) {
                console.error('Satır bulunamadı:', id);
                return;
            }
            
            const quantitySpan = row.querySelector('.item-quantity') || row.querySelector('.quantity-controls span');
            const totalCell = row.querySelector('.item-total') || row.querySelector('td:nth-child(6)');
            
            if (!quantitySpan || !totalCell) {
                console.error('Element bulunamadı');
                return;
            }
            
            // Loading durumu göster
            const originalQuantity = quantitySpan.textContent;
            quantitySpan.textContent = '...';
            row.style.opacity = '0.7';
            row.style.pointerEvents = 'none';

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
                    row.setAttribute('data-quantity', quantity);
                    
                    // Fiyatı data attribute'dan al (backend'den gelen gerçek değer)
                    const price = parseFloat(row.getAttribute('data-price'));
                    
                    if (isNaN(price) || price <= 0) {
                        console.error('Geçersiz fiyat:', price);
                        location.reload();
                        return;
                    }
                    
                    // Toplam fiyatı güncelle
                    const newTotal = price * quantity;
                    totalCell.textContent = `₺${newTotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    
                    // Satır görünürlüğünü geri getir
                    row.style.opacity = '1';
                    row.style.pointerEvents = 'auto';
                    
                    // Sepet özetini güncelle
                    updateCartSummary();
                    
                    // Sepet güncellemesini localStorage'a kaydet (checkout sayfası için)
                    localStorage.setItem('cart_updated', Date.now().toString());
                    
                    // Sepet badge'ini güncelle
                    updateCartBadge();
                    
                    // Toast göster (sadece önemli değişikliklerde)
                    if (Math.abs(quantity - parseInt(originalQuantity)) > 0) {
                        showToast('Başarılı!', 'Sepet güncellendi!', 'success');
                    }
                } else {
                    showToast('Hata!', data.message || 'Bir hata oluştu!', 'error');
                    quantitySpan.textContent = originalQuantity;
                    row.style.opacity = '1';
                    row.style.pointerEvents = 'auto';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Hata!', 'Bir hata oluştu!', 'error');
                quantitySpan.textContent = originalQuantity;
                row.style.opacity = '1';
                row.style.pointerEvents = 'auto';
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
            const rows = document.querySelectorAll('tbody tr[data-cart-id]');
            let subtotal = 0;
            
            rows.forEach(row => {
                // Fiyat ve miktarı data attribute'larından al (backend'den gelen gerçek değerler)
                let price = parseFloat(row.getAttribute('data-price'));
                let quantity = parseInt(row.getAttribute('data-quantity'));
                
                // Eğer data attribute yoksa, DOM'dan oku
                if (isNaN(price) || price <= 0) {
                    const quantitySpan = row.querySelector('.item-quantity') || row.querySelector('.quantity-controls span');
                    if (quantitySpan) {
                        quantity = parseInt(quantitySpan.textContent) || 0;
                    }
                    // Fiyat için fallback - data attribute olmalı ama yoksa parse et
                    const priceCell = row.querySelector('.item-price') || row.querySelector('td:nth-child(4)');
                    if (priceCell) {
                        const priceText = priceCell.textContent;
                        price = parseFloat(priceText.replace(/₺/g, '').replace(/\./g, '').replace(',', '.'));
                    }
                }
                
                // Eğer quantity data attribute'da yoksa, DOM'dan oku
                if (isNaN(quantity) || quantity <= 0) {
                    const quantitySpan = row.querySelector('.item-quantity') || row.querySelector('.quantity-controls span');
                    if (quantitySpan) {
                        quantity = parseInt(quantitySpan.textContent) || 0;
                    }
                }
                
                if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0) {
                    subtotal += price * quantity;
                }
            });
            
            const tax = subtotal * 0.18;
            const total = subtotal + tax;
            
            // Özeti güncelle - selector'ları düzelt
            const summaryBox = document.querySelector('.cart-summary-box');
            if (summaryBox) {
                const summaryRows = summaryBox.querySelectorAll('.summary-row');
                
                // Ara Toplam (ilk summary-row - Kargo hariç)
                if (summaryRows[0] && !summaryRows[0].classList.contains('total') && !summaryRows[0].querySelector('.text-success')) {
                    const spans = summaryRows[0].querySelectorAll('span');
                    if (spans.length >= 2) {
                        spans[spans.length - 1].textContent = `₺${subtotal.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    }
                }
                
                // KDV (ikinci summary-row)
                if (summaryRows[1] && !summaryRows[1].classList.contains('total') && !summaryRows[1].querySelector('.text-success')) {
                    const spans = summaryRows[1].querySelectorAll('span');
                    if (spans.length >= 2) {
                        spans[spans.length - 1].textContent = `₺${tax.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    }
                }
                
                // Toplam (total class'ı olan summary-row)
                const totalRow = summaryBox.querySelector('.summary-row.total');
                if (totalRow) {
                    const amountSpan = totalRow.querySelector('.amount');
                    if (amountSpan) {
                        amountSpan.textContent = `₺${total.toLocaleString('tr-TR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    }
                }
            }
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
