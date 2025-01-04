<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bạn muốn đăng xuất?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ</button>
                <form id="logoutForm">
                    <button class="btn btn-primary" type="submit">Đăng xuất</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Chi tiết người dùng</h5>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="detail-id"></span></p>
                    <p><strong>Tài khoản:</strong> <span id="detail-username"></span></p>
                    <p><strong>Mật khẩu:</strong> <span id="detail-password"></span></p>
                    <p><strong>Email:</strong> <span id="detail-email"></span></p>
                    <p><strong>Họ tên:</strong> <span id="detail-fullname"></span></p>
                    <p><strong>Số điện thoại:</strong> <span id="detail-phone"></span></p>
                    <p><strong>Địa chỉ:</strong> <span id="detail-address"></span></p>
                    <p><strong>Quyền:</strong> <span id="detail-role"></span></p>
                    <p><strong>Ngày tạo:</strong> <span id="detail-created-at"></span></p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>

                </div>
            </div>
        </div>
    </div>
<script>
    document.getElementById('logoutForm').addEventListener('submit', function (event) {
        event.preventDefault();
        fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(() => {
                        window.location.href = '/?page=login';
                    });
                } else {
                    console.error('Lỗi:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi hệ thống',
                        text: 'Đã xảy ra lỗi. Vui lòng thử lại sau.',
                    });
                }
            })
            .catch(error => console.error('Lỗi:', error));
    });
</script>