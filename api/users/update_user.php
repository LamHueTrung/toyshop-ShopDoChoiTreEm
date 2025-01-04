<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$userController = new UserController($pdo);

$userId = $_GET['id'] ?? null;
$data = json_decode(file_get_contents('php://input'), true);
$response = $userController->updateUser($userId, $data);

header('Content-Type: application/json');
echo json_encode($response);
