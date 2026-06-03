<?php include 'app/views/shares/header.php'; ?>

<?php
$activeTab = $_GET['tab'] ?? 'orders';
?>

<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h1 class="font-weight-bold mb-1" style="color: var(--text-main); letter-spacing: -1px;">Bảng Điều Khiển Quản Trị</h1>
                <p class="text-muted mb-0">Quản lý bán hàng, sản phẩm, danh mục và phân quyền tài khoản</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-outline-premium mt-2" style="border-radius: var(--radius-md);">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại cửa hàng
            </a>
        </div>
        
        <!-- Dashboard Navigation Tabs -->
        <div class="premium-card p-3 mb-4" style="border-radius: var(--radius-md);">
            <ul class="nav nav-pills" id="adminTabs" role="tablist" style="gap: 8px;">
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold <?php echo $activeTab === 'orders' ? 'active' : ''; ?>" 
                       href="?tab=orders" style="border-radius: var(--radius-sm); transition: var(--transition);">
                        <i class="fa-solid fa-receipt mr-2"></i> Quản lý Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold <?php echo $activeTab === 'products' ? 'active' : ''; ?>" 
                       href="?tab=products" style="border-radius: var(--radius-sm); transition: var(--transition);">
                        <i class="fa-solid fa-boxes-stacked mr-2"></i> Quản lý Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold <?php echo $activeTab === 'categories' ? 'active' : ''; ?>" 
                       href="?tab=categories" style="border-radius: var(--radius-sm); transition: var(--transition);">
                        <i class="fa-solid fa-tags mr-2"></i> Quản lý Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3 px-4 font-weight-bold <?php echo $activeTab === 'users' ? 'active' : ''; ?>" 
                       href="?tab=users" style="border-radius: var(--radius-sm); transition: var(--transition);">
                        <i class="fa-solid fa-users-gear mr-2"></i> Phân cấp Tài khoản
                    </a>
                </li>
            </ul>
        </div>

        <!-- Dashboard Content Areas -->
        <div class="tab-content">
            
            <!-- TAB 1: ORDERS -->
            <div class="tab-pane fade show <?php echo $activeTab === 'orders' ? 'active' : ''; ?>">
                <div class="premium-card p-4">
                    <h3 class="font-weight-bold mb-4" style="color: var(--text-main); font-size: 20px;"><i class="fa-solid fa-receipt mr-2 text-primary"></i>Danh Sách Đơn Hàng</h3>
                    
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Mã ĐH</th>
                                        <th>Khách hàng</th>
                                        <th>Thông tin liên hệ</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                        <th class="text-right">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td class="font-weight-bold">#<?php echo $order->id; ?></td>
                                            <td>
                                                <div class="font-weight-bold text-main"><?php echo htmlspecialchars($order->name); ?></div>
                                                <?php if (!empty($order->account_name)): ?>
                                                    <span class="badge badge-light text-muted small"><i class="fa-regular fa-user mr-1"></i><?php echo htmlspecialchars($order->account_name); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="small"><i class="fa-solid fa-phone text-muted mr-1"></i><?php echo htmlspecialchars($order->phone); ?></div>
                                                <div class="small text-muted text-truncate" style="max-width: 250px;"><i class="fa-solid fa-location-dot mr-1"></i><?php echo htmlspecialchars($order->address); ?></div>
                                            </td>
                                            <td class="font-weight-bold text-danger"><?php echo number_format($order->total_amount, 0, ',', '.'); ?> ₫</td>
                                            <td class="small"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <?php 
                                                $badgeClass = 'badge-warning';
                                                if ($order->status === 'Đang giao hàng') $badgeClass = 'badge-info';
                                                elseif ($order->status === 'Đã giao') $badgeClass = 'badge-success';
                                                elseif ($order->status === 'Đã hủy') $badgeClass = 'badge-danger';
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?> p-2 font-weight-bold" style="border-radius: 4px; font-size: 12px;">
                                                    <?php echo htmlspecialchars($order->status); ?>
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-flex justify-content-end align-items-center" style="gap: 8px;">
                                                    <!-- Xem chi tiết nhanh -->
                                                    <button class="btn btn-sm btn-light border" data-toggle="collapse" data-target="#order-detail-<?php echo $order->id; ?>" title="Xem sản phẩm">
                                                        <i class="fa-regular fa-eye"></i> Chi tiết
                                                    </button>
                                                    
                                                    <!-- Form Cập nhật Trạng thái -->
                                                    <form action="<?php echo BASE_URL; ?>/Account/updateOrderStatus" method="POST" class="d-flex align-items-center" style="gap: 4px;">
                                                        <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                                        <select name="status" class="form-control form-control-sm" style="width: 130px; height: 31px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                                            <option value="Đang xử lý" <?php echo $order->status === 'Đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                                                            <option value="Đang giao hàng" <?php echo $order->status === 'Đang giao hàng' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                                            <option value="Đã giao" <?php echo $order->status === 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                                                            <option value="Đã hủy" <?php echo $order->status === 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-premium py-1 px-2" style="border-radius: 4px; font-size: 11px; height: 31px;">Lưu</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Bảng mở rộng Chi tiết sản phẩm trong đơn hàng -->
                                        <tr class="collapse" id="order-detail-<?php echo $order->id; ?>" style="background-color: var(--light);">
                                            <td colspan="7" class="p-4" style="border-left: 4px solid var(--primary);">
                                                <h6 class="font-weight-bold mb-3"><i class="fa-solid fa-basket-shopping mr-2 text-primary"></i>Chi tiết sản phẩm đơn hàng #<?php echo $order->id; ?></h6>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <table class="table table-sm table-borderless mb-0">
                                                            <thead>
                                                                <tr class="text-muted small" style="border-bottom: 1px solid var(--border-color);">
                                                                    <th>Sản phẩm</th>
                                                                    <th class="text-center">Số lượng</th>
                                                                    <th class="text-right">Đơn giá</th>
                                                                    <th class="text-right">Thành tiền</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($orderDetails[$order->id] as $item): ?>
                                                                    <tr style="border-bottom: 1px dashed var(--border-color);">
                                                                        <td class="d-flex align-items-center py-2">
                                                                            <?php if (!empty($item->product_image)): ?>
                                                                                <img src="<?php echo (strpos($item->product_image, 'http') === 0) ? $item->product_image : BASE_URL . '/' . $item->product_image; ?>" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px; background: white; padding: 2px; border: 1px solid var(--border-color); border-radius: 4px;">
                                                                            <?php endif; ?>
                                                                            <span class="font-weight-bold small text-main"><?php echo htmlspecialchars($item->product_name ?? 'Sản phẩm đã bị xóa'); ?></span>
                                                                        </td>
                                                                        <td class="text-center py-2 small">x<?php echo $item->quantity; ?></td>
                                                                        <td class="text-right py-2 small"><?php echo number_format($item->price, 0, ',', '.'); ?> ₫</td>
                                                                        <td class="text-right py-2 font-weight-bold text-danger small"><?php echo number_format($item->quantity * $item->price, 0, ',', '.'); ?> ₫</td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card p-3" style="border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: var(--card-bg);">
                                                            <div class="small font-weight-bold mb-2">ĐỊA CHỈ NHẬN HÀNG</div>
                                                            <div class="small text-muted mb-1">Người nhận: <?php echo htmlspecialchars($order->name); ?></div>
                                                            <div class="small text-muted mb-1">Điện thoại: <?php echo htmlspecialchars($order->phone); ?></div>
                                                            <div class="small text-muted">Địa chỉ: <?php echo htmlspecialchars($order->address); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-receipt fa-3x mb-3"></i>
                            <h5>Chưa có đơn hàng nào được đặt</h5>
                            <p class="small">Các đơn hàng đặt từ khách hàng sẽ hiển thị tại đây.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB 2: PRODUCTS -->
            <div class="tab-pane fade show <?php echo $activeTab === 'products' ? 'active' : ''; ?>">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap: 10px;">
                        <h3 class="font-weight-bold mb-0" style="color: var(--text-main); font-size: 20px;"><i class="fa-solid fa-boxes-stacked mr-2 text-primary"></i>Danh Sách Sản Phẩm</h3>
                        <a href="<?php echo BASE_URL; ?>/Product/add" class="btn btn-premium btn-sm" style="border-radius: var(--radius-md);">
                            <i class="fa-solid fa-plus mr-1"></i> Đăng sản phẩm mới
                        </a>
                    </div>
                    
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Giá bán</th>
                                        <th>Mô tả</th>
                                        <th class="text-right">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <?php if ($product->image): ?>
                                                    <img src="<?php echo (strpos($product->image, 'http') === 0) ? $product->image : BASE_URL . '/' . $product->image; ?>" style="width: 50px; height: 50px; object-fit: contain; background: white; padding: 2px; border: 1px solid var(--border-color); border-radius: 6px;">
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/50x50" style="width: 50px; height: 50px; border-radius: 6px;">
                                                <?php endif; ?>
                                            </td>
                                            <td class="font-weight-bold text-main"><?php echo htmlspecialchars($product->name); ?></td>
                                            <td>
                                                <span class="badge badge-primary px-2 py-1" style="font-size: 11px; border-radius: 4px;">
                                                    <?php echo htmlspecialchars($product->category_name ?? 'Không phân loại'); ?>
                                                </span>
                                            </td>
                                            <td class="font-weight-bold text-danger"><?php echo number_format($product->price, 0, ',', '.'); ?> ₫</td>
                                            <td class="text-muted small text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($product->description); ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-warning text-white mr-1" title="Sửa sản phẩm" style="border-radius: 4px;">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $product->id; ?>" class="btn btn-sm btn-danger text-white" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" title="Xóa sản phẩm" style="border-radius: 4px;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                            <h5>Cửa hàng trống</h5>
                            <a href="<?php echo BASE_URL; ?>/Product/add" class="btn btn-premium mt-3">Thêm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB 3: CATEGORIES -->
            <div class="tab-pane fade show <?php echo $activeTab === 'categories' ? 'active' : ''; ?>">
                <div class="row">
                    <!-- Danh sách danh mục -->
                    <div class="col-lg-7 mb-4">
                        <div class="premium-card p-4">
                            <h3 class="font-weight-bold mb-4" style="color: var(--text-main); font-size: 20px;"><i class="fa-solid fa-tags mr-2 text-primary"></i>Danh Sách Danh Mục</h3>
                            
                            <?php if (!empty($categories)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th>Tên danh mục</th>
                                                <th>Mô tả</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category): ?>
                                                <tr>
                                                    <td class="font-weight-bold">#<?php echo $category->id; ?></td>
                                                    <td class="font-weight-bold text-main"><?php echo htmlspecialchars($category->name); ?></td>
                                                    <td class="text-muted small"><?php echo htmlspecialchars($category->description); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                                    <h5>Chưa có danh mục nào</h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Thêm danh mục mới -->
                    <div class="col-lg-5 mb-4">
                        <div class="premium-card p-4">
                            <h3 class="font-weight-bold mb-4" style="color: var(--text-main); font-size: 20px;"><i class="fa-solid fa-circle-plus mr-2 text-success"></i>Thêm Danh Mục Mới</h3>
                            
                            <form action="<?php echo BASE_URL; ?>/Account/addCategory" method="POST">
                                <div class="form-group mb-3">
                                    <label for="cat_name" class="small font-weight-bold text-muted mb-2">TÊN DANH MỤC</label>
                                    <input type="text" class="form-control" id="cat_name" name="name" placeholder="Ví dụ: iPhone, Samsung, Phụ kiện..." required style="border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500; height: 44px;">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="cat_desc" class="small font-weight-bold text-muted mb-2">MÔ TẢ DANH MỤC</label>
                                    <textarea class="form-control" id="cat_desc" name="description" rows="3" placeholder="Nhập mô tả ngắn cho danh mục sản phẩm này" style="border-radius: var(--radius-md); border-color: var(--border-color); background: transparent; color: var(--text-main); font-weight: 500;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-premium btn-block py-3" style="border-radius: var(--radius-md); font-weight: 700;">
                                    TẠO DANH MỤC <i class="fa-solid fa-check ml-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: USERS -->
            <div class="tab-pane fade show <?php echo $activeTab === 'users' ? 'active' : ''; ?>">
                <div class="premium-card p-4">
                    <h3 class="font-weight-bold mb-4" style="color: var(--text-main); font-size: 20px;"><i class="fa-solid fa-users-gear mr-2 text-primary"></i>Phân Cấp Quyền Hạn</h3>
                    
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Mã</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Họ và tên</th>
                                        <th>Quyền hiện tại</th>
                                        <th class="text-right">Thay đổi vai trò</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="font-weight-bold">#<?php echo $user->id; ?></td>
                                            <td class="font-weight-bold text-main"><?php echo htmlspecialchars($user->username); ?></td>
                                            <td><?php echo htmlspecialchars($user->fullname ?? 'Khách vãng lai'); ?></td>
                                            <td>
                                                <?php if ($user->role === 'admin'): ?>
                                                    <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 11px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        <i class="fa-solid fa-shield-halved mr-1"></i> Administrator
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary px-3 py-2 font-weight-bold" style="font-size: 11px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        <i class="fa-solid fa-user mr-1"></i> Customer
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-flex justify-content-end align-items-center" style="gap: 8px;">
                                                    <form action="<?php echo BASE_URL; ?>/Account/updateUserRole" method="POST" class="d-flex align-items-center mb-0" style="gap: 4px;">
                                                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                                                        <select name="role" class="form-control form-control-sm" style="width: 100px; height: 32px; border-radius: 4px; font-weight: 600; font-size: 12px;" <?php echo $_SESSION['username'] == $user->username ? 'disabled' : ''; ?>>
                                                            <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>Customer</option>
                                                            <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-premium py-1 px-2" style="border-radius: 4px; font-size: 11px; height: 32px;" <?php echo $_SESSION['username'] == $user->username ? 'disabled' : ''; ?>>Sửa</button>
                                                    </form>
                                                    
                                                    <?php if ($_SESSION['username'] != $user->username): ?>
                                                        <a href="<?php echo BASE_URL; ?>/Account/deleteUser/<?php echo $user->id; ?>" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0; border-radius: 4px;" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này? Thao tác này không thể hoàn tác!');">
                                                            <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0; border-radius: 4px;" disabled title="Không thể xóa tài khoản của chính bạn">
                                                            <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Styling adjustments for the tables */
    .table th {
        background-color: var(--light);
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        padding: 16px 12px;
    }
    
    .table td {
        color: var(--text-main);
        padding: 16px 12px;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--border-color);
    }
    
    /* Styling active state for tab pills */
    .nav-pills .nav-link {
        color: var(--text-muted);
        background-color: transparent;
    }
    
    .nav-pills .nav-link:hover {
        color: var(--primary);
        background-color: var(--primary-light);
    }
    
    body.dark-theme .nav-pills .nav-link:hover {
        background-color: rgba(79, 70, 229, 0.15);
    }
    
    .nav-pills .nav-link.active {
        color: white !important;
        background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    
    .text-main {
        color: var(--text-main) !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>
