<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm sản phẩm</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/bqrkjuna7ur9uaudwcr9dfgvtosnl29wt1l3eqb380kxdf2s/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#productDescription',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });
    </script>
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
                            <h6 class="m-0 font-weight-bold text-primary">Thêm sản phẩm</h6>
                        </div>
                        <div class="card-body">
                            <form id="addProductForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Tên sản phẩm <span class="text-danger">*</span>:</label>
                                    <input type="text" id="productName" class="form-control" placeholder="Nhập tên sản phẩm" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">Mô tả:</label>
                                    <textarea id="productDescription" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Giá sản phẩm <span class="text-danger">*</span>:</label>
                                    <input type="number" id="productPrice" class="form-control" placeholder="Nhập giá sản phẩm" required min="1">
                                </div>
                                <div class="mb-3">
                                    <label for="productStock" class="form-label">Số lượng <span class="text-danger">*</span>:</label>
                                    <input type="number" id="productStock" class="form-control" placeholder="Nhập số lượng" required min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span>:</label>
                                    <select id="category_id" class="form-control" required>
                                        <option value="" disabled selected>Chọn danh mục</option>
                                        <!-- Các danh mục sẽ được thêm từ API -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="productImages" class="form-label">Hình ảnh sản phẩm:</label>
                                    <input type="file" id="productImages" name="images[]" class="form-control" multiple>
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
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
            // Lấy danh sách danh mục từ API
            fetch('/api/category/get_category')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const categories = data.categories;
                        const categorySelect = $('#category_id');
                        categorySelect.empty();
                        categories.forEach(category => {
                            categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể tải danh sách danh mục.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });

            // Xử lý form thêm sản phẩm
            $('#addProductForm').on('submit', function (event) {
                event.preventDefault();

                // Kiểm tra dữ liệu bắt buộc
                if (!$('#productName').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Tên sản phẩm là bắt buộc.',
                    });
                    return;
                }

                if (!$('#productPrice').val() || $('#productPrice').val() <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Giá sản phẩm phải lớn hơn 0.',
                    });
                    return;
                }

                if (!$('#productStock').val() || $('#productStock').val() < 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Số lượng không được nhỏ hơn 0.',
                    });
                    return;
                }

                if (!$('#category_id').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Vui lòng chọn danh mục.',
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('name', $('#productName').val());
                formData.append('description', tinymce.get('productDescription').getContent());
                formData.append('price', $('#productPrice').val());
                formData.append('stock', $('#productStock').val());
                formData.append('category_id', $('#category_id').val());

                const files = $('#productImages')[0].files;
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }

                fetch('/api/products/add_product', {
                    method: 'POST',
                    enctype: "multipart/form-data",
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Sản phẩm đã được thêm thành công!',
                            }).then(() => {
                                window.location.href = '/?page=admin-products';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.error || 'Không thể thêm sản phẩm.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi thêm sản phẩm.',
                        });
                    });
            });
        });
    </script>
</body>

</html>
