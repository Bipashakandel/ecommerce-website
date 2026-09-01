<?php
session_start();
include 'config/db_config.php';

if(empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout');
    exit;
}

$cart_total = 0;
foreach($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

$shipping = $cart_total > 50 ? 0 : 10;
$tax = $cart_total * 0.1;
$grand_total = $cart_total + $shipping + $tax;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $zip = htmlspecialchars($_POST['zip']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    // Store order details in session for payment processing
    $_SESSION['order'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'payment_method' => $payment_method,
        'total' => $grand_total
    ];

    if($payment_method == 'esewa') {
        header('Location: esewa_payment.php');
    } else if($payment_method == 'khalti') {
        header('Location: khalti_payment.php');
    } else if($payment_method == 'card') {
        header('Location: payment.php');
    }
    exit;
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
        
        <div class="checkout-wrapper">
            <form method="POST" class="checkout-form">
                <div class="form-section">
                    <h2>Billing Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address *</label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State/Province *</label>
                            <input type="text" id="state" name="state" required>
                        </div>
                        <div class="form-group">
                            <label for="zip">ZIP Code *</label>
                            <input type="text" id="zip" name="zip" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Payment Method</h2>
                    
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="esewa" checked>
                            <div class="payment-label">
                                <i class="fas fa-wallet"></i>
                                <span>eSewa</span>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="khalti">
                            <div class="payment-label">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Khalti</span>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card">
                            <div class="payment-label">
                                <i class="fas fa-credit-card"></i>
                                <span>Credit/Debit Card</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Continue to Payment</button>
            </form>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    <?php foreach($_SESSION['cart'] as $item): ?>
                        <div class="summary-item">
                            <span class="item-name"><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                            <span class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($cart_total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span><?php echo $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (10%):</span>
                    <span>$<?php echo number_format($tax, 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($grand_total, 2); ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>