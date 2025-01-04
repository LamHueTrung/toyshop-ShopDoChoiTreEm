<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .cart-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .cart-table th,
        .cart-table td {
            vertical-align: middle;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<?php session_start(); ?>

<body>
    <header>
        <?php include('partials/header.php'); ?>
    </header>

    <div class="container cart-container">
        <h1 class="mb-4">Giỏ hàng của bạn</h1>
        <div id="cartContent">
            <p>Đang tải giỏ hàng...</p>
        </div>
        <div class="cart-actions mt-4">
            <a href="/" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
            <button id="checkoutButton" class="btn btn-success">Thanh toán</button>
        </div>
    </div>

    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartContent = document.getElementById('cartContent');
            const checkoutButton = document.getElementById('checkoutButton');
            const userId = '<?php echo $_SESSION["user"]["id"] ?? ""; ?>';

            // Fetch cart data
            function loadCart() {
                fetch(`/api/cart/get_cart?user_id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.cart_items.length > 0) {
                            renderCart(data.cart_items);
                        } else {
                            cartContent.innerHTML = '<p>Giỏ hàng của bạn đang trống.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cart:', error);
                        cartContent.innerHTML = '<p>Không thể tải giỏ hàng. Vui lòng thử lại sau.</p>';
                    });
            }

            // Render cart items
            function renderCart(items) {
                let cartHTML = `
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                let totalPrice = 0;

                items.forEach(item => {
                    const itemTotal = item.product_price * item.quantity;
                    totalPrice += itemTotal;

                    cartHTML += `
                        <tr>
                            <td>
                                <img src="${item.product_image || '/uploads/baby-toy.png'}" alt="${item.product_name}" style="width: 80px; height: 80px;">
                            </td>
                            <td>${item.product_name}</td>
                            <td>${Number(item.product_price).toLocaleString()} VND</td>
                            <td>
                                <input type="number" class="form-control quantity-input" data-id="${item.cart_id}" value="${item.quantity}" min="1">
                            </td>
                            <td>${Number(itemTotal).toLocaleString()} VND</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-btn" data-id="${item.cart_id}">Xóa</button>
                            </td>
                        </tr>
                    `;
                });

                cartHTML += `</tbody></table>
                    <div class="text-end total-price">Tổng tiền: ${Number(totalPrice).toLocaleString()} VND</div>`;

                cartContent.innerHTML = cartHTML;
                attachEventListeners();
            }

            // Attach event listeners
            function attachEventListeners() {
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const cartId = this.dataset.id;
                        const quantity = parseInt(this.value, 10);
                        updateCartItem(cartId, quantity);
                    });
                });

                document.querySelectorAll('.remove-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const cartId = this.dataset.id;
                        removeCartItem(cartId);
                    });
                });

                checkoutButton.addEventListener('click', checkoutCart);
            }

            // Update cart item
            function updateCartItem(cartId, quantity) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tính đăng chưa phát triển',
                    text: 'Quay lại sau.',
                });
            }

            // Remove cart item
            function removeCartItem(cartId) {
                fetch('/api/cart/delete_cart_item', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart_id: cartId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadCart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Xóa thành công',
                                text: 'Sản phẩm đã được xóa khỏi giỏ hàng.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.error || 'Không thể xóa sản phẩm khỏi giỏ hàng.',
                            });
                        }
                    });
            }

            // Checkout cart
            function checkoutCart() {
                window.location.href = `?page=checkout&user_id=${userId}`;
            }

            loadCart();
        });
    </script>
</body>

</html>