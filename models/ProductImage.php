<?php
class ProductImage
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($product_id, $image_url)
    {
        $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
        $stmt->execute([$product_id, $image_url]);
        return $this->pdo->lastInsertId();
    }

    public function getByProductId($product_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product_images WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM product_images WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
