<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/OrderController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$orderController = new OrderController($pdo);

$response = $orderController->getAllOrders();

header('Content-Type: application/json');
echo json_encode($response);
