# Abhi AquaLeaf - Packaged Drinking Water Bottle Selling Website

A complete, responsive e-commerce website for selling packaged drinking water bottles with PHP, MySQL, and modern responsive design.

## Features

### User Side
- **Authentication**: User signup, login, forgot password, profile management
- **Product Catalog**: Browse products with filters (category, price, popularity) and search
- **Product Details**: View product information, images, and related products
- **Shopping Cart**: Add to cart, update quantities, remove items
- **Checkout**: Secure checkout with multiple payment options (Stripe, Razorpay, PayPal)
- **Coupons**: Apply discount codes during checkout
- **Order Management**: View order history, order details, and download invoices
- **Support**: Submit support tickets and track status
- **FAQ**: Browse frequently asked questions
- **Responsive Design**: Mobile-first design with bottom navigation for mobile, professional navbar for desktop
- **Dark/Light Mode**: Toggle between dark and light themes

### Admin Side
- **Authentication**: Secure admin login with role-based access
- **Dashboard**: Overview with stats (users, products, orders, revenue)
- **Product Management**: Add, edit, delete products with image uploads
- **Order Management**: View orders, update status, manage refunds
- **User Management**: View users, block/unblock accounts, view purchase history
- **Coupon Management**: Create discount codes (flat/percentage) with usage limits and expiry
- **Reports**: Daily/monthly sales reports, top-selling products, revenue analytics
- **Support Management**: Manage support tickets, reply to queries
- **FAQ Management**: Add, edit, delete FAQs
- **Settings**: Configure payment gateways, tax rates, site branding

## Technology Stack

- **Frontend**: PHP, HTML5, CSS3, JavaScript
- **Backend**: PHP (PDO)
- **Database**: MySQL
- **UI Framework**: Custom CSS inspired by MDBootstrap/Bootstrap
- **Icons**: Font Awesome 6.4.0
- **Responsive**: Mobile-first design with media queries

## Installation

### Prerequisites
- XAMPP or WAMP server with PHP 7.4+
- MySQL database
- Apache web server with mod_rewrite enabled

### Steps

1. **Clone/Download the project** to `C:\xampp\htdocs\Abhi AquaLeaf\`

2. **Run the installer**:
   - Open your browser and go to: `http://localhost/Abhi%20AquaLeaf/install.php`
   - This will automatically create the database and import the schema

3. **Access the website**:
   - Main website: `http://localhost/Abhi%20AquaLeaf/`
   - Admin panel: `http://localhost/Abhi%20AquaLeaf/admin/`

4. **Default Admin Credentials**:
   - Email: `admin@aqualeaf.com`
   - Password: `admin123`

5. **Change the default admin password** after first login for security.

## Project Structure

```
Abhi AquaLeaf/
├── admin/                    # Admin panel
│   ├── includes/            # Admin header/footer
│   ├── products/            # Product management
│   ├── orders/              # Order management
│   ├── users/               # User management
│   ├── coupons/             # Coupon management
│   ├── reports/             # Analytics & reports
│   ├── support/             # Support tickets & FAQ
│   ├── settings/            # Site settings
│   ├── dashboard.php        # Admin dashboard
│   ├── login.php            # Admin login
│   └── logout.php           # Admin logout
├── api/                     # AJAX endpoints
│   ├── add-to-cart.php
│   ├── update-cart.php
│   └── remove-from-cart.php
├── assets/                  # Static assets
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript files
│   └── images/              # Images
├── config/                  # Configuration files
│   ├── config.php           # Site configuration
│   └── database.php         # Database connection
├── database/                # Database files
│   └── schema.sql           # Database schema
├── includes/                # Shared includes
│   ├── header.php           # Site header
│   └── footer.php           # Site footer
├── pages/                   # User-facing pages
│   ├── login.php            # User login
│   ├── signup.php           # User signup
│   ├── profile.php          # User profile
│   ├── products.php         # Product listing
│   ├── product-detail.php   # Product details
│   ├── cart.php             # Shopping cart
│   ├── checkout.php         # Checkout page
│   ├── process-payment.php  # Payment processing
│   ├── order-success.php    # Order confirmation
│   ├── orders.php           # Order history
│   ├── order-detail.php     # Order details
│   ├── contact.php          # Contact form
│   ├── faq.php              # FAQ page
│   ├── support.php          # Support tickets
│   ├── about.php            # About page
│   ├── privacy.php          # Privacy policy
│   ├── terms.php            # Terms & conditions
│   ├── forgot-password.php  # Forgot password
│   ├── reset-password.php   # Reset password
│   └── logout.php           # User logout
├── .htaccess                # Apache URL rewriting
├── index.php                # Homepage
├── install.php              # Database installer
└── README.md                # This file
```

## Database Schema

The database includes the following tables:
- `users` - User accounts
- `products` - Product catalog
- `product_screenshots` - Product images
- `orders` - Customer orders
- `order_items` - Order line items
- `coupons` - Discount codes
- `coupon_usage` - Coupon usage tracking
- `support_tickets` - Support tickets
- `ticket_messages` - Ticket messages
- `faqs` - Frequently asked questions
- `settings` - Site settings
- `cart` - Shopping cart
- `password_resets` - Password reset tokens

## Configuration

Edit `config/config.php` to customize:
- Site name and URL
- Currency settings
- Tax percentage (GST)
- Email settings
- File upload settings
- Payment gateway credentials
- Session timeout

## Payment Gateways

The system supports multiple payment gateways (to be configured in admin settings):
- Stripe
- Razorpay
- PayPal

Note: In production, you need to obtain API keys from the respective payment gateway providers.

## Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with PDO prepared statements
- XSS prevention with output escaping
- Session-based authentication
- CSRF protection (to be implemented)
- Secure file upload validation

## Responsive Design

### Mobile View (< 768px)
- Bottom navigation bar (Home, Products, Cart, Profile)
- Full-width cards
- Large touch-friendly buttons
- Vertical product list
- Native app-style UI

### Desktop View (> 768px)
- Professional navbar
- Grid product layout (3-4 per row)
- Sidebar navigation in admin
- Modern e-commerce styling

## Dark/Light Mode

Users can toggle between dark and light themes. The preference is saved in localStorage.

## Support

For issues or questions, please contact:
- Email: contact@aqualeaf.com
- Phone: +91 9876543210

## License

This project is for demonstration purposes.

## Credits

Built with PHP, MySQL, and vanilla JavaScript/CSS.
Icons by Font Awesome.
