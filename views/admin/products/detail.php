<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm sản phẩm</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- TinyMCE -->
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
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-lg-6 text-center">
                            <img id="productImage" src="/uploads/baby-toy.png" class="img-fluid product-image"
                                alt="Hình ảnh sản phẩm">
                        </div>

                        <!-- Product Details -->
                        <div class="col-lg-6">
                            <div class="product-details">
                                <h1 id="productName">Tên Sản Phẩm</h1>
                                <p id="productCategory" class="product-meta">Danh mục: <span
                                        class="text-primary">N/A</span></p>
                                <p id="productPrice" class="product-price">N/A VND</p>
                                <p id="productStock" class="product-meta">Số lượng: <span
                                        class="text-success">N/A</span></p>
                                <div id="productDescription" class="product-description mt-4">
                                    Mô tả sản phẩm chi tiết sẽ được hiển thị tại đây.
                                </div>
                                <div class="mt-4">
                                    <a href="/index.php?page=admin-product-edit&id=<?= $_GET['id']; ?>"
                                        class="btn btn-warning">Chỉnh sửa sản phẩm</a>
                                    <button id="deleteProductButton" class="btn btn-danger">Xóa sản phẩm</button>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productId = new URLSearchParams(window.location.search).get('id');
            const deleteProductButton = document.getElementById('deleteProductButton');
            const productImagesContainer = document.getElementById('productImages');

            // Fetch product details
            fetch(`/api/products/get_product_by_id?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error })
                            .then(() => window.location.href = '/?page=admin-product-list');
                    } else {
                        document.getElementById('productName').textContent = data.name;
                        document.getElementById('productCategory').innerHTML = `Danh mục: <span class="text-primary">${data.category_name}</span>`;
                        document.getElementById('productPrice').textContent = `${Number(data.price).toLocaleString()} VND`;
                        document.getElementById('productStock').innerHTML = `Số lượng: <span class="text-success">${data.stock}</span>`;
                        document.getElementById('productDescription').innerHTML = data.description || 'Không có mô tả';
                        document.getElementById('productImage').src = data.images.length > 0 ? data.images[0].image_url : '/uploads/baby-toy.png';

                        // Hiển thị hình ảnh sản phẩm
                        productImagesContainer.innerHTML = data.images.map(image => `
                            <div class="product-image-container">
                                <img src="${image.image_url}" alt="Hình ảnh" class="img-thumbnail" style="width: 150px; height: 150px;">
                            </div>
                        `).join('');
                    }
                });

            // Delete product
            deleteProductButton.addEventListener('click', () => {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa?',
                    text: 'Hành động này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/api/products/delete_product`, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: productId })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({ icon: 'success', title: 'Đã xóa', text: 'Sản phẩm đã được xóa thành công.' })
                                        .then(() => window.location.href = '/?page=admin-product-list');
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error });
                                }
                            });
                    }
                });
            });
        });
    </script>
</body>

</html>