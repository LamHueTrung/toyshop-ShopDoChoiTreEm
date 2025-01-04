<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$userController = new UserController($pdo);

// Lấy dữ liệu từ form
$data = $_POST;
$file = $files = $_FILES;

$response = $userController->createUser($data, $file);

header('Content-Type: application/json');
echo json_encode($response);
