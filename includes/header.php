<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="header">
    <div class="container">
        <nav class="navbar">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-shopping-bag"></i>
                        ShopHub
                    </a>
                </div>
                
                <button class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php" class="<?php echo $current_page == 'index' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="shop.php" class="<?php echo $current_page == 'shop' ? 'active' : ''; ?>">Shop</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                
                <div class="nav-icons">
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                    
                    <div class="auth-buttons">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <a href="logout.php" class="btn btn-logout">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-login">Login</a>
                            <a href="register.php" class="btn btn-register">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>