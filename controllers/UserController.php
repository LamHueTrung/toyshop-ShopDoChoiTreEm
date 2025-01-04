<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    /**
     * Tạo người dùng mới
     */
    public function createUser($data, $file = null)
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
        if ($this->userModel->isUsernameTaken($data['username'])) {
            throw new Exception('Tên đăng nhập đã tồn tại.');
        }

        if ($this->userModel->isEmailTaken($data['email'])) {
            throw new Exception('Email đã được đăng ký.');
        }

        // Xử lý upload hình ảnh
        $profilePicturePath = null;
        $uploadDir = __DIR__ . '/../public/uploads/';
            $filename = time() . '_' . basename($file['name']);
            $filePath = $uploadDir . $filename;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Chỉ chấp nhận các tệp ảnh JPG, PNG, GIF.');
            }

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception('Không thể tải lên hình ảnh.');
            }

        }
        $profilePicturePath = '/public/uploads/' . $filename;

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Tạo người dùng mới
        $userId = $this->userModel->create(
            $data['username'],
            $hashedPassword,
            $data['email'],
            $data['fullname'] ?? '',
            $data['phone'] ?? '',
            $data['address'] ?? '',
            $profilePicturePath ?? null,
            $data['role'] ?? 'user'
        );

        return [
            'success' => true,
            'message' => 'Người dùng được tạo thành công.',
            'user_id' => $userId,
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}



    /**
     * Lấy thông tin người dùng theo ID
     */
    public function getUserById($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID người dùng là bắt buộc.');
            }

            $user = $this->userModel->getById($id);

            if (!$user) {
                throw new Exception('Người dùng không tồn tại.');
            }

            return [
                'success' => true,
                'user' => $user,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateUser($id, $data)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID người dùng là bắt buộc.');
            }

            $user = $this->userModel->getById($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại.');
            }

            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Định dạng email không hợp lệ.');
            }

            $result = $this->userModel->update(
                $id,
                $data['username'] ?? $user['username'],
                $data['email'] ?? $user['email'],
                $data['fullname'] ?? $user['fullname'],
                $data['phone'] ?? $user['phone'],
                $data['address'] ?? $user['address'],
                $data['profile_picture'] ?? $user['profile_picture'],
                $data['role'] ?? $user['role']
            );

            return [
                'success' => $result,
                'message' => $result ? 'Cập nhật người dùng thành công.' : 'Cập nhật người dùng thất bại.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID người dùng là bắt buộc.');
            }

            $user = $this->userModel->getById($id);
            if (!$user) {
                throw new Exception('Người dùng không tồn tại.');
            }

            $result = $this->userModel->delete($id);

            return [
                'success' => $result,
                'message' => $result ? 'Xóa người dùng thành công.' : 'Xóa người dùng thất bại.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Lấy danh sách tất cả người dùng
     */
    public function getAllUsers()
    {
        try {
            $users = $this->userModel->getAll();

            return [
                'success' => true,
                'users' => $users,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
