<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chỉnh sửa sản phẩm</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/bqrkjuna7ur9uaudwcr9dfgvtosnl29wt1l3eqb380kxdf2s/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
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
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include(__DIR__ . '/../partials/sidebar.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- Topbar -->
                <?php include(__DIR__ . '/../partials/topbar.php'); ?>

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa sản phẩm</h6>
                        </div>
                        <div class="card-body">
                            <form id="editProductForm" enctype="multipart/form-data">
                                <input type="hidden" id="productId" />
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Tên sản phẩm <span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" id="productName" class="form-control"
                                        placeholder="Nhập tên sản phẩm" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">Mô tả:</label>
                                    <textarea id="productDescription" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Giá sản phẩm <span
                                            class="text-danger">*</span>:</label>
                                    <input type="number" id="productPrice" class="form-control"
                                        placeholder="Nhập giá sản phẩm" required min="1">
                                </div>
                                <div class="mb-3">
                                    <label for="productStock" class="form-label">Số lượng <span
                                            class="text-danger">*</span>:</label>
                                    <input type="number" id="productStock" class="form-control"
                                        placeholder="Nhập số lượng" required min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span
                                            class="text-danger">*</span>:</label>
                                    <select id="category_id" class="form-control" required>
                                        <option value="" disabled selected>Chọn danh mục</option>
                                        <!-- Các danh mục sẽ được thêm từ API -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="currentImages" class="form-label">Hình ảnh hiện tại:</label>
                                    <div id="currentImages" class="d-flex flex-wrap">
                                        <!-- Hình ảnh cũ sẽ được thêm tại đây -->
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="productImages" class="form-label">Hình ảnh sản phẩm:</label>
                                    <input type="file" id="productImages" name="images[]" class="form-control" multiple>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include(__DIR__ . '/../partials/footer.php'); ?>
            <?php include(__DIR__ . '/../partials/modal.php'); ?>

        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function () {
            const productId = new URLSearchParams(window.location.search).get('id');

            // Lấy thông tin sản phẩm để hiển thị
            fetch(`/api/products/get_product_by_id?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.error
                        }).then(() => {
                            window.location.href = '/?page=admin-products';
                        });
                    } else {
                        $('#productId').val(data.id);
                        $('#productName').val(data.name);
                        tinymce.get('productDescription').setContent(data.description || '');
                        $('#productPrice').val(data.price);
                        $('#productStock').val(data.stock);
                        $('#category_id').val(data.category_id);

                        // Hiển thị hình ảnh cũ
                        const currentImages = $('#currentImages');
                        currentImages.empty();
                        data.images.forEach(image => {
                            currentImages.append(`
                    <div class="image-wrapper position-relative me-2 mb-2">
                        <img src="${image.image_url}" alt="Product Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                    </div>
                `);
                        });

                    }
                })
                .catch(error => {
                    console.error('Error fetching product:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể tải thông tin sản phẩm.'
                    });
                });

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
                            text: 'Không thể tải danh sách danh mục.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });

            // Xử lý cập nhật sản phẩm
            $('#editProductForm').on('submit', function (event) {
                event.preventDefault();

                const formData = new FormData();
                formData.append('id', $('#productId').val());
                formData.append('name', $('#productName').val());
                formData.append('description', tinymce.get('productDescription').getContent());
                formData.append('price', $('#productPrice').val());
                formData.append('stock', $('#productStock').val());
                formData.append('category_id', $('#category_id').val());

                const files = $('#productImages')[0].files;
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }

                fetch(`/api/products/update_product?id=${productId}`, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Sản phẩm đã được cập nhật thành công!'
                            }).then(() => {
                                window.location.href = '/?page=admin-products';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.error || 'Không thể cập nhật sản phẩm.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi cập nhật sản phẩm.'
                        });
                    });
            });
        });
    </script>
</body>

</html>