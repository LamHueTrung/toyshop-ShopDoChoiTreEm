<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/OrderController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$orderController = new OrderController($pdo);


// Lấy dữ liệu JSON từ client
$data = json_decode(file_get_contents('php://input'), true);


if (empty($data['status'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Trạng thái mới không được cung cấp.',
    ]);
    exit;
}

// Gọi hàm cập nhật trạng thái đơn hàng
$response = $orderController->updateOrderStatus($data['id'], $data['status']);

// Trả về kết quả JSON
header('Content-Type: application/json');
echo json_encode($response);
