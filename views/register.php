<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Đăng ký tài khoản</h4>
                    </div>
                    <div class="card-body">
                        <form id="registerForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span>:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span>:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span>:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span>:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên:</label>
                                <input type="text" id="fullname" name="fullname" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="text" id="phone" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <textarea id="address" name="address" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Ảnh đại diện:</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/?page=login">Đã có tài khoản? Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Mật khẩu và xác nhận mật khẩu không khớp!',
                });
                return;
            }

            const formData = new FormData(this);

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: result.error,
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Đăng ký thành công!',
                    }).then(() => {
                        window.location.href = '/?page=login';
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Đã xảy ra lỗi khi đăng ký.',
                });
            }
        });
    </script>
</body>

</html>
