# Shop Bán Đồ Chơi Trẻ Em

Shop Bán Đồ Chơi Trẻ Em là một dự án PHP thuần, xây dựng hệ thống website bán đồ chơi trẻ em với các tính năng như quản lý tài khoản, sản phẩm, giỏ hàng, đánh giá, và hệ thống quản trị.

---

## 🚀 **Tính năng**

### **Người dùng**
1. **Đăng ký (Register):**
   - Tạo tài khoản mới với thông tin như username, email, mật khẩu, số điện thoại, địa chỉ.
   - Kiểm tra tài khoản/email đã tồn tại.
   - Validate dữ liệu đầu vào.

2. **Đăng nhập (Login):**
   - Xác thực bằng username/email và mật khẩu.
   - Trả về token JWT khi đăng nhập thành công.

3. **Quản lý thông tin cá nhân (Profile):**
   - Xem và chỉnh sửa thông tin cá nhân.
   - Thay đổi mật khẩu.

4. **Giỏ hàng (Cart):**
   - Thêm sản phẩm vào giỏ hàng.
   - Xóa sản phẩm khỏi giỏ hàng.
   - Cập nhật số lượng sản phẩm trong giỏ.

5. **Đặt hàng (Orders):**
   - Đặt hàng từ giỏ hàng.
   - Xem danh sách đơn hàng.
   - Xem chi tiết đơn hàng.

6. **Đánh giá sản phẩm (Reviews):**
   - Thêm, xem, và xóa đánh giá sản phẩm.

---

### **Admin**
1. **Quản lý sản phẩm:**
   - Thêm, sửa, và xóa sản phẩm.
   - Quản lý hình ảnh sản phẩm.

2. **Quản lý danh mục sản phẩm:**
   - Thêm, sửa, và xóa danh mục.

3. **Quản lý đơn hàng:**
   - Xem danh sách đơn hàng.
   - Cập nhật trạng thái đơn hàng (Pending → Confirmed → In Transit → Completed → Cancelled).

4. **Quản lý người dùng:**
   - Xem danh sách người dùng.
   - Xóa người dùng.

---

## 🛠 **Công nghệ sử dụng**

1. **Ngôn ngữ lập trình:**
   - PHP thuần.

2. **Cơ sở dữ liệu:**
   - MySQL.

3. **Thư viện & Công cụ:**
   - `firebase/php-jwt`: Quản lý xác thực bằng JWT.
   - `vlucas/phpdotenv`: Quản lý biến môi trường.
   - `openssl_encrypt`: Mã hóa và giải mã mật khẩu (AES-256-CBC).

4. **Frontend:**
   - HTML, CSS, JavaScript.
   - Bootstrap (nếu sử dụng giao diện quản trị).

5. **Server:**
   - Apache (XAMPP hoặc LAMP).

---

## 📂 **Cấu trúc thư mục**

```plaintext
toy-shop/
├── api/
│   ├── routes.php             # Route API
├── config/
│   ├── database.php           # Cấu hình kết nối cơ sở dữ liệu
├── controllers/
│   ├── AuthController.php     # Controller xử lý đăng nhập, đăng ký
│   ├── UserController.php     # Controller quản lý người dùng
│   ├── ProductController.php  # Controller quản lý sản phẩm
│   ├── CategoryController.php # Controller quản lý danh mục
│   ├── OrderController.php    # Controller quản lý đơn hàng
│   ├── CartController.php     # Controller giỏ hàng
│   ├── ReviewController.php   # Controller đánh giá sản phẩm
├── helpers/
│   ├── EncryptionHelper.php   # Mã hóa/giải mã dữ liệu
├── models/
│   ├── User.php               # Model người dùng
│   ├── Product.php            # Model sản phẩm
│   ├── Category.php           # Model danh mục
│   ├── Order.php              # Model đơn hàng
│   ├── Cart.php               # Model giỏ hàng
│   ├── Review.php             # Model đánh giá
├── public/
│   ├── index.php              # Điểm khởi đầu ứng dụng
├── views/
│   ├── home.php               # Trang chủ
│   ├── login.php              # Trang đăng nhập
│   ├── register.php           # Trang đăng ký
│   ├── profile.php            # Trang thông tin cá nhân
│   ├── cart.php               # Trang giỏ hàng
│   ├── checkout.php           # Trang thanh toán
│   ├── product.php            # Trang chi tiết sản phẩm
│   ├── admin/
│   │   ├── dashboard.php      # Dashboard quản trị
│   │   ├── products/          # Quản lý sản phẩm
│   │   ├── categories/        # Quản lý danh mục
│   │   ├── orders/            # Quản lý đơn hàng
│   │   ├── users/             # Quản lý người dùng
├── .env                       # File cấu hình môi trường
├── composer.json              # Cấu hình Composer
└── README.md                  # Hướng dẫn dự án
```

---

## 🔧 **Cách cài đặt**

1. **Clone repository từ GitHub:**
   ```bash
   git clone <URL của repository>
   cd toy-shop
   ```

2. **Cài đặt thư viện bằng Composer:**
   ```bash
   composer install
   ```

3. **Cấu hình file `.env`:**
   - Copy file `.env.example` thành `.env`:
     ```bash
     cp .env.example .env
     ```
   - Cập nhật thông tin:
     ```plaintext
     DB_HOST=127.0.0.1
     DB_NAME=toy_shop
     DB_USER=root
     DB_PASSWORD=
     JWT_SECRET=your_secret_key
     ENCRYPTION_KEY=your_32_byte_key
     ENCRYPTION_IV=your_16_byte_iv
     ```

4. **Tạo cơ sở dữ liệu:**
   - Tạo database:
     ```sql
     CREATE DATABASE toy_shop;
     ```
   - Import file `database.sql`:
     ```bash
     mysql -u root -p toy_shop < database.sql
     ```

5. **Khởi động server PHP:**
   ```bash
   php -S localhost:8000 -t public
   ```

6. **Truy cập ứng dụng:**
   - **Frontend:** `http://localhost:8000`
   - **API:** `http://localhost:8000/api`

---

## 📋 **Danh sách route**

### **Frontend**
| URL                                | Mô tả                       |
|------------------------------------|-----------------------------|
| `/index.php?page=home`             | Trang chủ                  |
| `/index.php?page=product&id={id}`  | Trang chi tiết sản phẩm    |
| `/index.php?page=cart`             | Trang giỏ hàng             |
| `/index.php?page=checkout`         | Trang thanh toán           |
| `/index.php?page=profile`          | Trang thông tin cá nhân    |
| `/index.php?page=orders`           | Trang danh sách đơn hàng   |

### **API**

| URL                     | Phương thức | Mô tả                                    | Tham số (nếu có)               |
|-------------------------|-------------|------------------------------------------|---------------------------------|
| `/api/register`         | POST        | Đăng ký người dùng mới                  | `username`, `email`, `password`|
| `/api/login`            | POST        | Đăng nhập người dùng                    | `email`, `password`            |
| `/api/users/get_profile`| GET         | Lấy thông tin cá nhân                   | JWT Header                     |
| `/api/users/update`     | PUT         | Cập nhật thông tin cá nhân              | JWT Header, Body JSON          |
| `/api/products`         | GET         | Lấy danh sách sản phẩm                  | Query params (nếu có)          |
| `/api/products/{id}`    | GET         | Lấy chi tiết sản phẩm                   | `{id}`                         |
| `/api/cart/get_cart`    | GET         | Lấy giỏ hàng của người dùng             | JWT Header                     |
| `/api/cart/add_item`    | POST        | Thêm sản phẩm vào giỏ hàng              | Body JSON                      |
| `/api/cart/update_item` | PUT         | Cập nhật số lượng sản phẩm trong giỏ    | Body JSON                      |
| `/api/cart/remove_item` | DELETE      | Xóa sản phẩm khỏi giỏ hàng              | Body JSON                      |
| `/api/orders`           | GET         | Lấy danh sách đơn hàng của người dùng   | JWT Header                     |
| `/api/orders/{id}`      | GET         | Lấy chi tiết đơn hàng                   | `{id}`                         |
| `/api/orders/create`    | POST        | Tạo đơn hàng mới                        | Body JSON                      |

---

## 🛡 **Bảo mật**
- Mật khẩu được mã hóa bằng AES-256-CBC với khóa và IV từ file `.env`.
- API xác thực bằng JWT.

---

## 👨‍💻 **Tác giả**
- **Họ tên:** Lâm Huệ Trung
- **Email:** lamhuetrung@gmail.com 
- **GitHub:** https://github.com/LamHueTrung

---

Nếu bạn cần thêm chi tiết hoặc hỗ trợ, hãy liên hệ tôi qua email hoặc GitHub! 🚀
