<?php

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController
{
    private $cartModel;
    private $productModel;

    public function __construct($pdo)
    {
        $this->cartModel = new Cart($pdo);
        $this->productModel = new Product($pdo);
    }

    public function addToCart($userId, $data)
    {
        try {
            if (empty($userId) || empty($data['product_id']) || empty($data['quantity'])) {
                throw new Exception('User ID, product ID, and quantity are required.');
            }

            if (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
                throw new Exception('Quantity must be a positive number.');
            }

            // Kiểm tra sản phẩm tồn tại
            $product = $this->productModel->getById($data['product_id']);
            if (!$product) {
                throw new Exception('Product not found.');
            }

            if ($product['stock'] < $data['quantity']) {
                throw new Exception('Insufficient stock for product: ' . $product['name']);
            }

            // Kiểm tra sản phẩm trong giỏ hàng
            $existingCartItem = $this->cartModel->getCartItem($userId, $data['product_id']);
            if ($existingCartItem) {
                // Cập nhật số lượng nếu đã tồn tại
                $newQuantity = $existingCartItem['quantity'] + $data['quantity'];
                $result = $this->cartModel->updateItem($existingCartItem['id'], $newQuantity);
            } else {
                // Thêm mới sản phẩm vào giỏ hàng
                $result = $this->cartModel->addItem($userId, $data['product_id'], $data['quantity']);
            }

            return [
                'success' => $result,
                'message' => $result ? 'Product added to cart successfully.' : 'Failed to add product to cart.',
            ];
        } catch (Exception $e) {
            error_log("Add to cart error: " . $e->getMessage()); // Ghi nhật ký lỗi
            return ['error' => $e->getMessage()];
        }
    }


    public function updateCartItem($userId, $cartId, $quantity)
    {
        try {
            if (empty($userId) || empty($cartId) || empty($quantity)) {
                throw new Exception('User ID, cart ID, and quantity are required.');
            }

            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception('Quantity must be a positive number.');
            }

            $cartItem = $this->cartModel->getById($cartId);
            if (!$cartItem || $cartItem['user_id'] !== $userId) {
                throw new Exception('Cart item not found or unauthorized access.');
            }

            $product = $this->productModel->getById($cartItem['product_id']);
            if (!$product) {
                throw new Exception('Product not found.');
            }

            if ($product['stock'] < $quantity) {
                throw new Exception('Insufficient stock for product: ' . $product['name']);
            }

            $result = $this->cartModel->updateItem($cartId, $quantity);

            return [
                'success' => $result,
                'message' => $result ? 'Cart item updated successfully.' : 'Failed to update cart item.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function removeCartItem($userId, $cartId)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($userId)) {
                throw new Exception('User ID is required.');
            }
            if (empty($cartId)) {
                throw new Exception('Cart ID is required.');
            }

            // Lấy thông tin giỏ hàng
            $cartItem = $this->cartModel->getById($cartId);
            if (!$cartItem) {
                throw new Exception('Cart item not found.');
            }

            // Kiểm tra quyền sở hữu giỏ hàng
            if ((int) $cartItem['user_id'] !== (int) $userId) {
                throw new Exception('Unauthorized access to this cart item.');
            }

            // Xóa sản phẩm khỏi giỏ hàng
            $result = $this->cartModel->removeItem($cartId);
            if (!$result) {
                throw new Exception('Failed to remove cart item.');
            }

            return [
                'success' => true,
                'message' => 'Cart item removed successfully.',
            ];
        } catch (PDOException $e) {
            // Ghi log nếu có lỗi cơ sở dữ liệu
            error_log('Database Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'A database error occurred. Please try again later.',
            ];
        } catch (Exception $e) {
            // Xử lý các lỗi khác
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function clearCart($userId)
    {
        try {
            if (empty($userId)) {
                throw new Exception('User ID is required.');
            }

            $result = $this->cartModel->clearCart($userId);

            return [
                'success' => $result,
                'message' => $result ? 'Cart cleared successfully.' : 'Failed to clear cart.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getCart($userId)
    {
        try {
            if (empty($userId)) {
                throw new Exception('User ID is required.');
            }

            $cartItems = $this->cartModel->getCartWithProductDetails($userId);
            return [
                'success' => true,
                'cart_items' => $cartItems
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
