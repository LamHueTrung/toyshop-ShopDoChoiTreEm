<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_GET['user_id'] ?? null;
$cartId = $_GET['cart_id'] ?? null;

$response = $cartController->updateCartItem($userId, $cartId, $data['quantity'] ?? 0);

header('Content-Type: application/json');
echo json_encode($response);
