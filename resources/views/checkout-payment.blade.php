<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Ödeme - PAULINE">
    <title>Ödeme - PAULINE</title>
    
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    @include('layouts.header')

    <!-- Payment Page -->
    <section class="checkout-page">
        <div class="container">
            <div class="checkout-header">
                <h1 class="page-title">Ödeme</h1>
                <div class="checkout-steps">
                    <div class="step active">
                        <span class="step-number">1</span>
                        <span class="step-label">Sepet</span>
                    </div>
                    <div class="step active">
                        <span class="step-number">2</span>
                        <span class="step-label">Bilgiler</span>
                    </div>
                    <div class="step active">
                        <span class="step-number">3</span>
                        <span class="step-label">Ödeme</span>
                    </div>
                    <div class="step">
                        <span class="step-number">4</span>
                        <span class="step-label">Onay</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sol Taraf - Kredi Kartı Formu -->
                <div class="col-lg-8">
                    <div class="payment-section">
                        <div class="payment-header">
                            <h3 class="section-title">
                                <i class="bi bi-credit-card-2-front"></i>
                                Kredi Kartı Bilgileri
                            </h3>
                        </div>
                        
                        <!-- Kart Önizleme -->
                        <div class="card-preview" id="cardPreview">
                            <div class="card-front">
                                <div class="card-chip">
                                    <i class="bi bi-cpu"></i>
                                </div>
                                <div class="card-number-display" id="cardNumberDisplay">
                                    <span class="card-digit">####</span>
                                    <span class="card-digit">####</span>
                                    <span class="card-digit">####</span>
                                    <span class="card-digit" id="lastFour">####</span>
                                </div>
                                <div class="card-info-row">
                                    <div class="card-name-display" id="cardNameDisplay">AD SOYAD</div>
                                    <div class="card-expiry-display" id="cardExpiryDisplay">MM/YY</div>
                                </div>
                            </div>
                        </div>
                        
                        <form id="paymentForm" class="payment-form">
                            <div class="form-group">
                                <label for="card_number" class="form-label">
                                    <i class="bi bi-credit-card"></i>
                                    Kart Numarası <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-control card-input" id="card_number" name="card_number" 
                                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                                    <i class="bi bi-credit-card-2-front input-icon"></i>
                                </div>
                                <small class="form-hint">16 haneli kart numaranızı giriniz</small>
                            </div>

                            <div class="form-group">
                                <label for="card_name" class="form-label">
                                    <i class="bi bi-person-badge"></i>
                                    Kart Üzerindeki İsim <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" class="form-control card-input" id="card_name" name="card_name" 
                                           placeholder="AD SOYAD" required>
                                    <i class="bi bi-person input-icon"></i>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group form-group-half">
                                    <label class="form-label">
                                        <i class="bi bi-calendar3"></i>
                                        Son Kullanma Tarihi <span class="required">*</span>
                                    </label>
                                    <div class="expiry-inputs">
                                        <select class="form-control card-input" id="card_expiry_month" name="card_expiry_month" required>
                                            <option value="">Ay</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                            @endfor
                                        </select>
                                        <span class="expiry-separator">/</span>
                                        <select class="form-control card-input" id="card_expiry_year" name="card_expiry_year" required>
                                            <option value="">Yıl</option>
                                            @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                                <option value="{{ $i }}">{{ substr($i, -2) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-group-half">
                                    <label for="card_cvv" class="form-label">
                                        <i class="bi bi-lock"></i>
                                        CVV <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" class="form-control card-input" id="card_cvv" name="card_cvv" 
                                               placeholder="123" maxlength="4" required>
                                        <i class="bi bi-shield-lock input-icon"></i>
                                    </div>
                                    <small class="form-hint">Kartınızın arkasındaki 3-4 haneli kod</small>
                                </div>
                            </div>

                            <div class="security-badge">
                                <div class="security-content">
                                    <i class="bi bi-shield-check-fill"></i>
                                    <div>
                                        <strong>Güvenli Ödeme</strong>
                                        <span>Tüm bilgileriniz SSL ile şifrelenmektedir</span>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-actions">
                                <button type="submit" class="btn-payment-submit" id="submitPaymentBtn">
                                    <i class="bi bi-lock-fill"></i>
                                    <span>Ödemeyi Tamamla</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sağ Taraf - Sipariş Özeti -->
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h3 class="summary-title">Sipariş Özeti</h3>
                        
                        <div class="order-items">
                            @foreach($checkoutData['cart'] as $id => $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">{{ $item['name'] }}</div>
                                        <div class="item-options">
                                            <span>{{ strtoupper($item['size']) }}</span>
                                            <span>{{ $item['flavor_name'] ?? $item['flavor'] }}</span>
                                        </div>
                                        <div class="item-quantity-price">
                                            <span class="quantity">Adet: {{ $item['quantity'] }}</span>
                                            <span class="price">₺{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Ara Toplam:</span>
                                <span>₺{{ number_format($checkoutData['subtotal'], 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>KDV (18%):</span>
                                <span>₺{{ number_format($checkoutData['tax'], 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Kargo:</span>
                                <span class="text-success">Ücretsiz</span>
                            </div>
                            <div class="total-row total">
                                <span>Toplam:</span>
                                <span class="amount">₺{{ number_format($checkoutData['total'], 2) }}</span>
                            </div>
                        </div>

                        <div class="customer-info-box">
                            <div class="info-box-header">
                                <i class="bi bi-geo-alt-fill"></i>
                                <h4>Teslimat Adresi</h4>
                            </div>
                            <div class="info-box-content">
                                <div class="address-line"><strong>{{ $checkoutData['first_name'] }} {{ $checkoutData['last_name'] }}</strong></div>
                                <div class="address-line">{{ $checkoutData['address'] }}</div>
                                <div class="address-line">{{ $checkoutData['district'] }}, {{ $checkoutData['city'] }}</div>
                                <div class="address-line postal">Posta Kodu: {{ $checkoutData['postal_code'] }}</div>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn-edit-info">
                            <i class="bi bi-arrow-left"></i>
                            <span>Bilgileri Düzenle</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        (function() {
            'use strict';
            
            // Kart numarası formatı ve önizleme
            const cardNumberInput = document.getElementById('card_number');
            const cardNameInput = document.getElementById('card_name');
            const cardExpiryMonth = document.getElementById('card_expiry_month');
            const cardExpiryYear = document.getElementById('card_expiry_year');
            
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                    if (formattedValue.length > 19) {
                        formattedValue = formattedValue.substring(0, 19);
                    }
                    e.target.value = formattedValue;
                    
                    // Kart önizlemesini güncelle
                    updateCardPreview();
                });
            }

            if (cardNameInput) {
                cardNameInput.addEventListener('input', function() {
                    updateCardPreview();
                });
            }

            if (cardExpiryMonth && cardExpiryYear) {
                cardExpiryMonth.addEventListener('change', updateCardPreview);
                cardExpiryYear.addEventListener('change', updateCardPreview);
            }

            function updateCardPreview() {
                // Kart numarası
                const cardNumber = cardNumberInput.value.replace(/\s/g, '');
                const lastFour = cardNumber.slice(-4) || '####';
                document.getElementById('lastFour').textContent = lastFour;
                
                // Kart ismi
                const cardName = cardNameInput.value.toUpperCase() || 'AD SOYAD';
                document.getElementById('cardNameDisplay').textContent = cardName;
                
                // Son kullanma tarihi
                const month = cardExpiryMonth.value || 'MM';
                const year = cardExpiryYear.value ? cardExpiryYear.value.slice(-2) : 'YY';
                document.getElementById('cardExpiryDisplay').textContent = month + '/' + year;
            }

            // CVV sadece rakam
            const cvvInput = document.getElementById('card_cvv');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }

            // Form gönderimi
            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('submitPaymentBtn');
                    if (!submitBtn) {
                        return;
                    }
                    
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> İşleniyor...';
                    
                    // Kart numarasını temizle (boşlukları kaldır)
                    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
                    document.getElementById('card_number').value = cardNumber;
                    
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);
                    
                    try {
                        const response = await fetch('/checkout/payment/process', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            if (typeof window.showToast === 'function') {
                                window.showToast('Başarılı!', result.message, 'success');
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: result.message
                                });
                            }
                            
                            setTimeout(() => {
                                window.location.href = result.redirect;
                            }, 1000);
                        } else {
                            if (typeof window.showToast === 'function') {
                                window.showToast('Hata!', result.message || 'Bir hata oluştu!', 'error');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: result.message || 'Bir hata oluştu!'
                                });
                            }
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (typeof window.showToast === 'function') {
                            window.showToast('Hata!', 'Bir hata oluştu!', 'error');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Bir hata oluştu!'
                            });
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        })();
    </script>
</body>
</html>
