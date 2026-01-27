<div class="order-summary">
    <h3 class="summary-title">Sipariş Özeti</h3>
    
    <div class="order-items">
        @foreach($cart as $id => $item)
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
            <span>₺{{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="total-row">
            <span>KDV (18%):</span>
            <span>₺{{ number_format($tax, 2) }}</span>
        </div>
        <div class="total-row">
            <span>Kargo:</span>
            <span class="text-success">Ücretsiz</span>
        </div>
        <div class="total-row total">
            <span>Toplam:</span>
            <span class="amount">₺{{ number_format($total, 2) }}</span>
        </div>
    </div>

    <button type="submit" class="btn btn-submit-order" id="submitOrderBtn">
        <i class="bi bi-lock-fill me-2"></i>
        Siparişi Tamamla
    </button>

    <div class="security-info">
        <i class="bi bi-shield-check"></i>
        <span>Güvenli ödeme garantisi</span>
    </div>

    <a href="{{ route('cart.index') }}" class="btn btn-back-cart">
        <i class="bi bi-arrow-left me-2"></i>
        Sepete Dön
    </a>
</div>
