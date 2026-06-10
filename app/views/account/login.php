<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <!-- Glassmorphism Login Card -->
        <div class="login-card p-5 shadow-lg" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: var(--radius-lg); transition: var(--transition);">
            
            <div class="text-center mb-4">
                <div class="logo-icon d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; font-size: 24px; box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25);">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h2 class="font-weight-bold" style="letter-spacing: -0.5px; color: var(--text-main);">Đăng Nhập</h2>
                <p class="text-muted small">Chào mừng bạn quay lại với TechStore</p>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer">
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-circle-check mr-1"></i> <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $error; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form đăng nhập - submit trực tiếp tới server -->
            <form action="<?php echo BASE_URL; ?>/Account/checkLogin" method="POST">
                <div class="form-group mb-4">
                    <label for="username" class="small font-weight-bold mb-2" style="color: var(--text-main);">TÊN ĐĂNG NHẬP</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-regular fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="small font-weight-bold mb-2" style="color: var(--text-main);">MẬT KHẨU</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                        <label class="form-check-label small font-weight-bold text-muted" for="remember_me" style="cursor: pointer;">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/Account/forgotPassword" class="small font-weight-bold" style="color: var(--primary); text-decoration: none;">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-premium btn-block py-3" style="border-radius: var(--radius-md); font-weight: 700; font-size: 15px; letter-spacing: 0.5px;">
                    ĐĂNG NHẬP <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Chưa có tài khoản?</span>
                <a href="<?php echo BASE_URL; ?>/Account/register" class="small font-weight-bold ml-1" style="color: var(--primary); text-decoration: none;">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dark Theme specifics for login card */
    body.dark-theme .login-card {
        background: rgba(19, 27, 46, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    
    .form-control:focus {
        box-shadow: none !important;
        border-color: var(--primary) !important;
    }
    
    .input-group:focus-within .input-group-text {
        border-color: var(--primary) !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>