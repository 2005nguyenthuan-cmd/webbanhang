<?php
// Cấu hình thông tin Email SMTP (Gmail)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '2005nguyenthuan@gmail.com');
// Thay vì khoảng trắng, App password thường viết liền trong hệ thống, nhưng để an toàn tôi sẽ dùng đúng chuỗi cung cấp. Tuy nhiên, PHPMailer chấp nhận cả khoảng trắng. Ta có thể bỏ khoảng trắng đi.
define('MAIL_PASSWORD', 'yosekwpelkvmhkqx');
define('MAIL_FROM_NAME', 'TechStore - Thế Giới Công Nghệ');
