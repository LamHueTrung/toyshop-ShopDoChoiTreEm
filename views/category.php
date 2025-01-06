<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuanQuach | Cửa hàng đồ chơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<?php session_start(); ?>

<body>
    <header>
        <?php include('partials/header.php'); ?>
    </header>
    <div class="container-fluid py-5  ">
        <div class="row d-flex">
            <div class="col-2 mb-4">
                <?php include('partials/sidebar.php'); ?>
            </div>
            <div class="col-10">

                <div class="row g-4" id="product-list">
                    <p>Đang tải sản phẩm...</p>
                </div>
            </div>

        </div>
    </div>
    </div>
    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        const productList = document.getElementById('product-list');
        const categoryId = <?php echo isset($_GET['id']) ? json_encode($_GET['id']) : 'null'; ?>;
        console.log(categoryId);
        const apiUrl = `/api/category/get_category_detail?id=${categoryId}`;
        // Gọi API để lấy danh sách sản phẩm
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data.success && data.products.length > 0) {
                    productList.innerHTML = ''; // Xóa nội dung cũ
                    data.products.forEach((product, index) => {
                        const imageUrl = product.images.length > 0 ? product.images[0].image_url : '/uploads/baby-toy.png';
                        const animationDelay = 0.3 + (index * 0.2); // Tính thời gian trễ
                        console.log(product);
                        const productCard = `
                            <div class="col-md-6 col-lg-3">
                                <div class="card h-100 animate__animated animate__zoomIn" style="animation-delay: ${animationDelay}s;">
                                    <a href="/?page=product&id=${product.id}" class="text-decoration-none">
                                        <img src="${imageUrl || '/uploads/baby-toy.png'}" class="card-img-top" alt="${product.name}">
                                    </a>
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-primary">${product.name}</h5>
                                        <p class="card-text text-muted">${Number(product.price).toLocaleString()} VND</p>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn rounded-pill add-to-cart-btn" data-id="${product.id}"><img style="width: 50px;" src="/img/basket.png" alt=""></button>
                                            <button class="btn rounded-pill add-to-view-btn" data-id="${product.id}"><img style="width: 50px;" src="/img/visual.png" alt=""></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        productList.insertAdjacentHTML('beforeend', productCard);
                    });

                    // Thêm sự kiện cho nút "Thêm vào giỏ hàng"
                    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            if(UseId == null){
                                Swal.fire({
                                text: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
                                icon: 'error',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Đăng nhập',
                                cancelButtonText: 'Quay lại sau'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/?page=login';
                                }
                            });
                            } else {
                                const productId = this.getAttribute('data-id');
                                addToCart(productId);
                            }
                        });
                    });
                    document.querySelectorAll('.add-to-view-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const productId = this.getAttribute('data-id');
                            window.location.href = `/?page=product&id=${productId}`;
                        });
                    });
                } else {
                    productList.innerHTML = '<p>Không có sản phẩm nào để hiển thị.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                productList.innerHTML = '<p>Không thể tải sản phẩm. Vui lòng thử lại sau.</p>';
            });

        // Hàm thêm vào giỏ hàng
        function addToCart(productId) {
            fetch('/api/cart/add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng!',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.error || 'Không thể thêm sản phẩm vào giỏ hàng.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.',
                    });
                });
        }
    });
</script>
</body>

</html>