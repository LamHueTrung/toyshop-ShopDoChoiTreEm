<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .profile-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .profile-info {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            text-align: center;
            padding: 20px;
        }

        .details-section,
        .orders-section,
        .links-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .order-status {
            font-weight: bold;
            text-transform: capitalize;
        }

        .order-status.completed {
            color: #28a745;
        }

        .order-status.processing {
            color: #ffc107;
        }

        .order-status.cancelled {
            color: #dc3545;
        }

        .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php session_start(); ?>
    <header>
        <?php include('partials/header.php'); ?>
    </header>

    <div class="container profile-container">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4">
                <div id="profileCard" class="card profile-card">
                    <button id="editProfileButton" class="btn btn-primary mt-3 w-100">Cập nhật thông tin</button>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="col-md-8">
                <!-- User Details -->
                <div id="detailsSection" class="details-section">
                    <h5>Thông tin cá nhân</h5>
                    <form id="updateForm">
                        <input type="text" id="fullName" class="form-control" placeholder="Họ và tên">
                        <input type="email" id="email" class="form-control" placeholder="Email">
                        <input type="text" id="phone" class="form-control" placeholder="Số điện thoại">
                        <textarea id="address" class="form-control" placeholder="Địa chỉ"></textarea>
                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                    </form>
                </div>

                <!-- Recent Orders -->
                <div id="ordersSection" class="orders-section">
                    <h5>Đơn hàng gần đây</h5>
                    <p>Đang tải thông tin...</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include('partials/footer.php'); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userId = '<?php echo $_SESSION["user"]["id"] ?? ""; ?>';
            const profileCard = document.getElementById('profileCard');
            const detailsSection = document.getElementById('detailsSection');
            const ordersSection = document.getElementById('ordersSection');
            const updateForm = document.getElementById('updateForm');

            // Fetch user information
            function loadUserInfo() {
                fetch(`/api/users/get_user_by_id?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderUserInfo(data.user);
                        } else {
                            profileCard.innerHTML = '<p>Không thể tải thông tin người dùng.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user info:', error);
                        profileCard.innerHTML = '<p>Đã xảy ra lỗi khi tải thông tin người dùng.</p>';
                    });
            }

            // Render user information
            function renderUserInfo(user) {
                profileCard.innerHTML = `
                    <img src="${user.profile_picture || 'default-profile.png'}" alt="Profile Picture">
                    <div class="profile-info">
                        <h5>${user.fullname}</h5>
                        <p>Email: ${user.email}</p>
                        <p>Phone: ${user.phone}</p>
                    </div>
                `;

                document.getElementById('fullName').value = user.fullname || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('phone').value = user.phone || '';
                document.getElementById('address').value = user.address || '';
            }

            // Fetch recent orders
            function loadRecentOrders() {
                fetch(`/api/orders/get_user_orders?user_id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderRecentOrders(data.orders);
                        } else {
                            ordersSection.innerHTML = '<p>Không thể tải thông tin đơn hàng.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching recent orders:', error);
                        ordersSection.innerHTML = '<p>Đã xảy ra lỗi khi tải thông tin đơn hàng.</p>';
                    });
            }

            // Render recent orders
            function renderRecentOrders(orders) {
                if (!orders || orders.length === 0) {
                    ordersSection.innerHTML = '<p>Bạn chưa có đơn hàng nào.</p>';
                    return;
                }

                let ordersHTML = '<ul class="list-group">';
                orders.forEach(order => {
                    ordersHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Đơn hàng #${order.id} - ${Number(order.total_price).toLocaleString()} VND
                        </li>
                    `;
                });
                ordersHTML += '</ul>';

                ordersSection.innerHTML = ordersHTML;
            }

            // Update user information
            updateForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const updatedData = {
                    fullname: document.getElementById('fullName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    address: document.getElementById('address').value,
                };

                fetch(`/api/users/update_user/${userId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updatedData),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cập nhật thành công!');
                            loadUserInfo();
                        } else {
                            alert(`Lỗi: ${data.error || 'Không thể cập nhật thông tin.'}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating user info:', error);
                        alert('Đã xảy ra lỗi khi cập nhật thông tin.');
                    });
            });

            // Load data
            loadUserInfo();
            loadRecentOrders();
        });
    </script>
</body>

</html>
