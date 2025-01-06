<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

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
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên danh mục</th>
                                            <th>Mô tả</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="category-table-body">
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
    <!-- <script src="js/demo/datatables-demo.js"></script> -->
    <script>
        $(document).ready(function () {
            // Gọi API để lấy danh sách danh mục
            fetch('/api/category/get_category')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const categories = data.categories; // Lấy danh sách danh mục từ API
                        const tableBody = $('#category-table-body');
                        tableBody.empty(); // Xóa dữ liệu cũ (nếu có)

                        // Lặp qua danh sách danh mục và thêm vào bảng
                        categories.forEach(category => {
                            const row = `
                    <tr>
                        <td>${category.id}</td>
                        <td>${category.name}</td>
                        <td>${category.description || 'Không có mô tả'}</td>
                        <td>
                            <button class="btn btn-success btn-sm btn-detail" data-id="${category.id}">Xem</button>
                            <button class="btn btn-info btn-sm btn-edit" data-id="${category.id}">Sửa</button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="${category.id}">Xoá</button>
                        </td>
                    </tr>
                    `;
                            tableBody.append(row);
                        });

                        // Thêm sự kiện Xóa
                        $('.btn-delete').on('click', function () {
                            const categoryId = $(this).data('id');
                            if (confirm('Bạn có chắc muốn xoá danh mục này?')) {
                                deleteCategory(categoryId);
                            }
                        });

                        // Thêm sự kiện Sửa
                        $('.btn-edit').on('click', function () {
                            const categoryId = $(this).data('id');
                            editCategory(categoryId);
                        });

                        // Thêm sự kiện Sửa
                        $('.btn-detail').on('click', function () {
                            const categoryId = $(this).data('id');
                            detailCategory(categoryId);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể tải danh sách danh mục',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi tải danh sách danh mục',
                    });
                });

            // Hàm xóa danh mục
            function deleteCategory(categoryId) {
                fetch(`/api/category/delete_category?id=${categoryId}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Danh mục đã được xoá',
                            });
                            location.reload(); // Tải lại trang sau khi xoá thành công
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể xoá danh mục',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting category:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi xoá danh mục',
                        });
                    });
            }

            function editCategory(categoryId) {
                // Chuyển hướng tới giao diện chỉnh sửa danh mục
                window.location.href = `/?page=admin-category-edit&id=${categoryId}`;
            }

            function detailCategory(categoryId) {
                // Chuyển hướng tới giao diện chỉnh sửa danh mục
                window.location.href = `/?page=admin-category-detail&id=${categoryId}`;
            }
        });

    </script>
</body>

</html>