<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center mb-5">
    <div class="col-lg-11">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0" style="font-size: 14px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/Product/" style="color: var(--primary); text-decoration: none;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--text-muted);"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></li>
            </ol>
        </nav>

        <?php if ($product): ?>
        <div class="card premium-card border-0 p-4">
            <div class="row">
                <!-- Product Images -->
                <div class="col-md-5 text-center mb-4 mb-md-0 d-flex flex-column justify-content-between">
                    <div class="p-3 bg-light rounded-lg d-flex align-items-center justify-content-center position-relative overflow-hidden" style="height: 380px; border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                        <?php if ($product->image): ?>
                            <?php if (filter_var($product->image, FILTER_VALIDATE_URL) || strpos($product->image, 'http://') === 0 || strpos($product->image, 'https://') === 0): ?>
                                <img src="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 100%; object-fit: contain;">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 100%; object-fit: contain;">
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x400?text=No+Image" class="img-fluid" alt="No Image">
                        <?php endif; ?>
                        
                        <div class="position-absolute" style="top: 15px; right: 15px;">
                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius: var(--radius-sm);">
                                <i class="fa-solid fa-shield mr-1"></i> Chính Hãng
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-md-7 pl-md-5 text-left">
                    <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold mb-3">
                        <i class="fas fa-tag mr-1"></i> <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Điện thoại'; ?>
                    </span>
                    
                    <h1 class="h2 font-weight-bold mb-3" style="color: var(--text-main); letter-spacing: -0.5px;">
                        <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                    </h1>
                    
                    <!-- Rating and Reviews Mock -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="text-warning mr-2">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                        </div>
                        <span class="text-muted small" style="font-weight: 500;">4.8 (85 đánh giá) | Đã bán 250+</span>
                    </div>

                    <!-- Price Box -->
                    <div class="p-3 mb-4 rounded-lg d-flex align-items-baseline" style="background: var(--light); border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
                        <span class="text-muted mr-3" style="font-size: 14px; font-weight: 600;">Giá bán:</span>
                        <span class="h1 text-danger font-weight-bold mb-0" style="letter-spacing: -1px;"><?php echo number_format($product->price, 0, ',', '.'); ?> ₫</span>
                    </div>

                    <!-- Variants Selection UI -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-secondary small text-uppercase mb-2">Chọn màu sắc:</h6>
                        <div class="d-flex">
                            <button class="btn btn-outline-secondary mr-2 py-2 px-3 active-variant color-btn" data-color="Đen Nhám" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                                <i class="fa-solid fa-circle mr-1 text-dark"></i> Đen Nhám
                            </button>
                            <button class="btn btn-outline-secondary mr-2 py-2 px-3 color-btn" data-color="Xám Titan" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                                <i class="fa-solid fa-circle mr-1 text-secondary"></i> Xám Titan
                            </button>
                            <button class="btn btn-outline-secondary py-2 px-3 color-btn" data-color="Vàng Gold" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                                <i class="fa-solid fa-circle mr-1 text-warning"></i> Vàng Gold
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-secondary small text-uppercase mb-2">Bộ nhớ trong:</h6>
                        <div class="d-flex">
                            <button class="btn btn-outline-secondary mr-2 py-2 px-3 active-variant storage-btn" data-storage="128 GB" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">128 GB</button>
                            <button class="btn btn-outline-secondary mr-2 py-2 px-3 storage-btn" data-storage="256 GB" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">256 GB</button>
                            <button class="btn btn-outline-secondary py-2 px-3 storage-btn" data-storage="512 GB" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">512 GB</button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-secondary small text-uppercase mb-2">Đặc điểm nổi bật:</h6>
                        <div class="text-muted" style="font-size: 14px; line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-5 pt-3 border-top d-flex">
                        <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $product->id; ?>" id="addToCartBtn" class="btn btn-lg btn-premium flex-grow-1 mr-3 py-3" style="border-radius: var(--radius-md); font-size: 16px;">
                            <i class="fas fa-cart-plus mr-2"></i> Thêm vào giỏ hàng
                        </a>
                        <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-lg btn-light border py-3 px-4" style="border-radius: var(--radius-md); color: var(--text-main); font-size: 16px; font-weight: 600; transition: var(--transition);">
                            <i class="fa-solid fa-arrow-left-long mr-2"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Specifications and Reviews Tabs -->
        <div class="card premium-card border-0 p-4 mt-4 text-left">
            <h4 class="font-weight-bold mb-4" style="color: var(--text-main);">Thông tin chi tiết & Đánh giá</h4>
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h5 class="h6 font-weight-bold text-uppercase text-secondary mb-3">Thông số kỹ thuật</h5>
                    <table class="table table-striped table-bordered" style="font-size: 14px; color: var(--text-main);">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold" style="width: 35%;">Màn hình</td>
                                <td>OLED Super Retina XDR</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Hệ điều hành</td>
                                <td>Mới nhất</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Camera sau</td>
                                <td>Chuyên nghiệp 48MP</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Camera trước</td>
                                <td>12MP, f/1.9</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Dung lượng pin</td>
                                <td>Bền bỉ cả ngày</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h5 class="h6 font-weight-bold text-uppercase text-secondary mb-3">Đánh giá tiêu biểu</h5>
                    <div class="media mb-3">
                        <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: 700;">N</div>
                        <div class="media-body">
                            <h6 class="mt-0 font-weight-bold mb-1" style="color: var(--text-main);">Nguyễn Văn A <small class="text-muted ml-2">Đã mua hàng</small></h6>
                            <div class="text-warning mb-1" style="font-size: 12px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="text-muted mb-0" style="font-size: 13px;">Sản phẩm cực kỳ tốt, chụp ảnh sắc nét, pin trâu dùng cả ngày không hết. Rất đáng đồng tiền bát gạo!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center py-5 shadow-sm" style="border-radius: var(--radius-lg);">
            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
            <h4>Rất tiếc, không tìm thấy sản phẩm!</h4>
            <p>Sản phẩm có thể đã bị xóa hoặc liên kết không đúng.</p>
            <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-premium mt-3">Quay lại danh sách</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .active-variant {
        border-color: var(--primary) !important;
        background-color: var(--primary-light) !important;
        color: var(--primary) !important;
    }
    body.dark-theme .active-variant {
        background-color: rgba(79, 70, 229, 0.2) !important;
    }
    .color-btn, .storage-btn {
        cursor: pointer;
        transition: var(--transition);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const colorBtns = document.querySelectorAll('.color-btn');
    const storageBtns = document.querySelectorAll('.storage-btn');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const baseCartUrl = "<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $product->id; ?>";

    let selectedColor = "Đen Nhám";
    let selectedStorage = "128 GB";

    function updateCartLink() {
        const encodedColor = encodeURIComponent(selectedColor);
        const encodedStorage = encodeURIComponent(selectedStorage);
        addToCartBtn.href = `${baseCartUrl}?color=${encodedColor}&storage=${encodedStorage}`;
    }

    colorBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            colorBtns.forEach(b => b.classList.remove('active-variant'));
            this.classList.add('active-variant');
            selectedColor = this.getAttribute('data-color');
            updateCartLink();
        });
    });

    storageBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            storageBtns.forEach(b => b.classList.remove('active-variant'));
            this.classList.add('active-variant');
            selectedStorage = this.getAttribute('data-storage');
            updateCartLink();
        });
    });

    // Initialize link on load
    updateCartLink();
});
</script>

<?php include 'app/views/shares/footer.php'; ?>