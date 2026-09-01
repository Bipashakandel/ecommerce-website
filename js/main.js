// Cart Management
function addToCart(productId, productName, productPrice) {
    const quantity = 1;
    addItemToCart(productId, productName, productPrice, quantity, 'https://via.placeholder.com/150');
    showNotification('Product added to cart!');
}

function addToCartDetail(productId, productName, productPrice) {
    const quantity = parseInt(document.getElementById('quantity').value);
    addItemToCart(productId, productName, productPrice, quantity, 'https://via.placeholder.com/150');
    showNotification(quantity + ' item(s) added to cart!');
}

function addItemToCart(productId, productName, productPrice, quantity, imageUrl) {
    const cartData = {
        action: 'add_to_cart',
        product_id: productId,
        product_name: productName,
        product_price: productPrice,
        quantity: quantity,
        image_url: imageUrl
    };

    // Store in session storage for demo
    let cart = JSON.parse(localStorage.getItem('cart')) || {};
    if(cart[productId]) {
        cart[productId].quantity += quantity;
    } else {
        cart[productId] = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            image: imageUrl
        };
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || {};
    let count = 0;
    for(let key in cart) {
        count += cart[key].quantity;
    }
    const cartCountEl = document.getElementById('cartCount');
    if(cartCountEl) {
        cartCountEl.textContent = count;
    }
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Mobile Menu Toggle
const menuToggle = document.getElementById('menuToggle');
const navMenu = document.getElementById('navMenu');

if(menuToggle) {
    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
}

// Close mobile menu when a link is clicked
if(navMenu) {
    const navLinks = navMenu.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
        });
    });
}

// Format currency
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2).replace(/\B(?=(\\d{3})+(?!\\d))/g, ',');
}

// Payment Form Validation
const paymentForm = document.getElementById('paymentForm');
if(paymentForm) {
    paymentForm.addEventListener('submit', (e) => {
        const cardNumber = document.getElementById('card_number').value;
        const cvv = document.getElementById('cvv').value;
        const expiry = document.getElementById('expiry').value;
        
        if(cardNumber.length !== 16) {
            alert('Please enter a valid 16-digit card number');
            e.preventDefault();
            return false;
        }
        
        if(cvv.length !== 3) {
            alert('Please enter a valid 3-digit CVV');
            e.preventDefault();
            return false;
        }
        
        if(!expiry.match(/\\d{2}\\/\\d{2}/)) {
            alert('Please enter expiry date in MM/YY format');
            e.preventDefault();
            return false;
        }
    });
}

// Quantity Input Restrictions
const quantityInputs = document.querySelectorAll('input[name="quantity"]');
quantityInputs.forEach(input => {
    input.addEventListener('change', () => {
        if(input.value < 1) {
            input.value = 1;
        }
    });
});

// Smooth Scrolling
const links = document.querySelectorAll('a[href^="#"]');
links.forEach(link => {
    link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');
        if(href === '#') return;
        
        const target = document.querySelector(href);
        if(target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Add to wishlist (placeholder)
function addToWishlist(productId) {
    showNotification('Added to wishlist!');
}

// Search functionality
const searchForm = document.querySelector('.search-form');
if(searchForm) {
    searchForm.addEventListener('submit', (e) => {
        // Form will submit naturally
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
});

// Add CSS for notifications
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #27AE60;
        color: white;
        padding: 1rem 2rem;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 1000;
        max-width: 300px;
    }
    
    .notification.show {
        opacity: 1;
    }
`;
document.head.appendChild(style);