<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CategoryController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$categoryController = new CategoryController($pdo);

$data = json_decode(file_get_contents('php://input'), true);

$response = $categoryController->createCategory($data);

header('Content-Type: application/json');
echo json_encode($response);
