<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // Hiển thị Form đăng ký
    public function register() {
        if (AccountModel::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }
        include_once 'app/views/account/register.php';
    }

    // Hiển thị Form đăng nhập
    public function login() {
        if (AccountModel::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }
        include_once 'app/views/account/login.php';
    }

    // Xử lý lưu đăng ký tài khoản mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            $errors = [];
            if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ và tên!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            
            if (!in_array($role, ['admin', 'user'])) $role = 'user';
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã tồn tại trong hệ thống!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $result = $this->accountModel->save($username, $fullName, $password, $role);
                if ($result) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['register_success'] = "Đăng ký tài khoản thành công! Hãy đăng nhập ngay.";
                    header('Location: ' . BASE_URL . '/Account/login');
                    exit;
                } else {
                    $errors['system'] = "Đã xảy ra lỗi hệ thống khi lưu tài khoản.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    // Xử lý Đăng xuất
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['fullname']);
        session_destroy();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Location: ' . BASE_URL . '/Product');
        exit;
    }

    // Xử lý Đăng nhập
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account && password_verify($password, $account->password)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;
                $_SESSION['fullname'] = $account->fullname;
                
                if ($account->role === 'admin') {
                    header('Location: ' . BASE_URL . '/Account/admin');
                } else {
                    header('Location: ' . BASE_URL . '/Product');
                }
                exit;
            } else {
                $error = $account ? "Mật khẩu nhập chưa chính xác!" : "Tên đăng nhập không tồn tại trên hệ thống!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }

    // --- CÁC PHƯƠNG THỨC MỚI QUẢN TRỊ ADMIN DASHBOARD ---

    // Trang chủ quản trị Admin Dashboard
    public function admin() {
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối. Bạn phải đăng nhập bằng tài khoản Quản trị viên!";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        $productModel = new ProductModel($this->db);
        $categoryModel = new CategoryModel($this->db);

        // Lấy sản phẩm, danh mục, và các tài khoản từ AccountModel
        $products = $productModel->getProducts();
        $categories = $categoryModel->getCategories();
        $users = $this->accountModel->getAccounts();

        // Lấy danh sách các đơn hàng và tổng tiền
        $queryOrders = "SELECT o.id, o.name, o.phone, o.address, o.created_at, o.status, a.username as account_name,
                               (SELECT SUM(od.quantity * od.price) FROM order_details od WHERE od.order_id = o.id) as total_amount
                        FROM orders o
                        LEFT JOIN account a ON o.user_id = a.id
                        ORDER BY o.created_at DESC";
        $stmtOrders = $this->db->prepare($queryOrders);
        $stmtOrders->execute();
        $orders = $stmtOrders->fetchAll(PDO::FETCH_OBJ);

        // Lấy chi tiết đơn đặt hàng phục vụ xem nhanh
        $orderDetails = [];
        foreach ($orders as $order) {
            $queryItems = "SELECT od.*, p.name as product_name, p.image as product_image
                           FROM order_details od
                           LEFT JOIN product p ON od.product_id = p.id
                           WHERE od.order_id = :order_id";
            $stmtItems = $this->db->prepare($queryItems);
            $stmtItems->execute([':order_id' => $order->id]);
            $orderDetails[$order->id] = $stmtItems->fetchAll(PDO::FETCH_OBJ);
        }

        include 'app/views/account/admin.php';
    }

    // Admin cập nhật vai trò của người dùng
    public function updateUserRole() {
        if (!AccountModel::isAdmin()) {
            die('Truy cập bị từ chối');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? '';
            $role = $_POST['role'] ?? '';

            if (!empty($userId) && !empty($role)) {
                $this->accountModel->updateAccountRole($userId, $role);
            }
        }
        header('Location: ' . BASE_URL . '/Account/admin?tab=users');
        exit();
    }

    // Admin xóa tài khoản thành viên
    public function deleteUser($id) {
        if (!AccountModel::isAdmin()) {
            die('Truy cập bị từ chối');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $currentUsername = $_SESSION['username'] ?? '';
        $userToDelete = $this->accountModel->getAccountById($id);
        
        if ($userToDelete && $userToDelete->username !== $currentUsername) {
            $this->accountModel->deleteAccount($id);
        }

        header('Location: ' . BASE_URL . '/Account/admin?tab=users');
        exit();
    }

    // Admin cập nhật trạng thái đơn hàng
    public function updateOrderStatus() {
        if (!AccountModel::isAdmin()) {
            die('Truy cập bị từ chối');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? '';
            $status = $_POST['status'] ?? '';

            if (!empty($orderId) && !empty($status)) {
                $query = "UPDATE orders SET status = :status WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id', $orderId);
                $stmt->execute();
            }
        }
        header('Location: ' . BASE_URL . '/Account/admin?tab=orders');
        exit();
    }

    // Admin thêm danh mục sản phẩm mới
    public function addCategory() {
        if (!AccountModel::isAdmin()) {
            die('Truy cập bị từ chối');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($name)) {
                $query = "INSERT INTO category (name, description) VALUES (:name, :description)";
                $stmt = $this->db->prepare($query);
                $name = htmlspecialchars(strip_tags($name));
                $description = htmlspecialchars(strip_tags($description));
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->execute();
            }
        }
        header('Location: ' . BASE_URL . '/Account/admin?tab=categories');
        exit();
    }
}
?>