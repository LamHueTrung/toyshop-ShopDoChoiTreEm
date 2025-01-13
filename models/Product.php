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
        // Câu lệnh SQL INSERT trực tiếp
        $stmt = $this->pdo->prepare(
            "INSERT INTO products (name, description, price, stock, category_id) 
         VALUES (:name, :description, :price, :stock, :category_id)"
        );

        // Thực hiện chèn dữ liệu
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':stock' => $stock,
            ':category_id' => $category_id
        ]);

        // Trả về ID sản phẩm vừa được tạo
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

    public function getByCategoryId($categoryId)
    {
        try {
            if (empty($categoryId)) {
                throw new Exception('Category ID is required.');
            }

            // Truy vấn lấy sản phẩm và hình ảnh liên quan
            $stmt = $this->pdo->prepare("
            SELECT 
                p.id,
                p.name,
                p.price,
                p.stock,
                p.category_id,
                pi.image_url
            FROM products p
            LEFT JOIN product_images pi ON p.id = pi.product_id
            WHERE p.category_id = :category_id
        ");
            $stmt->execute(['category_id' => $categoryId]);

            // Nhóm các sản phẩm và hình ảnh
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $products = [];
            foreach ($rows as $row) {
                $productId = $row['id'];
                if (!isset($products[$productId])) {
                    $products[$productId] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'price' => $row['price'],
                        'stock' => $row['stock'],
                        'category_id' => $row['category_id'],
                        'images' => []
                    ];
                }
                if ($row['image_url']) {
                    $products[$productId]['images'][] = $row['image_url'];
                }
            }

            return array_values($products); // Trả về danh sách sản phẩm
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function searchByKeyword($keyword)
    {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword1 OR description LIKE :keyword2";
        $stmt = $this->pdo->prepare($sql);

        // Gắn giá trị cho cả hai tham số
        $stmt->execute([
            'keyword1' => '%' . $keyword . '%',
            'keyword2' => '%' . $keyword . '%',
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
