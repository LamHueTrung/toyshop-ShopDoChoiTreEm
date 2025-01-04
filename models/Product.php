<?php
// models/Product.php
class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($name, $description, $price, $stock, $category_id)
    {
        $stmt = $this->pdo->prepare("CALL AddProduct(?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $category_id]);
        return $this->pdo->lastInsertId();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("CALL GetProductById(?)");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $description, $price, $stock, $category_id)
    {
        $stmt = $this->pdo->prepare("CALL UpdateProduct(?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$id, $name, $description, $price, $stock, $category_id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("CALL DeleteProduct(?)");
        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("CALL GetAllProducts()");
        return $stmt->fetchAll();
    }

    public function getCategoryById($category_id)
    {
        try {
            // Truy vấn danh mục theo ID
            $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            throw new Exception("Không thể lấy thông tin danh mục: " . $e->getMessage());
        }
    }

    public function updateStock($productId, $quantity)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$quantity, $productId, $quantity]);
    }
}
