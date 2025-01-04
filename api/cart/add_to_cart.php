<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

// Kết nối đến cơ sở dữ liệu
$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

session_start();
// Lấy dữ liệu từ body của yêu cầu
$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user']['id'] ?? null;

// Kiểm tra xem userId có hợp lệ không
if (empty($userId)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'User ID is required.',
    ]);
    exit;
}

// Kiểm tra xem dữ liệu đầu vào có hợp lệ không
if (empty($data['product_id']) || empty($data['quantity'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Product ID and quantity are required.',
    ]);
    exit;
}

// Thêm sản phẩm vào giỏ hàng
$response = $cartController->addToCart($userId, $data);

// Trả về phản hồi
if (isset($response['error'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => $response['error'],
    ]);
} else {
    http_response_code(200); // OK
    echo json_encode([
        'success' => true,
        'message' => $response['message'],
    ]);
}
