<?php
session_start();
include 'config/db_config.php';

if(!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = intval($_GET['order_id']);

try {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$order) {
        header('Location: index.php');
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="success-section">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase. Your order has been successfully placed.</p>
            
            <div class="order-details">
                <h2>Order Details</h2>
                <div class="detail-row">
                    <span>Order ID:</span>
                    <span>#<?php echo $order['id']; ?></span>
                </div>
                <div class="detail-row">
                    <span>Date:</span>
                    <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="detail-row">
                    <span>Total Amount:</span>
                    <span>Rs. <?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <span class="status"><?php echo ucfirst($order['status']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Shipping Address:</span>
                    <span><?php echo htmlspecialchars($order['shipping_address']); ?></span>
                </div>
            </div>

            <div class="order-items">
                <h2>Items Ordered</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                                <td>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                <a href="#" class="btn btn-secondary">Download Invoice</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>