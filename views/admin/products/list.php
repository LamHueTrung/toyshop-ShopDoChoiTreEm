<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Danh sách sản phẩm</title>

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

<?php session_start(); ?>
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

                    <!-- Page Heading -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mô tả</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Danh mục</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-table-body">
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
            // Gọi API để lấy danh sách sản phẩm
            fetch('/api/products/get_all_products')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const products = data.products; // Lấy danh sách sản phẩm từ API
                        const tableBody = $('#product-table-body');
                        tableBody.empty(); // Xóa dữ liệu cũ (nếu có)

                        // Lặp qua danh sách sản phẩm và thêm vào bảng
                        products.forEach(product => {
                            const imageUrl = product.images.length > 0 ? product.images[0].image_url : '/uploads/baby-toy.png';
                            const row = `
                                <tr>
                                    <td>${product.id}</td>
                                    <td><img src="${imageUrl}" alt="${product.name}" class="img-thumbnail" width="75"></td>
                                    <td>${product.name}</td>
                                    <td>${product.description || 'Không có mô tả'}</td>
                                    <td>${product.price.toLocaleString()} VND</td>
                                    <td>${product.stock}</td>
                                    <td>${product.category_name || 'Không có danh mục'}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm btn-edit" data-id="${product.id}">Sửa</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="${product.id}">Xoá</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });

                        // Thêm sự kiện Xóa
                        $('.btn-delete').on('click', function () {
                            const productId = $(this).data('id');
                            Swal.fire({
                                title: 'Bạn có chắc chắn?',
                                text: 'Sản phẩm sẽ bị xóa và không thể khôi phục!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Xóa',
                                cancelButtonText: 'Hủy'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    deleteProduct(productId);
                                }
                            });
                        });

                        // Thêm sự kiện Sửa
                        $('.btn-edit').on('click', function () {
                            const productId = $(this).data('id');
                            editProduct(productId);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể tải danh sách sản phẩm',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi tải danh sách sản phẩm',
                    });
                });

            // Hàm xóa sản phẩm
            function deleteProduct(productId) {
                fetch(`/api/products/delete_product?id=${productId}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Sản phẩm đã được xoá',
                            });
                            location.reload(); // Tải lại trang sau khi xoá thành công
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể xoá sản phẩm',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting product:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi xoá sản phẩm',
                        });
                    });
            }

            // Hàm sửa sản phẩm (chuyển hướng tới giao diện chỉnh sửa sản phẩm)
            function editProduct(productId) {
                window.location.href = `/?page=admin-product-edit&id=${productId}`;
            }
        });
    </script>

</body>

</html>
