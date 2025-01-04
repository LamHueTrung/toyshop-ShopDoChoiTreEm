<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/OrderController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$orderController = new OrderController($pdo);

$orderId = $_GET['id'] ?? null;
$response = $orderController->getOrderById($orderId);

header('Content-Type: application/json');
echo json_encode($response);
