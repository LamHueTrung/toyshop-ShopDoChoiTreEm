<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Chi tiết danh mục</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Chi tiết danh mục</h6>
                        </div>
                        <div class="card-body">
                            <div id="category-detail">
                                <!-- Nội dung chi tiết danh mục sẽ được hiển thị tại đây -->
                            </div>

                            <h6 class="mt-4 font-weight-bold text-primary">Danh sách sản phẩm</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-table-body">
                                        <!-- Danh sách sản phẩm sẽ được thêm tại đây -->
                                    </tbody>
                                </table>
                            </div>
                            <a href="/?page=admin-categories" class="btn btn-secondary mt-3">Quay lại</a>
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const categoryId = urlParams.get('id'); // Lấy ID danh mục từ URL

            if (!categoryId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'ID danh mục không hợp lệ.',
                });
                return;
            }

            // Gọi API để lấy thông tin danh mục và sản phẩm thuộc danh mục
            fetch(`/api/category/get_category_detail?id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const category = data.category;
                        const products = data.products;

                        // Hiển thị chi tiết danh mục
                        const detailDiv = document.getElementById("category-detail");
                        detailDiv.innerHTML = `
                            <p><strong>ID:</strong> ${category.id}</p>
                            <p><strong>Tên danh mục:</strong> ${category.name}</p>
                            <p><strong>Mô tả:</strong> ${category.description || 'Không có mô tả'}</p>
                            <p><strong>Ngày tạo:</strong> ${category.created_at}</p>
                        `;

                        // Hiển thị danh sách sản phẩm
                        const tableBody = document.getElementById("product-table-body");
                        tableBody.innerHTML = ''; // Xóa nội dung cũ nếu có
                        products.forEach(product => {
                            console.log(product);
                            const imageUrl = product.images || '/uploads/default-image.png';
                            const row = `
                                <tr>
                                    <td>${product.id}</td>
                                    <td><img src="${imageUrl}" alt="${product.name}" class="img-thumbnail" width="75"></td>
                                    <td>${product.name}</td>
                                    <td>${product.price.toLocaleString()} VND</td>
                                    <td>${product.stock}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm btn-edit" data-id="${product.id}">Sửa</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="${product.id}">Xoá</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.insertAdjacentHTML('beforeend', row);
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
                            text: 'Không thể tải thông tin danh mục hoặc sản phẩm.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching category or products:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi tải dữ liệu.',
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