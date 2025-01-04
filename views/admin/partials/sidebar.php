<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="?page=admin-users">
            <i class="fas fa-fw fa-cog"></i>
            <span>Quản lý người dùng</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDanhMuc" aria-expanded="true"
            aria-controls="collapseDanhMuc">
            <i class="fas fa-fw fa-cog"></i>
            <span>Danh mục sản phẩm</span>
        </a>
        <div id="collapseDanhMuc" class="collapse" aria-labelledby="headingDanhMuc" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="?page=admin-category-create">Thêm</a>
                <a class="collapse-item" href="?page=admin-categories">Danh sách</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSanPham" aria-expanded="true"
            aria-controls="collapseSanPham">
            <i class="fas fa-fw fa-cog"></i>
            <span>Sản phẩm</span>
        </a>
        <div id="collapseSanPham" class="collapse" aria-labelledby="headingSanPham" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="?page=admin-product-create">Thêm</a>
                <a class="collapse-item" href="?page=admin-products">Danh sách</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="?page=admin-orders" >
            <i class="fas fa-fw fa-cog"></i>
            <span>Quản lý đơn hàng</span>
        </a>
      
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>