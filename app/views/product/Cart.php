<?php include 'app/views/shares/header.php'; ?>

<div class="row text-left mb-5">
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card premium-card border-0 p-4">
            <h2 class="h4 font-weight-bold mb-4" style="color: var(--text-main); letter-spacing: -0.5px;">
                <i class="fa-solid fa-cart-shopping mr-2 text-primary"></i>Giỏ Hàng Của Bạn
            </h2>
            
            <?php if (!empty($cart)): ?>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle" style="color: var(--text-main);">
                        <thead>
                            <tr class="border-bottom" style="color: var(--text-muted); font-size: 13px; font-weight: 700; text-transform: uppercase;">
                                <th scope="col" style="width: 50%;">Sản phẩm</th>
                                <th scope="col" class="text-center">Đơn giá</th>
                                <th scope="col" class="text-center" style="width: 15%;">Số lượng</th>
                                <th scope="col" class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($cart as $id => $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr class="border-bottom align-middle" style="transition: var(--transition);">
                                    <td class="py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; border: 1px solid var(--border-color); flex-shrink: 0;">
                                                <?php if ($item['image']): ?>
                                                    <?php if (filter_var($item['image'], FILTER_VALIDATE_URL) || strpos($item['image'], 'http://') === 0 || strpos($item['image'], 'https://') === 0): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                                    <?php else: ?>
                                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/100x100?text=Tech" class="img-fluid" alt="No image">
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h6 class="font-weight-bold mb-1" style="font-size: 15px; color: var(--text-main);">
                                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </h6>
                                                <div class="d-flex flex-wrap align-items-center mt-1">
                                                    <?php if (isset($item['color'])): ?>
                                                        <span class="badge badge-light border text-muted mr-1 mb-1" style="font-size: 11px; font-weight: 600;"><i class="fa-solid fa-palette mr-1 text-primary"></i><?php echo htmlspecialchars($item['color']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (isset($item['storage'])): ?>
                                                        <span class="badge badge-light border text-muted mb-1" style="font-size: 11px; font-weight: 600;"><i class="fa-solid fa-microchip mr-1 text-secondary"></i><?php echo htmlspecialchars($item['storage']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-4 font-weight-bold" style="font-size: 15px; vertical-align: middle;">
                                        <?php echo number_format($item['price'], 0, ',', '.'); ?> ₫
                                    </td>
                                    <td class="text-center py-4" style="vertical-align: middle;">
                                        <div class="px-2 py-1 rounded text-center font-weight-bold border" style="background-color: var(--light); min-width: 45px; display: inline-block;">
                                            <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </td>
                                    <td class="text-right py-4 font-weight-bold text-primary" style="font-size: 16px; vertical-align: middle;">
                                        <?php echo number_format($subtotal, 0, ',', '.'); ?> ₫
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-light border py-2 px-3" style="border-radius: var(--radius-sm); font-weight: 600; color: var(--text-main);">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-basket-shopping fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Giỏ hàng của bạn đang trống</h4>
                    <p class="text-muted small mb-4">Hãy chọn mua các sản phẩm chất lượng tốt nhất từ cửa hàng của chúng tôi.</p>
                    <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-premium">
                        <i class="fa-solid fa-store mr-2"></i> Đến cửa hàng ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Order Summary Column -->
    <?php if (!empty($cart)): ?>
        <div class="col-lg-4">
            <div class="card premium-card border-0 p-4 shadow-sm" style="position: sticky; top: 100px;">
                <h3 class="h5 font-weight-bold mb-4" style="color: var(--text-main);">Tóm tắt đơn hàng</h3>
                
                <div class="d-flex justify-content-between mb-3" style="font-size: 14px;">
                    <span class="text-muted">Tạm tính:</span>
                    <span class="font-weight-bold" style="color: var(--text-main);"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="d-flex justify-content-between mb-3" style="font-size: 14px;">
                    <span class="text-muted">Phí vận chuyển:</span>
                    <span class="text-success font-weight-bold">Miễn phí</span>
                </div>
                <hr class="my-3" style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between mb-4">
                    <span class="font-weight-bold" style="color: var(--text-main); font-size: 16px;">Tổng cộng:</span>
                    <span class="h4 font-weight-bold text-danger mb-0"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/Product/checkout" class="btn btn-lg btn-premium w-100 py-3 mb-2" style="border-radius: var(--radius-md); font-size: 16px;">
                    <i class="fa-solid fa-credit-card mr-2"></i> Tiến hành thanh toán
                </a>
                <p class="text-muted text-center small mt-3 mb-0">Đảm bảo an toàn và bảo mật thông tin tuyệt đối.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>