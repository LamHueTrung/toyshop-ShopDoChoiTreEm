<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chi tiết đơn hàng</title>

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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include(__DIR__ . '/../partials/topbar.php'); ?>

                <!-- Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Chi tiết đơn hàng</h1>
                    <div id="orderDetails" class="card shadow mb-4">
                        <div class="card-body">
                            <p>Đang tải chi tiết đơn hàng...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
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
            const orderId = new URLSearchParams(window.location.search).get('id');
            const orderDetails = $('#orderDetails');

            if (!orderId) {
                orderDetails.html('<div class="card-body"><p class="text-danger">Không tìm thấy mã đơn hàng.</p></div>');
                return;
            }

            // Fetch order details
            fetch(`/api/orders/get_order_by_id?id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.order[0].order_id)
                        renderOrderDetails(data.order[0]);
                    } else {
                        orderDetails.html(`<div class="card-body"><p class="text-danger">${data.error || 'Không thể tải chi tiết đơn hàng.'}</p></div>`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    orderDetails.html('<div class="card-body"><p class="text-danger">Đã xảy ra lỗi khi tải chi tiết đơn hàng.</p></div>');
                });

            // Render order details
            // Render order details
            function renderOrderDetails(order) {
                let itemsHTML = `
        <tr>
            <td>${order.product_name}</td>
            <td>${order.quantity}</td>
            <td>${Number(order.product_price).toLocaleString()} VND</td>
            <td>${(order.quantity * order.product_price).toLocaleString()} VND</td>
        </tr>
    `;

                const orderStatusClass = getOrderStatusClass(order.status);

                orderDetails.html(`
        <div class="card-header">
            Đơn hàng #${order.order_id}
        </div>
        <div class="card-body">
            <h5 class="card-title">Thông tin khách hàng</h5>
            <p class="card-text">Họ và tên: ${order.user_name}</p>
            <p class="card-text">Email: ${order.user_email}</p>
            <p class="card-text">Số điện thoại: ${order.user_phone}</p>
            <hr>
            <h5 class="card-title">Địa chỉ giao hàng</h5>
            <p class="card-text">${order.user_address}</p>
            <hr>
            <h5 class="card-title">Sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML}
                </tbody>
            </table>
            <hr>
            <h5 class="card-title">Trạng thái</h5>
            <p class="card-text order-status-label ${orderStatusClass}">${mapOrderStatus(order.status)}</p>
            <hr>
            <h5 class="card-title">Tổng tiền</h5>
            <p class="text-success font-weight-bold">${Number(order.total_price).toLocaleString()} VND</p>
            <hr>
            <div class="d-flex justify-content-between">
                <button class="btn btn-success btn-update-status" data-status="completed">Đánh dấu hoàn thành</button>
                <button class="btn btn-danger btn-update-status" data-status="cancelled">Hủy đơn hàng</button>
            </div>
        </div>
    `);

                // Add event listeners for buttons
                $('.btn-update-status').on('click', function () {
                    const newStatus = $(this).data('status');
                    updateOrderStatus(order.order_id, newStatus);
                });
            }

            // Map order status to labels
            function mapOrderStatus(status) {
                const statusMap = {
                    pending: 'Chờ xử lý',
                    confirmed: 'Đã xác nhận',
                    in_transit: 'Đang giao',
                    completed: 'Hoàn thành',
                    cancelled: 'Đã hủy',
                };
                return statusMap[status] || 'Không xác định';
            }

            // Get CSS class for order status
            function getOrderStatusClass(status) {
                const classMap = {
                    pending: 'order-status-pending',
                    completed: 'order-status-completed',
                    cancelled: 'order-status-cancelled',
                };
                return classMap[status] || '';
            }

            // Update order status
            function updateOrderStatus(orderId, status) {
                fetch(`/api/orders/update_order`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: orderId, status }),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data.success) {
                            Swal.fire('Cập nhật trạng thái thành công!', '', 'success');
                            location.reload();
                        } else {
                            Swal.fire('Lỗi', 'Không thể cập nhật trạng thái.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order status:', error);
                        Swal.fire('Lỗi', 'Đã xảy ra lỗi khi cập nhật trạng thái!', 'error');
                    });
            }
        });
    </script>
</body>

</html>