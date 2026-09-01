-- ShopHub E-Commerce Database Schema

-- Create Database
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_name (name)
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_order_id (order_id)
);

-- Wishlist Table
CREATE TABLE IF NOT EXISTS wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id),
    INDEX idx_user_id (user_id)
);

-- Sample Products
INSERT INTO products (name, description, category, price, stock, image_url) VALUES
('Wireless Headphones', 'Premium wireless headphones with noise cancellation', 'Electronics', 4999.00, 50, 'https://via.placeholder.com/150'),
('USB-C Cable', 'Durable USB-C charging and data cable', 'Accessories', 499.00, 200, 'https://via.placeholder.com/150'),
('Phone Case', 'Protective phone case for all models', 'Accessories', 699.00, 150, 'https://via.placeholder.com/150'),
('Screen Protector', 'Tempered glass screen protector', 'Accessories', 299.00, 300, 'https://via.placeholder.com/150'),
('Portable Charger', '20000mAh portable power bank', 'Electronics', 1999.00, 75, 'https://via.placeholder.com/150'),
('Laptop Stand', 'Adjustable aluminum laptop stand', 'Accessories', 1499.00, 40, 'https://via.placeholder.com/150'),
('Mechanical Keyboard', 'RGB backlit mechanical gaming keyboard', 'Electronics', 3999.00, 30, 'https://via.placeholder.com/150'),
('USB Hub', 'Multi-port USB 3.0 hub', 'Accessories', 1299.00, 60, 'https://via.placeholder.com/150');

-- Sample Users (password: password123)
INSERT INTO users (first_name, last_name, email, password, phone) VALUES
('John', 'Doe', 'john@example.com', '$2y$10$WQQBGqLQj3l2RnVz5c5GVeUvHVLBZe2rZj7YvJzKvRvZhX0Ke9Rge', '+977-1234567890'),
('Jane', 'Smith', 'jane@example.com', '$2y$10$WQQBGqLQj3l2RnVz5c5GVeUvHVLBZe2rZj7YvJzKvRvZhX0Ke9Rge', '+977-9876543210');

-- Create Indexes for Better Performance
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_orders_payment_status ON orders(payment_status);
CREATE INDEX idx_order_items_product_id ON order_items(product_id);