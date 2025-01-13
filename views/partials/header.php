<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
  <div class="container-fluid">
    <!-- Brand -->
    <a class="navbar-brand fw-bold text-primary" href="#">QuanQuach Shop</a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active text-uppercase fw-semibold" aria-current="page" href="?page=home">Trang chủ</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-uppercase fw-semibold" href="#" id="productDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            Danh mục sản phẩm
          </a>
          <ul class="dropdown-menu" aria-labelledby="productDropdown">
            <li><a class="dropdown-item" href="#" onclick="showComingSoonAlert()">Sản phẩm mới</a></li>
            <li><a class="dropdown-item" href="#" onclick="showComingSoonAlert()">Sản phẩm bán chạy</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" onclick="showComingSoonAlert()">Khuyến mãi</a></li>
          </ul>
          <script>
            function showComingSoonAlert() {
              Swal.fire({
                icon: 'info',
                title: 'Thông báo',
                text: 'Tính năng này hiện chưa phát triển. Vui lòng quay lại sau!',
                confirmButtonText: 'OK'
              });
            }
          </script>

        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase fw-semibold" href="?page=cart">Giỏ hàng</a>
        </li>
      </ul>

      <!-- Right Links -->
      <form class="d-flex align-items-center me-2" id="searchForm">
        <input class="form-control me-2 rounded-pill" type="search" placeholder="Tìm kiếm sản phẩm..."
          aria-label="Search">
        <!-- <button class="btn btn-primary rounded-pill px-3" type="submit">Tìm kiếm</button> -->
      </form>
      <div class="d-flex">
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'user'): ?>
          <span class="me-3 align-self-center">Xin chào,
            <strong><a href="?page=profile"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></a> </span>
          <form id="logoutForm">
            <button class="btn btn-outline-danger rounded-pill px-3" type="submit">Đăng xuất</button>
          </form>
        <?php else: ?>
          <a class="btn btn-outline-primary rounded-pill px-3 me-2" href="/index.php?page=login">Đăng nhập</a>
          <a class="btn btn-primary rounded-pill px-3" href="/index.php?page=register">Đăng ký</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>
<script>
  document.getElementById('searchForm').addEventListener('submit', function (event) {
    event.preventDefault();
    fetch(`/api/products/get_all_products?q=${this.querySelector('input').value}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
          sessionStorage.setItem('products', JSON.stringify(data.products));
            window.location.href = '/?page=search';
        }
      })
      .catch(error => console.error('Lỗi:', error));
  });
</script>
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
            title: 'Đăng xuất thành công',
            text: data.message,
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            window.location.href = '/?page=home';
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

<style>
  .navbar {
    position: sticky;
    top: 0;
    z-index: 1040;
    background-color: #f8f9fa !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
</style>

<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/slide1.webp" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="img/slide2.webp" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<style>
  .navbar {
    background-color: #f8f9fa !important;
  }

  .navbar-brand {
    font-size: 1.5rem;
  }

  .nav-link {
    transition: color 0.2s;
  }

  .nav-link:hover {
    color: #0d6efd;
  }

  .btn {
    font-weight: 500;
  }

  .carousel {
    margin-top: 20px;
  }

  .carousel-inner img {
    max-height: 500px;
    object-fit: cover;
  }

  .footer a {
    text-decoration: none;
    transition: color 0.2s;
  }

  .footer a:hover {
    color: #0d6efd;
  }

  .bi {
    font-size: 1.2rem;
  }
</style>