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

    public function getAccountByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user', $email = null, $verificationToken = null) {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role, email=:email, verification_token=:verification_token";
        $stmt = $this->conn->prepare($query);
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));
        $email = $email ? htmlspecialchars(strip_tags($email)) : null;
        $verificationToken = $verificationToken ? htmlspecialchars(strip_tags($verificationToken)) : null;

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":verification_token", $verificationToken);
        return $stmt->execute();
    }

    // --- CÁC PHƯƠNG THỨC MỚI ĐỂ PHỤC VỤ QUẢN TRỊ ---

    // Lấy thông tin tài khoản theo ID
    public function getAccountById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lấy danh sách tất cả tài khoản
    public function getAccounts() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY role ASC, username ASC";
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

    // --- NEW METHODS FOR ADVANCED REQUIREMENTS ---

    // Cập nhật hồ sơ cá nhân
    public function updateProfile($id, $fullName, $email, $avatar = null) {
        if ($avatar) {
            $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, email=:email, avatar=:avatar WHERE id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, email=:email WHERE id=:id";
        }
        $stmt = $this->conn->prepare($query);
        $fullName = htmlspecialchars(strip_tags($fullName));
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':email', $email);
        if ($avatar) {
            $avatar = htmlspecialchars(strip_tags($avatar));
            $stmt->bindParam(':avatar', $avatar);
        }
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Đổi mật khẩu
    public function updatePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table_name . " SET password=:password WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Xác thực tài khoản qua email
    public function verifyEmail($token) {
        $query = "UPDATE " . $this->table_name . " SET is_verified=1, verification_token=NULL WHERE verification_token=:token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Đặt token nhớ mật khẩu
    public function setRememberToken($username, $token) {
        $query = "UPDATE " . $this->table_name . " SET remember_token=:token WHERE username=:username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':username', $username);
        return $stmt->execute();
    }

    // Đặt token đặt lại mật khẩu
    public function setResetToken($usernameOrEmail, $token) {
        $query = "UPDATE " . $this->table_name . " SET reset_token=:token WHERE username=:input OR email=:input";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':input', $usernameOrEmail);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Lấy tài khoản theo reset token
    public function getAccountByResetToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reset_token = :token LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Xóa reset token
    public function clearResetToken($id) {
        $query = "UPDATE " . $this->table_name . " SET reset_token=NULL WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Khóa/Mở khóa tài khoản
    public function toggleLock($id) {
        $user = $this->getAccountById($id);
        if (!$user) return false;
        $newStatus = $user->is_locked ? 0 : 1;
        $query = "UPDATE " . $this->table_name . " SET is_locked=:status WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>