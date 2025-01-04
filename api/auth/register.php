<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$pdo = require __DIR__ . '/../../config/database.php';
$authController = new AuthController($pdo);

// Xử lý dữ liệu POST
$data = $_POST;

$file = $_FILES;
// // Xử lý tệp tải lên (ảnh đại diện)
// if (!empty($_FILES['profile_picture']['tmp_name'])) {
//     $uploadDir = __DIR__ . '/../../public/uploads/profile_pictures/';
//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true);
//     }

//     $fileName = uniqid() . '-' . basename($_FILES['profile_picture']['name']);
//     $filePath = $uploadDir . $fileName;

//     if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
//         $data['profile_picture'] = '/uploads/profile_pictures/' . $fileName; // Lưu đường dẫn ảnh
//     } else {
//         $data['profile_picture'] = null;
//     }
// }

// Gọi phương thức register trong AuthController
$response = $authController->register($data, $file);

header('Content-Type: application/json');
echo json_encode($response);
