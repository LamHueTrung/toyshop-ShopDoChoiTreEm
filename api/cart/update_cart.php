<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

session_start();
$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user']['id'] ?? null;
$cartId = $data['cart_id'] ?? null;

$response = $cartController->updateCartItem($userId, $cartId, quantity: $data['quantity'] ?? 0);

header('Content-Type: application/json');
echo json_encode($response);
