<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/OrderController.php';
require_once __DIR__ . '/../../models/Cart.php';

session_start();

if (!isset($_SESSION['user']['id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'User is not logged in.',
    ]);
    exit;
}

$userId = $_SESSION['user']['id'];

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['cart_items']) || !is_array($data['cart_items'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Cart items are required.',
    ]);
    exit;
}

if (empty($data['total_price']) || !is_numeric($data['total_price'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Total price is required and must be numeric.',
    ]);
    exit;
}

try {
    $pdo = require __DIR__ . '/../../config/database.php';
    $orderController = new OrderController($pdo);

    // Create the order
    $response = $orderController->createOrder($userId, $data['cart_items'], $data['total_price']);
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred: ' . $e->getMessage(),
    ]);
}
