<?php
// models/OrderDetail.php
class OrderDetail
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addDetail($order_id, $product_id, $quantity, $price)
    {
        $stmt = $this->pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }

    public function getByOrderId($order_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM order_details WHERE order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM order_details WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
