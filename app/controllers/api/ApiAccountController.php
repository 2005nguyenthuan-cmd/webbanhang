<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/MailHelper.php';
require_once 'app/helpers/JwtHelper.php';

class ApiAccountController {
    private $db;
    private $accountModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function handleRequest($action, $subAction) {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($action) {
            case 'register':
                if ($method === 'POST') $this->register();
                else $this->methodNotAllowed();
                break;
            case 'login':
                if ($method === 'POST') $this->login();
                else $this->methodNotAllowed();
                break;
            case 'refresh-token':
                if ($method === 'POST') $this->refreshToken();
                else $this->methodNotAllowed();
                break;
            case 'profile':
                if ($method === 'GET') $this->getProfile();
                elseif ($method === 'POST') $this->updateProfile(); // POST because of file uploads
                else $this->methodNotAllowed();
                break;
            case 'change-password':
                if ($method === 'PUT' || $method === 'POST') $this->changePassword();
                else $this->methodNotAllowed();
                break;
            case 'forgot-password':
                if ($method === 'POST') $this->forgotPassword();
                else $this->methodNotAllowed();
                break;
            case 'reset-password':
                if ($method === 'POST') $this->resetPassword();
                else $this->methodNotAllowed();
                break;
            case 'users':
                if ($method === 'GET') $this->listUsers();
                else $this->methodNotAllowed();
                break;
            case 'update-role':
                if ($method === 'POST' || $method === 'PUT') $this->updateUserRole();
                else $this->methodNotAllowed();
                break;
            case 'toggle-lock':
                if ($method === 'POST' || $method === 'PUT') $this->toggleUserLock();
                else $this->methodNotAllowed();
                break;
            case 'delete-user':
                if ($method === 'DELETE' || $method === 'POST') $this->deleteUser();
                else $this->methodNotAllowed();
                break;
            default:
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Action not found.']);
                break;
        }
    }

    // Helper: authenticate token and return payload
    public function getAuthorizedUser($requiredRole = null) {
        $headers = getallheaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        if (empty($authHeader) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Token missing.']);
            exit();
        }

        $token = $matches[1];
        $payload = JwtHelper::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Token expired or invalid.']);
            exit();
        }

        // Check if user is locked
        $user = $this->accountModel->getAccountById($payload['sub']);
        if (!$user || $user->is_locked) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Tài khoản đã bị khóa hoặc không tồn tại.']);
            exit();
        }

        if ($requiredRole && $payload['role'] !== $requiredRole) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden. Quyền truy cập bị từ chối.']);
            exit();
        }

        return $payload;
    }

    private function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $username = $data['username'] ?? '';
        $fullName = $data['fullname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmpassword'] ?? '';
        $role = $data['role'] ?? 'user';

        if (empty($username) || empty($fullName) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc.']);
            return;
        }

        if ($password !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mật khẩu và xác nhận mật khẩu không khớp.']);
            return;
        }

        if ($this->accountModel->getAccountByUsername($username)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập này đã tồn tại.']);
            return;
        }

        if ($this->accountModel->getAccountByEmail($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email này đã được sử dụng.']);
            return;
        }

        $verificationToken = bin2hex(random_bytes(16));
        $result = $this->accountModel->save($username, $fullName, $password, $role, $email, $verificationToken);

        if ($result) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $absoluteBaseUrl = $protocol . $host . BASE_URL;
            $verifyLink = $absoluteBaseUrl . '/Account/verify?token=' . $verificationToken;

            // Send verification email
            $mailSent = MailHelper::sendVerificationEmail($email, $fullName, $verifyLink);

            echo json_encode([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.',
                'email_sent' => $mailSent
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi tạo tài khoản.']);
        }
    }

    private function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập và mật khẩu không được để trống.']);
            return;
        }

        $account = $this->accountModel->getAccountByUsername($username);

        if (!$account) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tài khoản không tồn tại trên hệ thống.']);
            return;
        }

        // Check lock status
        if ($account->is_locked) {
            if ($account->locked_until && strtotime($account->locked_until) > time()) {
                $lockedMinutes = ceil((strtotime($account->locked_until) - time()) / 60);
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => "Tài khoản của bạn đã bị khóa tạm thời. Thử lại sau {$lockedMinutes} phút."]);
                return;
            } else {
                // Unlock account if locked time has passed
                $queryUnlock = "UPDATE account SET is_locked = 0, locked_until = NULL, failed_attempts = 0 WHERE id = :id";
                $stmtUnlock = $this->db->prepare($queryUnlock);
                $stmtUnlock->execute([':id' => $account->id]);
                $account->is_locked = 0;
            }
        }

        if (!$account->is_verified) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tài khoản chưa được kích hoạt qua email.']);
            return;
        }

        if (password_verify($password, $account->password)) {
            // Login successful
            // Reset failure count
            $queryReset = "UPDATE account SET failed_attempts = 0, locked_until = NULL WHERE id = :id";
            $stmtReset = $this->db->prepare($queryReset);
            $stmtReset->execute([':id' => $account->id]);

            // Generate JWT
            $tokenPayload = [
                'sub' => $account->id,
                'username' => $account->username,
                'role' => $account->role,
                'fullname' => $account->fullname
            ];
            $token = JwtHelper::encode($tokenPayload, 3600); // 1 hour access token

            // Generate Refresh Token
            $refreshToken = bin2hex(random_bytes(32));
            $queryUpdateRefresh = "UPDATE account SET refresh_token = :refresh WHERE id = :id";
            $stmtRefresh = $this->db->prepare($queryUpdateRefresh);
            $stmtRefresh->execute([':refresh' => $refreshToken, ':id' => $account->id]);

            // Create session for compatibility (if required by UI)
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['username'] = $account->username;
            $_SESSION['role'] = $account->role;
            $_SESSION['fullname'] = $account->fullname;

            echo json_encode([
                'success' => true,
                'message' => 'Đăng nhập thành công.',
                'token' => $token,
                'refresh_token' => $refreshToken,
                'user' => [
                    'id' => $account->id,
                    'username' => $account->username,
                    'fullname' => $account->fullname,
                    'role' => $account->role,
                    'avatar' => $account->avatar,
                    'email' => $account->email
                ]
            ]);
        } else {
            // Login failed
            $failedAttempts = $account->failed_attempts + 1;
            if ($failedAttempts >= 5) {
                $lockedUntil = date('Y-m-d H:i:s', time() + 15 * 60); // 15 mins lock
                $queryLock = "UPDATE account SET failed_attempts = :attempts, is_locked = 1, locked_until = :locked_until WHERE id = :id";
                $stmtLock = $this->db->prepare($queryLock);
                $stmtLock->execute([
                    ':attempts' => $failedAttempts,
                    ':locked_until' => $lockedUntil,
                    ':id' => $account->id
                ]);

                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Đăng nhập sai quá 5 lần. Tài khoản của bạn đã bị khóa tạm thời 15 phút.']);
            } else {
                $queryUpdateFailed = "UPDATE account SET failed_attempts = :attempts WHERE id = :id";
                $stmtFailed = $this->db->prepare($queryUpdateFailed);
                $stmtFailed->execute([
                    ':attempts' => $failedAttempts,
                    ':id' => $account->id
                ]);

                $remaining = 5 - $failedAttempts;
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => "Mật khẩu không chính xác. Bạn còn {$remaining} lần thử trước khi tài khoản bị khóa."]);
            }
        }
    }

    private function refreshToken() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $refreshToken = $data['refresh_token'] ?? '';
        if (empty($refreshToken)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Refresh token is required.']);
            return;
        }

        $query = "SELECT * FROM account WHERE refresh_token = :refresh LIMIT 0,1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':refresh' => $refreshToken]);
        $account = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$account || $account->is_locked) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid or expired refresh token.']);
            return;
        }

        // Generate new JWT access token
        $tokenPayload = [
            'sub' => $account->id,
            'username' => $account->username,
            'role' => $account->role,
            'fullname' => $account->fullname
        ];
        $newToken = JwtHelper::encode($tokenPayload, 3600);

        echo json_encode([
            'success' => true,
            'token' => $newToken
        ]);
    }

    private function getProfile() {
        $authorizedUser = $this->getAuthorizedUser();
        $user = $this->accountModel->getAccountById($authorizedUser['sub']);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar
            ]
        ]);
    }

    private function updateProfile() {
        $authorizedUser = $this->getAuthorizedUser();
        $user = $this->accountModel->getAccountById($authorizedUser['sub']);

        $fullName = $_POST['fullname'] ?? $user->fullname;
        $email = $_POST['email'] ?? $user->email;
        $avatarPath = null;

        if (empty($fullName)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Họ tên không được để trống.']);
            return;
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $targetDir = "uploads/avatars/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES["avatar"]["name"]);
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
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

        if ($success) {
            // Update session for compatibility
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['fullname'] = $fullName;

            $updatedUser = $this->accountModel->getAccountById($user->id);
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công.',
                'data' => [
                    'fullname' => $updatedUser->fullname,
                    'email' => $updatedUser->email,
                    'avatar' => $updatedUser->avatar
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Đã có lỗi xảy ra khi cập nhật.']);
        }
    }

    private function changePassword() {
        $authorizedUser = $this->getAuthorizedUser();
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $oldPassword = $data['old_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ mật khẩu cũ và mới.']);
            return;
        }

        $user = $this->accountModel->getAccountById($authorizedUser['sub']);

        if (!password_verify($oldPassword, $user->password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mật khẩu cũ không chính xác.']);
            return;
        }

        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.']);
            return;
        }

        $success = $this->accountModel->updatePassword($user->id, $newPassword);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Thay đổi mật khẩu thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi đổi mật khẩu.']);
        }
    }

    private function forgotPassword() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $usernameOrEmail = $data['username_or_email'] ?? '';

        if (empty($usernameOrEmail)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên đăng nhập hoặc email.']);
            return;
        }

        $token = bin2hex(random_bytes(16));
        $success = $this->accountModel->setResetToken($usernameOrEmail, $token);

        if ($success) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $absoluteBaseUrl = $protocol . $host . BASE_URL;
            $resetLink = $absoluteBaseUrl . '/Account/resetPassword?token=' . $token;

            $account = $this->accountModel->getAccountByUsername($usernameOrEmail);
            if (!$account) {
                $account = $this->accountModel->getAccountByEmail($usernameOrEmail);
            }

            if ($account && !empty($account->email)) {
                $mailSent = MailHelper::sendResetPasswordEmail($account->email, $account->fullname, $resetLink);
                if ($mailSent) {
                    echo json_encode(['success' => true, 'message' => 'Một liên kết khôi phục mật khẩu đã được gửi đến email của bạn.']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi gửi email khôi phục. Vui lòng liên hệ quản trị viên.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Tài khoản không chứa email hợp lệ.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập hoặc Email không tồn tại trên hệ thống.']);
        }
    }

    private function resetPassword() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $token = $data['token'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmpassword'] ?? '';

        if (empty($token) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.']);
            return;
        }

        if ($password !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mật khẩu và xác nhận mật khẩu không khớp.']);
            return;
        }

        $account = $this->accountModel->getAccountByResetToken($token);

        if (!$account) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã khôi phục không hợp lệ hoặc đã hết hạn.']);
            return;
        }

        $this->accountModel->updatePassword($account->id, $password);
        $this->accountModel->clearResetToken($account->id);

        echo json_encode(['success' => true, 'message' => 'Đặt lại mật khẩu thành công. Hãy sử dụng mật khẩu mới để đăng nhập.']);
    }

    private function listUsers() {
        $this->getAuthorizedUser('admin');
        $users = $this->accountModel->getAccounts();
        echo json_encode([
            'success' => true,
            'data' => $users
        ]);
    }

    private function updateUserRole() {
        $authorizedUser = $this->getAuthorizedUser('admin');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $userId = $data['user_id'] ?? '';
        $role = $data['role'] ?? '';

        if (empty($userId) || empty($role)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID and Role are required.']);
            return;
        }

        // Prevent self modification of role
        $targetUser = $this->accountModel->getAccountById($userId);
        if ($targetUser && $targetUser->username === $authorizedUser['username']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bạn không thể tự thay đổi vai trò của chính mình.']);
            return;
        }

        $success = $this->accountModel->updateAccountRole($userId, $role);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật vai trò thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật vai trò.']);
        }
    }

    private function toggleUserLock() {
        $authorizedUser = $this->getAuthorizedUser('admin');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $userId = $data['user_id'] ?? '';

        if (empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required.']);
            return;
        }

        // Prevent self lock
        $targetUser = $this->accountModel->getAccountById($userId);
        if ($targetUser && $targetUser->username === $authorizedUser['username']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bạn không thể tự khóa tài khoản của chính mình.']);
            return;
        }

        $success = $this->accountModel->toggleLock($userId);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Thay đổi trạng thái khóa tài khoản thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi thay đổi khóa tài khoản.']);
        }
    }

    private function deleteUser() {
        $authorizedUser = $this->getAuthorizedUser('admin');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $userId = $data['user_id'] ?? $_GET['user_id'] ?? null;

        if (empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required.']);
            return;
        }

        // Prevent self deletion
        $targetUser = $this->accountModel->getAccountById($userId);
        if ($targetUser && $targetUser->username === $authorizedUser['username']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bạn không thể tự xóa tài khoản của chính mình.']);
            return;
        }

        $success = $this->accountModel->deleteAccount($userId);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Xóa tài khoản thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi xóa tài khoản.']);
        }
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    }
}
?>
