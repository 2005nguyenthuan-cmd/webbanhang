<?php include 'app/views/shares/header.php'; ?>

<div class="row mb-5 justify-content-center">
    <div class="col-lg-10">
        
        <div class="mb-4">
            <h1 class="font-weight-bold mb-1" style="color: var(--text-main); letter-spacing: -1px;">Hồ Sơ Cá Nhân</h1>
            <p class="text-muted mb-0">Quản lý thông tin tài khoản, ảnh đại diện và bảo mật của bạn</p>
        </div>

        <div class="row">
            
            <!-- LEFT PANEL: USER CARD -->
            <div class="col-md-4 mb-4">
                <div class="premium-card p-4 text-center">
                    <div class="avatar-container position-relative d-inline-block mb-3">
                        <?php 
                        $avatarUrl = !empty($user->avatar) ? (strpos($user->avatar, 'http') === 0 ? $user->avatar : BASE_URL . '/' . $user->avatar) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($user->fullname ?? $user->username); 
                        ?>
                        <img id="userAvatar" src="<?php echo htmlspecialchars($avatarUrl); ?>" 
                             alt="Avatar" 
                             class="rounded-circle img-thumbnail border border-primary shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                        
                        <span id="userVerifyBadge" class="position-absolute d-flex align-items-center justify-content-center text-white rounded-circle shadow <?php echo $user->is_verified ? 'bg-success' : 'bg-warning text-dark'; ?>" 
                              style="width: 28px; height: 28px; bottom: 5px; right: 5px; font-size: 12px; border: 2px solid var(--card-bg);">
                            <?php if ($user->is_verified): ?>
                                <i class="fa-solid fa-check"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <h4 id="userFullName" class="font-weight-bold mb-1 text-main"><?php echo htmlspecialchars($user->fullname); ?></h4>
                    <p id="userUsername" class="text-muted small mb-3">@<?php echo htmlspecialchars($user->username); ?></p>
                    
                    <div class="mb-3" id="userBadgeContainer">
                        <?php if ($user->role === 'admin'): ?>
                            <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 11px; border-radius: 4px; text-transform: uppercase;">
                                <i class="fa-solid fa-shield-halved mr-1"></i> Quản trị viên
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary px-3 py-2 font-weight-bold" style="font-size: 11px; border-radius: 4px; text-transform: uppercase;">
                                <i class="fa-solid fa-user mr-1"></i> Khách hàng
                            </span>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4" style="border-color: var(--border-color);">
                    
                    <div class="text-left">
                        <div class="small mb-2 text-muted"><i class="fa-regular fa-envelope mr-2"></i><strong>Email:</strong> <span id="userEmailText"><?php echo htmlspecialchars($user->email ?? 'Chưa cập nhật'); ?></span></div>
                        <div class="small mb-2 text-muted">
                            <i class="fa-solid fa-circle-check mr-2"></i><strong>Xác thực:</strong> 
                            <span id="userVerificationText">
                                <?php if ($user->is_verified): ?>
                                    <span class="text-success font-weight-bold">Đã xác minh</span>
                                <?php else: ?>
                                    <span class="text-warning font-weight-bold">Chưa xác minh</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: FORMS -->
            <div class="col-md-8">
                
                <!-- PROFILE UPDATE SUCCESS/ERROR ALERTS -->
                <div id="profileAlert">
                    <?php if (isset($_SESSION['profile_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md);">
                            <i class="fa-solid fa-circle-check mr-1"></i> <?php echo $_SESSION['profile_success']; unset($_SESSION['profile_success']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['profile_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md);">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $_SESSION['profile_error']; unset($_SESSION['profile_error']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- TAB CARD: ACCOUNT DETAILS -->
                <div class="premium-card p-4 mb-4">
                    <h3 class="font-weight-bold mb-4 text-main" style="font-size: 18px;"><i class="fa-regular fa-circle-user mr-2 text-primary"></i>Thông Tin Tài Khoản</h3>
                    
                    <form action="<?php echo BASE_URL; ?>/Account/updateProfile" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="fullname" class="small font-weight-bold text-muted mb-2">HỌ VÀ TÊN</label>
                                <input type="text" class="form-control profile-input" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user->fullname); ?>" required style="height: 44px; border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="email" class="small font-weight-bold text-muted mb-2">ĐỊA CHỈ EMAIL</label>
                                <input type="email" class="form-control profile-input" id="email" name="email" value="<?php echo htmlspecialchars($user->email ?? ''); ?>" required style="height: 44px; border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;">
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="avatar" class="small font-weight-bold text-muted mb-2">TẢI LÊN ẢNH ĐẠI DIỆN MỚI</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                <label class="custom-file-label" id="avatarLabel" for="avatar" style="border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-muted); font-weight: 500; height: 44px; line-height: 30px;">Chọn tệp hình ảnh...</label>
                            </div>
                            <small class="form-text text-muted mt-2">Định dạng hỗ trợ: JPG, PNG, GIF. Dung lượng tối đa: 5MB.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-premium px-4 py-2 font-weight-bold">
                            LƯU THÔNG TIN <i class="fa-solid fa-floppy-disk ml-1"></i>
                        </button>
                    </form>
                </div>

                <!-- PASSWORD SECURITY SUCCESS/ERROR ALERTS -->
                <div id="passwordAlert">
                    <?php if (isset($_SESSION['pw_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md);">
                            <i class="fa-solid fa-circle-check mr-1"></i> <?php echo $_SESSION['pw_success']; unset($_SESSION['pw_success']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['pw_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md);">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $_SESSION['pw_error']; unset($_SESSION['pw_error']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- TAB CARD: CHANGE PASSWORD -->
                <div class="premium-card p-4">
                    <h3 class="font-weight-bold mb-4 text-main" style="font-size: 18px;"><i class="fa-solid fa-lock mr-2 text-warning"></i>Đổi Mật Khẩu</h3>
                    
                    <form action="<?php echo BASE_URL; ?>/Account/updatePassword" method="POST">
                        <div class="form-group mb-3">
                            <label for="old_password" class="small font-weight-bold text-muted mb-2">MẬT KHẨU HIỆN TẠI</label>
                            <input type="password" class="form-control profile-input" id="old_password" name="old_password" placeholder="Nhập mật khẩu đang sử dụng" required style="height: 44px; border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="new_password" class="small font-weight-bold text-muted mb-2">MẬT KHẨU MỚI</label>
                                <input type="password" class="form-control profile-input" id="new_password" name="new_password" placeholder="Tối thiểu 6 ký tự" required style="height: 44px; border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;">
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label for="confirm_password" class="small font-weight-bold text-muted mb-2">XÁC NHẬN MẬT KHẨU MỚI</label>
                                <input type="password" class="form-control profile-input" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required style="height: 44px; border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-outline-premium px-4 py-2 font-weight-bold">
                            THAY ĐỔI MẬT KHẨU <i class="fa-solid fa-key ml-1"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('avatar');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0].name;
                const label = document.getElementById('avatarLabel');
                if (label) {
                    label.innerText = fileName;
                }
            });
        }
    });
</script>

<style>
    .profile-input:focus {
        box-shadow: none !important;
        border-color: var(--primary) !important;
        background: transparent !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>
