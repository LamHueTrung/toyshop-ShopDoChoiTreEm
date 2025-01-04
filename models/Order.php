<?php
// models/Order.php
class Order
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($user_id, $total_price, $status = 'pending')
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $total_price, $status]);
        return $this->pdo->lastInsertId();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM orders");
        return $stmt->fetchAll();
    }

    public function getFullOrderDetails($orderId)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            o.id AS order_id,
            o.user_id,
            o.total_price,
            o.status,
            o.created_at,
            o.updated_at,
            od.product_id,
            od.quantity,
            od.price AS order_detail_price,
            p.name AS product_name,
            p.description AS product_description,
            p.price AS product_price,
            u.fullname AS user_name,
            u.email AS user_email,
            u.phone AS user_phone,
            u.address AS user_address
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN products p ON od.product_id = p.id
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

}