<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

// Kết nối cơ sở dữ liệu và khởi tạo controller
$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

// Lấy user ID từ session hoặc query
session_start();
$userId = $_SESSION['user']['id'] ?? null;

if (!$userId) {
    $response = [
        'success' => false,
        'error' => 'User is not logged in.'
    ];
} else {
    // Gọi hàm lấy giỏ hàng
    $response = $cartController->getCart($userId);
}

// Trả về JSON response
header('Content-Type: application/json');
echo json_encode($response);
