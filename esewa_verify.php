<?php
session_start();
include 'config/db_config.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';

if($status == 'success' && $order_id > 0) {
    try {
        // Update order payment status
        $stmt = $pdo->prepare('UPDATE orders SET payment_status = "completed", status = "confirmed" WHERE id = ?');
        $stmt->execute([$order_id]);
        
        // Store order ID in session
        $_SESSION['order_id'] = $order_id;
        
        header('Location: order_success.php?order_id=' . $order_id);
    } catch (Exception $e) {
        $error = 'Error processing payment. Please contact support.';
    }
} else {
    header('Location: checkout.php');
}
exit;
?>