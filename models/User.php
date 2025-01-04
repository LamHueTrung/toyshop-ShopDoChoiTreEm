<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($username, $password, $email, $fullname, $phone, $address, $profile_picture, $role)
    {
        try {
            $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password, email, fullname, phone, address, role , profile_picture) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
            $stmt->execute([
                $username,
                $password,
                $email,
                $fullname ?? '',
                $phone ?? '',
                $address ?? '',
                $role,
                $profile_picture ?? null,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Ghi lỗi vào log (hoặc xử lý khác tùy yêu cầu)
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Không thể tạo tài khoản. Vui lòng thử lại.");
        }
    }


    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function update($id, $username, $email, $fullname, $phone, $address, $profile_picture, $role)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET username = ?, email = ?, fullname = ?, phone = ?, address = ?, profile_picture = ?, role = ?
            WHERE id = ?
        ");
        return $stmt->execute([$username, $email, $fullname, $phone, $address, $profile_picture, $role, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Kiểm tra tên đăng nhập đã tồn tại
    public function isUsernameTaken($username)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    // Kiểm tra email đã tồn tại
    public function isEmailTaken($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}
