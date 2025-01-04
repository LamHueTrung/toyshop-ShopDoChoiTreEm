<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$userController = new UserController($pdo);

$userId = $_GET['id'] ?? null;
$response = $userController->getUserById($userId);

header('Content-Type: application/json');
echo json_encode($response);
