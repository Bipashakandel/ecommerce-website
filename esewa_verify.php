<?php
session_start();
include 'config/db_config.php';

if(!isset($_SESSION['transaction_id'])) {
    header('Location: checkout.php');
    exit;
}

$transaction_id = $_SESSION['transaction_id'];
$order = $_SESSION['order'];

// Verify eSewa payment
$response_code = isset($_GET['oid']) ? $_GET['oid'] : '';
$ref_id = isset($_GET['refId']) ? $_GET['refId'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if($status == 'Complete') {
    try {
        // Verify transaction with eSewa
        $verify_url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'oid' => $response_code,
            'amt' => $order['total'],
            'refId' => $ref_id,
            'scd' => 'EPAYTEST'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verify_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Process the order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, status, payment_status, created_at) VALUES (?, ?, ?, 'pending', 'completed', NOW())");
        $stmt->execute([
            $_SESSION['user_id'],
            $order['total'],
            $order['address'] . ', ' . $order['city'] . ', ' . $order['state'] . ' ' . $order['zip']
        ]);
        
        $order_id = $pdo->lastInsertId();
        
        // Add order items
        foreach($_SESSION['cart'] as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }
        
        // Clear cart and order session
        $_SESSION['cart'] = [];
        unset($_SESSION['order']);
        unset($_SESSION['transaction_id']);
        
        $_SESSION['success'] = "Payment successful! Order #" . $order_id . " has been placed.";
        header('Location: order_success.php?order_id=' . $order_id);
        exit;
        
    } catch (Exception $e) {
        $error = "Payment verification failed: " . $e->getMessage();
    }
} else {
    $error = "Payment was not completed. Please try again.";
    header('Location: checkout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="success-section">
            <?php if(isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <a href="checkout.php" class="btn btn-primary">Back to Checkout</a>
            <?php else: ?>
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Verifying Payment...</h1>
                <p>Please wait while we verify your payment.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>