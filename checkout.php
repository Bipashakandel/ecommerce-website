<?php
session_start();
include 'config/db_config.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout');
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shipping_address = htmlspecialchars($_POST['shipping_address']);
    $city = htmlspecialchars($_POST['city']);
    $phone = htmlspecialchars($_POST['phone']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    
    // Get cart from request (passed via hidden form field)
    $cart_data = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
    
    if(empty($cart_data)) {
        $error = 'Cart is empty';
    } else if(empty($shipping_address) || empty($city) || empty($phone)) {
        $error = 'Please fill all required fields';
    } else {
        try {
            // Create order
            $full_address = $shipping_address . ', ' . $city;
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status, payment_status) VALUES (?, ?, ?, ?, "pending", "pending")');
            $stmt->execute([$_SESSION['user_id'], $total_amount, $full_address, $payment_method]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items
            foreach($cart_data as $item) {
                $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
                $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }
            
            // Redirect to payment
            if($payment_method == 'esewa') {
                header('Location: esewa_payment.php?order_id=' . $order_id);
            } else {
                header('Location: order_success.php?order_id=' . $order_id);
            }
            exit;
        } catch (Exception $e) {
            $error = 'Error creating order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Checkout</h1>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="checkout-form">
                <form id="checkoutForm" method="POST">
                    <h2>Shipping Information</h2>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>

                    <h2 style="margin-top: 2rem;">Payment Method</h2>
                    
                    <div class="form-group">
                        <label>
                            <input type="radio" name="payment_method" value="esewa" checked>
                            eSewa
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="radio" name="payment_method" value="cash_on_delivery">
                            Cash on Delivery
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="radio" name="payment_method" value="bank_transfer">
                            Bank Transfer
                        </label>
                    </div>

                    <input type="hidden" id="cart_data" name="cart_data" value="">
                    <input type="hidden" id="total_amount" name="total_amount" value="">
                    
                    <button type="button" class="btn btn-primary btn-block" onclick="submitCheckoutForm()">Place Order</button>
                </form>
            </div>

            <div class="checkout-order-summary">
                <h3>Order Summary</h3>
                <div id="checkoutItems"></div>
                <div class="summary-item" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                    <span>Subtotal:</span>
                    <span id="checkoutSubtotal">Rs. 0.00</span>
                </div>
                <div class="summary-item">
                    <span>Shipping:</span>
                    <span id="checkoutShipping">Rs. 200.00</span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total:</span>
                    <span id="checkoutTotal">Rs. 0.00</span>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
    <script>
        function submitCheckoutForm() {
            const form = document.getElementById('checkoutForm');
            document.getElementById('cart_data').value = JSON.stringify(cart);
            
            const subtotal = getCartTotal();
            const shipping = subtotal > 2500 ? 0 : 200;
            document.getElementById('total_amount').value = (subtotal + shipping).toFixed(2);
            
            form.submit();
        }

        function displayCheckoutSummary() {
            const checkoutItems = document.getElementById('checkoutItems');
            checkoutItems.innerHTML = '';
            
            cart.forEach(item => {
                const itemEl = document.createElement('div');
                itemEl.style.marginBottom = '1rem';
                itemEl.innerHTML = `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>${item.name}</span>
                        <span>x${item.quantity}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #666;">
                        <span>Rs. ${item.price.toFixed(2)} each</span>
                        <span>Rs. ${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `;
                checkoutItems.appendChild(itemEl);
            });
            
            const subtotal = getCartTotal();
            const shipping = subtotal > 2500 ? 0 : 200;
            const total = subtotal + shipping;
            
            document.getElementById('checkoutSubtotal').textContent = 'Rs. ' + subtotal.toFixed(2);
            document.getElementById('checkoutShipping').textContent = shipping === 0 ? 'Free' : 'Rs. ' + shipping.toFixed(2);
            document.getElementById('checkoutTotal').textContent = 'Rs. ' + total.toFixed(2);
        }
        
        document.addEventListener('DOMContentLoaded', displayCheckoutSummary);
    </script>
</body>
</html>