<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ReviewController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$reviewController = new ReviewController($pdo);

$userId = $_GET['user_id'] ?? null;
$reviewId = $_GET['review_id'] ?? null;

$response = $reviewController->deleteReview($userId, $reviewId);

header('Content-Type: application/json');
echo json_encode($response);
