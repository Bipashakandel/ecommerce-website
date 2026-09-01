# ShopHub - E-Commerce Website

A full-featured e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

- **User Authentication**: Registration and login system
- **Product Catalog**: Browse and search products
- **Shopping Cart**: Add/remove items and manage quantities
- **Checkout**: Complete order process
- **Payment Integration**: eSewa payment gateway integration
- **Order Management**: View order details and history
- **Responsive Design**: Mobile-friendly interface
- **Search & Filter**: Filter products by category and search

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Bipashakandel/ecommerce-website.git
   cd ecommerce-website
   ```

2. **Create Database**
   - Import `database.sql` to your MySQL server
   ```bash
   mysql -u root < database.sql
   ```

3. **Configure Database**
   - Edit `config/db_config.php`
   - Update database credentials if needed

4. **Start the server**
   ```bash
   php -S localhost:8000
   ```
   - Visit `http://localhost:8000` in your browser

## Project Structure

```
ecommerce-website/
├── config/
│   └── db_config.php          # Database configuration
├── includes/
│   ├── header.php             # Header component
│   └── footer.php             # Footer component
├── css/
│   └── styles.css             # Main stylesheet
├── js/
│   └── main.js                # JavaScript functionality
├── index.php                  # Home page
├── shop.php                   # Shop/Products page
├── product.php                # Product detail page
├── cart.php                   # Shopping cart page
├── checkout.php               # Checkout page
├── esewa_payment.php          # eSewa payment page
├── esewa_verify.php           # Payment verification
├── order_success.php          # Order confirmation
├── login.php                  # Login page
├── register.php               # Registration page
├── logout.php                 # Logout
├── database.sql               # Database schema
└── README.md                  # This file
```

## Key Pages

### Public Pages
- **index.php** - Home page with featured products
- **shop.php** - Product listing with filters and search
- **product.php** - Product detail page
- **cart.php** - Shopping cart management
- **checkout.php** - Order checkout process

### Authentication
- **login.php** - User login
- **register.php** - User registration
- **logout.php** - User logout

### Payment
- **esewa_payment.php** - eSewa payment integration
- **esewa_verify.php** - Payment verification and order creation
- **order_success.php** - Order confirmation page

## Database Schema

### Tables
- **users** - User accounts and authentication
- **products** - Product inventory
- **orders** - Customer orders
- **order_items** - Items within orders

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Font Awesome Icons
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Payment Gateway**: eSewa
- **APIs**: RESTful architecture

## Security Features

- Password hashing with PHP's password_hash()
- Prepared statements for SQL injection prevention
- Session-based authentication
- Input sanitization with htmlspecialchars()
- CSRF protection considerations

## Usage

### Creating an Account
1. Click "Register" on the homepage
2. Fill in your details
3. Create a password (minimum 6 characters)
4. Submit the form

### Shopping
1. Browse products on the Shop page
2. Use filters and search to find products
3. Click "Add to Cart" to add items
4. View cart and proceed to checkout

### Payment with eSewa
1. Proceed through checkout
2. Select eSewa as payment method
3. Complete eSewa payment
4. Return to website for order confirmation

## Future Enhancements

- Admin dashboard for product management
- User profile and order history
- Product reviews and ratings
- Wishlist functionality
- Multiple payment gateways (Khalti, Stripe)
- Email notifications
- Inventory management
- Analytics and reporting

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the MIT License.

## Support

For support, email support@shophub.com or open an issue on GitHub.

## Author

Bipasha Kandel

---

**Note**: This is a demonstration project. For production use, implement additional security measures and payment gateway authentication.
