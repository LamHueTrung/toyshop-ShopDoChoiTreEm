<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danh sách đơn hàng</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include(__DIR__ . '/../partials/topbar.php'); ?>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="orderTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Người dùng</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-table-body">
                                        <!-- Dữ liệu từ API sẽ được đổ vào đây -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include(__DIR__ . '/../partials/footer.php'); ?>
            <?php include(__DIR__ . '\..\partials\modal.php'); ?>

        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Gọi API để lấy danh sách đơn hàng
            fetch('/api/orders/get_orders')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const orders = data.orders; // Lấy danh sách đơn hàng từ API
                        const tableBody = $('#order-table-body');
                        tableBody.empty(); // Xóa dữ liệu cũ (nếu có)

                        // Lặp qua danh sách đơn hàng và thêm vào bảng
                        orders.forEach(order => {
                            const row = `
                                <tr>
                                    <td>${order.id}</td>
                                    <td>${order.user_id || 'N/A'}</td>
                                    <td>${order.total_price.toLocaleString()} VND</td>
                                    <td>${order.status}</td>
                                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                                    <td>${order.updated_at ? new Date(order.updated_at).toLocaleDateString() : 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm btn-view" data-id="${order.id}">Xem</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="${order.id}">Xoá</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });

                        // Thêm sự kiện Xem chi tiết
                        $('.btn-view').on('click', function () {
                            const orderId = $(this).data('id');
                            viewOrder(orderId);
                        });

                        // Thêm sự kiện Xóa
                        $('.btn-delete').on('click', function () {
                            const orderId = $(this).data('id');
                            if (confirm('Bạn có chắc muốn xóa đơn hàng này?')) {
                                deleteOrder(orderId);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể tải danh sách đơn hàng',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching orders:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Đã xảy ra lỗi khi tải danh sách đơn hàng',
                    });
                });

            // Hàm xem chi tiết đơn hàng
            function viewOrder(orderId) {
                window.location.href = `/?page=admin-order-details&id=${orderId}`;
            }

            // Hàm xóa đơn hàng
            function deleteOrder(orderId) {
                fetch(`/api/orders/delete_order?id=${orderId}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Đơn hàng đã được xóa',
                            });
                            location.reload(); // Tải lại trang sau khi xóa thành công
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể xóa đơn hàng',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting order:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Đã xảy ra lỗi khi xóa đơn hàng',
                        });
                    });
            }
        });
    </script>
</body>

</html>
