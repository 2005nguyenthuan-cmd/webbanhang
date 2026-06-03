<?php include 'app/views/shares/header.php'; ?>

<!-- =============================================
     HOMEPAGE BACKGROUND SYSTEM
     Animated mesh gradient + floating orbs + grid
================================================ -->
<div class="hp-bg" aria-hidden="true">
    <!-- Mesh gradient orbs -->
    <div class="hp-orb hp-orb--indigo"></div>
    <div class="hp-orb hp-orb--cyan"></div>
    <div class="hp-orb hp-orb--orange"></div>
    <div class="hp-orb hp-orb--violet"></div>
    <!-- Dot grid overlay -->
    <div class="hp-grid"></div>
</div>

<!-- =============================================
     HERO SECTION - Redesigned Premium
================================================ -->
<section class="hero-section position-relative overflow-hidden mb-5">

    <!-- Inner glow rings -->
    <div class="hero-glow hero-glow--left"  aria-hidden="true"></div>
    <div class="hero-glow hero-glow--right" aria-hidden="true"></div>

    <!-- Floating particles -->
    <div class="particles" aria-hidden="true">
        <?php for($i=1; $i<=12; $i++): ?>
        <span class="particle particle--<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>

    <!-- Hero content -->
    <div class="row align-items-center position-relative" style="z-index:2;">
        <div class="col-lg-7">
            <!-- Label -->
            <div class="hero-badge mb-3">
                <span class="badge-dot"></span>
                <span>NEW COLLECTION 2026</span>
            </div>

            <!-- Headline -->
            <h1 class="hero-title">
                Trải Nghiệm<br>
                <span class="hero-title--gradient">Công Nghệ</span><br>
                Đỉnh Cao
            </h1>

            <!-- Sub text -->
            <p class="hero-sub">
                Khám phá thế giới thiết bị công nghệ chính hãng với ưu đãi đặc quyền lớn nhất trong năm.
                Trả góp <strong>0%</strong>, bảo hành <strong>2 năm</strong> toàn quốc.
            </p>

            <!-- CTA row -->
            <div class="hero-actions">
                <a href="#product-section" class="btn-hero-primary">
                    <i class="fa-solid fa-fire mr-2"></i> Mua Ngay
                </a>
                <a href="<?php echo BASE_URL; ?>/Product/category/1" class="btn-hero-ghost">
                    <i class="fa-solid fa-mobile-screen mr-2"></i> Điện thoại
                </a>
            </div>

            <!-- Stats row -->
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat__num">100+</div>
                    <div class="hero-stat__label">Sản phẩm</div>
                </div>
                <div class="hero-stat__sep"></div>
                <div class="hero-stat">
                    <div class="hero-stat__num">5</div>
                    <div class="hero-stat__label">Danh mục</div>
                </div>
                <div class="hero-stat__sep"></div>
                <div class="hero-stat">
                    <div class="hero-stat__num">0%</div>
                    <div class="hero-stat__label">Lãi suất</div>
                </div>
            </div>
        </div>

        <!-- Right side decorative device mockup -->
        <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">
            <div class="hero-device">
                <div class="hero-device__glow"></div>
                <i class="fa-solid fa-mobile-screen-button hero-device__icon"></i>
                <!-- Floating mini-cards -->
                <div class="hero-float-card hero-float-card--a">
                    <i class="fa-solid fa-shield-halved text-success mr-1"></i>
                    <span>Bảo hành 2 năm</span>
                </div>
                <div class="hero-float-card hero-float-card--b">
                    <i class="fa-solid fa-truck-fast text-primary mr-1"></i>
                    <span>Ship toàn quốc</span>
                </div>
                <div class="hero-float-card hero-float-card--c">
                    <i class="fa-solid fa-percent text-warning mr-1"></i>
                    <span>Trả góp 0%</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =============================================
     PRODUCT SECTION
================================================ -->
<div id="product-section" class="mb-5">

    <!-- Category Filter Bar -->
    <?php if (!empty($categories)): ?>
        <div class="cat-bar mb-4">
            <span class="cat-bar__label d-none d-md-flex">
                <i class="fa-solid fa-filter mr-2"></i> Lọc:
            </span>
            <a href="<?php echo BASE_URL; ?>/Product/"
               class="cat-pill <?php echo (!isset($activeCategory) && (!isset($keyword) || $keyword === '')) ? 'cat-pill--active' : ''; ?>">
                Tất cả
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo BASE_URL; ?>/Product/category/<?php echo $cat->id; ?>"
                   class="cat-pill <?php echo (isset($activeCategory) && $activeCategory->id == $cat->id) ? 'cat-pill--active' : ''; ?>">
                    <?php echo htmlspecialchars($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Active Filters Alert -->
    <?php if (isset($keyword) && $keyword !== ''): ?>
        <div class="filter-alert mb-4">
            <div><i class="fa-solid fa-magnifying-glass mr-1"></i>
                Kết quả: <strong>"<?php echo htmlspecialchars($keyword); ?>"</strong>
                <span class="filter-badge ml-2"><?php echo count($products); ?> sản phẩm</span>
            </div>
            <a href="<?php echo BASE_URL; ?>/Product/" class="filter-clear">
                <i class="fa-solid fa-xmark mr-1"></i>Xóa
            </a>
        </div>
    <?php elseif (isset($activeCategory)): ?>
        <div class="filter-alert filter-alert--cyan mb-4">
            <div><i class="fa-solid fa-tag mr-1"></i>
                Danh mục: <strong>"<?php echo htmlspecialchars($activeCategory->name); ?>"</strong>
                <span class="filter-badge filter-badge--cyan ml-2"><?php echo count($products); ?> sản phẩm</span>
            </div>
            <a href="<?php echo BASE_URL; ?>/Product/" class="filter-clear filter-clear--cyan">
                <i class="fa-solid fa-xmark mr-1"></i>Hủy
            </a>
        </div>
    <?php endif; ?>

    <!-- Section Header -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="section-title">
                <?php
                if (isset($activeCategory)) echo htmlspecialchars($activeCategory->name);
                elseif (isset($keyword) && $keyword !== '') echo 'Kết quả tìm kiếm';
                else echo 'Sản Phẩm Nổi Bật';
                ?>
            </h2>
            <p class="section-sub">
                <?php
                if (isset($activeCategory)) echo htmlspecialchars($activeCategory->description ?? 'Tuyển chọn những sản phẩm tốt nhất hiện nay');
                else echo 'Tuyển chọn những thiết bị công nghệ tốt nhất hiện nay';
                ?>
            </p>
        </div>
        <?php if (AccountModel::isAdmin()): ?>
            <a href="<?php echo BASE_URL; ?>/Product/add" class="btn-add-product">
                <i class="fa-solid fa-plus mr-1"></i> Thêm mới
            </a>
        <?php endif; ?>
    </div>

    <!-- Product Grid -->
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="prod-card">
                        <!-- Image -->
                        <div class="prod-card__img-wrap">
                            <?php if ($product->image): ?>
                                <?php $imgSrc = (filter_var($product->image, FILTER_VALIDATE_URL) || strpos($product->image,'http') === 0)
                                    ? htmlspecialchars($product->image, ENT_QUOTES,'UTF-8')
                                    : BASE_URL . '/' . htmlspecialchars($product->image, ENT_QUOTES,'UTF-8'); ?>
                                <img src="<?php echo $imgSrc; ?>"
                                     alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES,'UTF-8'); ?>"
                                     class="prod-card__img">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x300?text=No+Image"
                                     alt="No Image" class="prod-card__img">
                            <?php endif; ?>

                            <!-- Category badge -->
                            <span class="prod-card__cat">
                                <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name,ENT_QUOTES,'UTF-8') : 'SmartPhone'; ?>
                            </span>

                            <!-- Quick add overlay -->
                            <div class="prod-card__overlay">
                                <a href="<?php echo BASE_URL; ?>/Product/addToCart/<?php echo $product->id; ?>"
                                   class="prod-card__quick-add">
                                    <i class="fa-solid fa-cart-plus mr-1"></i> Thêm vào giỏ
                                </a>
                            </div>

                            <!-- Admin actions -->
                            <?php if (AccountModel::isAdmin()): ?>
                                <div class="prod-card__admin">
                                    <a href="<?php echo BASE_URL; ?>/Product/edit/<?php echo $product->id; ?>"
                                       class="prod-card__admin-btn prod-card__admin-btn--edit" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/Product/delete/<?php echo $product->id; ?>"
                                       class="prod-card__admin-btn prod-card__admin-btn--del" title="Xóa"
                                       onclick="return confirm('Xóa sản phẩm này?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Body -->
                        <div class="prod-card__body">
                            <h3 class="prod-card__name">
                                <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $product->id; ?>">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES,'UTF-8'); ?>
                                </a>
                            </h3>
                            <p class="prod-card__desc">
                                <?php echo htmlspecialchars($product->description, ENT_QUOTES,'UTF-8'); ?>
                            </p>
                            <div class="prod-card__footer">
                                <span class="prod-card__price">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> ₫
                                </span>
                                <a href="<?php echo BASE_URL; ?>/Product/show/<?php echo $product->id; ?>"
                                   class="prod-card__detail-btn">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3 d-block"></i>
                <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                <p class="text-muted small">Hãy thử từ khóa khác hoặc xóa bộ lọc.</p>
                <a href="<?php echo BASE_URL; ?>/Product/" class="btn btn-premium mt-3">Quay lại cửa hàng</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =============================================
     ALL HOMEPAGE STYLES
================================================ -->
<style>
/* ── Background System ─────────────────────────────── */
.hp-bg {
    position: fixed;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

/* Dot-grid texture */
.hp-grid {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle, rgba(79,70,229,.12) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 1;
}
body.dark-theme .hp-grid {
    background-image:
        radial-gradient(circle, rgba(129,140,248,.08) 1px, transparent 1px);
}

/* Orbs */
.hp-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    animation: orbFloat 30s infinite alternate ease-in-out;
    will-change: transform;
}
.hp-orb--indigo {
    width: 55vw; height: 55vw;
    top: -15%; left: -10%;
    background: radial-gradient(circle, rgba(79,70,229,.55) 0%, transparent 70%);
    opacity: .18;
    animation-duration: 28s;
}
.hp-orb--cyan {
    width: 50vw; height: 50vw;
    bottom: -15%; right: -10%;
    background: radial-gradient(circle, rgba(6,182,212,.6) 0%, transparent 70%);
    opacity: .16;
    animation-duration: 34s;
    animation-delay: -8s;
}
.hp-orb--orange {
    width: 30vw; height: 30vw;
    top: 45%; left: 55%;
    background: radial-gradient(circle, rgba(249,115,22,.55) 0%, transparent 70%);
    opacity: .14;
    animation-duration: 22s;
    animation-delay: -14s;
}
.hp-orb--violet {
    width: 28vw; height: 28vw;
    top: 20%; right: 25%;
    background: radial-gradient(circle, rgba(167,139,250,.7) 0%, transparent 70%);
    opacity: .12;
    animation-duration: 26s;
    animation-delay: -4s;
}

body.dark-theme .hp-orb { opacity: .13; }
body.dark-theme .hp-orb--indigo { opacity: .2; }
body.dark-theme .hp-orb--cyan   { opacity: .18; }

@keyframes orbFloat {
    0%   { transform: translate(0,0) scale(1); }
    33%  { transform: translate(4%,6%) scale(1.08); }
    66%  { transform: translate(-3%,3%) scale(.95); }
    100% { transform: translate(2%,-5%) scale(1.05); }
}

/* ── Hero Section ──────────────────────────────────── */
.hero-section {
    background: linear-gradient(135deg,
        rgba(79,70,229,.92) 0%,
        rgba(37,99,235,.85) 40%,
        rgba(6,182,212,.88) 100%);
    border-radius: var(--radius-lg);
    padding: 72px 56px;
    margin-bottom: 0;
    box-shadow:
        0 25px 60px -12px rgba(79,70,229,.45),
        0 0 0 1px rgba(255,255,255,.08) inset;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    overflow: hidden;
    color: #fff;
    position: relative;
}

/* Inner glow rings */
.hero-glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hero-glow--left {
    width: 500px; height: 500px;
    top: -200px; left: -150px;
    background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 60%);
}
.hero-glow--right {
    width: 400px; height: 400px;
    bottom: -180px; right: -100px;
    background: radial-gradient(circle, rgba(6,182,212,.25) 0%, transparent 65%);
}

/* Floating particles */
.particles { position: absolute; inset: 0; pointer-events: none; }
.particle {
    position: absolute;
    width: 4px; height: 4px;
    border-radius: 50%;
    background: rgba(255,255,255,.5);
    animation: particleDrift linear infinite;
}
.particle--1  { top:10%; left:12%; animation-duration:14s; animation-delay:0s;   width:3px;  height:3px; }
.particle--2  { top:25%; left:30%; animation-duration:18s; animation-delay:-3s;  width:5px;  height:5px; opacity:.4; }
.particle--3  { top:60%; left:8%;  animation-duration:12s; animation-delay:-5s;  }
.particle--4  { top:80%; left:40%; animation-duration:20s; animation-delay:-2s;  width:6px;  height:6px; opacity:.3; }
.particle--5  { top:15%; left:70%; animation-duration:16s; animation-delay:-7s;  width:2px;  height:2px; }
.particle--6  { top:45%; left:85%; animation-duration:22s; animation-delay:-1s;  width:4px;  height:4px; }
.particle--7  { top:70%; left:60%; animation-duration:10s; animation-delay:-4s;  width:3px;  height:3px; opacity:.6; }
.particle--8  { top:5%;  left:50%; animation-duration:15s; animation-delay:-9s;  }
.particle--9  { top:90%; left:20%; animation-duration:19s; animation-delay:-6s;  width:5px;  height:5px; opacity:.3; }
.particle--10 { top:35%; left:92%; animation-duration:13s; animation-delay:-11s; width:2px;  height:2px; }
.particle--11 { top:55%; left:45%; animation-duration:24s; animation-delay:-3s;  width:6px;  height:6px; opacity:.25; }
.particle--12 { top:75%; left:78%; animation-duration:17s; animation-delay:-8s;  width:3px;  height:3px; }
@keyframes particleDrift {
    0%   { transform: translateY(0)   rotate(0deg);   opacity:.8; }
    50%  { transform: translateY(-25px) rotate(180deg); opacity:.3; }
    100% { transform: translateY(0)   rotate(360deg); opacity:.8; }
}

/* Hero badge */
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    backdrop-filter: blur(8px);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #fff;
}
.badge-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 0 3px rgba(74,222,128,.3);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(74,222,128,.3); }
    50%      { box-shadow: 0 0 0 6px rgba(74,222,128,.1); }
}

/* Hero Title */
.hero-title {
    font-size: clamp(2rem, 5vw, 3.8rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -2px;
    margin-bottom: 20px;
    color: #fff;
}
.hero-title--gradient {
    background: linear-gradient(90deg, #fde68a 0%, #fb923c 50%, #f472b6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hero sub */
.hero-sub {
    font-size: 1rem;
    line-height: 1.75;
    opacity: .88;
    max-width: 520px;
    margin-bottom: 32px;
    font-weight: 400;
}
.hero-sub strong { color: #fde68a; font-weight: 700; }

/* Hero CTA */
.hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 40px; }
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #f97316, #fbbf24);
    color: #1e293b !important;
    font-weight: 800;
    font-size: 15px;
    padding: 14px 30px;
    border-radius: var(--radius-md);
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(249,115,22,.45);
    transition: all .3s cubic-bezier(.4,0,.2,1);
    letter-spacing: .2px;
}
.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(249,115,22,.55);
}
.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,.12);
    color: #fff !important;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 28px;
    border-radius: var(--radius-md);
    text-decoration: none;
    border: 1px solid rgba(255,255,255,.28);
    backdrop-filter: blur(8px);
    transition: all .3s cubic-bezier(.4,0,.2,1);
}
.btn-hero-ghost:hover {
    background: rgba(255,255,255,.22);
    transform: translateY(-3px);
}

/* Hero Stats */
.hero-stats {
    display: flex;
    align-items: center;
    gap: 20px;
}
.hero-stat__num {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1;
    color: #fde68a;
}
.hero-stat__label {
    font-size: 12px;
    opacity: .75;
    font-weight: 500;
    margin-top: 3px;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.hero-stat__sep {
    width: 1px;
    height: 36px;
    background: rgba(255,255,255,.2);
}

/* Hero Device */
.hero-device {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 280px;
    height: 280px;
}
.hero-device__glow {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.18) 0%, transparent 70%);
    animation: deviceGlow 4s ease-in-out infinite alternate;
}
@keyframes deviceGlow {
    0%   { transform: scale(1);    opacity: .8; }
    100% { transform: scale(1.15); opacity: 1;  }
}
.hero-device__icon {
    font-size: 160px;
    color: rgba(255,255,255,.18);
    filter: drop-shadow(0 20px 40px rgba(0,0,0,.35));
    position: relative;
    z-index: 1;
    animation: deviceFloat 6s ease-in-out infinite alternate;
}
@keyframes deviceFloat {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-16px); }
}

/* Floating mini-cards */
.hero-float-card {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.92);
    color: #1e293b;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 12px;
    white-space: nowrap;
    box-shadow: 0 8px 24px rgba(0,0,0,.18);
    backdrop-filter: blur(12px);
    animation: floatCard 5s ease-in-out infinite alternate;
}
.hero-float-card--a { top: 10px;  left: -60px;  animation-delay: 0s; }
.hero-float-card--b { bottom: 40px; left: -80px; animation-delay: -1.5s; }
.hero-float-card--c { top: 50%;  right: -20px;   animation-delay: -3s; }
@keyframes floatCard {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-10px); }
}

/* ── Category bar ──────────────────────────────────── */
.cat-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    background: rgba(255,255,255,.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 50px;
    overflow-x: auto;
    scrollbar-width: none;
    box-shadow: 0 2px 16px rgba(79,70,229,.06);
}
.cat-bar::-webkit-scrollbar { display: none; }
body.dark-theme .cat-bar {
    background: rgba(19,27,46,.65);
    border-color: rgba(255,255,255,.08);
}
.cat-bar__label {
    display: inline-flex;
    align-items: center;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--text-muted);
    white-space: nowrap;
    margin-right: 6px;
}
.cat-pill {
    display: inline-block;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    text-decoration: none;
    background: transparent;
    color: var(--text-muted);
    border: 1.5px solid var(--border-color);
    transition: all .25s;
}
.cat-pill:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
    text-decoration: none;
}
.cat-pill--active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff !important;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(79,70,229,.28);
}

/* ── Filter alert ──────────────────────────────────── */
.filter-alert {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 500;
    background: rgba(79,70,229,.08);
    color: var(--primary);
    border: 1px solid rgba(79,70,229,.15);
}
.filter-alert--cyan {
    background: rgba(6,182,212,.08);
    color: var(--secondary);
    border-color: rgba(6,182,212,.15);
}
.filter-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    background: var(--primary);
    color: #fff;
}
.filter-badge--cyan { background: var(--secondary); }
.filter-clear {
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 6px;
    background: rgba(79,70,229,.1);
    color: var(--primary);
    text-decoration: none;
    transition: all .2s;
}
.filter-clear--cyan {
    background: rgba(6,182,212,.1);
    color: var(--secondary);
}
.filter-clear:hover { background: var(--primary); color: #fff; text-decoration: none; }

/* ── Section header ────────────────────────────────── */
.section-title {
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -.5px;
    color: var(--text-main);
    margin-bottom: 4px;
}
.section-sub {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}
.btn-add-product {
    display: inline-flex;
    align-items: center;
    padding: 9px 20px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    transition: all .25s;
}
.btn-add-product:hover {
    background: var(--primary);
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
}

/* ── Product Cards ─────────────────────────────────── */
.prod-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform .35s cubic-bezier(.4,0,.2,1),
                box-shadow .35s cubic-bezier(.4,0,.2,1),
                border-color .35s;
    box-shadow: var(--shadow);
    position: relative;
}
.prod-card:hover {
    transform: translateY(-8px);
    box-shadow:
        0 24px 48px -12px rgba(79,70,229,.18),
        0 0 0 1.5px rgba(79,70,229,.18);
    border-color: rgba(79,70,229,.2);
}

.prod-card__img-wrap {
    position: relative;
    height: 220px;
    background: linear-gradient(135deg, rgba(248,250,252,1) 0%, rgba(241,245,249,1) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
body.dark-theme .prod-card__img-wrap {
    background: linear-gradient(135deg, rgba(15,23,42,.8) 0%, rgba(30,41,59,.6) 100%);
}
.prod-card__img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    padding: 16px;
    transition: transform .5s cubic-bezier(.4,0,.2,1);
}
.prod-card:hover .prod-card__img {
    transform: scale(1.1);
}

/* Category badge */
.prod-card__cat {
    position: absolute;
    top: 12px; left: 12px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 6px;
    z-index: 3;
    box-shadow: 0 3px 8px rgba(79,70,229,.35);
}

/* Quick-add overlay */
.prod-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(79,70,229,.85) 0%, transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 18px;
    opacity: 0;
    transition: opacity .3s;
    z-index: 4;
}
.prod-card:hover .prod-card__overlay { opacity: 1; }
.prod-card__quick-add {
    display: inline-flex;
    align-items: center;
    padding: 9px 22px;
    background: #fff;
    color: var(--primary) !important;
    font-weight: 800;
    font-size: 13px;
    border-radius: 50px;
    text-decoration: none;
    transform: translateY(10px);
    transition: transform .3s, box-shadow .3s;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
}
.prod-card:hover .prod-card__quick-add {
    transform: translateY(0);
}

/* Admin actions */
.prod-card__admin {
    position: absolute;
    top: 12px; right: 12px;
    display: flex;
    gap: 6px;
    z-index: 5;
    opacity: 0;
    transition: opacity .3s;
}
.prod-card:hover .prod-card__admin { opacity: 1; }
.prod-card__admin-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 32px;
    border-radius: 50%;
    font-size: 12px;
    color: #fff;
    text-decoration: none;
    box-shadow: var(--shadow-sm);
    transition: transform .2s;
}
.prod-card__admin-btn:hover { transform: scale(1.15); }
.prod-card__admin-btn--edit { background: var(--warning); }
.prod-card__admin-btn--del  { background: var(--danger); }

/* Card body */
.prod-card__body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.prod-card__name {
    font-size: 14px;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 40px;
}
.prod-card__name a {
    color: var(--text-main);
    text-decoration: none;
    transition: color .2s;
}
.prod-card__name a:hover { color: var(--primary); }
.prod-card__desc {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 36px;
    line-height: 1.5;
}
.prod-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.prod-card__price {
    font-size: 18px;
    font-weight: 800;
    color: var(--danger);
    letter-spacing: -.3px;
}
.prod-card__detail-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    border-radius: var(--radius-sm);
    background: var(--primary-light);
    color: var(--primary);
    font-size: 14px;
    text-decoration: none;
    transition: all .2s;
}
.prod-card__detail-btn:hover {
    background: var(--primary);
    color: #fff;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>