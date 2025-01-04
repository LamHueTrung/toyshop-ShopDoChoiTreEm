<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$productController = new ProductController($pdo);

$productId = $_GET['id'] ?? null;
$response = $productController->deleteProduct($productId);

header('Content-Type: application/json');
echo json_encode($response);
