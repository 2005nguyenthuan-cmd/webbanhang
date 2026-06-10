<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <!-- Glassmorphism Register Card -->
        <div class="register-card p-5 shadow-lg" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: var(--radius-lg); transition: var(--transition);">
            
            <div class="text-center mb-4">
                <div class="logo-icon d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), var(--primary)); color: white; font-size: 24px; box-shadow: 0 8px 16px rgba(6, 182, 212, 0.25);">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h2 class="font-weight-bold" style="letter-spacing: -0.5px; color: var(--text-main);">Đăng Ký</h2>
                <p class="text-muted small">Tạo tài khoản TechStore hoàn toàn miễn phí</p>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer">
                <?php if (isset($errors) && count($errors) > 0): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); font-size: 14px; font-weight: 500;">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $err; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Form đăng ký - submit trực tiếp tới server -->
            <form action="<?php echo BASE_URL; ?>/Account/save" method="POST">
                <div class="form-group mb-3">
                    <label for="fullname" class="small font-weight-bold mb-2" style="color: var(--text-main);">HỌ VÀ TÊN</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-regular fa-id-card"></i></span>
                        </div>
                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ và tên của bạn" required value="<?php echo htmlspecialchars($fullName ?? ''); ?>" style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="small font-weight-bold mb-2" style="color: var(--text-main);">EMAIL XÁC THỰC</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-regular fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập địa chỉ email của bạn" required value="<?php echo htmlspecialchars($email ?? ''); ?>" style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="username" class="small font-weight-bold mb-2" style="color: var(--text-main);">TÊN ĐĂNG NHẬP</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-regular fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Chọn tên đăng nhập viết liền" required value="<?php echo htmlspecialchars($username ?? ''); ?>" style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="small font-weight-bold mb-2" style="color: var(--text-main);">MẬT KHẨU</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Tạo mật khẩu bảo mật" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="confirmpassword" class="small font-weight-bold mb-2" style="color: var(--text-main);">XÁC NHẬN MẬT KHẨU</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent" style="border-right: none; border-top-left-radius: var(--radius-md); border-bottom-left-radius: var(--radius-md); border-color: var(--border-color); color: var(--text-muted);"><i class="fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Nhập lại mật khẩu" required style="border-left: none; border-top-right-radius: var(--radius-md); border-bottom-right-radius: var(--radius-md); border-color: var(--border-color); background-color: transparent; color: var(--text-main); font-weight: 500; height: 46px; transition: var(--transition);">
                    </div>
                </div>

                <button type="submit" class="btn btn-premium btn-block py-3 mt-4" style="border-radius: var(--radius-md); font-weight: 700; font-size: 15px; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--secondary), var(--primary)); box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);">
                    ĐĂNG KÝ NGAY <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Đã có tài khoản?</span>
                <a href="<?php echo BASE_URL; ?>/Account/login" class="small font-weight-bold ml-1" style="color: var(--secondary); text-decoration: none;">Đăng nhập</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dark Theme specifics for register card */
    body.dark-theme .register-card {
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