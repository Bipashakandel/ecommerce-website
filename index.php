<?php
session_start();
include 'config/db_config.php';

try {
    $stmt = $pdo->query('SELECT * FROM products LIMIT 8');
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $featured_products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopHub - Your Trusted Online Store</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to ShopHub</h1>
                <p>Discover amazing products at unbeatable prices</p>
                <a href="shop.php" class="btn btn-secondary btn-lg">Start Shopping</a>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="container">
        <div class="featured-section">
            <h2>Featured Products</h2>
            <div class="products-grid">
                <?php foreach($featured_products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="product-overlay">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 60) . '...'; ?></p>
                            <div class="product-footer">
                                <span class="price">Rs. <?php echo number_format($product['price'], 2); ?></span>
                                <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)" title="Add to cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>Free Shipping</h3>
                    <p>On orders above Rs. 2,500</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-undo"></i>
                    <h3>Easy Returns</h3>
                    <p>30-day money-back guarantee</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Payment</h3>
                    <p>100% secure transactions</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>Dedicated customer service</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>