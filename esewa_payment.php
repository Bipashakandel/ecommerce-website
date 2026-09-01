<?php
session_start();
include 'config/db_config.php';

if(!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = intval($_GET['order_id']);

// Generate eSewa payment form parameters
$amount = isset($_GET['amount']) ? intval($_GET['amount']) : 0;
$tax_amount = 0;
$total_amount = $amount + $tax_amount;
$transaction_uuid = uniqid();
$product_code = 'SHOPHUB';
$success_url = 'https://' . $_SERVER['HTTP_HOST'] . '/ecommerce-website/esewa_verify.php';
$failure_url = 'https://' . $_SERVER['HTTP_HOST'] . '/ecommerce-website/checkout.php';
$product_service_charge = 0;
$product_delivery_charge = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSewa Payment - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="checkout-form" style="max-width: 600px; margin: 3rem auto;">
            <h2>eSewa Payment</h2>
            <p>Order ID: #<?php echo $order_id; ?></p>
            <p>Amount: Rs. <?php echo $total_amount; ?></p>
            
            <form id="esewa-payment-form" action="https://uat.esewa.com.np/epay/main" method="POST">
                <input type="hidden" name="amount" value="<?php echo $total_amount; ?>" />
                <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>" />
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>" />
                <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" />
                <input type="hidden" name="product_code" value="<?php echo $product_code; ?>" />
                <input type="hidden" name="product_service_charge" value="<?php echo $product_service_charge; ?>" />
                <input type="hidden" name="product_delivery_charge" value="<?php echo $product_delivery_charge; ?>" />
                <input type="hidden" name="success_url" value="<?php echo $success_url; ?>?order_id=<?php echo $order_id; ?>" />
                <input type="hidden" name="failure_url" value="<?php echo $failure_url; ?>" />
                <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code" />
                <input type="hidden" name="signature" value="" />
                
                <button type="submit" class="btn btn-primary btn-block">Pay with eSewa</button>
            </form>

            <a href="checkout.php" class="btn btn-secondary btn-block" style="margin-top: 1rem;">Cancel Payment</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script>
        // Auto-submit the form
        window.onload = function() {
            document.getElementById('esewa-payment-form').submit();
        };
    </script>
</body>
</html>