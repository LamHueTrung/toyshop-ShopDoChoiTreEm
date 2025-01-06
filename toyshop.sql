-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 06, 2025 lúc 02:19 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `toyshop`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddProduct` (IN `p_name` VARCHAR(255), IN `p_description` TEXT, IN `p_price` DECIMAL(10,2), IN `p_stock` INT, IN `p_category_id` INT, OUT `new_id` INT)   BEGIN
    -- Chèn sản phẩm mới vào bảng
    INSERT INTO products (name, description, price, stock, category_id)
    VALUES (p_name, p_description, p_price, p_stock, p_category_id);

    -- Lấy ID của bản ghi vừa thêm
    SET new_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteProduct` (IN `productId` INT)   BEGIN
    -- Xóa hình ảnh liên kết với sản phẩm
    DELETE FROM product_images WHERE product_id = productId;

    -- Xóa sản phẩm
    DELETE FROM products WHERE id = productId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllProducts` ()   BEGIN
    SELECT * FROM products;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductById` (IN `product_id` INT)   BEGIN
    SELECT 
        p.id,
        p.name,
        p.description,
        p.price,
        p.stock,
        p.category_id,
        c.name AS category_name
    FROM 
        products p
    LEFT JOIN 
        categories c ON p.category_id = c.id
    WHERE 
        p.id = product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateProduct` (IN `p_id` INT, IN `p_name` VARCHAR(255), IN `p_description` TEXT, IN `p_price` DECIMAL(10,2), IN `p_stock` INT, IN `p_category_id` INT)   BEGIN
    UPDATE products
    SET
        name = p_name,
        description = p_description,
        price = p_price,
        stock = p_stock,
        category_id = p_category_id,
        updated_at = NOW()
    WHERE id = p_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(3, 'Đồ chơi sơ sinh', 'con nít mới biết bú', '2025-01-05 14:10:16'),
(4, 'BÚP BÊ GIẤY', 'HỌC LIỆU BÓC DÁN MONTESSORI', '2025-01-06 04:44:23'),
(5, 'Đồ chơi trang điểm, phụ kiện cho bé', 'Với những bộ trang sức nhẫn,lắc, kẹp tóc, kính mát…nhiều mẫu mã đẹp kết hợp màu sắc rất tinh tế. Kèm theo những bộ phụ kiện được làm thủ công bằng tay rất khéo léo và tỉ mỉ mà rất ít cửa hàng hiện nay sở hữu, các mẹ hãy nhanh tay ghé shop để tha hồ lựa cho các bé iu của mình nha.', '2025-01-06 04:46:50'),
(6, 'SÁCH VẢI GIÁO DỤC', '', '2025-01-06 04:49:38'),
(7, 'Đồ chơi lắp ráp', 'Shop bán đồ chơi lắp ráp với nhiều loại như, đồ chơi lắp ráp siêu nhân, đồ chơi lắp ráp lego, đồ chơi lắp ráp mô hình, đồ chơi lắp ráp bằng gỗ. Giúp trẻ phát huy trí sáng tạo, thông minh và rèn luyện khéo léo thông qua việc lắp ráp những mô hình đồ chơi.', '2025-01-06 04:52:14'),
(8, 'Đồ chơi gỗ', 'Shop đồ chơi gỗ thông minh dành cho bé với nhiều đồ chơi bằng gỗ của các thương hiệu nổi tiếng việt nam như: đồ chơi gỗ winwintoys, đồ chơi gỗ Đức thành, đồ chơi gỗ mother garden, đồ chơi gỗ veesano.', '2025-01-06 04:54:19'),
(9, 'Đồ chơi giáo dục', 'Các loại đồ chơi giáo dục, đồ chơi phát triển trí thông minh cho trẻ, chất lượng đảm bảo an toàn. Đồ chơi giáo dục, đồ chơi thông minh giúp trẻ phát triển các kĩ năng', '2025-01-06 04:54:53'),
(10, 'Đồ chơi tô màu, tô tượng', 'Đồ chơi tô màu, bột vẽ là đồ chơi giáo dục tốt, giúp bé thỏa sức sáng tạo và thể hiện năng khiếu thẩm mỹ của mình thông qua việc tô màu, vẽ tranh', '2025-01-06 04:55:05'),
(11, 'Cát động lực - Tranh cát', 'Các loại tranh cát và cát động lực dành cho bé. Tô màu tranh cát với những bức tranh doraemon, hello kitty, elsa, công chúa, hoàng tử… rất nhiều những bức tranh cát đẹp dành cho bé tô màu. Cát động lực các loại nhưng làm lâu đài, làm bánh kem, làm động lực. Đồ chơi an toàn đảm bảo cho sức khoẻ của bé, giúp quý phụ huynh yên tâm.', '2025-01-06 04:55:29'),
(12, 'ĐỒ CHƠI VẬN ĐỘNG', 'Các mẫu đồ chơi trong nhà, ngoài trời giúp bé vận động thỏa thích chơi đùa nhằm nâng cao sức khỏe và khả năng sáng tạo của bé.', '2025-01-06 04:55:45'),
(13, 'Đồ chơi khác', 'Đồ chơi trẻ em mới nhất, an toàn cho trẻ, giá cả phải chăng, giao hàng thu tiền tận nơi trên toàn quốc.', '2025-01-06 04:56:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','in_transit','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(10, 5, 144000.00, 'pending', '2025-01-06 12:59:14', '2025-01-06 12:59:14'),
(11, 5, 144000.00, 'pending', '2025-01-06 13:00:24', '2025-01-06 13:00:24'),
(12, 5, 144000.00, 'pending', '2025-01-06 13:00:51', '2025-01-06 13:00:51'),
(13, 5, 359000.00, 'cancelled', '2025-01-06 13:01:23', '2025-01-06 13:18:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(8, 10, 25, 1, 144000.00),
(9, 11, 25, 1, 144000.00),
(10, 12, 25, 1, 144000.00),
(11, 13, 26, 1, 60000.00),
(12, 13, 25, 1, 144000.00),
(13, 13, 27, 1, 155000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `updated_at`, `category_id`, `created_at`) VALUES
(25, 'Bộ tranh cát màu thế giới thú cưng K-503', '<h2 style=\"box-sizing: border-box; font-family: Roboto, sans-serif; font-weight: 200; line-height: 1.1; color: #484747; margin: 20px 0px; font-size: 20px; text-align: center;\">Bộ tranh c&aacute;t m&agrave;u thế giới th&uacute; cưng K-503</h2>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; user-select: text !important;\">Sản phẩm được l&agrave;m từ chất liệu an to&agrave;n cho trẻ nhỏ, n&ecirc;n qu&yacute; phụ huynh ho&agrave;n to&agrave;n c&oacute; thể y&ecirc;n t&acirc;m sẽ kh&ocirc;ng g&acirc;y ảnh hưởng đến sức khỏe của b&eacute;.</p>\r\n<blockquote style=\"box-sizing: border-box; padding: 0px 0px 0px 50px; margin: 0px 0px 20px; font-size: 17.5px; border: 0px; position: relative; font-style: italic; line-height: 1.6; color: #323232; font-family: \'Open Sans\', sans-serif; text-align: center;\">\r\n<p style=\"box-sizing: border-box; margin: 0px; user-select: text !important;\">Qu&yacute; kh&aacute;ch c&oacute; thể đến trực tiếp mua h&agrave;ng tại&nbsp;<a style=\"box-sizing: border-box; color: #a161bf; text-decoration-line: none; transition: 0.3s;\" href=\"https://dochoitreem.com/\">shop đồ chơi trẻ em&nbsp;</a>&nbsp;<span style=\"box-sizing: border-box; font-weight: bold;\">29/5 L&ecirc; Đức Thọ, Phường 7, Q.G&ograve; Vấp, TP. Hồ Ch&iacute; Minh</span>&nbsp;hoặc li&ecirc;n hệ số<span style=\"box-sizing: border-box; user-select: text !important; color: #ff0000;\">&nbsp;<span style=\"box-sizing: border-box; font-weight: bold;\">HOTLINE 0979 08 6789.</span></span></p>\r\n<figure id=\"attachment_39957\" class=\"thumbnail wp-caption aligncenter\" style=\"margin-top: 0px; margin-bottom: 1.5em; box-sizing: border-box; display: block; clear: both; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 682px;\"><img class=\"size-large wp-image-39957\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2021/07/Bo-tranh-cat-mau-the-gioi-thu-cung-K-503-1-672x640.jpg\" sizes=\"(max-width: 672px) 100vw, 672px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2021/07/Bo-tranh-cat-mau-the-gioi-thu-cung-K-503-1-672x640.jpg 672w, https://dochoitreem.com/wp-content/uploads/2021/07/Bo-tranh-cat-mau-the-gioi-thu-cung-K-503-1-227x216.jpg 227w, https://dochoitreem.com/wp-content/uploads/2021/07/Bo-tranh-cat-mau-the-gioi-thu-cung-K-503-1-250x238.jpg 250w, https://dochoitreem.com/wp-content/uploads/2021/07/Bo-tranh-cat-mau-the-gioi-thu-cung-K-503-1.jpg 700w\" alt=\"Bộ tranh c&aacute;t m&agrave;u thế giới th&uacute; cưng\" width=\"672\" height=\"640\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Bộ tranh c&aacute;t m&agrave;u thế giới th&uacute; cưng</figcaption>\r\n</figure>\r\n</blockquote>', 144000.00, 51, '2025-01-06 13:57:52', 3, '2025-01-06 04:58:59'),
(26, 'Sách tô màu ma thuật và cây bút thần kì SACH', '<h3 style=\"box-sizing: border-box; font-family: \'Open Sans Condensed\'; line-height: 1.1; color: #323232; margin: 20px 0px; font-size: 20px; text-align: center;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; SACH</h3>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; user-select: text !important;\">ản phẩm được l&agrave;m từ chất liệu&nbsp;an to&agrave;n kh&ocirc;ng độc hại n&ecirc;n c&aacute;c bậc phụ huynh c&oacute; thể y&ecirc;n t&acirc;m cho b&eacute; vui chơi m&agrave; kh&ocirc;ng lo ảnh hưởng đến sức khỏe.</p>\r\n<blockquote style=\"box-sizing: border-box; padding: 0px 0px 0px 50px; margin: 0px 0px 20px; font-size: 17.5px; border: 0px; position: relative; font-style: italic; line-height: 1.6; color: #323232; font-family: \'Open Sans\', sans-serif; text-align: center;\">\r\n<p style=\"box-sizing: border-box; margin: 0px; user-select: text !important;\">Đặt h&agrave;ng nhanh nhất qua&nbsp;<span style=\"box-sizing: border-box; user-select: text !important; color: #ff0000;\"><span style=\"box-sizing: border-box; font-weight: bold;\">HOTLINE 0979.08.6789</span></span>&nbsp;hoặc đến mua trực tiếp tại cửa h&agrave;ng&nbsp;<a style=\"box-sizing: border-box; color: #a161bf; text-decoration-line: none; transition: 0.3s;\" href=\"https://dochoitreem.com/\">đồ chơi&nbsp;</a>trẻ em&nbsp;<span style=\"box-sizing: border-box; font-weight: bold;\">369 Phan Đ&igrave;nh Ph&ugrave;ng, P.15, Q.Ph&uacute; Nhuận, TP.HCM</span></p>\r\n<figure id=\"attachment_37607\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37607\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave;\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave;</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37601\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37601\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-cho-cuu-ho.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-cho-cuu-ho.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-cho-cuu-ho-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-cho-cuu-ho-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-cho-cuu-ho-250x188.jpg 250w\" alt=\"Đồ chơi s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh ch&oacute; cứu hộ\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh ch&oacute; cứu hộ</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37602\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37602\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-xe-hoi.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-xe-hoi.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-xe-hoi-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-xe-hoi-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-sach-to-mau-ma-thuat-hinh-xe-hoi-250x188.jpg 250w\" alt=\"Đồ chơi s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh xe McQueen\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh xe McQueen</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37603\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37603\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-chuot-mickey.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-chuot-mickey.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-chuot-mickey-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-chuot-mickey-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-chuot-mickey-250x188.jpg 250w\" alt=\"Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh chuột Mickey\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh chuột Mickey</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37604\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37604\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-cong-chua-Sofia.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-cong-chua-Sofia.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-cong-chua-Sofia-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-cong-chua-Sofia-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-cong-chua-Sofia-250x188.jpg 250w\" alt=\"Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh c&ocirc;ng ch&uacute;a Sofia\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh c&ocirc;ng ch&uacute;a Sofia</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37605\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37605\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-Meo-Hello-Kitty.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-Meo-Hello-Kitty.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-Meo-Hello-Kitty-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-Meo-Hello-Kitty-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-Meo-Hello-Kitty-250x188.jpg 250w\" alt=\"Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh m&egrave;o Hello Kitty\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh m&egrave;o Hello Kitty</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37606\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37606\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-nang-tien-ca.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-nang-tien-ca.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-nang-tien-ca-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-nang-tien-ca-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/do-choi-tre-em-sach-to-mau-ma-thuat-hinh-nang-tien-ca-250x188.jpg 250w\" alt=\"Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh n&agrave;ng Ti&ecirc;n C&aacute;\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">Đồ chơi trẻ em s&aacute;ch t&ocirc; m&agrave;u ma thuật h&igrave;nh n&agrave;ng Ti&ecirc;n C&aacute;</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37608\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37608\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-cong-chua-bach-tuyet.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-cong-chua-bach-tuyet.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-cong-chua-bach-tuyet-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-cong-chua-bach-tuyet-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-cong-chua-bach-tuyet-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh c&ocirc;ng ch&uacute;a Bạch Tuyết\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh c&ocirc;ng ch&uacute;a Bạch Tuyết</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37609\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37609\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-cong-chua-Elsa.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-cong-chua-Elsa.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-cong-chua-Elsa-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-cong-chua-Elsa-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-cong-chua-Elsa-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh c&ocirc;ng ch&uacute;a Elsa\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh c&ocirc;ng ch&uacute;a Elsa</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37610\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37610\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-heo-peppa-pig.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-heo-peppa-pig.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-heo-peppa-pig-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-heo-peppa-pig-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-heo-peppa-pig-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh gia đ&igrave;nh heo Peppa Pig\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh gia đ&igrave;nh heo Peppa Pig</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37611\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37611\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-khung-long.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-khung-long.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-khung-long-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-khung-long-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-khung-long-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh khủng long\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh khủng long</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37612\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37612\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-ngua-pony.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-ngua-pony.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-ngua-pony-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-ngua-pony-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-ngua-pony-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh ngựa Pony\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh ngựa Pony</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37613\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37613\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-nguoi-nhen.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-nguoi-nhen.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-nguoi-nhen-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-nguoi-nhen-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-nguoi-nhen-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh người nhện\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh người nhện</figcaption>\r\n</figure>\r\n<figure id=\"attachment_37614\" class=\"thumbnail wp-caption alignnone\" style=\"margin: 0px 0px 1.5em; box-sizing: border-box; display: block; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; width: 810px;\"><img class=\"size-full wp-image-37614\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-thu-bien.jpg\" sizes=\"(max-width: 800px) 100vw, 800px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-thu-bien.jpg 800w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-thu-bien-288x216.jpg 288w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-thu-bien-768x576.jpg 768w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-va-cay-but-than-ki-hinh-thu-bien-250x188.jpg 250w\" alt=\"S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh th&uacute; biển đại dương\" width=\"800\" height=\"600\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">S&aacute;ch t&ocirc; m&agrave;u ma thuật v&agrave; c&acirc;y b&uacute;t thần k&igrave; h&igrave;nh th&uacute; biển đại dương</figcaption>\r\n</figure>\r\n<p style=\"box-sizing: border-box; margin: 0px; user-select: text !important;\"><img class=\"size-full wp-image-32603 aligncenter\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; clear: both; display: block; margin: 0px auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-SACH-1.jpg\" sizes=\"(max-width: 350px) 100vw, 350px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-SACH-1.jpg 350w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-SACH-1-216x216.jpg 216w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-SACH-1-230x230.jpg 230w, https://dochoitreem.com/wp-content/uploads/2019/05/sach-to-mau-ma-thuat-SACH-1-250x250.jpg 250w\" alt=\"\" width=\"350\" height=\"350\" loading=\"lazy\" /></p>\r\n</blockquote>', 60000.00, 79, '2025-01-06 12:59:00', 3, '2025-01-06 05:00:13'),
(27, 'Ghép hình thay quần áo cho gia đình Gấu Mèo Thỏ MWZ-201342', '<p style=\"box-sizing: border-box; margin: 0px 0px 15px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\"><span style=\"box-sizing: border-box; font-weight: bold;\">Gh&eacute;p h&igrave;nh thay quần &aacute;o cho gia đ&igrave;nh gấu m&egrave;o thỏ cho b&eacute; l&agrave; bộ đồ chơi gh&eacute;p h&igrave;nh gi&uacute;p b&eacute; vừa chơi vừa r&egrave;n tr&iacute; th&ocirc;ng minh. Với bộ đồ chơi gh&eacute;p h&igrave;nh b&eacute; c&oacute; thể ph&acirc;n biệt được m&agrave;u sắc, h&igrave;nh dạng đồ vật. Bộ đồ chơi gh&eacute;p thay đổi trang phục theo c&aacute;c chủ đề kh&aacute;c nhau.</span></p>\r\n<figure id=\"attachment_39345\" class=\"thumbnail wp-caption aligncenter\" style=\"margin-top: 0px; margin-bottom: 1.5em; box-sizing: border-box; display: block; clear: both; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; width: 1034px;\"><img class=\"size-full wp-image-39345\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3.jpg\" sizes=\"(max-width: 1024px) 100vw, 1024px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3.jpg 1024w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3-216x216.jpg 216w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3-768x768.jpg 768w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3-640x640.jpg 640w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3-230x230.jpg 230w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-3-250x250.jpg 250w\" alt=\"gh&eacute;p h&igrave;nh gia đ&igrave;nh gấu\" width=\"1024\" height=\"1024\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">gh&eacute;p h&igrave;nh gia đ&igrave;nh gấu</figcaption>\r\n</figure>\r\n<h3 style=\"box-sizing: border-box; font-family: \'Open Sans Condensed\'; line-height: 1.1; color: #323232; margin: 20px 0px; font-size: 20px;\">Giới thiệu bộ đồ chơi gh&eacute;p h&igrave;nh:</h3>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">Xuất xứ: Trung Quốc</p>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">K&iacute;ch thước: 14.8 x 30 x 4.6 (cm)</p>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">Chất liệu: Giấy cứng</p>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">Th&iacute;ch hợp cho b&eacute; từ 3 tuổi trở l&ecirc;n</p>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">Bộ gh&eacute;p h&igrave;nh với nhiều phần nhỏ kh&aacute;c nhau, c&oacute; 60 miếng gh&eacute;p thay đổi trang phục cho 5 chủ đề kh&aacute;c nhau. B&eacute; c&oacute; thể gh&eacute;p c&aacute;c bộ trang phục kh&aacute;c nhau để tạo n&ecirc;n nhiều bộ trang phục lạ mắt cho gia đ&igrave;nh gấu.</p>\r\n<figure id=\"attachment_39344\" class=\"thumbnail wp-caption aligncenter\" style=\"margin-top: 0px; margin-bottom: 1.5em; box-sizing: border-box; display: block; clear: both; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; width: 1034px;\"><img class=\"wp-image-39344 size-full\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2.jpg\" sizes=\"(max-width: 1024px) 100vw, 1024px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2.jpg 1024w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2-216x216.jpg 216w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2-768x768.jpg 768w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2-640x640.jpg 640w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2-230x230.jpg 230w, https://dochoitreem.com/wp-content/uploads/2021/05/bo-go-2-250x250.jpg 250w\" alt=\"gh&eacute;p h&igrave;nh nam ch&acirc;m thay trang phục gia đ&igrave;nh gấu\" width=\"1024\" height=\"1024\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">gh&eacute;p h&igrave;nh nam ch&acirc;m thay trang phục gia đ&igrave;nh gấu</figcaption>\r\n</figure>\r\n<figure id=\"attachment_39346\" class=\"thumbnail wp-caption aligncenter\" style=\"margin-top: 0px; margin-bottom: 1.5em; box-sizing: border-box; display: block; clear: both; padding: 4px; line-height: 1.42857; background-color: #ffffff; border: 1px solid #dddddd; border-radius: 4px; transition: border 0.2s ease-in-out; max-width: 100%; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; width: 873px;\"><img class=\"wp-image-39346 size-full\" style=\"box-sizing: border-box; height: auto; max-width: 100%; border: 0px; vertical-align: middle; display: block; margin-right: auto; margin-left: auto;\" src=\"https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1.jpg\" sizes=\"(max-width: 863px) 100vw, 863px\" srcset=\"https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1.jpg 863w, https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1-218x216.jpg 218w, https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1-768x762.jpg 768w, https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1-645x640.jpg 645w, https://dochoitreem.com/wp-content/uploads/2021/05/Untitled-1-250x248.jpg 250w\" alt=\"gh&eacute;p h&igrave;nh nam ch&acirc;m thay trang phục gia đ&igrave;nh gấu, thỏ\" width=\"863\" height=\"856\" loading=\"lazy\" />\r\n<figcaption class=\"caption wp-caption-text\" style=\"box-sizing: border-box; padding: 9px; color: #696969; margin: 0.8075em 0px;\">gh&eacute;p h&igrave;nh nam ch&acirc;m thay trang phục gia đ&igrave;nh gấu, thỏ</figcaption>\r\n</figure>\r\n<h3 style=\"box-sizing: border-box; font-family: \'Open Sans Condensed\'; line-height: 1.1; color: #323232; margin: 20px 0px; font-size: 20px;\">Lợi &iacute;ch khi chơi bộ gh&eacute;p h&igrave;nh:</h3>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; user-select: text !important;\">Giúp bé nhận biết trang phục các nghề cơ bản xung quanh<br style=\"box-sizing: border-box;\" />Giúp bé rèn luyện khả năng ghi nhớ, rèn luyện óc sáng tạo và trí tưởng tượng<br style=\"box-sizing: border-box;\" />Giúp bé rèn luyện khả năng ghi nhớ và sự khéo léo, cẩn thận, sự tưởng tượng<br style=\"box-sizing: border-box;\" />Giúp bé luyện vận động tinh.<br style=\"box-sizing: border-box;\" />Gắn kết các thành viên trong gia đình khi ba mẹ hướng dẫn bé chơi.</p>\r\n<p style=\"box-sizing: border-box; margin: 15px 0px; color: #323232; font-family: \'Open Sans\', sans-serif; font-size: 14px; text-align: center; user-select: text !important;\"><em style=\"box-sizing: border-box;\">C&aacute;c mẹ c&oacute; thể an t&acirc;m rằng sản ph&acirc;̉m đã được ki&ecirc;̉m định ch&acirc;́t lượng tuy&ecirc;̣t đ&ocirc;́i an toàn cho các bé y&ecirc;u, h&atilde;y nhanh tay đặt mua để th&ecirc;m v&agrave;o bộ sưu tập đồ chơi của b&eacute; nh&eacute;. H&atilde;y c&ugrave;ng dochoitrem.com d&agrave;nh cho con y&ecirc;u những g&igrave; tốt đẹp nhất.</em></p>', 155000.00, 24, '2025-01-06 12:59:18', 3, '2025-01-06 05:01:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `uploaded_at`) VALUES
(87, 25, '/uploads/products/677b7ef01e55e.jpg', '2025-01-06 06:57:52'),
(88, 25, '/uploads/products/677b7ef0209e2.jpg', '2025-01-06 06:57:52'),
(89, 25, '/uploads/products/677b7ef02288a.jpg', '2025-01-06 06:57:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(5, 5, 25, 5, 'hi', '2025-01-06 11:59:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fullname`, `phone`, `address`, `profile_picture`, `role`, `created_at`) VALUES
(3, 'admin', '4c5OcvuUzCi0JJ+7xsG3pQ==', 'admin@example.com', 'Administrator', NULL, NULL, NULL, 'admin', '2025-01-04 02:47:41'),
(5, 'LamHueTrung', '6EZnPhq+MH44NltxDrVedw==', 'lamhuetrung@gmail.com', 'Lâm Huệ Trung', '0763849007', 'Trà Vinh', '', 'user', '2025-01-04 16:39:05'),
(21, 'test', 'Xjs/tSln+Bnax5K+1hSGsg==', 'test@8mail.pro', 'aa', '0763849007', 'Tiểu Cần', '/uploads/profile_pictures/6779b014b5df5-avt.jpg', 'user', '2025-01-04 22:03:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
