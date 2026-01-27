import './bootstrap';
import 'bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import { Modal, Toast } from 'bootstrap';

// Product data
const products = {
  1: {
    id: 1,
    name: 'Şık Çanta Pasta',
    description: 'El yapımı fondant detaylar ile özenle hazırlanmış özel tasarım pasta. Çikolata ganaj ve kadife kaplama ile.',
    basePrice: 850,
    image: 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
      'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80',
      'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 850, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 1010, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'nutella', name: 'Nutella', image: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80' },
      { id: 'tropical', name: 'Tropical', image: 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80' },
      { id: 'pistachio', name: 'Pistachio Raspberry', image: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80' },
      { id: 'chocolate', name: 'Chocolate Delight', image: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80' }
    ]
  },
  2: {
    id: 2,
    name: 'Altın Detaylı Pasta',
    description: 'Çikolata ganaj ve altın varak ile süslenmiş lüks pasta.',
    basePrice: 950,
    image: 'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=600&q=80',
      'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 950, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 1110, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'nutella', name: 'Nutella', image: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80' },
      { id: 'tropical', name: 'Tropical', image: 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&q=80' },
      { id: 'pistachio', name: 'Pistachio Raspberry', image: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80' }
    ]
  },
  3: {
    id: 3,
    name: 'Red Velvet Pasta',
    description: 'Klasik kırmızı kadife lezzeti, cream cheese frosting ile.',
    basePrice: 750,
    image: 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 750, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 910, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'classic', name: 'Classic Red Velvet', image: 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=100&q=80' },
      { id: 'chocolate', name: 'Chocolate Velvet', image: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80' }
    ]
  },
  4: {
    id: 4,
    name: 'Mavi Rüya Pasta',
    description: 'Vanilya ve yaban mersini ile hafif ve lezzetli.',
    basePrice: 800,
    image: 'https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 800, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 960, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'blueberry', name: 'Blueberry Vanilla', image: 'https://images.unsplash.com/photo-1535254973040-607b474cb50d?w=100&q=80' },
      { id: 'lemon', name: 'Lemon Blueberry', image: 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=100&q=80' }
    ]
  },
  5: {
    id: 5,
    name: 'Çikolata Delisi',
    description: 'Üç kat çikolata lezzeti - bitter, sütlü ve beyaz çikolata.',
    basePrice: 700,
    image: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 700, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 860, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'triple', name: 'Triple Chocolate', image: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=100&q=80' },
      { id: 'dark', name: 'Dark Chocolate', image: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&q=80' }
    ]
  },
  6: {
    id: 6,
    name: 'Meyveli Şölen',
    description: 'Taze meyveler ve hafif krema ile sağlıklı lezzet.',
    basePrice: 650,
    image: 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=600&q=80',
    images: [
      'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=600&q=80'
    ],
    sizes: [
      { id: 's', name: 'S size (1.5 kg - 5-6 kişilik)', price: 650, description: '5-6 kişilik' },
      { id: 'm', name: 'M size (2.5 kg - 9-10 kişilik)', price: 810, description: '9-10 kişilik' }
    ],
    flavors: [
      { id: 'mixed', name: 'Mixed Fruits', image: 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=100&q=80' },
      { id: 'berry', name: 'Berry Mix', image: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=100&q=80' }
    ]
  }
};

// Cart state
let cart = [];
let currentProduct = null;
let selectedSize = null;
let selectedFlavor = null;
let quantity = 1;

document.addEventListener('DOMContentLoaded', function () {
  // Initialize Swiper for Top Picks
  const topPicksSwiper = new Swiper('.top-picks-swiper', {
    modules: [Navigation, Pagination, Autoplay],
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 30,
      },
    },
  });

  // Navbar scroll effect
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', function () {
    if (window.scrollY > 50) {
      navbar.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
    } else {
      navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
    }
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href !== '#productModal' && href !== '#cartModal') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });

  // Product card click handlers
  document.querySelectorAll('.product-card').forEach((card, index) => {
    card.addEventListener('click', function (e) {
      if (!e.target.classList.contains('btn-add-cart')) {
        openProductModal(index + 1);
      }
    });
  });

  // Load cart from session
  loadCart();
  updateCartBadge();
});

// Open product modal
function openProductModal(productId) {
  // Use database products if available, otherwise fallback to static data
  const dbProduct = window.productsData && window.productsData[productId];
  
  if (dbProduct) {
    currentProduct = {
      id: dbProduct.id,
      name: dbProduct.name,
      description: dbProduct.description || '',
      basePrice: dbProduct.basePrice,
      image: dbProduct.image || '',
      images: dbProduct.images && dbProduct.images.length > 0 ? dbProduct.images : (dbProduct.image ? [dbProduct.image] : []),
      sizes: dbProduct.sizes || [],
      flavors: dbProduct.flavors || [],
    };
  } else {
    currentProduct = products[productId];
  }
  
  if (!currentProduct) {
    alert('Ürün bulunamadı!');
    return;
  }

  selectedSize = null;
  selectedFlavor = null;
  quantity = 1;

  const modalHtml = `
        <div class="modal fade product-modal" id="productModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="product-gallery">
                                    <img src="${currentProduct.image}" alt="${currentProduct.name}" class="main-image" id="mainImage">
                                    <div class="thumbnail-images">
                                        ${currentProduct.images.map((img, idx) => `
                                            <img src="${img}" alt="Thumbnail ${idx + 1}" class="${idx === 0 ? 'active' : ''}" onclick="changeMainImage('${img}', this)">
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="product-details">
                                    <h2>${currentProduct.name}</h2>
                                    <p class="product-description">${currentProduct.description}</p>

                                    <div class="option-group">
                                        <label>Size <span class="required">*</span></label>
                                        <div class="size-options">
                                            ${currentProduct.sizes.map(size => `
                                                <div class="option-item" onclick="selectSize('${size.id}', ${size.price})">
                                                    <input type="radio" name="size" value="${size.id}">
                                                    <div class="option-info">
                                                        <div class="option-name">${size.name}</div>
                                                        <div class="option-price">+₺${size.price}</div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>

                                    <div class="option-group">
                                        <label>Flavor <span class="required">*</span></label>
                                        <div class="flavor-options">
                                            ${currentProduct.flavors.map(flavor => `
                                                <div class="option-item" onclick="selectFlavor('${flavor.id}', '${flavor.name}')">
                                                    <input type="radio" name="flavor" value="${flavor.id}">
                                                    <img src="${flavor.image}" alt="${flavor.name}" class="option-image">
                                                    <div class="option-info">
                                                        <div class="option-name">${flavor.name}</div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>

                                    <div class="quantity-selector">
                                        <label>Miktar:</label>
                                        <div class="quantity-controls">
                                            <button onclick="decreaseQuantity()">-</button>
                                            <input type="number" value="1" id="quantityInput" min="1" readonly>
                                            <button onclick="increaseQuantity()">+</button>
                                        </div>
                                    </div>

                                    <div class="price-row">
                                        <span class="total-label">Toplam:</span>
                                        <span class="total-price" id="totalPrice">₺${currentProduct.basePrice}</span>
                                    </div>

                                    <button class="btn btn-add-to-cart" id="addToCartBtn" onclick="addToCart()" disabled>
                                        Sepete Ekle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

  // Remove existing modal if any
  const existingModal = document.getElementById('productModal');
  if (existingModal) {
    existingModal.remove();
  }

  // Add modal to body
  document.body.insertAdjacentHTML('beforeend', modalHtml);

  // Show modal
  const modal = new Modal(document.getElementById('productModal'));
  modal.show();
}

// Change main image
window.changeMainImage = function (src, element) {
  document.getElementById('mainImage').src = src;
  document.querySelectorAll('.thumbnail-images img').forEach(img => img.classList.remove('active'));
  element.classList.add('active');
};

// Select size
window.selectSize = function (sizeId, price) {
  selectedSize = { id: sizeId, price: price };
  document.querySelectorAll('.size-options .option-item').forEach(item => item.classList.remove('selected'));
  event.currentTarget.classList.add('selected');
  event.currentTarget.querySelector('input').checked = true;
  updatePrice();
  checkFormValidity();
};

// Select flavor
window.selectFlavor = function (flavorId, flavorName) {
  selectedFlavor = { id: flavorId, name: flavorName };
  document.querySelectorAll('.flavor-options .option-item').forEach(item => item.classList.remove('selected'));
  event.currentTarget.classList.add('selected');
  event.currentTarget.querySelector('input').checked = true;
  checkFormValidity();
};

// Quantity controls
window.increaseQuantity = function () {
  quantity++;
  document.getElementById('quantityInput').value = quantity;
  updatePrice();
};

window.decreaseQuantity = function () {
  if (quantity > 1) {
    quantity--;
    document.getElementById('quantityInput').value = quantity;
    updatePrice();
  }
};

// Update price
function updatePrice() {
  if (selectedSize) {
    const total = selectedSize.price * quantity;
    document.getElementById('totalPrice').textContent = `₺${total}`;
  }
}

// Check form validity
function checkFormValidity() {
  const addToCartBtn = document.getElementById('addToCartBtn');
  if (selectedSize && selectedFlavor) {
    addToCartBtn.disabled = false;
  } else {
    addToCartBtn.disabled = true;
  }
}

// Add to cart
window.addToCart = async function () {
  if (!selectedSize || !selectedFlavor) {
    alert('Lütfen boy ve lezzet seçiniz!');
    return;
  }

  const cartItem = {
    product_id: currentProduct.id,
    name: currentProduct.name,
    price: selectedSize.price,
    size: selectedSize.id,
    flavor: selectedFlavor.id,
    flavor_name: selectedFlavor.name,
    quantity: quantity,
    image: currentProduct.image
  };

  try {
    const response = await fetch('/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(cartItem)
    });

    const data = await response.json();

    if (data.success) {
      showToast('Başarılı!', 'Ürün sepete eklendi!', 'success');
      loadCart();
      updateCartBadge();

      // Close modal
      const modal = Modal.getInstance(document.getElementById('productModal'));
      modal.hide();
    } else {
      showToast('Hata!', data.message || 'Bir hata oluştu!', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showToast('Hata!', 'Bir hata oluştu!', 'error');
  }
};

// Load cart
async function loadCart() {
  try {
    const response = await fetch('/cart/count');
    const data = await response.json();
    cart = data.cart || [];
  } catch (error) {
    console.error('Error loading cart:', error);
  }
}

// Update cart badge
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

// Open cart modal
function openCartModal() {
  window.location.href = '/cart';
}

// Show toast notification
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
  const toast = new Toast(toastElement, {
    autohide: true,
    delay: 3000
  });
  toast.show();

  toastElement.addEventListener('hidden.bs.toast', function () {
    toastElement.remove();
  });
}
