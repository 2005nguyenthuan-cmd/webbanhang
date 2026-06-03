<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center text-left mb-5">
    <div class="col-md-9 col-lg-8">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0" style="font-size: 14px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/Product/" style="color: var(--primary); text-decoration: none;">Cửa hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--text-muted);">Sửa sản phẩm</li>
            </ol>
        </nav>

        <div class="card premium-card border-0 p-4">
            <div class="border-bottom pb-3 mb-4">
                <h1 class="h4 font-weight-bold mb-1" style="color: var(--text-main); letter-spacing: -0.5px;">
                    <i class="fa-solid fa-pen-to-square mr-2 text-primary"></i>Chỉnh sửa sản phẩm
                </h1>
                <p class="text-muted mb-0" style="font-size: 13px;">Cập nhật lại các thông tin của sản phẩm dưới đây</p>
            </div>
            
            <div class="card-body p-0">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4" style="border-radius: var(--radius-sm);">
                    <ul class="mb-0 small font-weight-bold">
                        <?php foreach ($errors as $error): ?>
                        <li><i class="fa-solid fa-triangle-exclamation mr-1"></i><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/Product/update" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                    <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">
                    
                    <div class="form-group mb-4">
                        <label for="name" class="font-weight-bold text-secondary small text-uppercase mb-2">Tên điện thoại / sản phẩm:</label>
                        <input type="text" id="name" name="name" class="form-control form-control-lg border" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="border-radius: var(--radius-sm); font-size: 15px;" required>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description" class="font-weight-bold text-secondary small text-uppercase mb-2">Mô tả chi tiết sản phẩm:</label>
                        <textarea id="description" name="description" class="form-control border" rows="5" style="border-radius: var(--radius-sm); font-size: 14px;" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="price" class="font-weight-bold text-secondary small text-uppercase mb-2">Giá bán niêm yết (₫):</label>
                                <div class="input-group">
                                    <input type="number" id="price" name="price" class="form-control border" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" style="border-radius: var(--radius-sm) 0 0 var(--radius-sm); font-size: 15px;" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text font-weight-bold text-muted bg-light" style="border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">VND</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="category_id" class="font-weight-bold text-secondary small text-uppercase mb-2">Hãng sản xuất / Danh mục:</label>
                                <select id="category_id" name="category_id" class="form-control border" style="border-radius: var(--radius-sm); font-size: 15px; height: auto; padding: 10px 15px;" required>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-secondary small text-uppercase d-block mb-2">Hình ảnh hiện tại:</label>
                        <?php if ($product->image): ?>
                            <div class="p-2 bg-light rounded mb-3 d-inline-block border" style="max-height: 170px;">
                                <?php if (filter_var($product->image, FILTER_VALIDATE_URL) || strpos($product->image, 'http://') === 0 || strpos($product->image, 'https://') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" class="img-thumbnail border-0" style="max-height: 140px; object-fit: contain; background: transparent;">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL . '/' . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" class="img-thumbnail border-0" style="max-height: 140px; object-fit: contain; background: transparent;">
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-3"><i class="fa-solid fa-image-slash mr-1"></i>Chưa có hình ảnh đại diện.</p>
                        <?php endif; ?>
                        
                        <label for="image" class="font-weight-bold text-secondary small text-uppercase d-block mb-2">Thay đổi ảnh đại diện mới:</label>
                        <div class="custom-file mb-2" style="height: 50px;">
                            <input type="file" id="image" name="image" class="custom-file-input" style="height: 50px;">
                            <label class="custom-file-label d-flex align-items-center" for="image" style="height: 50px; border-radius: var(--radius-sm); font-size: 14px; color: var(--text-muted);">
                                <i class="fa-regular fa-image mr-2 text-primary" style="font-size: 18px;"></i>Chọn hình ảnh mới...
                            </label>
                        </div>
                        <div class="text-center my-2 text-muted font-weight-bold small">- HOẶC NHẬP ĐƯỜNG DẪN ẢNH (URL) MỚI -</div>
                        <input type="text" id="image_url" name="image_url" class="form-control border" value="<?php echo (filter_var($product->image, FILTER_VALIDATE_URL) || strpos($product->image, 'http://') === 0 || strpos($product->image, 'https://') === 0) ? htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Nhập liên kết ảnh trực tuyến (ví dụ: https://example.com/iphone.jpg)" style="border-radius: var(--radius-sm); font-size: 14px; padding: 12px 15px; height: auto;">
                    </div>
                    
                    <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                        <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-light border px-4 py-2.5 mr-2" style="border-radius: var(--radius-md); font-weight: 600; color: var(--text-main);">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-premium px-5 py-2.5" style="border-radius: var(--radius-md);">
                            <i class="fas fa-check mr-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Hiển thị tên file đã chọn trên nhãn custom-file-input
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.querySelector('.custom-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    var fileName = e.target.files[0].name;
                    var nextSibling = e.target.nextElementSibling;
                    nextSibling.innerHTML = '<i class="fa-regular fa-image mr-2 text-success" style="font-size: 18px;"></i>' + fileName;
                }
            });
        }
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>