<div class="row g-4" id="product-list">
    <p>Đang tải sản phẩm...</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productList = document.getElementById('product-list');
        const apiUrl = '/api/products/get_all_products';

        // Gọi API để lấy danh sách sản phẩm
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.products.length > 0) {
                    productList.innerHTML = ''; // Xóa nội dung cũ
                    data.products.forEach((product, index) => {
                        const imageUrl = product.images.length > 0 ? product.images[0].image_url : '/uploads/baby-toy.png';
                        const animationDelay = 0.3 + (index * 0.2); // Tính thời gian trễ
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
                            const productId = this.getAttribute('data-id');
                            addToCart(productId);
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
