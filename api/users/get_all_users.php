<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$userController = new UserController($pdo);

$response = $userController->getAllUsers();

header('Content-Type: application/json');
echo json_encode($response);
