<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ReviewController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$reviewController = new ReviewController($pdo);

$productId = $_GET['id'] ?? null;

$response = $reviewController->getReviewsByProductId($productId);

header('Content-Type: application/json');
echo json_encode($response);
