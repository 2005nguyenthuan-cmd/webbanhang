<?php
session_start();
if (!defined('BASE_URL')) {
    define('BASE_URL', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
}

require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';

// Auto-login from Remember Me cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_me'])) {
    $parts = explode(':', $_COOKIE['remember_me'], 2);
    if (count($parts) === 2) {
        $username = $parts[0];
        $token = $parts[1];
        
        $db = (new Database())->getConnection();
        $accountModel = new AccountModel($db);
        $account = $accountModel->getAccountByUsername($username);
        if ($account && $account->remember_token === $token && !$account->is_locked && $account->is_verified) {
            $_SESSION['username'] = $account->username;
            $_SESSION['role'] = $account->role;
            $_SESSION['fullname'] = $account->fullname;
        }
    }
}

require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
// Product/add
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);
// Kiểm tra phần đầu tiên của URL để xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' :
'ProductController';
// Kiểm tra phần thứ hai của URL để xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// die ("controller=$controllerName - action=$action");

// Kiểm tra xem controller và action có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
// Xử lý không tìm thấy controller
die('Controller not found');
}
require_once 'app/controllers/' . $controllerName . '.php';
$controller = new $controllerName();
if (!method_exists($controller, $action)) {
// Xử lý không tìm thấy action
die('Action not found');
}
// Gọi action với các tham số còn lại (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));