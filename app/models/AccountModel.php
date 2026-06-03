<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user') {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }

    // --- CÁC PHƯƠNG THỨC MỚI ĐỂ PHỤC VỤ QUẢN TRỊ ---

    // Lấy thông tin tài khoản theo ID
    public function getAccountById($id) {
        $query = "SELECT id, username, fullname, role FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lấy danh sách tất cả tài khoản
    public function getAccounts() {
        $query = "SELECT id, username, fullname, role FROM " . $this->table_name . " ORDER BY role ASC, username ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Cập nhật quyền người dùng
    public function updateAccountRole($id, $role) {
        if ($role !== 'admin' && $role !== 'user') {
            return false;
        }
        $query = "UPDATE " . $this->table_name . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Xóa tài khoản người dùng
    public function deleteAccount($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kiểm tra đăng nhập tĩnh
    public static function isLoggedIn() {
        return isset($_SESSION['username']);
    }

    // Kiểm tra quyền Admin tĩnh
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
?>