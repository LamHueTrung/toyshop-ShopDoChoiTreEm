<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

session_start();

// Kết nối tới cơ sở dữ liệu
$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

// Lấy user ID từ session
$userId = $_SESSION['user']['id'] ?? null;

// Lấy dữ liệu từ body request
$data = json_decode(file_get_contents('php://input'), true);
$cartId = $data['cart_id'] ?? null;

if (!$userId) {
    // Kiểm tra nếu user chưa đăng nhập
    $response = [
        'success' => false,
        'error' => 'User is not logged in.',
    ];
} elseif (!$cartId) {
    // Kiểm tra nếu không có cart ID
    $response = [
        'success' => false,
        'error' => 'Cart ID is required.',
    ];
} else {
    // Gọi phương thức removeCartItem từ CartController
    $response = $cartController->removeCartItem($userId, $cartId);
}

// Trả về JSON response
header('Content-Type: application/json');
echo json_encode($response);
