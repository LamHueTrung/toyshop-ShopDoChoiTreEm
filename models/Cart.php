<?php
// models/Cart.php
class Cart
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addItem($user_id, $product_id, $quantity)
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO carts (user_id, product_id, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + ?
    ");
        return $stmt->execute([$user_id, $product_id, $quantity, $quantity]);
    }


    // Lấy thông tin giỏ hàng theo ID
    public function getById($cart_id)
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT 
                c.id AS cart_id,
                c.user_id,
                c.quantity,
                p.id AS product_id,
                p.name AS product_name,
                p.price AS product_price,
                p.stock AS product_stock
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.id = ?
        ");
            $stmt->execute([$cart_id]);
            $cartItem = $stmt->fetch();

            if (!$cartItem) {
                throw new Exception("Cart item not found. Cart ID: $cart_id");
            }

            return $cartItem;
        } catch (PDOException $e) {
            // Log lỗi cơ sở dữ liệu
            error_log('Database Error: ' . $e->getMessage());
            return false;
        } 
    }


    public function getCartItem($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM carts
        WHERE user_id = ? AND product_id = ?
    ");
        $stmt->execute([$user_id, $product_id]);
        return $stmt->fetch();
    }


    // Lấy danh sách giỏ hàng theo user_id
    public function getByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.name AS product_name, p.price AS product_price, p.stock AS product_stock, p.image_url AS product_image
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Lấy giỏ hàng với thông tin chi tiết sản phẩm
    public function getCartWithProductDetails($userId)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            c.id AS cart_id,
            c.quantity,
            p.id AS product_id,
            p.name AS product_name,
            p.price AS product_price,
            pi.image_url AS product_image
        FROM carts c
        INNER JOIN products p ON c.product_id = p.id
        LEFT JOIN product_images pi ON pi.product_id = p.id
        WHERE c.user_id = ?
        GROUP BY c.id
    ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }


    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateItem($cart_id, $quantity)
    {
        $stmt = $this->pdo->prepare("UPDATE carts SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $cart_id]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeItem($cart_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM carts WHERE id = ?");
        return $stmt->execute([$cart_id]);
    }

    // Xóa toàn bộ giỏ hàng của user
    public function clearCart($user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM carts WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }
}
