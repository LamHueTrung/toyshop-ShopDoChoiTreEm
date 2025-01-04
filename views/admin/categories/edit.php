<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sửa danh mục sản phẩm</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php session_start();?>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include(__DIR__ . '/../partials/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include(__DIR__ . '/../partials/topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sửa danh mục sản phẩm</h6>
                        </div>
                        <div class="card-body">
                            <form id="editCategoryForm">
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Tên danh mục:</label>
                                    <input type="text" id="categoryName" class="form-control" placeholder="Nhập tên danh mục" required>
                                </div>
                                <div class="mb-3">
                                    <label for="categoryDescription" class="form-label">Mô tả:</label>
                                    <textarea id="categoryDescription" class="form-control" rows="3" placeholder="Nhập mô tả"></textarea>
                                </div>
                                <input type="hidden" id="categoryId">
                                <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include(__DIR__ . '/../partials/footer.php'); ?>
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
    <?php include(__DIR__ . '/../partials/modal.php'); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function () {
            const categoryId = new URLSearchParams(window.location.search).get('id');

            // Lấy thông tin danh mục theo ID
            fetch(`/api/category/get_category_by_id?id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const category = data.category;
                        $('#categoryName').val(category.name);
                        $('#categoryDescription').val(category.description || '');
                        $('#categoryId').val(category.id);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.error
                        }).then(() => {
                            window.location.href = '/?page=admin-categories';
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching category details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi lấy thông tin danh mục.'
                    }).then(() => {
                        window.location.href = '/?page=admin-categories';
                    });
                });

            // Sự kiện gửi form
            $('#editCategoryForm').on('submit', function (event) {
                event.preventDefault();
                const categoryName = $('#categoryName').val();
                const categoryDescription = $('#categoryDescription').val();

                fetch(`/api/category/update_category?id=${categoryId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: categoryName,
                        description: categoryDescription,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Danh mục đã được cập nhật thành công!'
                            }).then(() => {
                                window.location.href = '/?page=admin-categories';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.error || 'Không thể cập nhật danh mục.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi cập nhật danh mục.'
                        });
                    });
            });
        });
    </script>

</body>

</html>
