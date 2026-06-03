<?php include 'app/views/shares/header.php'; ?>

<!-- Hero Section -->
<div class="p-5 mb-5 rounded-lg text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #4f46e5, #06b6d4); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
    <!-- Background Decor Shapes -->
    <div style="position: absolute; width: 300px; height: 300px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); top: -100px; right: -50px;"></div>
    <div style="position: absolute; width: 150px; height: 150px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); bottom: -50px; left: 10%; decoration: blur;"></div>
    
    <div class="row align-items-center position-relative" style="z-index: 1;">
        <div class="col-lg-7 text-left">
            <span class="badge badge-pill badge-warning px-3 py-2 text-dark font-weight-bold mb-3">NEW COLLECTION</span>
            <h1 class="display-4 font-weight-bold mb-3" style="letter-spacing: -1.5px; line-height: 1.1;">Trải Nghiệm Công Nghệ Đỉnh Cao</h1>
            <p class="lead mb-4" style="opacity: 0.9; font-weight: 400; max-width: 550px;">
                Khám phá thế giới điện thoại di động thông minh chính hãng với ưu đãi đặc quyền lớn nhất trong năm. Trả góp 0%, bảo hành 2 năm toàn quốc.
            </p>
            <a href="#product-section" class="btn btn-warning btn-lg px-4 py-3 font-weight-bold shadow-sm" style="border-radius: var(--radius-md); transition: var(--transition);">
                <i class="fa-solid fa-fire mr-2"></i> Mua Ngay
            </a>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-center">
            <i class="fa-solid fa-mobile-screen-button text-white" style="font-size: 180px; opacity: 0.15; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.2));"></i>
        </div>
    </div>
</div>

<div id="product-section" class="mb-5">
    
    <!-- Category Filter Slider -->
    <?php if (!empty($categories)): ?>
        <div class="mb-4 p-3 rounded-lg d-flex align-items-center" style="background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow-x: auto; gap: 10px; scrollbar-width: none;">
            <span class="font-weight-bold text-muted small mr-2 d-none d-md-inline" style="letter-spacing: 0.5px; text-transform: uppercase;"><i class="fa-solid fa-filter mr-1"></i> Lọc theo:</span>
            <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-sm py-2 px-3 font-weight-bold <?php echo (!isset($activeCategory) && (!isset($keyword) || $keyword === '')) ? 'btn-premium' : 'btn-light border'; ?>" style="border-radius: 20px; white-space: nowrap; transition: var(--transition);">
                Tất cả sản phẩm
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo BASE_URL; ?>/Product/category/<?php echo $cat->id; ?>" class="btn btn-sm py-2 px-3 font-weight-bold <?php echo (isset($activeCategory) && $activeCategory->id == $cat->id) ? 'btn-premium' : 'btn-light border'; ?>" style="border-radius: 20px; white-space: nowrap; transition: var(--transition);">
                    <?php echo htmlspecialchars($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Active Filters Alert -->
    <?php if (isset($keyword) && $keyword !== ''): ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4 p-3 shadow-sm border-0" style="border-radius: var(--radius-md); font-weight: 500; font-size: 14px; background: rgba(79, 70, 229, 0.1); color: var(--primary);">
            <div>
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Kết quả tìm kiếm từ khóa: <strong>"<?php echo htmlspecialchars($keyword); ?>"</strong>
                <span class="badge badge-primary ml-2 py-1 px-2" style="border-radius: 4px;"><?php echo count($products); ?> sản phẩm</span>
            </div>
            <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-sm btn-light font-weight-bold py-1 px-3" style="border-radius: 4px; border: 1px solid var(--border-color); color: var(--text-main);"><i class="fa-solid fa-xmark mr-1"></i>Xóa tìm kiếm</a>
        </div>
    <?php elseif (isset($activeCategory)): ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4 p-3 shadow-sm border-0" style="border-radius: var(--radius-md); font-weight: 500; font-size: 14px; background: rgba(6, 182, 212, 0.1); color: var(--secondary);">
            <div>
                <i class="fa-solid fa-tag mr-1"></i> Danh mục: <strong>"<?php echo htmlspecialchars($activeCategory->name); ?>"</strong>
                <span class="badge badge-info ml-2 py-1 px-2 text-white" style="border-radius: 4px; background-color: var(--secondary);"><?php echo count($products); ?> sản phẩm</span>
            </div>
            <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-sm btn-light font-weight-bold py-1 px-3" style="border-radius: 4px; border: 1px solid var(--border-color); color: var(--text-main);"><i class="fa-solid fa-xmark mr-1"></i>Hủy bộ lọc</a>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="font-weight-bold mb-1" style="color: var(--text-main); letter-spacing: -0.5px;">
                <?php 
                if (isset($activeCategory)) echo htmlspecialchars($activeCategory->name);
                elseif (isset($keyword) && $keyword !== '') echo 'Kết quả tìm kiếm';
                else echo 'Sản Phẩm Nổi Bật';
                ?>
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                <?php
                if (isset($activeCategory)) echo htmlspecialchars($activeCategory->description ?? 'Tuyển chọn những dòng điện thoại di động tốt nhất hiện nay');
                else echo 'Tuyển chọn những dòng điện thoại di động tốt nhất hiện nay';
                ?>
            </p>
        </div>
        <?php if (AccountModel::isAdmin()): ?>
            <a href="<?php echo BASE_URL; ?>/Product/add" class="btn btn-outline-premium btn-sm" style="border-radius: var(--radius-md);">
                <i class="fa-solid fa-plus mr-1"></i> Thêm sản phẩm mới
            </a>
        <?php endif; ?>
    </div>

    <!-- Product Grid -->
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card premium-card h-100 d-flex flex-column">
                        <!-- Product Image Container -->
                        <div class="position-relative overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 220px; transition: var(--transition);">
                            <?php if ($product->image): ?>
                                <?php if (filter_var($product->image, FILTER_VALIDATE_URL) || strpos($product->image, 'http://') === 0 || strpos($product->image, 'https://') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                         class="img-fluid p-3 product-img" 
                                         style="max-height: 100%; transition: transform 0.5s ease; object-fit: contain;">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL . '/' . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                         class="img-fluid p-3 product-img" 
                                         style="max-height: 100%; transition: transform 0.5s ease; object-fit: contain;">
                                <?php endif; ?>
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x300?text=No+Image" 
                                     alt="No Image" 
                                     class="img-fluid p-3" 
                                     style="max-height: 100%; object-fit: contain;">
                            <?php endif; ?>
                            
                            <!-- Badges -->
                            <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                                <span class="badge badge-primary px-2 py-1" style="border-radius: 4px; font-weight: 700; font-size: 11px; text-transform: uppercase;">
                                    <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'SmartPhone'; ?>
                                </span>
                            </div>
                            
                            <!-- Admin Actions (Edit/Delete) - Only visible to Admins -->
                            <?php if (AccountModel::isAdmin()): ?>
                                <div class="position-absolute admin-actions" style="top: 12px; right: 12px; display: flex; gap: 6px; z-index: 10; opacity: 0; transition: var(--transition);">
                                    <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-warning d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; padding: 0; border: none; box-shadow: var(--shadow-sm);" title="Sửa sản phẩm">
                                        <i class="fa-solid fa-pen" style="font-size: 12px;"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $product->id; ?>" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; padding: 0; border: none; box-shadow: var(--shadow-sm);" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" title="Xóa sản phẩm">
                                        <i class="fa-solid fa-trash" style="font-size: 12px;"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <h3 class="h6 font-weight-bold mb-2 text-truncate-2" style="height: 40px; overflow: hidden; line-height: 1.4;">
                                    <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $product->id; ?>" style="color: var(--text-main); text-decoration: none; transition: var(--transition);">
                                        <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </h3>
                                <p class="text-muted small text-truncate-2 mb-3" style="height: 36px; overflow: hidden;">
                                    <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="h6 font-weight-bold text-danger mb-0" style="font-size: 17px;">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> ₫
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="card-footer bg-transparent border-0 px-3 pb-3 pt-0 d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $product->id; ?>" class="btn btn-light btn-sm flex-grow-1 mr-2" style="border-radius: var(--radius-sm); font-weight: 600; color: var(--text-main); border: 1px solid var(--border-color); transition: var(--transition);">
                                <i class="fa-regular fa-eye mr-1"></i> Chi tiết
                            </a>
                            <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-premium btn-sm px-3" style="border-radius: var(--radius-sm);" title="Thêm vào giỏ hàng">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                <p class="text-muted small">Hãy thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc.</p>
                <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-premium mt-3">
                    Quay lại cửa hàng
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Two-line text clamp utility */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .premium-card:hover .product-img {
        transform: scale(1.08);
    }
    
    .premium-card:hover .admin-actions {
        opacity: 1 !important;
    }
    
    .btn-warning {
        background-color: var(--accent);
        border-color: var(--accent);
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #ea580c;
        border-color: #ea580c;
        color: white;
    }
    
    /* Hide scrollbar for category slider */
    .mb-4.p-3::-webkit-scrollbar {
        display: none;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>