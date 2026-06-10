<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
}
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Thế Giới Công Nghệ</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 4 & FontAwesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #06b6d4;
            --accent: #f97316;
            --accent-light: #ffedd5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            
            --light: #f8fafc;
            --dark: #0f172a;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.05), 0 8px 16px -6px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 20px 25px -5px rgba(79, 70, 229, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.dark-theme {
            --light: #090d16;
            --dark: #f8fafc;
            --card-bg: #131b2e;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #1e293b;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 20px 25px rgba(0, 0, 0, 0.4);
            background-color: var(--light);
        }

        body {
            background-color: var(--light);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navigation */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: var(--transition);
        }
        
        body.dark-theme .navbar-custom {
            background: rgba(19, 27, 46, 0.8);
            border-bottom: 1px solid rgba(30, 41, 59, 0.8);
        }

        .navbar-custom .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .navbar-custom .nav-link {
            color: var(--text-main) !important;
            font-weight: 600;
            font-size: 15px;
            padding: 8px 16px !important;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            margin: 0 2px;
        }

        .navbar-custom .nav-link:hover, 
        .navbar-custom .nav-item.active .nav-link {
            color: var(--primary) !important;
            background-color: var(--primary-light);
        }

        body.dark-theme .navbar-custom .nav-link:hover,
        body.dark-theme .navbar-custom .nav-item.active .nav-link {
            color: #818cf8 !important;
            background-color: rgba(79, 70, 229, 0.2);
        }

        /* Search Bar */
        .search-container {
            position: relative;
            width: 320px;
        }

        .search-container input {
            background-color: var(--light);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 10px 16px;
            padding-right: 45px;
            color: var(--text-main);
            font-size: 14px;
            font-weight: 500;
            width: 100%;
            transition: var(--transition);
        }

        .search-container input:focus {
            background-color: var(--card-bg);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        .search-container button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            padding: 8px;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .search-container button:hover {
            color: var(--primary);
        }

        /* Cart Icon */
        .cart-icon-btn {
            position: relative;
            background: var(--light);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            text-decoration: none !important;
        }

        .cart-icon-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: transparent;
            transform: translateY(-2px);
        }
        
        body.dark-theme .cart-icon-btn:hover {
            background: rgba(79, 70, 229, 0.2);
            color: #818cf8;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent);
            color: white;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--card-bg);
            box-shadow: var(--shadow-sm);
        }

        /* Dark Mode Toggle */
        .theme-toggle-btn {
            background: var(--light);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            margin-right: 10px;
        }

        .theme-toggle-btn:hover {
            background: var(--border-color);
            transform: rotate(15deg);
        }

        /* Buttons & Forms Premium styling */
        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white !important;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 24px;
            font-weight: 600;
            letter-spacing: -0.2px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: var(--transition);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .btn-premium:active {
            transform: translateY(0);
        }

        .btn-outline-premium {
            background: transparent;
            color: var(--primary) !important;
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            padding: 8px 22px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-premium:hover {
            background: var(--primary);
            color: white !important;
            transform: translateY(-2px);
        }

        /* Card custom styling */
        .premium-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .main-content {
            flex: 1;
        }

        /* Dark mode dropdown custom styles */
        body.dark-theme .dropdown-menu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        body.dark-theme .dropdown-item {
            color: var(--text-main) !important;
        }
        body.dark-theme .dropdown-item:hover {
            background-color: rgba(79, 70, 229, 0.15) !important;
            color: var(--primary) !important;
        }
        body.dark-theme .dropdown-divider {
            border-color: var(--border-color) !important;
        }
    </style>
    
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/Product/">
            <i class="fa-solid fa-bolt mr-2"></i>TechStore
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="color: var(--text-main); display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/Account/admin') === false && strpos($_SERVER['REQUEST_URI'], '/Product/add') === false && strpos($_SERVER['REQUEST_URI'], '/Product/cart') === false) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/Product/">
                        <i class="fa-solid fa-store mr-1"></i> Cửa hàng
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/Account/admin') !== false) ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/Account/admin">
                        <i class="fa-solid fa-toolbox mr-1"></i> Quản lý
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="d-flex align-items-center">
                <!-- Search -->
                <form class="search-container mr-3 d-none d-md-block" action="<?php echo BASE_URL; ?>/Product/search" method="GET">
                    <input type="search" name="keyword" placeholder="Tìm kiếm sản phẩm công nghệ..." required value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <!-- Theme Toggle -->
                <button class="theme-toggle-btn mr-2" id="themeToggle" title="Đổi giao diện Sáng/Tối">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                </button>

                <!-- Cart Button -->
                <a class="cart-icon-btn mr-2" href="<?php echo BASE_URL; ?>/Product/cart" title="Giỏ hàng của bạn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge" id="cartBadgeCount"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>

                <!-- User/Auth Section -->
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center py-2 px-3" type="button" id="userMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: var(--radius-md); font-size: 14px; gap: 6px; border: 1px solid var(--border-color); color: var(--text-main); background: var(--light); height: 42px;">
                            <i class="fa-solid fa-circle-user" style="font-size: 16px;"></i>
                            <span class="d-none d-lg-inline">Hi, <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right p-2 shadow-lg" aria-labelledby="userMenuBtn" style="border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--card-bg); min-width: 200px;">
                            <div class="px-3 py-2 border-bottom mb-2 small text-muted">
                                Quyền: <strong><?php echo ($_SESSION['role'] === 'admin') ? 'Quản trị viên' : 'Khách hàng'; ?></strong>
                            </div>
                            <a class="dropdown-item py-2 small font-weight-bold" href="<?php echo BASE_URL; ?>/Account/profile" style="border-radius: 4px; color: var(--text-main);"><i class="fa-solid fa-address-card mr-2 text-info"></i>Hồ sơ cá nhân</a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a class="dropdown-item py-2 small font-weight-bold" href="<?php echo BASE_URL; ?>/Account/admin" style="border-radius: 4px; color: var(--text-main);"><i class="fa-solid fa-toolbox mr-2 text-primary"></i>Bảng điều khiển</a>
                                <a class="dropdown-item py-2 small font-weight-bold" href="<?php echo BASE_URL; ?>/Product/add" style="border-radius: 4px; color: var(--text-main);"><i class="fa-solid fa-plus mr-2 text-success"></i>Thêm sản phẩm</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item py-2 small text-danger font-weight-bold" href="<?php echo BASE_URL; ?>/Account/logout" style="border-radius: 4px;"><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/Account/login" class="btn btn-outline-premium py-2 px-3 d-flex align-items-center justify-content-center" style="border-radius: var(--radius-md); font-size: 14px; height: 42px;">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> <span class="d-none d-lg-inline">Đăng nhập</span>
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 main-content">

    <!-- Thông báo lỗi phân quyền dùng chung -->
    <?php if (isset($_SESSION['auth_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--radius-md); font-weight: 500; font-size: 14px;">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $_SESSION['auth_error']; unset($_SESSION['auth_error']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>