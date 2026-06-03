<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center mb-5">
    <div class="col-md-7 text-center">
        <div class="card premium-card border-0 p-5 shadow-lg" style="border-radius: var(--radius-lg);">
            <div class="mb-4">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 80px; filter: drop-shadow(0 8px 16px rgba(16, 185, 129, 0.2));"></i>
            </div>
            
            <h1 class="h3 font-weight-bold mb-3" style="color: var(--text-main); letter-spacing: -0.5px;">Đặt Hàng Thành Công!</h1>
            
            <p class="text-muted mb-4 mx-auto" style="max-width: 450px; font-size: 15px; line-height: 1.6;">
                Cảm ơn bạn đã tin tưởng mua sắm tại <strong>TechStore</strong>. Đơn hàng của bạn đã được hệ thống ghi nhận thành công và đang được nhân viên chuẩn bị giao đi.
            </p>
            
            <div class="p-3 mb-4 rounded border text-left" style="background-color: var(--light); border-color: var(--border-color); font-size: 14px;">
                <span class="text-muted d-block mb-1"><i class="fa-solid fa-circle-info mr-2 text-primary"></i><strong>Thông tin thêm:</strong></span>
                <span class="text-muted">Chúng tôi sẽ gửi email xác nhận chi tiết đơn hàng hoặc liên hệ qua số điện thoại của bạn trước khi giao hàng. Thời gian giao hàng dự kiến từ 2-4 ngày làm việc.</span>
            </div>
            
            <div class="d-flex justify-content-center">
                <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-premium px-5 py-3" style="border-radius: var(--radius-md); font-size: 15px; font-weight: 700;">
                    <i class="fa-solid fa-bag-shopping mr-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>