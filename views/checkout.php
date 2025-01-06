<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .checkout-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }

        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<?php session_start(); ?>

<body>
    <header>
        <?php include('partials/header.php'); ?>
    </header>

    <div class="container checkout-container">
        <h1 class="mb-4">Xác nhận thanh toán</h1>

        <!-- Cart Summary -->
        <div id="cartSummary">
            <p>Đang tải thông tin giỏ hàng...</p>
        </div>

        <!-- Shipping Information Form -->
        <div class="form-section mt-4">
            <h4>Thông tin giao hàng</h4>
            <form id="checkoutForm">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Họ và tên</label>
                    <input type="text" id="fullName" class="form-control"
                        value="<?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <textarea id="address" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
                    <select id="paymentMethod" class="form-select">
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        <option value="e_wallet">Ví điện tử</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
            </form>
        </div>
    </div>

    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = '<?php echo $_SESSION["user"]["id"] ?? ""; ?>';
        const cartSummary = document.getElementById('cartSummary');
        const checkoutForm = document.getElementById('checkoutForm');
        let cartItems = []; // Danh sách sản phẩm trong giỏ hàng
        let totalPrice = 0; // Tổng tiền

        // Tải thông tin giỏ hàng
        function loadCartSummary() {
            fetch(`/api/cart/get_cart?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.cart_items.length > 0) {
                        cartItems = data.cart_items; // Lưu danh sách sản phẩm
                        totalPrice = data.cart_items.reduce((sum, item) => sum + (item.product_price * item.quantity), 0); // Tính tổng tiền
                        renderCartSummary(cartItems, totalPrice);
                    } else {
                        cartSummary.innerHTML = '<p>Giỏ hàng của bạn đang trống.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading cart summary:', error);
                    cartSummary.innerHTML = '<p>Không thể tải thông tin giỏ hàng. Vui lòng thử lại sau.</p>';
                });
        }

        // Hiển thị thông tin giỏ hàng
        function renderCartSummary(items, totalPrice) {
            let summaryHTML = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            items.forEach(item => {
                const itemTotal = item.product_price * item.quantity;
                summaryHTML += `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>${Number(item.product_price).toLocaleString()} VND</td>
                        <td>${Number(itemTotal).toLocaleString()} VND</td>
                    </tr>
                `;
            });

            summaryHTML += `
                    </tbody>
                </table>
                <div class="text-end total-price">Tổng tiền: ${Number(totalPrice).toLocaleString()} VND</div>
            `;

            cartSummary.innerHTML = summaryHTML;
        }

        // Xử lý gửi form thanh toán
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value.trim();
            const address = document.getElementById('address').value.trim();
            const paymentMethod = document.getElementById('paymentMethod').value;

            if (!fullName || !address) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Vui lòng điền đầy đủ thông tin giao hàng.',
                });
                return;
            }

            if (!totalPrice || isNaN(totalPrice)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Tổng tiền không hợp lệ.',
                });
                return;
            }

            fetch('/api/orders/create_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_id: userId,
                    full_name: fullName,
                    address: address,
                    payment_method: paymentMethod,
                    cart_items: cartItems,
                    total_price: totalPrice // Gửi tổng tiền trong yêu cầu
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đặt hàng thành công!',
                            text: 'Cảm ơn bạn đã mua hàng. Chúng tôi sẽ liên hệ sớm nhất.',
                        }).then(() => {
                            window.location.href = '/';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.error || 'Không thể thực hiện đặt hàng.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error during checkout:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại sau.',
                    });
                });
        });

        // Gọi hàm tải giỏ hàng khi trang được tải
        loadCartSummary();
    });
</script>

</body>

</html>