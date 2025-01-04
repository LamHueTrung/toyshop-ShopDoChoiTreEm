<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/CategoryController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$categoryController = new CategoryController($pdo);

$response = $categoryController->getAllCategories();

header('Content-Type: application/json');
echo json_encode($response);
