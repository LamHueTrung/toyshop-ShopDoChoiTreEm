<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .product-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .carousel-inner img {
            max-height: 400px;
            object-fit: contain;
            border-radius: 10px;
        }

        .product-details h1 {
            font-weight: bold;
            color: #333;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: #28a745;
        }

        .related-products,
        .comments-section {
            margin-top: 30px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .comment-box {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .comment-box strong {
            font-weight: bold;
            color: #333;
        }

        .comment-box small {
            color: #888;
        }

        .comment-box p {
            margin-top: 10px;
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .btn-add-to-cart {
            background-color: #ff6f61;
            /* Màu cam nổi bật */
            border: none;
            border-radius: 30px;
            /* Bo tròn nút */
            padding: 10px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            /* Màu chữ trắng */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-add-to-cart:hover {
            background-color: #ff8b74;
            /* Màu cam nhạt hơn khi hover */
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.3);
            transform: translateY(-3px);
            /* Đẩy nút lên nhẹ khi hover */
            color: #ffffff;
        }

        .thumbnail-gallery {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            gap: 10px;
        }

        .thumbnail-gallery img {
            max-height: 80px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .thumbnail-gallery img:hover,
        .thumbnail-gallery img.active {
            border-color: #007bff;
        }

        .btn-add-to-cart:active {
            background-color: #e66052;
            /* Màu cam đậm hơn khi click */
            transform: translateY(2px);
            /* Đẩy nút xuống nhẹ khi click */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <?php session_start(); ?>
    <header>
        <?php include('partials/header.php'); ?>
    </header>

    <div class="container product-container">
        <div class="row">
            <!-- Product Image Carousel -->
            <div class="col-lg-6 text-center">
                <div id="productImageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselImages">
                        <!-- Images will be dynamically added here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="thumbnail-gallery" id="thumbnailGallery"></div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-details">
                    <h1 id="productName">Tên Sản Phẩm</h1>
                    <p id="productCategory" class="product-meta">Danh mục: <span class="text-primary">N/A</span></p>
                    <p id="productPrice" class="product-price">99,000 VND</p>

                    <!-- Input số lượng -->
                    <div class="mt-3">
                        <label for="productQuantity" class="form-label">Số lượng:</label>
                        <input type="number" id="productQuantity" class="form-control" value="1" min="1" max="100">
                    </div>

                    <button id="addToCartButton" class="btn btn-primary btn-add-to-cart mt-3">Thêm vào giỏ hàng</button>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div id="productDescription" class="product-description mt-4">
            <h4>Mô tả sản phẩm</h4>
            <p>Mô tả sản phẩm chi tiết sẽ được hiển thị tại đây.</p>
        </div>

        <!-- Related Products -->
        <div class="related-products">
            <h3>Sản phẩm cùng danh mục</h3>
            <div id="relatedProducts" class="row">
                <!-- Related products will be added dynamically here -->
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h3>Bình luận</h3>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="mt-3">
                    <div class="rating mb-2">
                        <span>Đánh giá:</span>
                        <select id="rating" class="form-select">
                            <option value="5">5 sao</option>
                            <option value="4">4 sao</option>
                            <option value="3">3 sao</option>
                            <option value="2">2 sao</option>
                            <option value="1">1 sao</option>
                        </select>
                    </div>
                    <textarea id="commentInput" class="form-control" rows="3"
                        placeholder="Viết bình luận của bạn..."></textarea>
                    <button id="postCommentButton" class="btn btn-primary mt-2">Gửi bình luận</button>
                </div>
            <?php else: ?>
                <p class="text-muted">Vui lòng <a href="/index.php?page=login">đăng nhập</a> để bình luận.</p>
            <?php endif; ?>
            <div id="commentList" class="mb-4 mt-4">
                <p>Đang tải bình luận...</p>
            </div>
        </div>
    </div>

    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productId = new URLSearchParams(window.location.search).get('id');
            const productQuantityInput = document.getElementById('productQuantity');
            const addToCartButton = document.getElementById('addToCartButton');
            const carouselImages = document.getElementById('carouselImages');
            const relatedProducts = document.getElementById('relatedProducts');
            const productDescription = document.getElementById('productDescription');
            const commentList = document.getElementById('commentList');
            const commentInput = document.getElementById('commentInput');
            const thumbnailGallery = document.getElementById('thumbnailGallery');
            const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
            const postCommentButton = document.getElementById('postCommentButton');
            const ratingSelect = document.getElementById('rating');
            const UseId = <?php echo isset($_SESSION['user']['id']) ? json_encode($_SESSION['user']['id']) : 'null'; ?>;

            // Fetch product details
            fetch(`/api/products/get_product_by_id?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error });
                    } else {
                        document.getElementById('productName').textContent = data.name;
                        document.getElementById('productCategory').innerHTML = `Danh mục: <span class="text-primary">${data.category_name}</span>`;
                        document.getElementById('productPrice').textContent = `${Number(data.price).toLocaleString()} VND`;
                        productDescription.querySelector('p').innerHTML = data.description || 'Không có mô tả';

                        // Hiển thị hình ảnh trong carousel
                        carouselImages.innerHTML = data.images.map((img, index) => `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${img.image_url}" class="d-block w-100">
                            </div>
                        `).join('');

                        thumbnailGallery.innerHTML = data.images.map((img, index) => `
                            <img src="${img.image_url}" class="${index === 0 ? 'active' : ''}" data-index="${index}">
                        `).join('');

                        // Add event for thumbnails
                        thumbnailGallery.querySelectorAll('img').forEach((thumbnail, index) => {
                            thumbnail.addEventListener('click', () => {
                                document.querySelector('.carousel-item.active').classList.remove('active');
                                document.querySelector('.thumbnail-gallery img.active').classList.remove('active');

                                carouselImages.children[index].classList.add('active');
                                thumbnail.classList.add('active');
                            });
                        });

                        // Sản phẩm cùng danh mục
                        fetch(`/api/category/get_category_detail?id=${data.category_id}`)
                            .then(res => res.json())
                            .then(categoryData => {
                                relatedProducts.innerHTML = categoryData.products.map(product => `
                                    <div class="col-md-3">
                                        <div class="product-card">
                                            <img src="${product.images || '/uploads/default.png'}" alt="${product.name}">
                                            <h5>${product.name}</h5>
                                            <p>${Number(product.price).toLocaleString()} VND</p>
                                            <a href="/?page=product&id=${product.id}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                                        </div>  
                                    </div>
                                `).join('');
                            });
                        document.querySelectorAll('.btn-add-to-cart').forEach(button => {
                            button.addEventListener('click', function () {
                                if (UseId == null) {
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
                                    addToCart(productId);
                                }
                            });
                        });
                    }
                });

            function addToCart(productId) {
                const quantity = parseInt(productQuantityInput.value, 10);
                if (quantity <= 0) {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Số lượng phải lớn hơn 0.' });
                    return;
                }

                fetch('/api/cart/add_to_cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId, quantity })
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({ icon: data.success ? 'success' : 'error', title: data.success ? 'Thành công' : 'Lỗi', text: data.message });
                    });
            }
            const loadComments = () => {
                fetch(`/api/review/get_reviews?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        commentList.innerHTML = data.success
                            ? data.reviews.map(comment => `
                                <div class="comment-box">
                                    <strong>${comment.user_name}</strong> </br>
                                    <small>Đánh giá: ${comment.rating} sao</small>
                                    <p>${comment.comment}</p>
                                    <small class="text-muted">${new Date(comment.created_at).toLocaleString()}</small>
                                </div>`).join('')
                            : '<p>Chưa có bình luận nào.</p>';
                    });
            };
            loadComments();

            postCommentButton.addEventListener('click', () => {
                const commentContent = commentInput.value.trim();
                const rating = ratingSelect.value;
                if (!commentContent) {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Vui lòng nhập nội dung bình luận.' });
                    return;
                }

                fetch('/api/review/add_review', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId, comment: commentContent, rating })
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({ icon: data.success ? 'success' : 'error', title: data.success ? 'Thành công' : 'Lỗi', text: data.error });
                        if (data.success) {
                            commentInput.value = '';
                            loadComments();
                        }
                    });
            });
        });
    </script>
</body>

</html>