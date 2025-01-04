<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
session_start();

// Kiểm tra nếu session 'user' không tồn tại hoặc role không phải là 'admin'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Chuyển hướng về trang đăng nhập
    header('Location: /?page=login');
    exit(); // Ngừng thực thi mã sau khi chuyển hướng
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include(__DIR__ . '\..\partials\sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include(__DIR__ . '\..\partials\topbar.php'); ?>

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Tài khoản</th>
                                            <th>Mật khẩu</th>
                                            <th>Họ tên</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="user-table-body">
                                        <!-- Dữ liệu từ API sẽ được đổ vào đây -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include(__DIR__ . '\..\partials\footer.php'); ?>

            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include(__DIR__ . '\..\partials\modal.php'); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $(document).ready(function () {
            // Gọi API để lấy danh sách người dùng
            fetch('/api/users/get_all_users')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const users = data.users; // Lấy danh sách user từ API
                        const tableBody = $('#user-table-body');
                        tableBody.empty(); // Xóa dữ liệu cũ (nếu có)

                        // Lặp qua danh sách user và thêm vào bảng
                        users.forEach(user => {
                            const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.password}</td>
                            <td>${user.fullname || 'N/A'}</td>
                            <td>
                            <button class="btn btn-info btn-sm btn-view-detail" data-id="${user.id}">Xem chi tiết</button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="${user.id}">Xoá</button>
                            </td>
                        </tr>
                    `;
                            tableBody.append(row);
                        });

                        // Thêm sự kiện Xóa
                        $('.btn-delete').on('click', function () {
                            const userId = $(this).data('id');
                            if (confirm('Bạn có chắc muốn xoá người dùng này?')) {
                                deleteUser(userId);
                            }
                        });
                    } else {
                        alert('Không thể tải danh sách người dùng');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                    alert('Đã xảy ra lỗi khi tải danh sách người dùng');
                });

            // Hàm xóa user
            function deleteUser(userId) {
                fetch(`/api/users/delete_user?id=${userId}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Xoá người dùng thành công');
                            location.reload(); // Tải lại trang sau khi xoá thành công
                        } else {
                            alert('Không thể xoá người dùng');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting user:', error);
                        alert('Đã xảy ra lỗi khi xoá người dùng');
                    });
            }

            // Sự kiện khi nhấn vào nút "Xem Chi Tiết"
            $(document).on('click', '.btn-view-detail', function () {
                const userId = $(this).data('id');

                // Gọi API để lấy thông tin chi tiết của người dùng
                fetch(`/api/users/get_user_by_id?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const user = data.user;

                            // Điền thông tin người dùng vào modal
                            $('#detail-id').text(user.id);
                            $('#detail-username').text(user.username);
                            $('#detail-password').text(user.password);
                            $('#detail-email').text(user.email);
                            $('#detail-fullname').text(user.fullname || 'N/A');
                            $('#detail-phone').text(user.phone || 'N/A');
                            $('#detail-address').text(user.address || 'N/A');
                            $('#detail-role').text(user.role);
                            $('#detail-created-at').text(user.created_at);

                            // Hiển thị modal
                            $('#userDetailModal').modal('show');
                        } else {
                            alert('Không thể lấy thông tin người dùng');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user details:', error);
                        alert('Đã xảy ra lỗi khi lấy thông tin người dùng');
                    });
            });
        });

    </script>

</body>

</html>