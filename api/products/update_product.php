<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$productController = new ProductController($pdo);

// Lấy product ID từ query string
$productId = $_GET['id'] ?? null;

// Kiểm tra product ID hợp lệ
if (!$productId || !is_numeric($productId)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid product ID.']);
    exit();
}

// Kiểm tra method phải là POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method. Only POST is allowed.']);
    exit();
}

// Lấy dữ liệu từ form-data
$data = [
    'name' => $_POST['name'] ?? null,
    'description' => $_POST['description'] ?? null,
    'price' => $_POST['price'] ?? null,
    'stock' => $_POST['stock'] ?? null,
    'category_id' => $_POST['category_id'] ?? null,
];

// Gọi hàm updateProduct trong ProductController
$response = $productController->updateProduct($productId, $data, $_FILES);

// Trả về kết quả dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
