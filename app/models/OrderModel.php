<?php
class OrderModel {
    private $conn;
    private $table_name = "orders";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($userId, $name, $phone, $address, $items) {
        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO " . $this->table_name . " (user_id, name, phone, address, status, payment_method, payment_status) VALUES (:user_id, :name, :phone, :address, 'Đang xử lý', 'COD', 'Chưa thanh toán')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->execute();
            $orderId = $this->conn->lastInsertId();

            foreach ($items as $item) {
                // Bridge between the object and array
                $productId = isset($item['id']) ? $item['id'] : $item->id;
                $quantity = isset($item['quantity']) ? $item['quantity'] : $item->quantity;
                $price = isset($item['price']) ? $item['price'] : $item->price;

                $queryDetail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                $stmtDetail = $this->conn->prepare($queryDetail);
                $stmtDetail->bindParam(':order_id', $orderId);
                $stmtDetail->bindParam(':product_id', $productId);
                $stmtDetail->bindParam(':quantity', $quantity);
                $stmtDetail->bindParam(':price', $price);
                $stmtDetail->execute();
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getOrders($userId = null) {
        if ($userId) {
            $query = "SELECT o.*, 
                             (SELECT SUM(od.quantity * od.price) FROM order_details od WHERE od.order_id = o.id) as total_amount
                      FROM " . $this->table_name . " o 
                      WHERE o.user_id = :user_id 
                      ORDER BY o.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
        } else {
            $query = "SELECT o.*, a.username as account_name,
                             (SELECT SUM(od.quantity * od.price) FROM order_details od WHERE od.order_id = o.id) as total_amount
                      FROM " . $this->table_name . " o 
                      LEFT JOIN account a ON o.user_id = a.id
                      ORDER BY o.created_at DESC";
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderById($id) {
        $query = "SELECT o.*, 
                         (SELECT SUM(od.quantity * od.price) FROM order_details od WHERE od.order_id = o.id) as total_amount
                  FROM " . $this->table_name . " o 
                  WHERE o.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getOrderDetails($orderId) {
        $query = "SELECT od.*, p.name as product_name, p.image as product_image
                  FROM order_details od
                  LEFT JOIN product p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function cancelOrder($orderId) {
        $query = "UPDATE " . $this->table_name . " SET status = 'Đã hủy' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }

    public function updateOrderStatus($orderId, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }

    public function updatePaymentStatus($orderId, $paymentStatus, $paymentMethod) {
        $query = "UPDATE " . $this->table_name . " SET payment_status = :payment_status, payment_method = :payment_method WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':payment_status', $paymentStatus);
        $stmt->bindParam(':payment_method', $paymentMethod);
        $stmt->bindParam(':id', $orderId);
        return $stmt->execute();
    }
}
?>
