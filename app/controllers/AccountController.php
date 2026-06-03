<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/MailHelper.php');

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
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            $errors = [];
            if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ và tên!";
            if (empty($email)) $errors['email'] = "Vui lòng nhập email!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            
            if (!in_array($role, ['admin', 'user'])) $role = 'user';
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã tồn tại trong hệ thống!";
            }
            if (!empty($email) && $this->accountModel->getAccountByEmail($email)) {
                $errors['email'] = "Email này đã được đăng ký bởi tài khoản khác!";
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $verificationToken = bin2hex(random_bytes(16));
                $result = $this->accountModel->save($username, $fullName, $password, $role, $email, $verificationToken);
                if ($result) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    // Domain part needs to be absolute for emails, assuming http://localhost/project1 or similar based on BASE_URL
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                    $host = $_SERVER['HTTP_HOST'];
                    $absoluteBaseUrl = $protocol . $host . BASE_URL;
                    
                    $verifyLink = $absoluteBaseUrl . '/Account/verify?token=' . $verificationToken;
                    
                    // Send Email
                    $mailSent = MailHelper::sendVerificationEmail($email, $fullName, $verifyLink);
                    
                    if ($mailSent) {
                        $_SESSION['register_success'] = "Đăng ký thành công! Vui lòng kiểm tra hộp thư email (bao gồm cả thư mục Spam) để kích hoạt tài khoản.";
                    } else {
                        $_SESSION['register_success'] = "Đăng ký thành công nhưng hệ thống không thể gửi email xác thực lúc này. Vui lòng liên hệ quản trị viên.";
                    }
                    
                    header('Location: ' . BASE_URL . '/Account/login');
                    exit;
                } else {
                    $errors['system'] = "Đã xảy ra lỗi hệ thống khi lưu tài khoản.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    // Xử lý xác thực tài khoản qua email
    public function verify() {
        $token = $_GET['token'] ?? '';
        if ($token) {
            $verified = $this->accountModel->verifyEmail($token);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($verified) {
                $_SESSION['register_success'] = "Xác thực email và kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.";
            } else {
                $_SESSION['auth_error'] = "Mã xác thực không hợp lệ hoặc tài khoản đã được xác thực trước đó.";
            }
        }
        header('Location: ' . BASE_URL . '/Account/login');
        exit();
    }

    // Xử lý Đăng nhập
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $rememberMe = isset($_POST['remember_me']);
            
            $account = $this->accountModel->getAccountByUsername($username);
            
            if ($account) {
                if ($account->is_locked) {
                    $error = "Tài khoản của bạn đã bị khóa bởi Quản trị viên!";
                    include_once 'app/views/account/login.php';
                    exit;
                }
                
                if (!$account->is_verified) {
                    $error = "Tài khoản chưa được xác thực email. Vui lòng kiểm tra email hoặc nhấp vào liên kết kích hoạt để sử dụng tài khoản!";
                    include_once 'app/views/account/login.php';
                    exit;
                }
                
                if (password_verify($password, $account->password)) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['username'] = $account->username;
                    $_SESSION['role'] = $account->role;
                    $_SESSION['fullname'] = $account->fullname;
                    
                    // Xử lý Remember Me (ghi nhớ đăng nhập)
                    if ($rememberMe) {
                        $rememberToken = bin2hex(random_bytes(32));
                        $this->accountModel->setRememberToken($account->username, $rememberToken);
                        setcookie('remember_me', $account->username . ':' . $rememberToken, time() + 30 * 24 * 60 * 60, '/');
                    }
                    
                    if ($account->role === 'admin') {
                        header('Location: ' . BASE_URL . '/Account/admin');
                    } else {
                        header('Location: ' . BASE_URL . '/Product');
                    }
                    exit;
                } else {
                    $error = "Mật khẩu nhập chưa chính xác!";
                    include_once 'app/views/account/login.php';
                    exit;
                }
            } else {
                $error = "Tên đăng nhập không tồn tại trên hệ thống!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }

    // Xử lý Đăng xuất
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['username'])) {
            $this->accountModel->setRememberToken($_SESSION['username'], null);
        }
        setcookie('remember_me', '', time() - 3600, '/');
        
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

    // --- QUÊN MẬT KHẨU & ĐẶT LẠI MẬT KHẨU ---

    // Form Quên mật khẩu
    public function forgotPassword() {
        include_once 'app/views/account/forgotPassword.php';
    }

    // Xử lý yêu cầu gửi link đặt lại mật khẩu
    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = $_POST['username_or_email'] ?? '';
            $token = bin2hex(random_bytes(16));
            
            $success = $this->accountModel->setResetToken($usernameOrEmail, $token);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($success) {
                // Domain part needs to be absolute for emails
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'];
                $absoluteBaseUrl = $protocol . $host . BASE_URL;
                
                $resetLink = $absoluteBaseUrl . '/Account/resetPassword?token=' . $token;
                
                // Fetch the full user details to get the actual email if they inputted a username
                // Or just use the email they provided if it is an email
                $account = $this->accountModel->getAccountByUsername($usernameOrEmail);
                if (!$account) {
                    $account = $this->accountModel->getAccountByEmail($usernameOrEmail);
                }
                
                if ($account && !empty($account->email)) {
                    $mailSent = MailHelper::sendResetPasswordEmail($account->email, $account->fullname, $resetLink);
                    
                    if ($mailSent) {
                        $_SESSION['reset_success'] = "Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn! Vui lòng kiểm tra hộp thư (bao gồm cả mục Spam).";
                    } else {
                        $_SESSION['reset_error'] = "Yêu cầu đã được xử lý nhưng không thể gửi email. Vui lòng thử lại sau.";
                    }
                } else {
                    $_SESSION['reset_error'] = "Tài khoản không được liên kết với địa chỉ email hợp lệ nào.";
                }
            } else {
                $_SESSION['reset_error'] = "Tên đăng nhập hoặc Email không tồn tại trên hệ thống!";
            }
        }
        header('Location: ' . BASE_URL . '/Account/forgotPassword');
        exit();
    }

    // Form đặt lại mật khẩu mới
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        $account = $this->accountModel->getAccountByResetToken($token);
        if (!$account) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Mã thông báo khôi phục mật khẩu không hợp lệ hoặc đã hết hạn.";
            header('Location: ' . BASE_URL . '/Account/login');
            exit();
        }
        include_once 'app/views/account/resetPassword.php';
    }

    // Xử lý lưu mật khẩu mới
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            
            $account = $this->accountModel->getAccountByResetToken($token);
            if (!$account) {
                die('Mã khôi phục không hợp lệ');
            }
            
            $errors = [];
            if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu mới!";
            if ($password !== $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            
            if (count($errors) > 0) {
                include_once 'app/views/account/resetPassword.php';
            } else {
                $this->accountModel->updatePassword($account->id, $password);
                $this->accountModel->clearResetToken($account->id);
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['register_success'] = "Đặt lại mật khẩu thành công! Hãy đăng nhập bằng mật khẩu mới.";
                header('Location: ' . BASE_URL . '/Account/login');
                exit();
            }
        }
    }

    // --- HỒ SƠ CÁ NHÂN & ĐỔI MẬT KHẨU ---

    // Trang hồ sơ cá nhân
    public function profile() {
        if (!AccountModel::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Account/login');
            exit();
        }
        $user = $this->accountModel->getAccountByUsername($_SESSION['username']);
        include_once 'app/views/account/profile.php';
    }

    // Xử lý cập nhật hồ sơ cá nhân & ảnh đại diện
    public function updateProfile() {
        if (!AccountModel::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Account/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->accountModel->getAccountByUsername($_SESSION['username']);
            $fullName = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $avatarPath = null;
            
            // Xử lý tải ảnh đại diện
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $targetDir = "uploads/avatars/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES["avatar"]["name"]);
                $targetFile = $targetDir . $fileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                
                // Kiểm tra loại file và kích thước
                $check = getimagesize($_FILES["avatar"]["tmp_name"]);
                if ($check !== false && $_FILES["avatar"]["size"] <= 5 * 1024 * 1024) {
                    if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
                            $avatarPath = $targetFile;
                        }
                    }
                }
            }
            
            $success = $this->accountModel->updateProfile($user->id, $fullName, $email, $avatarPath);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($success) {
                $_SESSION['fullname'] = $fullName;
                $_SESSION['profile_success'] = "Cập nhật thông tin tài khoản thành công!";
            } else {
                $_SESSION['profile_error'] = "Cập nhật thất bại. Vui lòng kiểm tra lại.";
            }
        }
        header('Location: ' . BASE_URL . '/Account/profile');
        exit();
    }

    // Xử lý đổi mật khẩu
    public function updatePassword() {
        if (!AccountModel::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Account/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->accountModel->getAccountByUsername($_SESSION['username']);
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!password_verify($oldPassword, $user->password)) {
                $_SESSION['pw_error'] = "Mật khẩu cũ không chính xác!";
                header('Location: ' . BASE_URL . '/Account/profile');
                exit();
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['pw_error'] = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
                header('Location: ' . BASE_URL . '/Account/profile');
                exit();
            }
            
            $success = $this->accountModel->updatePassword($user->id, $newPassword);
            if ($success) {
                $_SESSION['pw_success'] = "Thay đổi mật khẩu thành công!";
            } else {
                $_SESSION['pw_error'] = "Đã xảy ra lỗi khi thay đổi mật khẩu.";
            }
        }
        header('Location: ' . BASE_URL . '/Account/profile');
        exit();
    }

    // --- CÁC PHƯƠNG THỨC QUẢN TRỊ ADMIN ---

    // Trang chủ quản trị Admin Dashboard (Báo cáo & Phân quyền)
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
        $totalUsers = count($users);

        // Lấy danh sách các đơn hàng và tổng tiền
        $queryOrders = "SELECT o.id, o.name, o.phone, o.address, o.created_at, o.status, a.username as account_name,
                               (SELECT SUM(od.quantity * od.price) FROM order_details od WHERE od.order_id = o.id) as total_amount
                        FROM orders o
                        LEFT JOIN account a ON o.user_id = a.id
                        ORDER BY o.created_at DESC";
        $stmtOrders = $this->db->prepare($queryOrders);
        $stmtOrders->execute();
        $orders = $stmtOrders->fetchAll(PDO::FETCH_OBJ);
        $totalOrders = count($orders);

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

        // TÍNH TOÁN DOANH THU & PHÂN TÍCH (Chức năng mở rộng)
        $queryRevenue = "SELECT SUM(od.quantity * od.price) as total_revenue
                         FROM order_details od
                         JOIN orders o ON od.order_id = o.id
                         WHERE o.status != 'Đã hủy'";
        $stmtRev = $this->db->prepare($queryRevenue);
        $stmtRev->execute();
        $totalRevenue = $stmtRev->fetch(PDO::FETCH_OBJ)->total_revenue ?? 0;

        // Thống kê doanh thu theo từng trạng thái đơn hàng
        $queryRevenueByStatus = "SELECT o.status, SUM(od.quantity * od.price) as revenue
                                 FROM order_details od
                                 JOIN orders o ON od.order_id = o.id
                                 GROUP BY o.status";
        $stmtRevStatus = $this->db->prepare($queryRevenueByStatus);
        $stmtRevStatus->execute();
        $revenueStats = $stmtRevStatus->fetchAll(PDO::FETCH_OBJ);

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

    // Admin khóa hoặc mở khóa tài khoản người dùng
    public function toggleUserLock($id) {
        if (!AccountModel::isAdmin()) {
            die('Truy cập bị từ chối');
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $currentUser = $this->accountModel->getAccountByUsername($_SESSION['username']);
        if ($currentUser && $currentUser->id != $id) {
            $this->accountModel->toggleLock($id);
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