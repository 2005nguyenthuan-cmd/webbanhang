<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <!-- Glassmorphism Reset Card -->
        <div class="reset-card p-5 shadow-lg" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: var(--radius-lg); transition: var(--transition);">
            
            <div class="text-center mb-4">
                <div class="logo-icon d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), var(--primary)); color: white; font-size: 24px; box-shadow: 0 8px 16px rgba(6, 182, 212, 0.25);">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <h2 class="font-weight-bold" style="letter-spacing: -0.5px; color: var(--text-main);">Đặt Lại Mật Khẩu</h2>
                <p class="text-muted small">Nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <!-- Báo lỗi nếu có -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> 
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $field => $errMsg): ?>
                            <li><?php echo htmlspecialchars($errMsg); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Form đặt lại mật khẩu -->
            <form action="<?php echo BASE_URL; ?>/Account/processResetPassword" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? $_POST['token'] ?? ''); ?>">

                <div class="form-group mb-3">
                    <label for="password" class="small font-weight-bold mb-2" style="color: var(--text-main);">MẬT KHẨU MỚI</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu mới ít nhất 6 ký tự" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="confirmpassword" class="small font-weight-bold mb-2" style="color: var(--text-main);">XÁC NHẬN MẬT KHẨU MỚI</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Nhập lại mật khẩu mới" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <button type="submit" class="btn btn-premium btn-block py-3" style="border-radius: var(--radius-md); font-weight: 700; font-size: 15px; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--secondary), var(--primary)); box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);">
                    CẬP NHẬT MẬT KHẨU <i class="fa-solid fa-check ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    body.dark-theme .reset-card {
        background: rgba(19, 27, 46, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    .form-control:focus {
        box-shadow: none !important;
        border-color: var(--secondary) !important;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--secondary) !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>
