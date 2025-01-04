<?php
// models/Review.php
class Review
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addReview($user_id, $product_id, $rating, $comment)
    {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $rating, $comment]);
    }

    public function getByProductId($product_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReview($id, $rating, $comment)
    {
        $stmt = $this->pdo->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$rating, $comment, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
