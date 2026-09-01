<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Shopping Cart</h1>
        
        <div id="emptyCart" style="display: none;">
            <div class="no-products">
                <p>Your cart is empty</p>
                <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>

        <div class="cart-container">
            <div class="cart-items" id="cartItems"></div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">Rs. 0.00</span>
                </div>
                <div class="summary-item">
                    <span>Shipping:</span>
                    <span id="shipping">Rs. 200.00</span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total:</span>
                    <span id="total">Rs. 0.00</span>
                </div>
                <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle"></i> Free shipping on orders above Rs. 2,500
                </p>
                <button class="btn btn-primary btn-block" onclick="proceedToCheckout()">Proceed to Checkout</button>
                <a href="shop.php" class="btn btn-secondary btn-block">Continue Shopping</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>