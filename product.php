<?php
session_start();
include 'config/db_config.php';

if(!isset($_GET['id'])) {
    header('Location: shop.php');
    exit;
}

$product_id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$product) {
        header('Location: shop.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: shop.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <a href="shop.php">Shop</a> / <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="product-detail">
            <div class="product-image-section">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <div class="product-detail-section">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span>(127 reviews)</span>
                </div>

                <div class="price-section">
                    <div class="price">Rs. <?php echo number_format($product['price'], 2); ?></div>
                    <div class="stock <?php echo $product['stock'] > 0 ? '' : 'out-of-stock'; ?>">
                        <?php echo $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ')' : 'Out of Stock'; ?>
                    </div>
                </div>

                <p><?php echo htmlspecialchars($product['description']); ?></p>

                <div class="quantity-section">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                </div>

                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="addToCartDetail(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="btn btn-secondary" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                        <i class="fas fa-heart"></i> Add to Wishlist
                    </button>
                </div>

                <div class="product-features">
                    <h3>Key Features</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Free Shipping on orders above Rs. 2,500</li>
                        <li><i class="fas fa-check"></i> 30-day money-back guarantee</li>
                        <li><i class="fas fa-check"></i> 1-year warranty</li>
                        <li><i class="fas fa-check"></i> 24/7 customer support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>