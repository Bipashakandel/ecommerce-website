<?php
session_start();
include 'config/db_config.php';

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle remove item
if(isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    unset($_SESSION['cart'][$remove_id]);
}

// Handle update quantity
if(isset($_POST['update_quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    if($quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

$cart_items = [];
$total = 0;

foreach($_SESSION['cart'] as $product_id => $item) {
    $cart_items[] = $item;
    $total += $item['price'] * $item['quantity'];
}
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
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Shopping Cart</span>
        </div>

        <div class="cart-section">
            <h1>Shopping Cart</h1>

            <?php if(!empty($cart_items)): ?>
                <div class="cart-wrapper">
                    <div class="cart-items-section">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cart_items as $item): ?>
                                    <tr class="cart-item">
                                        <td class="product-name">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                                        </td>
                                        <td class="price">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="quantity">
                                            <form method="POST" class="quantity-form">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit()">
                                                <input type="hidden" name="update_quantity" value="1">
                                            </form>
                                        </td>
                                        <td class="item-total">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td class="action">
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="remove_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn-remove"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span><?php echo $total > 50 ? 'FREE' : '$10.00'; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (10%):</span>
                            <span>$<?php echo number_format($total * 0.1, 2); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>$<?php echo number_format($total + ($total > 50 ? 0 : 10) + ($total * 0.1), 2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                        <a href="shop.php" class="btn btn-secondary btn-block">Continue Shopping</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your Cart is Empty</h2>
                    <p>Add some products to your cart to get started!</p>
                    <a href="shop.php" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>