<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class CategoryController
{
    private $categoryModel;
    private $productModel;

    public function __construct($pdo)
    {
        $this->categoryModel = new Category($pdo);
        $this->productModel = new Product($pdo);
    }

    public function createCategory($data)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($data['name'])) {
                throw new Exception('Category name is required.');
            }

            // Kiểm tra danh mục đã tồn tại
            $existingCategories = $this->categoryModel->getAll();
            foreach ($existingCategories as $category) {
                if ($category['name'] === $data['name']) {
                    throw new Exception('Category name already exists.');
                }
            }

            // Tạo danh mục mới
            $categoryId = $this->categoryModel->create(
                $data['name'],
                $data['description'] ?? ''
            );

            return [
                'success' => true,
                'message' => 'Category created successfully.',
                'category_id' => $categoryId,
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getCategoryById($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Category ID is required.');
            }

            $category = $this->categoryModel->getById($id);

            if (!$category) {
                throw new Exception('Category not found.');
            }

            return [
                'success' => true,
                'category' => $category
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getProductsById($id)
    {
        try {
            // Kiểm tra nếu ID danh mục không được cung cấp
            if (empty($id)) {
                throw new Exception('Category ID is required.');
            }

            // Kiểm tra danh mục tồn tại
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                throw new Exception('Category not found.');
            }

            // Lấy danh sách sản phẩm theo ID danh mục
            $products = $this->productModel->getByCategoryId($id);

            if (!$products || count($products) === 0) {
                throw new Exception('No products found for this category.');
            }

            return [
                'success' => true,
                'category' => $category,
                'products' => $products
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function updateCategory($id, $data)
    {
        try {
            if (empty($id)) {
                throw new Exception('Category ID is required.');
            }

            // Kiểm tra danh mục tồn tại
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                throw new Exception('Category not found.');
            }

            // Cập nhật danh mục
            $result = $this->categoryModel->update(
                $id,
                $data['name'] ?? $category['name'],
                $data['description'] ?? $category['description']
            );

            return [
                'success' => $result,
                'message' => $result ? 'Category updated successfully.' : 'Failed to update category.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteCategory($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Category ID is required.');
            }

            // Kiểm tra danh mục tồn tại
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                throw new Exception('Category not found.');
            }

            // Xóa danh mục
            $result = $this->categoryModel->delete($id);

            return [
                'success' => $result,
                'message' => $result ? 'Category deleted successfully.' : 'Failed to delete category.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->categoryModel->getAll();
            if (!$categories || count($categories) === 0) {
                throw new Exception('No categories found.');
            }

            return [
                'success' => true,
                'categories' => $categories
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}
