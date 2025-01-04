<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/ProductImage.php';

class ProductController
{
    private $productModel;
    private $productImageModel;

    public function __construct($pdo)
    {
        $this->productModel = new Product($pdo);
        $this->productImageModel = new ProductImage($pdo);
    }

    public function createProduct($data, $files)
    {
        try {
            // Kiểm tra dữ liệu bắt buộc
            if (empty($data['name']) || empty($data['price']) || empty($data['stock']) || empty($data['category_id'])) {
                throw new Exception('Tên, giá, số lượng, và danh mục là bắt buộc.');
            }

            if (!is_numeric($data['price']) || $data['price'] <= 0) {
                throw new Exception('Giá sản phẩm không hợp lệ.');
            }

            if (!is_numeric($data['stock']) || $data['stock'] < 0) {
                throw new Exception('Số lượng sản phẩm không hợp lệ.');
            }

            // Tạo sản phẩm mới
            $productId = $this->productModel->create(
                $data['name'],
                $data['description'] ?? '',
                $data['price'],
                $data['stock'],
                $data['category_id']
            );

            // Xử lý hình ảnh (nếu có)
            if (!empty($files['images'])) {
                $this->uploadImages($productId, $files['images']);
            }

            return [
                'success' => true,
                'message' => 'Sản phẩm đã được tạo thành công.',
                'product_id' => $productId,
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function getProductById($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID sản phẩm là bắt buộc.');
            }

            $product = $this->productModel->getById($id);

            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm.');
            }

            // Lấy hình ảnh của sản phẩm
            $product['images'] = $this->productImageModel->getByProductId($id);

            return $product;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateProduct($id, $data, $files)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID sản phẩm là bắt buộc.');
            }

            // Kiểm tra sản phẩm tồn tại
            $product = $this->productModel->getById($id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm.');
            }

            // Kiểm tra và xác thực dữ liệu đầu vào
            if (!empty($data['price']) && (!is_numeric($data['price']) || $data['price'] <= 0)) {
                throw new Exception('Giá sản phẩm không hợp lệ.');
            }

            if (!empty($data['stock']) && (!is_numeric($data['stock']) || $data['stock'] < 0)) {
                throw new Exception('Số lượng sản phẩm không hợp lệ.');
            }

            // Cập nhật sản phẩm
            $result = $this->productModel->update(
                $id,
                $data['name'] ?? $product['name'],               // Trường `name`
                $data['description'] ?? $product['description'], // Trường `description`
                $data['price'] ?? $product['price'],             // Trường `price`
                $data['stock'] ?? $product['stock'],             // Trường `stock`
                $data['category_id'] ?? $product['category_id']  // Trường `category_id`
            );

            // Xử lý hình ảnh mới (nếu có)
            if (!empty($files['images']) && is_array($files['images']['tmp_name'])) {
                // Lấy danh sách hình ảnh cũ
                $oldImages = $this->productImageModel->getByProductId($id);

                // Xóa hình ảnh cũ khỏi hệ thống
                foreach ($oldImages as $image) {
                    if (file_exists($image['image_url'])) {
                        unlink($image['image_url']);
                    }
                    // Xóa thông tin ảnh khỏi database
                    $this->productImageModel->delete($image['id']);
                }

                // Tải lên hình ảnh mới
                $this->uploadImages($id, $files['images']);
            }

            return [
                'success' => $result,
                'message' => $result ? 'Cập nhật sản phẩm thành công.' : 'Cập nhật sản phẩm thất bại.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }



    public function deleteProduct($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID sản phẩm là bắt buộc.');
            }

            // Kiểm tra sản phẩm tồn tại
            $product = $this->productModel->getById($id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm.');
            }

            // Xóa hình ảnh liên quan
            $images = $this->productImageModel->getByProductId($id);
            foreach ($images as $image) {
                if (file_exists($image['image_url'])) {
                    unlink($image['image_url']);
                }
                $this->productImageModel->delete($image['id']);
            }

            // Xóa sản phẩm
            $result = $this->productModel->delete($id);

            return [
                'success' => $result,
                'message' => $result ? 'Sản phẩm đã được xóa thành công.' : 'Xóa sản phẩm thất bại.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getAllProducts()
    {
        try {
            // Lấy tất cả sản phẩm
            $products = $this->productModel->getAll();

            // Xử lý từng sản phẩm để thêm hình ảnh và danh mục
            foreach ($products as &$product) {
                // Lấy danh sách hình ảnh cho sản phẩm
                $images = $this->productImageModel->getByProductId($product['id']);
                $product['images'] = !empty($images) ? $images : [['image_url' => '/uploads/default-image.jpg']];

                // Lấy thông tin danh mục
                $category = $this->productModel->getCategoryById($product['category_id']);
                $product['category_name'] = $category ? $category['name'] : 'Không có danh mục';
            }

            return [
                'success' => true,
                'products' => $products,
            ];
        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }


    private function uploadImages($productId, $images)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadDir = __DIR__ . '/../public/uploads/products/';

        // Tạo thư mục upload nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($images['tmp_name'] as $key => $tmpName) {
            if ($images['error'][$key] === UPLOAD_ERR_OK) {
                $extension = pathinfo($images['name'][$key], PATHINFO_EXTENSION);
                if (!in_array(strtolower($extension), $allowedExtensions)) {
                    throw new Exception('Định dạng ảnh không hợp lệ: ' . $images['name'][$key]);
                }

                // Tạo tên file ngẫu nhiên
                $filename = uniqid() . '.' . $extension;
                $filePath = $uploadDir . $filename;

                // Di chuyển file vào thư mục uploads
                if (!move_uploaded_file($tmpName, $filePath)) {
                    throw new Exception('Không thể tải lên hình ảnh: ' . $images['name'][$key]);
                }

                // Lưu thông tin ảnh vào bảng `product_images`
                $this->productImageModel->create($productId, '/uploads/products/' . $filename);
            }
        }
    }

}
