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

        .product-image {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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

        .product-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-add-to-cart {
            padding: 10px 30px;
            font-size: 1.2rem;
        }

        .product-meta {
            font-size: 0.9rem;
            color: #888;
        }

        .comments-section {
            margin-top: 50px;
        }

        .comment-box {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
        }

        .rating {
            display: flex;
            align-items: center;
        }

        .rating span {
            margin-right: 10px;
        }

        .rating select {
            max-width: 80px;
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
            <!-- Product Image -->
            <div class="col-lg-6 text-center">
                <img id="productImage" src="/uploads/baby-toy.png" class="img-fluid product-image"
                    alt="Hình ảnh sản phẩm">
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <div class="product-details">
                    <h1 id="productName">Tên Sản Phẩm</h1>
                    <p id="productCategory" class="product-meta">Danh mục: <span class="text-primary">Đồ chơi trẻ
                            em</span></p>
                    <p id="productPrice" class="product-price">99,000 VND</p>

                    <!-- Input số lượng -->
                    <div class="mt-3">
                        <label for="productQuantity" class="form-label">Số lượng:</label>
                        <input type="number" id="productQuantity" class="form-control" value="1" min="1" max="100">
                    </div>

                    <button id="addToCartButton" class="btn btn-primary btn-add-to-cart mt-3">Thêm vào giỏ hàng</button>
                    <div id="productDescription" class="product-description mt-4">
                        Mô tả sản phẩm chi tiết sẽ được hiển thị tại đây.
                    </div>
                </div>
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
            const commentList = document.getElementById('commentList');
            const commentInput = document.getElementById('commentInput');
            const postCommentButton = document.getElementById('postCommentButton');
            const ratingSelect = document.getElementById('rating');

            // Fetch product details
            fetch(`/api/products/get_product_by_id?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.error })
                            .then(() => window.location.href = '/?page=home');
                    } else {
                        document.getElementById('productName').textContent = data.name;
                        document.getElementById('productCategory').innerHTML = `Danh mục: <span class="text-primary">${data.category_name}</span>`;
                        document.getElementById('productPrice').textContent = `${Number(data.price).toLocaleString()} VND`;
                        document.getElementById('productDescription').textContent = data.description || 'Không có mô tả';
                        document.getElementById('productImage').src = data.images.length > 0 ? data.images[0].image_url : '/uploads/baby-toy.png';
                    }
                });

            // Add to Cart
            addToCartButton.addEventListener('click', () => {
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
            });

            // Fetch comments
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

            // Post a new comment
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