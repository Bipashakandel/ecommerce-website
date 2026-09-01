<?php
session_start();
include 'config/db_config.php';

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'newest';

try {
    $query = 'SELECT * FROM products WHERE 1=1';
    $params = [];
    
    if($search) {
        $query .= ' AND (name LIKE ? OR description LIKE ?)';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    
    if($category) {
        $query .= ' AND category = ?';
        $params[] = $category;
    }
    
    if($sort == 'price_low') {
        $query .= ' ORDER BY price ASC';
    } else if($sort == 'price_high') {
        $query .= ' ORDER BY price DESC';
    } else {
        $query .= ' ORDER BY created_at DESC';
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->prepare('SELECT DISTINCT category FROM products WHERE category IS NOT NULL');
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $products = [];
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - ShopHub</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="shop-wrapper">
            <div class="shop-sidebar">
                <h3>Filters</h3>
                
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <h4>Search</h4>
                        <div class="search-form">
                            <input type="text" name="search" placeholder="Search products" value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Category</h4>
                        <select name="category">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category == $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <h4>Sort By</h4>
                        <select name="sort">
                            <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Apply Filters</button>
                </form>
            </div>

            <div class="shop-content">
                <h1>Shop Products</h1>
                <p class="product-count">Showing <?php echo count($products); ?> product<?php echo count($products) != 1 ? 's' : ''; ?></p>

                <?php if(count($products) > 0): ?>
                    <div class="products-grid">
                        <?php foreach($products as $product): ?>
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
                <?php else: ?>
                    <div class="no-products">
                        <p>No products found matching your criteria.</p>
                        <a href="shop.php" class="btn btn-primary">Clear Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>