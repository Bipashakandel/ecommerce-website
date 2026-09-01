<?php
session_start();
include 'config/db_config.php';

if(!isset($_SESSION['order'])) {
    header('Location: checkout.php');
    exit;
}

$order = $_SESSION['order'];
$amount = intval($order['total'] * 100); // Convert to paisa

// eSewa Configuration
define('ESEWA_MERCHANT_CODE', 'EPAYTEST'); // Use your actual merchant code
define('ESEWA_SUCCESS_URL', 'http://localhost/ecommerce-website/esewa_verify.php');
define('ESEWA_FAILURE_URL', 'http://localhost/ecommerce-website/checkout.php');
define('ESEWA_WEBSITE_URL', 'http://localhost/ecommerce-website');

// Generate unique transaction ID
$transaction_id = 'TXN' . time();

// Store transaction ID in session
$_SESSION['transaction_id'] = $transaction_id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSewa Payment - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>eSewa Payment</h1>
        
        <div class="payment-wrapper">
            <div class="payment-section">
                <h2>Complete Your Payment</h2>
                <p>You will be redirected to eSewa to complete the payment securely.</p>
                
                <div class="payment-details">
                    <div class="detail-row">
                        <span>Transaction ID:</span>
                        <span><?php echo htmlspecialchars($transaction_id); ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Amount:</span>
                        <span>Rs. <?php echo number_format($order['total'], 2); ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Customer Email:</span>
                        <span><?php echo htmlspecialchars($order['email']); ?></span>
                    </div>
                </div>

                <form id="esewaForm" action="https://uat.esewa.com.np/epay/main" method="POST">
                    <input type="hidden" id="tamt" name="tamt" value="<?php echo $order['total']; ?>">
                    <input type="hidden" id="amt" name="amt" value="<?php echo $order['total']; ?>">
                    <input type="hidden" id="txAmt" name="txAmt" value="0">
                    <input type="hidden" id="psc" name="psc" value="0">
                    <input type="hidden" id="scd" name="scd" value="<?php echo ESEWA_MERCHANT_CODE; ?>">
                    <input type="hidden" id="pid" name="pid" value="<?php echo htmlspecialchars($transaction_id); ?>">
                    <input type="hidden" id="su" name="su" value="<?php echo ESEWA_SUCCESS_URL; ?>">
                    <input type="hidden" id="fu" name="fu" value="<?php echo ESEWA_FAILURE_URL; ?>">
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-wallet"></i> Pay with eSewa
                    </button>
                </form>

                <a href="checkout.php" class="btn btn-secondary btn-lg" style="margin-top: 1rem;">Back to Checkout</a>
            </div>

            <div class="payment-summary">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Amount:</span>
                    <span>Rs. <?php echo number_format($order['total'], 2); ?></span>
                </div>
                <p class="payment-info" style="margin-top: 2rem; color: #666; font-size: 0.9rem;">
                    <i class="fas fa-lock"></i> Secure payment powered by eSewa
                </p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Auto-submit form after 2 seconds
        setTimeout(function() {
            document.getElementById('esewaForm').submit();
        }, 2000);
    </script>
</body>
</html>