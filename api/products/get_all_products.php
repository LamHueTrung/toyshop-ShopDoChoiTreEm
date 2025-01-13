<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$productController = new ProductController($pdo);
$response = $productController->getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $controller = new ProductController($pdo);
    $keyword = $_GET['q'];

    $response = $controller->searchProducts($keyword);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


header('Content-Type: application/json');
echo json_encode($response);
