<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderDetail.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController
{
    private $orderModel;
    private $orderDetailModel;
    private $productModel;
    private $cartModel;

    public function __construct($pdo)
    {
        $this->orderModel = new Order($pdo);
        $this->orderDetailModel = new OrderDetail($pdo);
        $this->productModel = new Product($pdo);
        $this->cartModel = new Cart($pdo); // Model giỏ hàng
    }

    /**
     * Checkout - Tạo đơn hàng từ giỏ hàng
     */
    public function createOrder($userId, $cartItems, $totalPrice)
    {
        try {
            if (empty($userId) || empty($cartItems)) {
                throw new Exception('User ID and cart items are required.');
            }

            // Create the order
            $orderId = $this->orderModel->create($userId, $totalPrice);

            if (!$orderId) {
                throw new Exception('Failed to create the order.');
            }

            // Add order details
            foreach ($cartItems as $item) {
                $this->orderDetailModel->addDetail($orderId, $item['product_id'], $item['quantity'], $item['product_price']);
                $this->cartModel->removeItem($item['cart_id']);
                // Update product stock
                $this->productModel->updateStock($item['product_id'], $item['quantity']);
            }

            return [
                'success' => true,
                'message' => 'Order created successfully.',
                'id_cart' => $cartItems,
                'order_id' => $orderId,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }


    /**
     * Lấy tất cả đơn hàng
     */
    public function getAllOrders()
    {
        try {
            $orders = $this->orderModel->getAll();

            foreach ($orders as &$order) {
                // Lấy thông tin chi tiết đơn hàng
                $order['details'] = $this->orderDetailModel->getByOrderId($order['id']);
                $order['status_label'] = $this->mapOrderStatus($order['status']);
            }

            return [
                'success' => true,
                'orders' => $orders,
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Lấy thông tin đơn hàng theo ID
     */
    public function getOrderById($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Order ID is required.');
            }

            $order = $this->orderModel->getFullOrderDetails($id);

            if (!$order) {
                throw new Exception('Order not found.');
            }

            // $order['details'] = $this->orderDetailModel->getByOrderId($id);

            return [
                'success' => true,
                'order' => $order,
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($id, $status)
    {
        try {
            if (empty($id)) {
                throw new Exception('Order ID is required.');
            }

            $validStatuses = ['pending', 'confirmed', 'in_transit', 'completed', 'cancelled'];
            if (!in_array($status, $validStatuses, true)) {
                throw new Exception('Invalid order status.');
            }

            $order = $this->orderModel->getById($id);

            if (!$order) {
                throw new Exception('Order not found.');
            }

            $result = $this->orderModel->update($id, $status);

            return [
                'success' => $result,
                'message' => $result ? 'Order status updated successfully.' : 'Failed to update order status.',
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Xóa đơn hàng
     */
    public function deleteOrder($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('Order ID is required.');
            }

            $order = $this->orderModel->getById($id);

            if (!$order) {
                throw new Exception('Order not found.');
            }

            // Xóa chi tiết đơn hàng
            $details = $this->orderDetailModel->getByOrderId($id);
            foreach ($details as $detail) {
                $this->orderDetailModel->delete($detail['id']);
            }

            // Xóa đơn hàng
            $result = $this->orderModel->delete($id);

            return [
                'success' => $result,
                'message' => $result ? 'Order deleted successfully.' : 'Failed to delete order.',
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Map trạng thái đơn hàng
     */
    private function mapOrderStatus($status)
    {
        $statusMap = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'in_transit' => 'Đang vận chuyển',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $statusMap[$status] ?? 'Không xác định';
    }
}
