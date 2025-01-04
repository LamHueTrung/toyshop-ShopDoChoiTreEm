<?php

// Lấy URL và phương thức HTTP hiện tại
$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Base directory (thư mục chứa các file API)
$baseDir = __DIR__;

// Tách URL thành các phần
$segments = explode('/', trim($url, '/'));

// Kiểm tra định dạng URL: phải có ít nhất `api/{folder}/{file}`
if (count($segments) >= 3 && $segments[0] === 'api') {
    $folder = $segments[1]; // Thư mục API (auth, cart, category, v.v.)
    $file = $segments[2];   // File PHP (login, add_product, get_reviews, v.v.)

    // Xây dựng đường dẫn đến file API
    $filePath = "$baseDir/$folder/{$file}.php";

    // Kiểm tra file có tồn tại không
    if (file_exists($filePath)) {
        // Gọi file API
        require_once $filePath;
        exit; // Kết thúc xử lý sau khi gọi file
    }
}

// Nếu không tìm thấy route, trả về lỗi 404
http_response_code(404);
echo json_encode([
    'error' => 'Endpoint not found',
    'path' => $url,
    'method' => $method,
]);
