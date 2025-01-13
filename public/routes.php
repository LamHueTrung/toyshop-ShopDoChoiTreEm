<?php

// Autoload các controller và cấu hình
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ReviewController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

// Lấy URL và method hiện tại
$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Xử lý route
// Kiểm tra URL được gửi lên
$page = $_GET['page'] ?? 'home'; // Nếu không có `page`, mặc định là `home`

switch ($page) {
    case 'home':
        require_once __DIR__ . '/../views/home.php';
        break;
    case 'search':
        require_once __DIR__ . '/../views/search.php';
        break;
    case 'login':
        require_once __DIR__ . '/../views/login.php';
        break;

    case 'register':
        require_once __DIR__ . '/../views/register.php';
        break;

    case 'product':
        require_once __DIR__ . '/../views/product.php';
        break;

    case 'cart':
        require_once __DIR__ . '/../views/cart.php';
        break;

    case 'checkout':
        require_once __DIR__ . '/../views/checkout.php';
        break;

    case 'profile':
        require_once __DIR__ . '/../views/profile.php';
        break;

    case 'category':
        require_once __DIR__ . '/../views/category.php';
        break;

    case 'orders':
        require_once __DIR__ . '/../views/orders/list.php';
        break;

    case 'order-details':
        require_once __DIR__ . '/../views/orders/details.php';
        break;

    // Admin routes
    case 'admin-dashboard':
        require_once __DIR__ . '/../views/admin/dashboard.php';
        break;

    case 'admin-products':
        require_once __DIR__ . '/../views/admin/products/list.php';
        break;

    case 'admin-product-create':
        require_once __DIR__ . '/../views/admin/products/create.php';
        break;

    case 'admin-product-edit':
        require_once __DIR__ . '/../views/admin/products/edit.php';
        break;

    case 'admin-product-detail':
        require_once __DIR__ . '/../views/admin/products/detail.php';
        break;

    case 'admin-categories':
        require_once __DIR__ . '/../views/admin/categories/list.php';
        break;

    case 'admin-category-create':
        require_once __DIR__ . '/../views/admin/categories/create.php';
        break;

    case 'admin-category-edit':
        require_once __DIR__ . '/../views/admin/categories/edit.php';
        break;

    case 'admin-category-detail':
        require_once __DIR__ . '/../views/admin/categories/detail.php';
        break;
    case 'admin-orders':
        require_once __DIR__ . '/../views/admin/orders/list.php';
        break;

    case 'admin-order-details':
        require_once __DIR__ . '/../views/admin/orders/details.php';
        break;

    case 'admin-users':
        require_once __DIR__ . '/../views/admin/users/list.php';
        break;

    default:
        // Trang lỗi 404
        require_once __DIR__ . '/../views/errors/404.php';
        break;
}
