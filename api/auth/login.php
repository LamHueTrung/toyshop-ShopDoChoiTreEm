<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$authController = new AuthController($pdo);

$data = json_decode(file_get_contents('php://input'), true);
$response = $authController->login($data);

header('Content-Type: application/json');
echo json_encode($response);
