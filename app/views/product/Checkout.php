<?php 
include 'app/views/shares/header.php'; 
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="row text-left mb-5">
    <!-- Billing/Shipping Form -->
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card premium-card border-0 p-4">
            <h2 class="h4 font-weight-bold mb-4" style="color: var(--text-main); letter-spacing: -0.5px;">
                <i class="fa-solid fa-truck-fast mr-2 text-primary"></i>Thông Tin Giao Hàng
            </h2>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/Product/processCheckout">
                <div class="form-group mb-3">
                    <label for="name" class="font-weight-bold text-secondary small text-uppercase">Họ và tên người nhận:</label>
                    <input type="text" id="name" name="name" class="form-control form-control-lg border" placeholder="Ví dụ: Nguyễn Văn A" style="border-radius: var(--radius-sm); font-size: 15px;" required>
                </div>
                <div class="form-group mb-3">
                    <label for="phone" class="font-weight-bold text-secondary small text-uppercase">Số điện thoại liên hệ:</label>
                    <input type="tel" id="phone" name="phone" class="form-control form-control-lg border" placeholder="Ví dụ: 0987654321" style="border-radius: var(--radius-sm); font-size: 15px;" required>
                </div>
                <div class="form-group mb-4">
                    <label for="address" class="font-weight-bold text-secondary small text-uppercase">Địa chỉ giao hàng chi tiết:</label>
                    <textarea id="address" name="address" class="form-control border" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" style="border-radius: var(--radius-sm); font-size: 15px;" required></textarea>
                </div>
                
                <!-- Payment Methods -->
                <h3 class="h6 font-weight-bold text-secondary small text-uppercase mb-3">Phương thức thanh toán:</h3>
                <div class="payment-methods mb-4">
                    <div class="custom-control custom-radio p-3 mb-2 rounded border d-flex align-items-center" style="background: var(--light); border-color: var(--border-color); cursor: pointer;">
                        <input type="radio" id="cod" name="payment_method" class="custom-control-input" checked>
                        <label class="custom-control-label pl-2 font-weight-bold" for="cod" style="color: var(--text-main); cursor: pointer;">
                            <i class="fa-solid fa-money-bill-wave text-success mr-2"></i>Thanh toán khi nhận hàng (COD)
                        </label>
                    </div>
                    <div class="custom-control custom-radio p-3 mb-2 rounded border d-flex align-items-center" style="background: var(--light); border-color: var(--border-color); cursor: pointer;">
                        <input type="radio" id="bank" name="payment_method" class="custom-control-input">
                        <label class="custom-control-label pl-2 font-weight-bold" for="bank" style="color: var(--text-main); cursor: pointer;">
                            <i class="fa-solid fa-building-columns text-primary mr-2"></i>Chuyển khoản ngân hàng qua mã QR
                        </label>
                    </div>
                    <div class="custom-control custom-radio p-3 rounded border d-flex align-items-center" style="background: var(--light); border-color: var(--border-color); cursor: pointer;">
                        <input type="radio" id="momo" name="payment_method" class="custom-control-input">
                        <label class="custom-control-label pl-2 font-weight-bold" for="momo" style="color: var(--text-main); cursor: pointer;">
                            <i class="fa-solid fa-wallet text-danger mr-2"></i>Ví điện tử (Momo / ZaloPay)
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-lg btn-premium w-100 py-3 mt-2" style="border-radius: var(--radius-md); font-size: 16px;">
                    <i class="fa-solid fa-circle-check mr-2"></i>Xác nhận & Đặt hàng
                </button>
            </form>
        </div>
    </div>
    
    <!-- Order Summary Sidebar -->
    <div class="col-lg-5">
        <div class="card premium-card border-0 p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h5 font-weight-bold mb-0" style="color: var(--text-main);">Đơn hàng của bạn</h3>
                <a href="<?php echo BASE_URL; ?>/Product/cart" class="text-primary small font-weight-bold" style="text-decoration: none;">Chỉnh sửa</a>
            </div>
            
            <div class="order-items-list mb-4" style="max-height: 280px; overflow-y: auto;">
                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $cartKey => $item): ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="bg-light rounded p-1 mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border: 1px solid var(--border-color); flex-shrink: 0;">
                                <?php if ($item['image']): ?>
                                    <?php if (filter_var($item['image'], FILTER_VALIDATE_URL) || strpos($item['image'], 'http://') === 0 || strpos($item['image'], 'https://') === 0): ?>
                                        <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50x50?text=Tech" class="img-fluid" alt="No image">
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1 min-width-0 pr-2">
                                <h6 class="font-weight-bold text-truncate mb-1" style="font-size: 14px; color: var(--text-main);" title="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="text-muted small" style="font-size: 11px; font-weight: 500;">
                                    <span>SL: <?php echo $item['quantity']; ?></span>
                                    <?php if (isset($item['color'])): ?>
                                        <span class="mx-1">|</span><span><?php echo htmlspecialchars($item['color']); ?></span>
                                    <?php endif; ?>
                                    <?php if (isset($item['storage'])): ?>
                                        <span class="mx-1">|</span><span><?php echo htmlspecialchars($item['storage']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right font-weight-bold" style="font-size: 14px; color: var(--text-main); flex-shrink: 0;">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> ₫
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center my-4">Chưa có sản phẩm nào trong đơn hàng.</p>
                <?php endif; ?>
            </div>
            
            <div class="price-breakdown" style="font-size: 14px;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tổng tiền sản phẩm:</span>
                    <span class="font-weight-bold" style="color: var(--text-main);"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phí giao hàng:</span>
                    <span class="text-success font-weight-bold">Miễn phí</span>
                </div>
                <hr class="my-3" style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <span class="font-weight-bold" style="color: var(--text-main); font-size: 15px;">Tổng thanh toán:</span>
                    <span class="h4 font-weight-bold text-danger mb-0"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>