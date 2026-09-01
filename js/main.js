// Cart Management
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if(cartCount) {
        cartCount.textContent = cart.length;
    }
}

function addToCart(productId, productName, price) {
    const item = {
        id: productId,
        name: productName,
        price: price,
        quantity: 1
    };
    
    const existingItem = cart.find(item => item.id === productId);
    if(existingItem) {
        existingItem.quantity++;
    } else {
        cart.push(item);
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification('Item added to cart!', 'success');
}

function addToCartDetail(productId, productName, price) {
    const quantityInput = document.getElementById('quantity');
    const quantity = parseInt(quantityInput.value);
    
    for(let i = 0; i < quantity; i++) {
        const item = {
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        };
        
        const existingItem = cart.find(item => item.id === productId);
        if(existingItem) {
            existingItem.quantity++;
        } else {
            cart.push(item);
        }
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${quantity} item(s) added to cart!`, 'success');
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    location.reload();
}

function updateCartQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if(item) {
        item.quantity = parseInt(quantity);
        if(item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            localStorage.setItem('cart', JSON.stringify(cart));
            location.reload();
        }
    }
}

function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Wishlist Management
let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

function addToWishlist(productId) {
    if(!wishlist.find(item => item.id === productId)) {
        wishlist.push({id: productId});
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        showNotification('Added to wishlist!', 'success');
    } else {
        showNotification('Already in wishlist!', 'info');
    }
}

function removeFromWishlist(productId) {
    wishlist = wishlist.filter(item => item.id !== productId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    showNotification('Removed from wishlist!', 'success');
}

// Notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Mobile Menu Toggle
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');

if(menuToggle) {
    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
    
    navMenu.addEventListener('click', (e) => {
        if(e.target.tagName === 'A') {
            navMenu.classList.remove('active');
        }
    });
}

// Cart Page Functions
function displayCart() {
    const cartContainer = document.getElementById('cartItems');
    const emptyCartMessage = document.getElementById('emptyCart');
    const cartSummary = document.querySelector('.cart-summary');
    
    if(!cartContainer) return;
    
    cartContainer.innerHTML = '';
    
    if(cart.length === 0) {
        emptyCartMessage.style.display = 'block';
        if(cartSummary) cartSummary.style.display = 'none';
        return;
    }
    
    emptyCartMessage.style.display = 'none';
    if(cartSummary) cartSummary.style.display = 'block';
    
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <img src="https://via.placeholder.com/100" class="cart-item-image" alt="${item.name}">
            <div class="cart-item-details">
                <h3>${item.name}</h3>
                <p>Rs. ${item.price.toFixed(2)}</p>
            </div>
            <div class="cart-item-quantity">
                <input type="number" value="${item.quantity}" min="1" onchange="updateCartQuantity(${item.id}, this.value)">
            </div>
            <div class="cart-item-total">
                Rs. ${(item.price * item.quantity).toFixed(2)}
            </div>
            <button class="btn-remove" onclick="removeFromCart(${item.id})">Remove</button>
        `;
        cartContainer.appendChild(cartItem);
    });
    
    updateCartSummary();
}

function updateCartSummary() {
    const subtotal = getCartTotal();
    const shipping = subtotal > 2500 ? 0 : 200;
    const total = subtotal + shipping;
    
    const subtotalEl = document.getElementById('subtotal');
    const shippingEl = document.getElementById('shipping');
    const totalEl = document.getElementById('total');
    
    if(subtotalEl) subtotalEl.textContent = 'Rs. ' + subtotal.toFixed(2);
    if(shippingEl) shippingEl.textContent = shipping === 0 ? 'Free' : 'Rs. ' + shipping.toFixed(2);
    if(totalEl) totalEl.textContent = 'Rs. ' + total.toFixed(2);
}

// Checkout Functions
function proceedToCheckout() {
    if(cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    window.location.href = 'checkout.php';
}

function submitCheckout() {
    const form = document.getElementById('checkoutForm');
    if(form.checkValidity()) {
        form.submit();
    } else {
        showNotification('Please fill all required fields!', 'error');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    displayCart();
    updateCartSummary();
});

// Form Validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePhone(phone) {
    const phoneRegex = /^[0-9\-\+\(\)\s]{10,}$/;
    return phoneRegex.test(phone);
}

// Password Strength Checker
function checkPasswordStrength(password) {
    let strength = 0;
    if(password.length >= 8) strength++;
    if(/[A-Z]/.test(password)) strength++;
    if(/[a-z]/.test(password)) strength++;
    if(/[0-9]/.test(password)) strength++;
    if(/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search Products
const searchInput = document.querySelector('input[name="search"]');
if(searchInput) {
    searchInput.addEventListener('input', debounce(() => {
        // Auto-search functionality can be added here
    }, 500));
}

console.log('ShopHub - JavaScript loaded successfully');