<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-weight: bold;
            color: #6a11cb;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            background-color: #fff;
            padding: 0 5px;
            font-size: 16px;
            color: #aaa;
            transition: all 0.3s ease-in-out;
        }

        .form-group input {
            width: 100%;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .form-group input:focus,
        .form-group input:not(:placeholder-shown) {
            border-color: #6a11cb;
        }

        .form-group input:focus+label,
        .form-group input:not(:placeholder-shown)+label {
            top: -10px;
            font-size: 12px;
            color: #6a11cb;
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
        }

        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: bold;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    // Chuyển hướng về trang đăng nhập
    header('Location: /?page=admin-dashboard');
    exit(); // Ngừng thực thi mã sau khi chuyển hướng
}
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user') {
    // Chuyển hướng về trang đăng nhập
    header('Location: /?page=home');
    exit(); // Ngừng thực thi mã sau khi chuyển hướng
}
?>

<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form id="loginForm">
            <div class="form-group">
                <input type="text" id="username" name="username" class="form-control" placeholder=" " required>
                <label for="username">Tên đăng nhập</label>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder=" " required>
                <label for="password">Mật khẩu</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Đăng nhập</button>
        </form>
        <div class="login-footer">
            <p>Bạn chưa có tài khoản? <a href="?page=register">Đăng ký ngay</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(() => {
                            if (data.role === 'admin') {
                                window.location.href = '/?page=admin-dashboard'; // Chuyển đến dashboard admin
                            } else if (data.role === 'user') {
                                window.location.href = '/?page=home'; // Chuyển đến trang home cho user
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Thông báo',
                                    text: 'Không xác định được vai trò người dùng!',
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.error,
                        });
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        });
    </script>
</body>

</html>