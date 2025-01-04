<?php
// index.php - Điểm khởi đầu của ứng dụng

// Hiển thị lỗi (dùng khi phát triển, nên tắt khi triển khai sản phẩm)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tự động tải các thư viện từ composer (nếu có)
require_once __DIR__ . '/../vendor/autoload.php';

// Kết nối cơ sở dữ liệu
$pdo = require __DIR__ . '/../config/database.php';

// Import helper để mã hóa
require_once __DIR__ . '/../helpers/EncryptionHelper.php';

function createDefaultAdmin($pdo)
{
    // Kiểm tra xem admin đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
    $stmt->execute();
    $adminExists = $stmt->fetch();

    if (!$adminExists) {
        // Tạo đối tượng EncryptionHelper
        $encryptionHelper = new EncryptionHelper();

        // Mật khẩu gốc (plain text)
        $plainPassword = 'admin123';

        // Mã hóa mật khẩu
        $encryptedPassword = $encryptionHelper->encrypt($plainPassword);

        // Thêm tài khoản admin vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, fullname, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $encryptedPassword, 'admin@example.com', 'Administrator', 'admin']);

    } 
}

// Gọi hàm tạo admin
createDefaultAdmin($pdo);
// Kiểm tra URL hiện tại
$requestUri = $_SERVER['REQUEST_URI'];

// Nếu URL bắt đầu bằng `/api`, chuyển hướng đến file `api/routes.php`
if (strpos($requestUri, '/api') === 0) {
    require_once __DIR__ . '\..\api\routes.php';
} else {
    // Ngược lại, xử lý route của giao diện front-end
    require_once __DIR__ . '\routes.php';
}
