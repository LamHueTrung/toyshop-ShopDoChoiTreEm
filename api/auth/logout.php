<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

// Khởi tạo AuthController
$pdo = require __DIR__ . '/../../config/database.php';
$authController = new AuthController($pdo);

// Xử lý logout
$response = $authController->logout();

// Trả về phản hồi JSON
header('Content-Type: application/json');
echo json_encode($response);
