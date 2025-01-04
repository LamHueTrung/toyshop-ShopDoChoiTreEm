<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/EncryptionHelper.php'; // Thêm helper mã hóa

class AuthController
{
    private $userModel;
    private $encryptionHelper;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->encryptionHelper = new EncryptionHelper();
    }

    public function register($data, $files = null)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
                throw new Exception('Tên đăng nhập, mật khẩu và email là bắt buộc.');
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Định dạng email không hợp lệ.');
            }

            // Kiểm tra tài khoản đã tồn tại
            $existingUser = $this->userModel->getAll();
            foreach ($existingUser as $user) {
                if ($user['username'] === $data['username']) {
                    throw new Exception('Tên đăng nhập đã tồn tại.');
                }
                if ($user['email'] === $data['email']) {
                    throw new Exception('Email đã được đăng ký.');
                }
            }

            // Xử lý ảnh đại diện nếu có
            $profilePicturePath = '';
            if ($files && !empty($files['profile_picture']['tmp_name'])) {
                $uploadDir = __DIR__ . '/../public/uploads/profile_pictures/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '-' . basename($files['profile_picture']['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($files['profile_picture']['tmp_name'], $filePath)) {
                    $profilePicturePath = '/uploads/profile_pictures/' . $fileName;
                } else {
                    throw new Exception('Không thể tải lên ảnh đại diện.');
                }
            }

            // Mã hóa mật khẩu bằng AES-256-CBC
            $encryptedPassword = $this->encryptionHelper->encrypt($data['password']);

            // Tạo người dùng mới
            return $this->userModel->create(
                $data['username'],
                $encryptedPassword,
                $data['email'],
                $data['fullname'] ?? '',
                $data['phone'] ?? '',
                $data['address'] ?? '',
                $profilePicturePath ?? '/uploads/profile_pictures/' . $fileName,
                $data['role'] ?? 'user'
            );
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function login($data)
    {
        session_start();

        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($data['username']) || empty($data['password'])) {
                throw new Exception('Username and password are required.');
            }

            // Lấy thông tin người dùng
            $users = $this->userModel->getAll();
            $user = null;
            foreach ($users as $u) {
                if ($u['username'] === $data['username']) {
                    $user = $u;
                    break;
                }
            }
            if (!$user) {
                throw new Exception('User not found.');
            }

            // Giải mã mật khẩu trong cơ sở dữ liệu
            $decryptedPassword = $this->encryptionHelper->decrypt($user['password']);

            // Kiểm tra mật khẩu
            if ($data['password'] !== $decryptedPassword) {
                throw new Exception('Invalid password.');
            }

            // Lưu thông tin người dùng vào session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'fullname' => $user['fullname'],
            ];

            return [
                'message' => 'Login successful.',
                'success' => true,
                'role' => $user['role']
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function logout()
    {
        session_start();

        // Xóa toàn bộ dữ liệu session
        $_SESSION = [];

        // Hủy toàn bộ session
        session_destroy();

        return [
            'success' => true,
            'message' => 'Logout successful.',
        ];
    }

    private function generateJWT($payload)
    {
        // Giả sử bạn sử dụng JWT với thư viện firebase/php-jwt
        $secretKey = $_ENV['JWT_SECRET'] ?? 'default_secret';
        return \Firebase\JWT\JWT::encode($payload, $secretKey, 'HS256');
    }
}
