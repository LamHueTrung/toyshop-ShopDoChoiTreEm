<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ReviewController.php';

session_start();

try {
    $pdo = require __DIR__ . '/../../config/database.php';
    $reviewController = new ReviewController($pdo);

    // Lấy user_id từ session
    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
        throw new Exception('Bạn cần đăng nhập để thêm đánh giá.');
    }

    $userId = $_SESSION['user']['id'] ?? null;

    // Lấy dữ liệu từ request
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($userId)) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'error' => 'User ID is required.',
        ]);
        exit;
    }
    
    // // Kiểm tra xem dữ liệu đầu vào có hợp lệ không
    // if (empty($data['product_id']) || empty($data['quantity'])) {
    //     http_response_code(400); // Bad Request
    //     echo json_encode([
    //         'success' => false,
    //         'error' => 'Product ID and quantity are required.',
    //     ]);
    //     exit;
    // }

    // Gọi phương thức thêm đánh giá
    $response = $reviewController->addReview($userId, $data);

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Xử lý lỗi và trả về response lỗi
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
