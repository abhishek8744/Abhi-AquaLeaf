<?php
// session_start() should be called in individual files, not here to avoid duplicate calls

define('SITE_NAME', 'Abhi AquaLeaf');
define('SITE_URL', 'http://localhost/Abhi%20AquaLeaf');
define('BASE_PATH', dirname(__DIR__));

// Currency Settings
define('CURRENCY', '₹');
define('CURRENCY_CODE', 'INR');

// Tax Settings
define('GST_PERCENTAGE', 18);

// Email Settings
define('ADMIN_EMAIL', 'admin@aqualeaf.com');
define('FROM_EMAIL', 'noreply@aqualeaf.com');

// File Upload Settings
define('UPLOAD_DIR', BASE_PATH . '/assets/images/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Pagination
define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE', 20);

// Password Settings
define('MIN_PASSWORD_LENGTH', 6);

// Session Timeout (in seconds)
define('SESSION_TIMEOUT', 3600);

// Payment Gateway (Stripe, Razorpay, PayPal)
define('PAYMENT_GATEWAY', 'stripe');
define('STRIPE_PUBLIC_KEY', '');
define('STRIPE_SECRET_KEY', '');
define('RAZORPAY_KEY_ID', '');
define('RAZORPAY_KEY_SECRET', '');
define('PAYPAL_CLIENT_ID', '');
define('PAYPAL_SECRET', '');

// Coupon Settings
define('COUPON_EXPIRY_DAYS', 30);

// Download Settings
define('DOWNLOAD_EXPIRY_HOURS', 24);
?>
