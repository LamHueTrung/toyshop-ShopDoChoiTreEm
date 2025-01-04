<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$productController = new ProductController($pdo);

$response = $productController->getAllProducts();

header('Content-Type: application/json');
echo json_encode($response);
