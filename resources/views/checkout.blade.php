<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Ödeme - PAULINE">
    <title>Ödeme - PAULINE</title>
    
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.header')

    <!-- Checkout Page -->
    <section class="checkout-page">
        <div class="container">
            <div class="checkout-header">
                <h1 class="page-title">Sipariş Bilgileri</h1>
                <div class="checkout-steps">
                    <div class="step active">
                        <span class="step-number">1</span>
                        <span class="step-label">Sepet</span>
                    </div>
                    <div class="step active">
                        <span class="step-number">2</span>
                        <span class="step-label">Bilgiler</span>
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span class="step-label">Ödeme</span>
                    </div>
                    <div class="step">
                        <span class="step-number">4</span>
                        <span class="step-label">Onay</span>
                    </div>
                </div>
            </div>

            @if(!Auth::check())
            <!-- Üye Olmayan Kullanıcılar İçin Bilgilendirme -->
            <div class="alert alert-info" style="background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <div>
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Üye misiniz?</strong> Giriş yaparak kayıtlı bilgilerinizi kullanabilirsiniz.
                    </div>
                    <div style="margin-top: 0.5rem;">
                        <a href="{{ route('login') }}" class="btn btn-sm" style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; margin-right: 0.5rem;">
                            Giriş Yap
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-sm" style="background: transparent; color: var(--primary-color); padding: 0.5rem 1rem; text-decoration: none; border: 1px solid var(--primary-color); border-radius: 5px;">
                            Üye Ol
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <form id="checkoutForm" class="checkout-form">
                <div class="row">
                    <!-- Sol Taraf - Form -->
                    <div class="col-lg-8">
                        <!-- Müşteri Bilgileri -->
                        <div class="checkout-section">
                            <h3 class="section-title">
                                <i class="bi bi-person me-2"></i>
                                Müşteri Bilgileri
                            </h3>
                            
                            @php
                                $userNameParts = Auth::check() && Auth::user()->name ? explode(' ', Auth::user()->name, 2) : ['', ''];
                                $firstName = $userNameParts[0] ?? '';
                                $lastName = $userNameParts[1] ?? '';
                            @endphp
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Ad <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ $firstName }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Soyad <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ $lastName }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-posta <span class="required">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefon <span class="required">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="05XX XXX XX XX" 
                                           value="{{ Auth::check() ? Auth::user()->phone : '' }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Teslimat Adresi -->
                        <div class="checkout-section">
                            <h3 class="section-title">
                                <i class="bi bi-geo-alt me-2"></i>
                                Teslimat Adresi
                            </h3>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Adres <span class="required">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="3" required>{{ Auth::check() ? Auth::user()->address : '' }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">İl <span class="required">*</span></label>
                                    <select class="form-control" id="city" name="city" required>
                                        <option value="">Seçiniz</option>
                                        <option value="istanbul" {{ Auth::check() && Auth::user()->city == 'istanbul' ? 'selected' : '' }}>İstanbul</option>
                                        <option value="ankara" {{ Auth::check() && Auth::user()->city == 'ankara' ? 'selected' : '' }}>Ankara</option>
                                        <option value="izmir" {{ Auth::check() && Auth::user()->city == 'izmir' ? 'selected' : '' }}>İzmir</option>
                                        <option value="antalya" {{ Auth::check() && Auth::user()->city == 'antalya' ? 'selected' : '' }}>Antalya</option>
                                        <option value="bursa" {{ Auth::check() && Auth::user()->city == 'bursa' ? 'selected' : '' }}>Bursa</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="district" class="form-label">İlçe <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="district" name="district" 
                                           value="{{ Auth::check() ? Auth::user()->district : '' }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label">Posta Kodu <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="10" 
                                           value="{{ Auth::check() ? Auth::user()->postal_code : '' }}" required>
                                </div>
                            </div>
                            
                            @if(Auth::check())
                            <div class="mb-3">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" name="save_address" value="1" checked>
                                    <span>Bu adresi kaydet</span>
                                </label>
                            </div>
                            @endif
                        </div>

                        <!-- Ödeme Yöntemi -->
                        <div class="checkout-section">
                            <h3 class="section-title">
                                <i class="bi bi-credit-card me-2"></i>
                                Ödeme Yöntemi
                            </h3>
                            
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery" checked>
                                    <label for="cash_on_delivery" class="payment-label">
                                        <div class="payment-icon">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        <div class="payment-info">
                                            <div class="payment-name">Kapıda Ödeme</div>
                                            <div class="payment-desc">Teslimat sırasında nakit veya kredi kartı ile ödeme</div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="payment-method">
                                    <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                                    <label for="credit_card" class="payment-label">
                                        <div class="payment-icon">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </div>
                                        <div class="payment-info">
                                            <div class="payment-name">Kredi Kartı</div>
                                            <div class="payment-desc">Güvenli ödeme sayfasına yönlendirileceksiniz</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notlar -->
                        <div class="checkout-section">
                            <h3 class="section-title">
                                <i class="bi bi-chat-left-text me-2"></i>
                                Sipariş Notları (Opsiyonel)
                            </h3>
                            
                            <div class="mb-3">
                                <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Siparişinizle ilgili özel notlarınızı buraya yazabilirsiniz..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sağ Taraf - Sipariş Özeti -->
                    <div class="col-lg-4">
                        @include('partials.order-summary')
                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        (function() {
            'use strict';
            
            // Güncelleme devam ediyor mu kontrolü
            let isUpdating = false;
            let intervalId = null;
            let lastCartUpdate = localStorage.getItem('cart_updated') || '0';
            
            // Sepet özetini güncelle
            function updateOrderSummary() {
                // Eğer zaten bir güncelleme devam ediyorsa, yeni güncelleme başlatma
                if (isUpdating) {
                    return;
                }
                
                isUpdating = true;
                
                fetch('/checkout', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.html) {
                            const orderSummaryElement = document.querySelector('.order-summary');
                            if (orderSummaryElement) {
                                // Sadece içeriği güncelle, script'leri çalıştırma
                                orderSummaryElement.innerHTML = data.html;
                            }
                        } else {
                            // Sepet boşsa sepet sayfasına yönlendir
                            window.location.href = '/cart';
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order summary:', error);
                    })
                    .finally(() => {
                        isUpdating = false;
                    });
            }
            
            // Storage event listener - başka sekmede sepet değişikliği olduğunda
            window.addEventListener('storage', function(e) {
                if (e.key === 'cart_updated' && !isUpdating) {
                    updateOrderSummary();
                }
            });
            
            // Interval'ı başlat
            function startCartUpdateListener() {
                if (intervalId) {
                    return; // Zaten çalışıyor
                }
                
                intervalId = setInterval(function() {
                    // Eğer güncelleme devam ediyorsa, bu iterasyonu atla
                    if (isUpdating) {
                        return;
                    }
                    
                    const currentCartUpdate = localStorage.getItem('cart_updated') || '0';
                    if (currentCartUpdate !== lastCartUpdate) {
                        lastCartUpdate = currentCartUpdate;
                        updateOrderSummary();
                    }
                }, 2000); // 2000ms'de bir kontrol et (daha az sıklıkla)
            }
            
            // Form gönderimi - sadece bir kez bağla
            function initCheckoutForm() {
                const checkoutForm = document.getElementById('checkoutForm');
                if (!checkoutForm || checkoutForm.dataset.listenerAttached === 'true') {
                    return;
                }
                
                checkoutForm.dataset.listenerAttached = 'true';
                checkoutForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('submitOrderBtn');
                    if (!submitBtn) {
                        console.error('Submit button not found');
                        return;
                    }
                    
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> İşleniyor...';
                    
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);
                    
                    try {
                        const response = await fetch('/checkout/process', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Interval'ı temizle
                            if (intervalId) {
                                clearInterval(intervalId);
                                intervalId = null;
                            }
                            
                            // showToast kullan (app.js'den gelecek)
                            if (typeof window.showToast === 'function') {
                                window.showToast('Başarılı!', result.message, 'success');
                            } else {
                                alert('Başarılı! ' + result.message);
                            }
                            
                            setTimeout(() => {
                                window.location.href = result.redirect;
                            }, 1000);
                        } else {
                            if (typeof window.showToast === 'function') {
                                window.showToast('Hata!', result.message || 'Bir hata oluştu!', 'error');
                            } else {
                                alert('Hata! ' + (result.message || 'Bir hata oluştu!'));
                            }
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (typeof window.showToast === 'function') {
                            window.showToast('Hata!', 'Bir hata oluştu!', 'error');
                        } else {
                            alert('Bir hata oluştu!');
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
            
            // Telefon numarası formatı - sadece bir kez bağla
            function initPhoneInput() {
                const phoneInput = document.getElementById('phone');
                if (!phoneInput || phoneInput.dataset.listenerAttached === 'true') {
                    return;
                }
                
                phoneInput.dataset.listenerAttached = 'true';
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0 && value[0] !== '0') {
                        value = '0' + value;
                    }
                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }
                    e.target.value = value;
                });
            }
            
            // Sayfa yüklendiğinde başlat
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    startCartUpdateListener();
                    initCheckoutForm();
                    initPhoneInput();
                });
            } else {
                // DOMContentLoaded zaten geçmiş
                startCartUpdateListener();
                initCheckoutForm();
                initPhoneInput();
            }
        })();
    </script>
</body>
</html>
