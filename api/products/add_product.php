<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$productController = new ProductController($pdo);

// Xử lý dữ liệu từ form và file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST; // Dữ liệu từ form
    $files = $_FILES; // Dữ liệu file upload

    // Gọi hàm tạo sản phẩm trong controller
    $response = $productController->createProduct($data, $files);

    // Trả về phản hồi
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Trường hợp không phải POST
http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Method not allowed.']);
exit();
