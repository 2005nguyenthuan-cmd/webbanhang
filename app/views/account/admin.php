<?php include 'app/views/shares/header.php'; ?>

<!-- ===================== ADMIN DASHBOARD ===================== -->

<?php
$activeTab = $_GET['tab'] ?? 'orders';
$currentUser = $_SESSION['username'] ?? '';
?>

<!-- Page Header -->
<div class="adm-header mb-4">
    <div class="adm-header__left">
        <div class="adm-header__icon">
            <i class="fa-solid fa-gauge-high"></i>
        </div>
        <div>
            <h1 class="adm-header__title">Bảng Điều Khiển</h1>
            <p class="adm-header__sub">Quản lý toàn bộ hệ thống TechStore</p>
        </div>
    </div>
    <a href="<?php echo BASE_URL; ?>/Product/" class="adm-back-btn">
        <i class="fa-solid fa-store mr-2"></i> Xem cửa hàng
    </a>
</div>

<!-- ── STAT CARDS ─────────────────────────────────────────── -->
<div class="row adm-stats mb-4">
    <!-- Revenue -->
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card stat-card--indigo">
            <div class="stat-card__icon"><i class="fa-solid fa-coins"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Doanh thu</div>
                <div class="stat-card__value"><?php echo number_format($totalRevenue, 0, ',', '.'); ?> ₫</div>
                <div class="stat-card__hint">Trừ đơn đã hủy</div>
            </div>
            <div class="stat-card__bg-icon"><i class="fa-solid fa-coins"></i></div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card stat-card--emerald">
            <div class="stat-card__icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Đơn hàng</div>
                <div class="stat-card__value"><?php echo $totalOrders; ?></div>
                <div class="stat-card__hint">Tổng tất cả đơn</div>
            </div>
            <div class="stat-card__bg-icon"><i class="fa-solid fa-receipt"></i></div>
        </div>
    </div>

    <!-- Members -->
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card stat-card--amber">
            <div class="stat-card__icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Thành viên</div>
                <div class="stat-card__value"><?php echo $totalUsers; ?></div>
                <div class="stat-card__hint">Tài khoản đã đăng ký</div>
            </div>
            <div class="stat-card__bg-icon"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <!-- Products -->
    <div class="col-lg-3 col-sm-6 mb-3">
        <div class="stat-card stat-card--cyan">
            <div class="stat-card__icon"><i class="fa-solid fa-mobile-screen"></i></div>
            <div class="stat-card__body">
                <div class="stat-card__label">Sản phẩm</div>
                <div class="stat-card__value"><?php echo count($products); ?></div>
                <div class="stat-card__hint">Đang bày bán</div>
            </div>
            <div class="stat-card__bg-icon"><i class="fa-solid fa-mobile-screen"></i></div>
        </div>
    </div>
</div>

<!-- ── TAB NAV ────────────────────────────────────────────── -->
<div class="adm-tab-nav mb-4">
    <a href="?tab=orders" class="adm-tab <?php echo $activeTab === 'orders' ? 'adm-tab--active' : ''; ?>">
        <i class="fa-solid fa-receipt mr-2"></i>Đơn hàng
        <span class="adm-tab__badge"><?php echo count($orders); ?></span>
    </a>
    <a href="?tab=products" class="adm-tab <?php echo $activeTab === 'products' ? 'adm-tab--active' : ''; ?>">
        <i class="fa-solid fa-boxes-stacked mr-2"></i>Sản phẩm
    </a>
    <a href="?tab=categories" class="adm-tab <?php echo $activeTab === 'categories' ? 'adm-tab--active' : ''; ?>">
        <i class="fa-solid fa-tags mr-2"></i>Danh mục
    </a>
    <a href="?tab=users" class="adm-tab <?php echo $activeTab === 'users' ? 'adm-tab--active' : ''; ?>">
        <i class="fa-solid fa-users-gear mr-2"></i>Tài khoản
        <span class="adm-tab__badge <?php echo $activeTab === 'users' ? '' : 'adm-tab__badge--gray'; ?>"><?php echo count($users); ?></span>
    </a>
</div>

<!-- ── TAB CONTENT ───────────────────────────────────────── -->

<!-- ===== TAB 1: ORDERS ===== -->
<div id="pane_orders" class="tab-content-pane adm-card <?php echo $activeTab === 'orders' ? '' : 'd-none'; ?>">
    <div class="adm-card__head">
        <div class="adm-card__head-title">
            <i class="fa-solid fa-receipt text-primary mr-2"></i>Quản lý Đơn hàng
        </div>
    </div>
    <div class="table-responsive">
        <?php if (empty($orders)): ?>
            <div class="adm-empty">
                <i class="fa-solid fa-receipt fa-3x mb-3"></i>
                <h5>Chưa có đơn hàng nào</h5>
                <p class="small">Đơn hàng từ khách hàng sẽ xuất hiện tại đây.</p>
            </div>
        <?php else: ?>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Liên hệ</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): 
                        $sc = 'status-processing';
                        if ($order->status === 'Đang giao hàng') $sc = 'status-shipping';
                        else if ($order->status === 'Đã giao') $sc = 'status-done';
                        else if ($order->status === 'Đã hủy') $sc = 'status-cancel';
                    ?>
                    <tr>
                        <td><span class="adm-id">#<?php echo $order->id; ?></span></td>
                        <td>
                            <div class="font-weight-bold"><?php echo htmlspecialchars($order->name); ?></div>
                            <?php if (!empty($order->account_name)): ?>
                                <span class="adm-sub"><i class="fa-regular fa-user mr-1"></i><?php echo htmlspecialchars($order->account_name); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="adm-sub"><i class="fa-solid fa-phone mr-1"></i><?php echo htmlspecialchars($order->phone); ?></div>
                            <div class="adm-sub text-truncate" style="max-width:200px" title="<?php echo htmlspecialchars($order->address); ?>"><i class="fa-solid fa-location-dot mr-1"></i><?php echo htmlspecialchars($order->address); ?></div>
                        </td>
                        <td class="font-weight-bold text-danger"><?php echo number_format($order->total_amount ?? 0, 0, ',', '.'); ?> ₫</td>
                        <td class="adm-sub"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td>
                            <span class="adm-status <?php echo $sc; ?>"><?php echo htmlspecialchars($order->status); ?></span>
                        </td>
                        <td class="text-right">
                            <div class="d-flex justify-content-end align-items-center" style="gap:8px">
                                <button class="adm-btn-icon btn-view-detail" data-order-id="<?php echo $order->id; ?>">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <form action="<?php echo BASE_URL; ?>/Account/updateOrderStatus" method="POST" class="d-flex" style="gap:4px">
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                    <select name="status" class="adm-select">
                                        <option value="Đang xử lý" <?php echo $order->status === 'Đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                                        <option value="Đang giao hàng" <?php echo $order->status === 'Đang giao hàng' ? 'selected' : ''; ?>>Đang giao</option>
                                        <option value="Đã giao" <?php echo $order->status === 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                                        <option value="Đã hủy" <?php echo $order->status === 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                    <button type="submit" class="adm-btn-save">Lưu</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr class="collapse d-none detail-row-<?php echo $order->id; ?>">
                        <td colspan="7" class="adm-detail-row">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="adm-detail-title">
                                        <i class="fa-solid fa-basket-shopping mr-2 text-primary"></i>
                                        Chi tiết đơn #<?php echo $order->id; ?>
                                    </div>
                                    <table class="adm-inner-table">
                                        <tr class="adm-inner-table__head">
                                            <th>Sản phẩm</th><th class="text-center">SL</th>
                                            <th class="text-right">Đơn giá</th><th class="text-right">Thành tiền</th>
                                        </tr>
                                        <?php if (isset($orderDetails[$order->id])): ?>
                                            <?php foreach ($orderDetails[$order->id] as $item): ?>
                                            <tr>
                                                <td class="d-flex align-items-center py-2" style="gap:10px">
                                                    <?php $imgUrl = !empty($item->product_image) ? (strpos($item->product_image, 'http') === 0 ? $item->product_image : BASE_URL . '/' . $item->product_image) : BASE_URL . '/uploads/images.jfif'; ?>
                                                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" style="width:38px;height:38px;object-fit:contain;background:#fff;border:1px solid var(--border-color);border-radius:6px;padding:2px">
                                                    <span class="font-weight-bold small"><?php echo htmlspecialchars($item->product_name ?? 'Sản phẩm đã xóa'); ?></span>
                                                </td>
                                                <td class="text-center small">x<?php echo $item->quantity; ?></td>
                                                <td class="text-right small"><?php echo number_format($item->price, 0, ',', '.'); ?> ₫</td>
                                                <td class="text-right small font-weight-bold text-danger"><?php echo number_format($item->quantity * $item->price, 0, ',', '.'); ?> ₫</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <div class="adm-address-card">
                                        <div class="adm-address-card__title">Địa chỉ nhận hàng</div>
                                        <div class="adm-address-card__row"><i class="fa-solid fa-user mr-1"></i><?php echo htmlspecialchars($order->name); ?></div>
                                        <div class="adm-address-card__row"><i class="fa-solid fa-phone mr-1"></i><?php echo htmlspecialchars($order->phone); ?></div>
                                        <div class="adm-address-card__row"><i class="fa-solid fa-location-dot mr-1"></i><?php echo htmlspecialchars($order->address); ?></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- ===== TAB 2: PRODUCTS ===== -->
<div id="pane_products" class="tab-content-pane adm-card <?php echo $activeTab === 'products' ? '' : 'd-none'; ?>">
    <div class="adm-card__head">
        <div class="adm-card__head-title">
            <i class="fa-solid fa-boxes-stacked text-primary mr-2"></i>Quản lý Sản phẩm
        </div>
        <a href="<?php echo BASE_URL; ?>/Product/add" class="adm-btn-primary">
            <i class="fa-solid fa-plus mr-1"></i>Thêm sản phẩm
        </a>
    </div>
    <div class="table-responsive">
        <?php if (empty($products)): ?>
            <div class="adm-empty">
                <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                <h5>Cửa hàng trống</h5>
                <a href="<?php echo BASE_URL; ?>/Product/add" class="adm-btn-primary mt-3">Thêm sản phẩm ngay</a>
            </div>
        <?php else: ?>
            <table class="adm-table">
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
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php $imgUrl = !empty($p->image) ? (strpos($p->image, 'http') === 0 ? $p->image : BASE_URL . '/' . $p->image) : 'https://via.placeholder.com/50'; ?>
                                <img src="<?php echo htmlspecialchars($imgUrl); ?>" style="width:50px;height:50px;object-fit:contain;border-radius:8px;border:1px solid var(--border-color);background:#fff;padding:3px">
                            </td>
                            <td class="font-weight-bold"><?php echo htmlspecialchars($p->name); ?></td>
                            <td><span class="adm-cat-badge"><?php echo htmlspecialchars($p->category_name ?? 'Khác'); ?></span></td>
                            <td class="font-weight-bold text-danger"><?php echo number_format($p->price, 0, ',', '.'); ?> ₫</td>
                            <td class="adm-sub text-truncate" style="max-width:220px"><?php echo htmlspecialchars($p->description); ?></td>
                            <td class="text-right">
                                <div class="d-flex justify-content-end" style="gap:6px">
                                    <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $p->id; ?>" class="adm-action-btn adm-action-btn--edit" title="Sửa"><i class="fa-solid fa-pen"></i></a>
                                    <!-- Using an old-fashioned confirm for delete -->
                                    <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $p->id; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" class="adm-action-btn adm-action-btn--del" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- ===== TAB 3: CATEGORIES ===== -->
<div id="pane_categories" class="tab-content-pane <?php echo $activeTab === 'categories' ? '' : 'd-none'; ?>">
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="adm-card">
                <div class="adm-card__head">
                    <div class="adm-card__head-title"><i class="fa-solid fa-tags text-primary mr-2"></i>Danh mục hiện có</div>
                </div>
                <div class="table-responsive">
                    <?php if (empty($categories)): ?>
                        <div class="adm-empty">
                            <i class="fa-solid fa-folder-open fa-3x mb-3"></i>
                            <h5>Chưa có danh mục nào</h5>
                        </div>
                    <?php else: ?>
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Tên danh mục</th>
                                    <th>Mô tả</th>
                                    <th class="text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><span class="adm-id">#<?php echo $cat->id; ?></span></td>
                                        <td class="font-weight-bold"><?php echo htmlspecialchars($cat->name); ?></td>
                                        <td class="adm-sub"><?php echo htmlspecialchars($cat->description ?? '—'); ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo BASE_URL; ?>/Category/delete/<?php echo $cat->id; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="adm-action-btn adm-action-btn--del" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="adm-card">
                <div class="adm-card__head">
                    <div class="adm-card__head-title"><i class="fa-solid fa-circle-plus text-success mr-2"></i>Thêm danh mục mới</div>
                </div>
                <div class="p-4">
                    <form action="<?php echo BASE_URL; ?>/Account/addCategory" method="POST">
                        <div class="form-group mb-3">
                            <label class="adm-label">TÊN DANH MỤC</label>
                            <input type="text" name="name" class="adm-input" placeholder="Ví dụ: iPhone, Samsung..." required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="adm-label">MÔ TẢ</label>
                            <textarea name="description" class="adm-input" rows="3" placeholder="Mô tả ngắn..."></textarea>
                        </div>
                        <button type="submit" class="adm-btn-primary w-100 py-3" style="font-size:15px;font-weight:800;border-radius:var(--radius-md);">
                            TẠO DANH MỤC <i class="fa-solid fa-check ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TAB 4: USERS ===== -->
<div id="pane_users" class="tab-content-pane adm-card <?php echo $activeTab === 'users' ? '' : 'd-none'; ?>">
    <div class="adm-card__head">
        <div class="adm-card__head-title"><i class="fa-solid fa-users-gear text-primary mr-2"></i>Quản lý Tài khoản</div>
    </div>
    <div class="table-responsive">
        <?php if (empty($users)): ?>
            <div class="adm-empty">
                <i class="fa-solid fa-users fa-3x mb-3"></i>
                <h5>Không có tài khoản nào</h5>
            </div>
        <?php else: ?>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tài khoản</th>
                        <th>Họ tên</th>
                        <th>Email / Xác thực</th>
                        <th>Quyền</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Quản lý</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <?php 
                        $avUrl = !empty($u->avatar) ? (strpos($u->avatar, 'http') === 0 ? $u->avatar : BASE_URL . '/' . $u->avatar) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($u->fullname ?? $u->username);
                        $isSelf = ($u->username === $currentUser);
                        ?>
                        <tr>
                            <td><span class="adm-id">#<?php echo $u->id; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center" style="gap:10px">
                                    <img src="<?php echo htmlspecialchars($avUrl); ?>" class="rounded-circle" style="width:34px;height:34px;object-fit:cover;border:2px solid var(--border-color)">
                                    <span class="font-weight-bold"><?php echo htmlspecialchars($u->username); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($u->fullname ?? '—'); ?></td>
                            <td>
                                <div class="small"><?php echo htmlspecialchars($u->email ?? 'Chưa thiết lập'); ?></div>
                                <?php if ($u->is_verified): ?>
                                    <span class="adm-verify adm-verify--ok"><i class="fa-solid fa-check mr-1"></i>Đã xác thực</span>
                                <?php else: ?>
                                    <span class="adm-verify adm-verify--pending"><i class="fa-solid fa-clock mr-1"></i>Chưa xác thực</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($u->role === 'admin'): ?>
                                    <span class="adm-role adm-role--admin"><i class="fa-solid fa-shield-halved mr-1"></i>Admin</span>
                                <?php else: ?>
                                    <span class="adm-role adm-role--user"><i class="fa-solid fa-user mr-1"></i>User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($u->is_locked): ?>
                                    <span class="adm-lock adm-lock--locked"><i class="fa-solid fa-lock mr-1"></i>Bị khóa</span>
                                <?php else: ?>
                                    <span class="adm-lock adm-lock--active"><i class="fa-solid fa-circle-check mr-1"></i>Hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-end align-items-center" style="gap:6px">
                                    
                                    <!-- Role selector form -->
                                    <form action="<?php echo BASE_URL; ?>/Account/updateUserRole" method="POST" class="d-flex" style="gap:4px">
                                        <input type="hidden" name="user_id" value="<?php echo $u->id; ?>">
                                        <select name="role" class="adm-select" <?php echo $isSelf ? 'disabled' : ''; ?>>
                                            <option value="user" <?php echo $u->role === 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $u->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                        <button type="submit" class="adm-btn-save" <?php echo $isSelf ? 'disabled' : ''; ?>>Đổi</button>
                                    </form>

                                    <?php if (!$isSelf): ?>
                                        <a href="<?php echo BASE_URL; ?>/Account/toggleUserLock/<?php echo $u->id; ?>" onclick="return confirm('Bạn có chắc muốn <?php echo $u->is_locked ? 'mở khóa' : 'khóa'; ?> tài khoản này?');" class="adm-action-btn <?php echo $u->is_locked ? 'adm-action-btn--unlock' : 'adm-action-btn--lock'; ?>" title="<?php echo $u->is_locked ? 'Mở khóa' : 'Khóa tài khoản'; ?>">
                                            <i class="fa-solid <?php echo $u->is_locked ? 'fa-lock-open' : 'fa-lock'; ?>"></i>
                                        </a>
                                        
                                        <a href="<?php echo BASE_URL; ?>/Account/deleteUser/<?php echo $u->id; ?>" onclick="return confirm('Bạn có chắc muốn xóa tài khoản này vĩnh viễn?');" class="adm-action-btn adm-action-btn--del" title="Xóa tài khoản">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="adm-action-btn" disabled title="Không thể tự thao tác" style="opacity:.35">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
/* ... existing styles ... */
/* ── Page Header ─────────────────────────────────────── */
.adm-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
.adm-header__left { display: flex; align-items: center; gap: 16px; }
.adm-header__icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; box-shadow: 0 8px 20px rgba(79,70,229,.3); flex-shrink: 0; }
.adm-header__title { font-size: 1.75rem; font-weight: 800; letter-spacing: -.5px; color: var(--text-main); margin: 0; }
.adm-header__sub { font-size: 13px; color: var(--text-muted); margin: 0; }
.adm-back-btn { display: inline-flex; align-items: center; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 700; font-size: 14px; color: var(--primary) !important; border: 2px solid var(--primary); text-decoration: none; transition: all .25s; }
.adm-back-btn:hover { background: var(--primary); color: #fff !important; text-decoration: none; transform: translateY(-2px); }
/* ── Stat Cards ─────────────────────────────────────── */
.stat-card { border-radius: var(--radius-lg); padding: 24px; display: flex; align-items: center; gap: 18px; position: relative; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,.08); transition: transform .3s, box-shadow .3s; }
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,.12); }
.stat-card--indigo { background: linear-gradient(135deg, #4f46e5, #6366f1); }
.stat-card--emerald { background: linear-gradient(135deg, #059669, #10b981); }
.stat-card--amber  { background: linear-gradient(135deg, #d97706, #f59e0b); }
.stat-card--cyan   { background: linear-gradient(135deg, #0891b2, #06b6d4); }
.stat-card__icon { width: 52px; height: 52px; flex-shrink: 0; border-radius: 14px; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; }
.stat-card__label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: rgba(255,255,255,.75); margin-bottom: 4px; }
.stat-card__value { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1.1; letter-spacing: -.5px; }
.stat-card__hint { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 4px; }
.stat-card__bg-icon { position: absolute; right: -10px; bottom: -12px; font-size: 80px; color: rgba(255,255,255,.07); pointer-events: none; }
/* ── Tab Nav ──────────────────────────────────────── */
.adm-tab-nav { display: flex; gap: 6px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 8px; flex-wrap: wrap; box-shadow: var(--shadow-sm); }
.adm-tab { display: inline-flex; align-items: center; padding: 10px 20px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 700; color: var(--text-muted); text-decoration: none; transition: all .2s; white-space: nowrap; gap: 6px; }
.adm-tab:hover { background: var(--primary-light); color: var(--primary); text-decoration: none; }
.adm-tab--active { background: linear-gradient(135deg, var(--primary), var(--secondary)) !important; color: #fff !important; box-shadow: 0 4px 14px rgba(79,70,229,.3); }
.adm-tab__badge { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; border-radius: 10px; padding: 0 6px; font-size: 11px; font-weight: 800; background: rgba(255,255,255,.25); color: #fff; }
.adm-tab__badge--gray { background: rgba(100,116,139,.15); color: var(--text-muted); }
.adm-tab--active .adm-tab__badge--gray { background: rgba(255,255,255,.25); color: #fff; }
/* ── Card ─────────────────────────────────────────── */
.adm-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 24px; }
.adm-card__head { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 12px; }
.adm-card__head-title { font-size: 17px; font-weight: 800; color: var(--text-main); display: flex; align-items: center; }
/* ── Table ────────────────────────────────────────── */
.adm-table { width: 100%; border-collapse: collapse; }
.adm-table th { background: var(--light); color: var(--text-muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding: 14px 16px; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
.adm-table td { padding: 14px 16px; color: var(--text-main); border-bottom: 1px solid var(--border-color); vertical-align: middle; font-size: 14px; }
.adm-table tbody tr:last-child td { border-bottom: none; }
.adm-table tbody tr:hover td { background: rgba(79,70,229,.025); }
/* ── Misc elements ────────────────────────────────── */
.adm-id { font-weight: 800; font-size: 13px; color: var(--primary); }
.adm-sub { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
.adm-empty { text-align: center; padding: 60px 20px; color: var(--text-muted); }
/* Status badges */
.adm-status { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.status-processing { background: rgba(245,158,11,.12); color: #d97706; }
.status-shipping   { background: rgba(6,182,212,.12);  color: #0891b2; }
.status-done       { background: rgba(16,185,129,.12); color: #059669; }
.status-cancel     { background: rgba(239,68,68,.12);  color: #dc2626; }
/* Role & verify badges */
.adm-role, .adm-verify, .adm-lock { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.adm-role--admin   { background: rgba(239,68,68,.12);  color: #dc2626; }
.adm-role--user    { background: rgba(100,116,139,.1); color: var(--text-muted); }
.adm-verify--ok    { background: rgba(16,185,129,.12); color: #059669; }
.adm-verify--pending { background: rgba(245,158,11,.12); color: #d97706; }
.adm-lock--active  { background: rgba(16,185,129,.12); color: #059669; }
.adm-lock--locked  { background: rgba(239,68,68,.12);  color: #dc2626; }
/* Category badge */
.adm-cat-badge { display: inline-block; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: linear-gradient(135deg, rgba(79,70,229,.12), rgba(6,182,212,.08)); color: var(--primary); border: 1px solid rgba(79,70,229,.15); }
/* Action buttons */
.adm-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; font-size: 13px; text-decoration: none; border: none; cursor: pointer; transition: all .2s; background: var(--light); color: var(--text-muted); border: 1px solid var(--border-color); }
.adm-action-btn:hover { transform: scale(1.1); text-decoration: none; }
.adm-action-btn--edit   { background: rgba(245,158,11,.1); color: #d97706; border-color: rgba(245,158,11,.2); }
.adm-action-btn--del    { background: rgba(239,68,68,.1);  color: #dc2626; border-color: rgba(239,68,68,.2);  }
.adm-action-btn--unlock { background: rgba(16,185,129,.1); color: #059669; border-color: rgba(16,185,129,.2); }
.adm-action-btn--lock   { background: rgba(245,158,11,.1); color: #d97706; border-color: rgba(245,158,11,.2); }
.adm-btn-icon { display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 12px; border-radius: 8px; font-size: 13px; border: 1px solid var(--border-color); background: var(--light); color: var(--text-muted); cursor: pointer; transition: all .2s; }
.adm-btn-icon:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }
/* Select & Save */
.adm-select { height: 32px; padding: 0 8px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-main); outline: none; }
.adm-btn-save { height: 32px; padding: 0 12px; border-radius: 8px; font-size: 12px; font-weight: 800; border: none; cursor: pointer; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; transition: all .2s; }
.adm-btn-save:hover { opacity: .88; transform: scale(1.04); }
.adm-btn-save:disabled { opacity: .4; cursor: not-allowed; transform: none; }
/* Primary button */
.adm-btn-primary { display: inline-flex; align-items: center; padding: 10px 20px; border-radius: var(--radius-md); font-size: 14px; font-weight: 700; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff !important; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .25s; }
.adm-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(79,70,229,.35); text-decoration: none; }
/* Detail expand row */
.adm-detail-row { background: var(--light) !important; padding: 24px 20px !important; border-left: 4px solid var(--primary); }
.adm-detail-title { font-weight: 800; font-size: 15px; color: var(--text-main); margin-bottom: 16px; }
.adm-inner-table { width: 100%; border-collapse: collapse; }
.adm-inner-table__head th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); padding: 8px 12px; border-bottom: 1px solid var(--border-color); }
.adm-inner-table td { padding: 8px 12px; font-size: 13px; border-bottom: 1px dashed var(--border-color); vertical-align: middle; color: var(--text-main); }
/* Address card */
.adm-address-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px; }
.adm-address-card__title { font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); margin-bottom: 12px; }
.adm-address-card__row { font-size: 13px; color: var(--text-muted); margin-bottom: 6px; line-height: 1.5; }
/* Category form inputs */
.adm-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); margin-bottom: 8px; }
.adm-input { width: 100%; padding: 12px 14px; border-radius: var(--radius-md); border: 1.5px solid var(--border-color); background: transparent; color: var(--text-main); font-size: 14px; font-weight: 500; transition: border-color .2s, box-shadow .2s; outline: none; display: block; }
.adm-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
.w-100 { width: 100%; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Toggle Order Details
    const detailButtons = document.querySelectorAll('.btn-view-detail');
    detailButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const orderId = btn.getAttribute('data-order-id');
            const row = document.querySelector('.detail-row-' + orderId);
            if (row) {
                row.classList.toggle('d-none');
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
