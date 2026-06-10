<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <!-- Glassmorphism Forgot Card -->
        <div class="forgot-card p-5 shadow-lg" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: var(--radius-lg); transition: var(--transition);">
            
            <div class="text-center mb-4">
                <div class="logo-icon d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--warning), var(--primary)); color: white; font-size: 24px; box-shadow: 0 8px 16px rgba(245, 158, 11, 0.25);">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h2 class="font-weight-bold" style="letter-spacing: -0.5px; color: var(--text-main);">Quên Mật Khẩu</h2>
                <p class="text-muted small">Nhập tên đăng nhập hoặc email đã đăng ký để tìm lại mật khẩu</p>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer">
                <?php if (isset($_SESSION['reset_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-circle-check mr-1"></i> <?php echo $_SESSION['reset_success']; unset($_SESSION['reset_success']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['reset_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $_SESSION['reset_error']; unset($_SESSION['reset_error']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form quên mật khẩu -->
            <form action="<?php echo BASE_URL; ?>/Account/sendResetLink" method="POST">
                <div class="form-group mb-4">
                    <label for="username_or_email" class="small font-weight-bold mb-2" style="color: var(--text-main);">TÊN ĐĂNG NHẬP HOẶC EMAIL</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-regular fa-envelope"></i></span>
                        </div>
                        <input type="text" class="form-control" id="username_or_email" name="username_or_email" placeholder="Nhập tên đăng nhập hoặc email" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <button type="submit" class="btn btn-premium btn-block py-3 mt-2" style="border-radius: var(--radius-md); font-weight: 700; font-size: 15px; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--warning), var(--primary)); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                    GỬI LIÊN KẾT KHÔI PHỤC <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>/Account/login" class="small font-weight-bold" style="color: var(--primary); text-decoration: none;"><i class="fa-solid fa-arrow-left mr-1"></i> Quay lại Đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<style>
    body.dark-theme .forgot-card {
        background: rgba(19, 27, 46, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    .form-control:focus {
        box-shadow: none !important;
        border-color: var(--warning) !important;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--warning) !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>
