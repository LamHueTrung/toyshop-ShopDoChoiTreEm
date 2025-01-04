<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CartController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$cartController = new CartController($pdo);

$userId = $_GET['user_id'] ?? null;

$response = $cartController->clearCart($userId);

header('Content-Type: application/json');
echo json_encode($response);
