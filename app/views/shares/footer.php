</div> <!-- End Container -->

<footer class="text-white text-center text-lg-start mt-5" style="background-color: var(--card-bg); border-top: 1px solid var(--border-color); color: var(--text-main) !important; transition: var(--transition);">
    <div class="container p-5">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-left">
                <h5 class="text-uppercase font-weight-bold mb-4" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <i class="fa-solid fa-bolt mr-2"></i>TechStore
                </h5>
                <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">
                    Hệ thống bán lẻ thiết bị công nghệ hiện đại hàng đầu. 
                    Chúng tôi mang lại giải pháp và trải nghiệm mua sắm thông minh và uy tín vượt trội cho mọi khách hàng.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0 text-left">
                <h5 class="text-uppercase font-weight-bold mb-4" style="color: var(--text-main); font-size: 15px;">Liên kết nhanh</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>/Product/" style="color: var(--text-muted); text-decoration: none; transition: var(--transition); font-size: 14px;">
                            <i class="fa-solid fa-angle-right mr-1" style="font-size: 11px;"></i> Cửa hàng
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>/Product/add" style="color: var(--text-muted); text-decoration: none; transition: var(--transition); font-size: 14px;">
                            <i class="fa-solid fa-angle-right mr-1" style="font-size: 11px;"></i> Thêm sản phẩm
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo BASE_URL; ?>/Product/cart" style="color: var(--text-muted); text-decoration: none; transition: var(--transition); font-size: 14px;">
                            <i class="fa-solid fa-angle-right mr-1" style="font-size: 11px;"></i> Giỏ hàng cá nhân
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0 text-left">
                <h5 class="text-uppercase font-weight-bold mb-4" style="color: var(--text-main); font-size: 15px;">Kết nối với chúng tôi</h5>
                <div class="d-flex">
                    <a href="#" class="mr-3 text-center" style="width: 38px; height: 38px; border-radius: 50%; background: var(--light); color: var(--text-main); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); transition: var(--transition); text-decoration: none;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="mr-3 text-center" style="width: 38px; height: 38px; border-radius: 50%; background: var(--light); color: var(--text-main); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); transition: var(--transition); text-decoration: none;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-center" style="width: 38px; height: 38px; border-radius: 50%; background: var(--light); color: var(--text-main); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); transition: var(--transition); text-decoration: none;">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: var(--light); border-top: 1px solid var(--border-color); color: var(--text-muted); font-size: 13px;">
        © 2026 TechStore. Thiết kế nâng cấp bởi <strong>Antigravity</strong>.
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Dark/Light Theme Switching Logic
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Check localStorage for saved theme preference
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-theme');
        const isDark = document.body.classList.contains('dark-theme');
        
        if (isDark) {
            localStorage.setItem('theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            localStorage.setItem('theme', 'light');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    });
</script>
</body>
</html>