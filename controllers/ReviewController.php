<?php

require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class ReviewController
{
    private $reviewModel;
    private $productModel;
    private $userModel;

    public function __construct($pdo)
    {
        $this->reviewModel = new Review($pdo);
        $this->productModel = new Product($pdo);
        $this->userModel = new User($pdo);
    }

    public function addReview($userId, $data)
    {
        try {
            if (empty($userId) || empty($data['product_id']) || empty($data['rating'])) {
                throw new Exception('User ID, product ID, and rating are required.');
            }

            if (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
                throw new Exception('Rating must be a number between 1 and 5.');
            }

            $product = $this->productModel->getById($data['product_id']);
            if (!$product) {
                throw new Exception('Product not found.');
            }

            $result = $this->reviewModel->addReview(
                $userId,
                $data['product_id'],
                $data['rating'],
                $data['comment'] ?? ''
            );

            return [
                'success' => $result,
                'message' => $result ? 'Review added successfully.' : 'Failed to add review.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getReviewsByProductId($productId)
    {
        try {
            if (empty($productId)) {
                throw new Exception('Product ID is required.');
            }

            $product = $this->productModel->getById($productId);
            if (!$product) {
                throw new Exception('Product not found.');
            }

            $reviews = $this->reviewModel->getByProductId($productId);

            foreach ($reviews as &$review) {
                $user = $this->userModel->getById($review['user_id']);
                if ($user) {
                    $review['user_name'] = $user['username'];
                }
            }

            return [
                'success' => true,
                'reviews' => $reviews,
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateReview($userId, $reviewId, $data)
    {
        try {
            if (empty($userId) || empty($reviewId)) {
                throw new Exception('User ID and review ID are required.');
            }

            $review = $this->reviewModel->getById($reviewId);
            if (!$review || $review['user_id'] != $userId) {
                throw new Exception('Review not found or access denied.');
            }

            if (!empty($data['rating']) && (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5)) {
                throw new Exception('Rating must be a number between 1 and 5.');
            }

            $result = $this->reviewModel->updateReview(
                $reviewId,
                $data['rating'] ?? $review['rating'],
                $data['comment'] ?? $review['comment']
            );

            return [
                'success' => $result,
                'message' => $result ? 'Review updated successfully.' : 'Failed to update review.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteReview($userId, $reviewId)
    {
        try {
            if (empty($userId) || empty($reviewId)) {
                throw new Exception('User ID and review ID are required.');
            }

            $review = $this->reviewModel->getById($reviewId);
            if (!$review || $review['user_id'] != $userId) {
                throw new Exception('Review not found or access denied.');
            }

            $result = $this->reviewModel->delete($reviewId);

            return [
                'success' => $result,
                'message' => $result ? 'Review deleted successfully.' : 'Failed to delete review.',
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
