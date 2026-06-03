-- SQL Database Initialization Script
-- Database: my_store

CREATE DATABASE IF NOT EXISTS my_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_store;

-- 1. Create table account
CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create table category
CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create table product
CREATE TABLE IF NOT EXISTS product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NULL,
    image VARCHAR(255) NULL,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create table orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Đang xử lý',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Create table order_details
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed account (admin: admin, user: user)
INSERT INTO account (username, fullname, password, role) VALUES
('admin', 'Administrator', '$2y$10$wK1hF9x2xZ/g4p7s9E0f/.hV5y23FfGgKxH/L3sR0pQv/y3fA1bC.', 'admin'),
('user', 'Khách hàng thử nghiệm', '$2y$10$tM.yF9x2xZ/g4p7s9E0f/.hV5y23FfGgKxH/L3sR0pQv/y3fA1bC.', 'user')
ON DUPLICATE KEY UPDATE id=id;

-- Seed category
INSERT INTO category (id, name, description) VALUES
(1, 'Điện thoại', 'Điện thoại di động thông minh chính hãng, bảo hành 2 năm toàn quốc'),
(2, 'Laptop', 'Các dòng máy tính xách tay cấu hình cao, phục vụ cho học tập, công việc và chơi game'),
(3, 'Máy tính bảng', 'Máy tính bảng mỏng nhẹ, màn hình sắc nét, hỗ trợ bút cảm ứng tiện lợi'),
(4, 'Phụ kiện', 'Bàn phím, chuột, sạc, cáp và các thiết bị công nghệ chính hãng khác'),
(5, 'Âm thanh', 'Tai nghe không dây, tai nghe chụp tai và loa bluetooth âm thanh cực đỉnh')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

-- Seed product (100 items)
INSERT INTO product (id, name, description, price, category_id, image) VALUES
(1, 'iPhone 15 Pro Max 256GB', 'Đỉnh cao công nghệ với khung viền Titanium siêu nhẹ, camera zoom 5x, nút Action tiện lợi và cổng Type-C mới.', 30990000.00, 1, 'uploads/images.jfif'),
(2, 'iPhone 15 Pro 128GB', 'Sức mạnh từ chip A17 Pro mạnh mẽ, camera 3 mắt đỉnh cao cùng thiết kế viền Titanium đẳng cấp.', 25490000.00, 1, 'uploads/images.jfif'),
(3, 'iPhone 15 Plus 128GB', 'Màn hình lớn 6.7 inch thoải mái giải trí, Dynamic Island thông minh cùng thời lượng pin cực khủng.', 22990000.00, 1, 'uploads/images.jfif'),
(4, 'iPhone 15 128GB', 'Đột phá với Dynamic Island, camera chính 48MP siêu nét và thiết kế mặt lưng kính pha màu thời thượng.', 19790000.00, 1, 'uploads/images.jfif'),
(5, 'iPhone 14 Pro Max 128GB', 'Siêu phẩm năm ngoái với chip A16 Bionic, màn hình Always-On display và thiết kế sang trọng.', 26490000.00, 1, 'uploads/images.jfif'),
(6, 'iPhone 13 128GB', 'Lựa chọn quốc dân với hiệu năng mượt mà bền bỉ từ A15 Bionic, camera quay phim điện ảnh chuyên nghiệp.', 13990000.00, 1, 'uploads/images.jfif'),
(7, 'iPhone 11 64GB', 'Chiếc iPhone giá tốt nhất, cấu hình ổn định đáp ứng mượt mà nhu cầu sử dụng hàng ngày.', 8490000.00, 1, 'uploads/images.jfif'),
(8, 'Samsung Galaxy S24 Ultra 256GB', 'Đỉnh cao quyền năng AI hỗ trợ dịch thuật trực tiếp, tìm kiếm thông minh và camera zoom quang học 5x 50MP.', 29990000.00, 1, 'uploads/images.jfif'),
(9, 'Samsung Galaxy S24 Plus 256GB', 'Thiết kế hiện đại, viền màn hình siêu mỏng, dung lượng pin lớn cùng cấu hình Exynos 2400 mạnh mẽ.', 22990000.00, 1, 'uploads/images.jfif'),
(10, 'Samsung Galaxy S24 128GB', 'Kích thước nhỏ gọn cầm nắm vừa tay, tích hợp tính năng Galaxy AI thông minh nâng tầm trải nghiệm.', 18990000.00, 1, 'uploads/images.jfif'),
(11, 'Samsung Galaxy Z Fold5 256GB', 'Điện thoại gập mở thế hệ mới với bản lề Flex cải tiến không kẽ hở, hỗ trợ bút S Pen đa nhiệm tối đa.', 34990000.00, 1, 'uploads/images.jfif'),
(12, 'Samsung Galaxy Z Flip5 256GB', 'Thiết kế gập vỏ sò cá tính, màn hình phụ Flex Window 3.4 inch độc đáo chụp ảnh không cần mở máy.', 17990000.00, 1, 'uploads/images.jfif'),
(13, 'Samsung Galaxy A55 5G 128GB', 'Thiết kế khung viền kim loại sang trọng, khả năng kháng nước bụi IP67 cùng camera chụp đêm ấn tượng.', 9690000.00, 1, 'uploads/images.jfif'),
(14, 'Samsung Galaxy A35 5G 128GB', 'Màn hình Super AMOLED 120Hz mượt mà, bộ ba camera 50MP hỗ trợ chống rung quang học OIS.', 7990000.00, 1, 'uploads/images.jfif'),
(15, 'Xiaomi 14 Ultra 512GB', 'Ống kính Leica thế hệ mới chụp ảnh nghệ thuật đỉnh cao, cảm biến 1 inch thu sáng vượt trội.', 29990000.00, 1, 'uploads/images.jfif'),
(16, 'Xiaomi 14 256GB', 'Cấu hình Snapdragon 8 Gen 3 siêu mạnh, sạc nhanh HyperCharge 90W đầy pin chỉ trong 30 phút.', 20990000.00, 1, 'uploads/images.jfif'),
(17, 'Redmi Note 13 Pro 5G 128GB', 'Màn hình AMOLED 1.5K sắc nét, camera chính 200MP siêu độ phân giải chụp ảnh siêu chi tiết.', 8490000.00, 1, 'uploads/images.jfif'),
(18, 'Redmi 13C 6GB/128GB', 'Màn hình lớn 90Hz bảo vệ mắt tốt, pin trâu 5000mAh sử dụng trọn vẹn cả ngày dài không lo hết pin.', 3090000.00, 1, 'uploads/images.jfif'),
(19, 'Oppo Find N3 5G 512GB', 'Thiết kế gập mỏng nhẹ nhất phân khúc, camera Hasselblad đẳng cấp cùng khả năng đa nhiệm 3 ứng dụng mượt mà.', 41990000.00, 1, 'uploads/images.jfif'),
(20, 'Oppo Reno11 Pro 5G', 'Thiết kế mặt lưng vân đá độc đáo, camera chân dung tele chuyên nghiệp xóa phông ảo diệu.', 14990000.00, 1, 'uploads/images.jfif'),
(21, 'Oppo Reno11 5G 256GB', 'Thiết kế mỏng nhẹ cong tràn viền, sạc siêu tốc SuperVOOC 67W, hiệu năng MediaTek Dimensity 7050.', 9990000.00, 1, 'uploads/images.jfif'),
(22, 'Oppo A78 8GB/256GB', 'Loa kép âm lượng lớn 200%, sạc nhanh 67W, bộ nhớ trong cực lớn thoải mái lưu trữ dữ liệu.', 5990000.00, 1, 'uploads/images.jfif'),
(23, 'Vivo X100 Pro 512GB', 'Hệ thống ống kính Zeiss đỉnh cao chụp chân dung hoàn mỹ, chip Dimensity 9300 hiệu năng vượt trội.', 24990000.00, 1, 'uploads/images.jfif'),
(24, 'Vivo V30 5G 256GB', 'Hệ thống đèn Aura Light thế hệ mới hỗ trợ chụp đêm chuyên nghiệp, camera selfie 50MP sắc nét.', 12490000.00, 1, 'uploads/images.jfif'),
(25, 'Vivo Y200 5G 128GB', 'Màn hình tần số quét 120Hz mượt mà, thiết kế sang trọng siêu mỏng nhẹ thời trang cá tính.', 7290000.00, 1, 'uploads/images.jfif'),
(26, 'MacBook Pro 14 M3 8GB/512GB', 'Hiệu năng vượt trội từ chip M3 sản xuất trên tiến trình 3nm, màn hình Liquid Retina XDR đẹp hoàn hảo.', 39990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(27, 'MacBook Pro 16 M3 Pro 18GB/512GB', 'Quái thú đồ họa cho dân chuyên nghiệp, xử lý mượt mà các tác vụ render video 4K/8K, lập trình nặng.', 59990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(28, 'MacBook Air 13 M3 8GB/256GB', 'Thiết kế siêu mỏng nhẹ không tiếng ồn, hiệu năng ấn tượng cùng thời lượng pin lên đến 18 tiếng liên tục.', 27990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(29, 'MacBook Air 15 M3 16GB/512GB', 'Không gian hiển thị rộng rãi 15.3 inch sắc nét, cấu hình nâng cấp 16GB RAM đa nhiệm mượt mà.', 37990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(30, 'Dell XPS 13 Plus 9320 Core i7', 'Kiệt tác thiết kế mỏng nhẹ tương lai với bàn phím tràn viền, touchpad tàng hình và màn hình OLED cảm ứng.', 45990000.00, 2, 'uploads/acer.png'),
(31, 'Dell Inspiron 16 5630 Core i5', 'Màn hình 16 inch tỷ lệ 16:10 làm việc văn phòng thoải mái, vỏ nhôm bền bỉ cứng cáp.', 18490000.00, 2, 'uploads/acer.png'),
(32, 'Dell Vostro 3520 Core i5', 'Laptop văn phòng thực dụng với màn hình 120Hz mượt mà, đầy đủ các cổng kết nối phổ biến hiện nay.', 13990000.00, 2, 'uploads/acer.png'),
(33, 'HP Spectre x360 14 Core Ultra 7', 'Laptop 2-trong-1 xoay gập 360 độ cao cấp nhất của HP, đi kèm màn hình OLED sắc nét cảm ứng mượt mà.', 48990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(34, 'HP Pavilion 14 Core i5 13th Gen', 'Dòng laptop văn phòng mỏng nhẹ phổ thông, vỏ nhôm sang trọng, cấu hình ổn định cho học tập.', 15290000.00, 2, 'uploads/acer.png'),
(35, 'HP EliteBook 840 G10 Core i7', 'Dòng máy doanh nghiệp cao cấp bảo mật thông tin tuyệt đối, độ bền đạt chuẩn quân đội Mỹ.', 28990000.00, 2, 'uploads/acer.png'),
(36, 'Asus Zenbook 14 OLED UX3405', 'Đỉnh cao siêu mỏng nhẹ chỉ 1.2kg, cấu hình Core Ultra 5 thế hệ mới tích hợp nhân xử lý AI thông minh.', 26990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(37, 'Asus Vivobook 15 X1504ZA Core i3', 'Laptop giá rẻ cho học sinh sinh viên đáp ứng tốt các nhu cầu học tập, xem phim, lướt web hàng ngày.', 9990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(38, 'Asus ROG Zephyrus G14 Ryzen 7', 'Laptop gaming mỏng nhẹ đẹp nhất thế giới, màn hình 120Hz OLED, card đồ họa RTX 4060 chiến game đỉnh cao.', 39990000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(39, 'Lenovo ThinkPad X1 Carbon Gen 11', 'Laptop doanh nhân huyền thoại với bàn phím gõ êm nhất thế giới, trọng lượng siêu nhẹ dưới 1kg từ sợi carbon.', 49990000.00, 2, 'uploads/acer.png'),
(40, 'Lenovo Legion 5 Core i7 RTX 4060', 'Quái thú gaming quốc dân tản nhiệt siêu mát, cấu hình mạnh mẽ cân tốt mọi tựa game AAA hiện nay.', 32990000.00, 2, 'uploads/acer.png'),
(41, 'Lenovo IdeaPad Slim 3 Core i5', 'Thiết kế đơn giản, cấu hình Core i5 RAM 16GB mượt mà đa nhiệm tốt nhất trong tầm giá.', 14490000.00, 2, 'uploads/acer.png'),
(42, 'Acer Predator Helios Neo 16', 'Laptop gaming cao cấp màn hình 2K 165Hz siêu mượt, hiệu năng đồ họa cực khủng từ RTX 4060.', 33990000.00, 2, 'uploads/acer.png'),
(43, 'Acer Aspire 5 A515 Core i5', 'Sản phẩm đa dụng mỏng nhẹ vỏ nhôm, cấu hình mạnh với ổ cứng SSD 512GB siêu tốc.', 12990000.00, 2, 'uploads/acer.png'),
(44, 'Acer Swift Go 14 OLED Core i5', 'Màn hình OLED 90Hz hiển thị màu sắc rực rỡ chuẩn đồ họa, mỏng nhẹ thời trang tiện lợi mang đi lại.', 17990000.00, 2, 'uploads/acer.png'),
(45, 'iPad Pro 11 inch M4 Wifi 256GB', 'Đột phá công nghệ với màn hình Tandem OLED siêu sáng siêu mỏng, sức mạnh hủy diệt từ chip Apple M4.', 28490000.00, 3, 'uploads/tải xuống.webp'),
(46, 'iPad Pro 13 inch M4 Wifi 256GB', 'Màn hình khổng lồ 13 inch Tandem OLED, độ mỏng ấn tượng chỉ 5.1mm, thích hợp cho họa sĩ vẽ digital chuyên nghiệp.', 36990000.00, 3, 'uploads/tải xuống.webp'),
(47, 'iPad Air 11 inch M2 Wifi 128GB', 'Sở hữu sức mạnh vượt trội từ chip M2, tương thích bút Apple Pencil Pro mới cho trải nghiệm vẽ tuyệt vời.', 16490000.00, 3, 'uploads/tải xuống.webp'),
(48, 'iPad Air 13 inch M2 Wifi 128GB', 'Lần đầu tiên dòng Air có phiên bản màn hình lớn 13 inch, mang lại không gian làm việc rộng rãi giá hợp lý.', 21490000.00, 3, 'uploads/tải xuống.webp'),
(49, 'iPad Gen 10 10.9 inch Wifi 64GB', 'Thiết kế vuông vức thời trang đa sắc màu, kết nối Type-C hiện đại phục vụ tốt học online và giải trí gia đình.', 9490000.00, 3, 'uploads/tải xuống.webp'),
(50, 'iPad Mini 6 Wifi 64GB', 'Kích thước siêu nhỏ gọn 8.3 inch nằm gọn trong lòng bàn tay, hiệu năng cực mạnh từ chip A15 Bionic.', 11990000.00, 3, 'uploads/tải xuống.webp'),
(51, 'Samsung Galaxy Tab S9 Ultra 256GB', 'Màn hình khổng lồ 14.6 inch Dynamic AMOLED 2X, đi kèm bút S Pen thần thánh và khả năng kháng nước bụi IP68.', 25990000.00, 3, 'uploads/tải xuống.webp'),
(52, 'Samsung Galaxy Tab S9 FE Wifi', 'Phiên bản rút gọn cấu hình ngon giá tốt, kèm sẵn bút S Pen trong hộp thoải mái ghi chú vẽ vời.', 9990000.00, 3, 'uploads/tải xuống.webp'),
(53, 'Samsung Galaxy Tab A9 Wifi 64GB', 'Máy tính bảng phân khúc bình dân, thiết kế vỏ kim loại sang trọng, phù hợp cho trẻ em học tập giải trí.', 3290000.00, 3, 'uploads/tải xuống.webp'),
(54, 'Xiaomi Pad 6 8GB/128GB', 'Màn hình 144Hz siêu mượt hiển thị cực đẹp, vỏ nhôm nguyên khối chắc chắn cấu hình Snapdragon 870 ổn định.', 7990000.00, 3, 'uploads/tải xuống.webp'),
(55, 'Redmi Pad SE 4GB/128GB', 'Màn hình lớn 11 inch tần số quét 90Hz, 4 loa âm thanh vòm Dolby Atmos giá rẻ giật mình.', 3990000.00, 3, 'uploads/tải xuống.webp'),
(56, 'Bàn phím cơ không dây Keychron K2 V2', 'Bàn phím cơ gọn nhẹ layout 84 phím, kết nối cùng lúc 3 thiết bị bluetooth, tương thích tốt cả Windows và Mac.', 1850000.00, 4, 'uploads/artboard_2.png'),
(57, 'Bàn phím cơ không dây Keychron K6', 'Layout 68 phím siêu nhỏ gọn, tính năng hotswap dễ dàng thay đổi switch theo sở thích cá nhân.', 1950000.00, 4, 'uploads/artboard_2.png'),
(58, 'Bàn phím Logitech MX Keys S', 'Bàn phím không dây cao cấp thiết kế công thái học gõ phím êm ái, đèn nền thông minh tự động sáng khi đưa tay tới gần.', 2890000.00, 4, 'uploads/artboard_2.png'),
(59, 'Chuột không dây Logitech MX Master 3S', 'Chuột văn phòng tốt nhất thế giới, nút cuộn từ tính MagSpeed siêu nhanh, cảm biến 8000 DPI di chuột trên kính mượt mà.', 2490000.00, 4, 'uploads/artboard_2.png'),
(60, 'Chuột chơi game Logitech G502 Hero', 'Chuột gaming huyền thoại tích hợp 11 nút lập trình, cảm biến Hero 25K siêu chính xác cùng tạ điều chỉnh trọng lượng.', 1090000.00, 4, 'uploads/artboard_2.png'),
(61, 'Chuột bluetooth Logitech Pebble M350', 'Thiết kế mỏng nhẹ dạng sỏi, nút bấm silent giảm tiếng ồn 90%, thời trang nhiều màu sắc cá tính.', 390000.00, 4, 'uploads/artboard_2.png'),
(62, 'Sạc nhanh Anker Nano 3 30W', 'Củ sạc siêu nhỏ gọn sử dụng công nghệ GaN an toàn, sạc nhanh tối đa cho iPhone và các điện thoại Android khác.', 350000.00, 4, 'uploads/artboard_2.png'),
(63, 'Sạc nhanh Anker Prime 67W GaN', 'Trang bị 3 cổng sạc (2 Type-C, 1 USB-A) sạc nhanh cùng lúc cho cả điện thoại, máy tính bảng và laptop mỏng nhẹ.', 990000.00, 4, 'uploads/artboard_2.png'),
(64, 'Cáp sạc đa năng Baseus 3 trong 1', 'Tích hợp cả 3 đầu sạc Type-C, Lightning và Micro USB tiện lợi sạc nhiều thiết bị chỉ với một dây cáp.', 180000.00, 4, 'uploads/artboard_2.png'),
(65, 'Sạc dự phòng Shargeek Storm 2 100W', 'Thiết kế vỏ trong suốt độc đáo thấy rõ linh kiện bên trong, màn hình IPS hiển thị chi tiết thông số dòng điện sạc.', 4990000.00, 4, 'uploads/artboard_2.png'),
(66, 'Sạc dự phòng Anker 10000mAh 12W', 'Thiết kế gọn nhẹ dễ dàng mang đi học đi làm, dung lượng 10000mAh sạc đầy hơn 2 lần iPhone thường.', 450000.00, 4, 'uploads/artboard_2.png'),
(67, 'Giá đỡ laptop tản nhiệt nhôm gấp gọn', 'Chất liệu nhôm siêu nhẹ cao cấp, điều chỉnh 6 mức độ cao công thái học chống mỏi cổ vai gáy.', 190000.00, 4, 'uploads/artboard_2.png'),
(68, 'Đế sạc không dây 3 trong 1 Belkin', 'Đế sạc nhanh chuẩn Magsafe sạc cùng lúc iPhone, Apple Watch và tai nghe AirPods sang trọng trên bàn làm việc.', 3290000.00, 4, 'uploads/artboard_2.png'),
(69, 'Tai nghe AirPods Pro 2 USB-C', 'Chống ồn chủ động ANC đỉnh cao tăng gấp 2 lần, tính năng âm thanh thích ứng cùng cổng sạc Type-C tiện lợi.', 5790000.00, 5, 'uploads/artboard_3.png'),
(70, 'Tai nghe AirPods 3', 'Thiết kế công thái học bám tai cực tốt, âm thanh vòm sống động Spatial Audio kháng nước nhẹ chuẩn IPX4.', 4190000.00, 5, 'uploads/artboard_3.png'),
(71, 'Tai nghe Sony WF-1000XM5', 'Tai nghe true wireless chống ồn tốt nhất thế giới, tái tạo âm thanh độ phân giải cao Hi-Res sắc nét từng chi tiết.', 5490000.00, 5, 'uploads/artboard_3.png'),
(72, 'Tai nghe Sony WH-1000XM5', 'Tai nghe chụp tai chống ồn đỉnh cao, thời lượng pin khủng lên đến 30 tiếng liên tục, đệm tai da êm ái.', 6990000.00, 5, 'uploads/artboard_3.png'),
(73, 'Tai nghe Marshall Motif II A.N.C', 'Thiết kế đậm chất rock đặc trưng Marshall, kết nối Bluetooth 5.3 LE tiết kiệm pin, thời gian nghe nhạc đến 30 giờ.', 4990000.00, 5, 'uploads/artboard_3.png'),
(74, 'Loa bluetooth Marshall Emberton II', 'Thiết kế nhỏ gọn cổ điển sang trọng, chống nước bụi tuyệt đối IP67, âm thanh đa hướng 360 độ độc đáo.', 3990000.00, 5, 'uploads/artboard_3.png'),
(75, 'Loa bluetooth JBL Charge 5', 'Loa di động âm trầm cực sâu mạnh mẽ đặc trưng JBL Pro Sound, tích hợp cổng USB làm sạc dự phòng cho điện thoại.', 3490000.00, 5, 'uploads/artboard_3.png'),
(76, 'Loa bluetooth JBL Flip 6', 'Thiết kế dạng ống tiện lợi mang đi du lịch dã ngoại, hệ thống loa 2 đường tiếng cho âm thanh to rõ ràng.', 2690000.00, 5, 'uploads/artboard_3.png'),
(77, 'Tai nghe chụp tai gaming Kingston HyperX Cloud II', 'Tai nghe gaming huyền thoại tái tạo âm thanh vòm 7.1 giả lập, giúp game thủ định vị tiếng bước chân cực chính xác.', 1850000.00, 5, 'uploads/artboard_3.png'),
(78, 'Asus Zenbook 14 OLED UX3405 (Màu Đen)', 'Đỉnh cao siêu mỏng nhẹ chỉ 1.2kg, cấu hình Core Ultra 5 thế hệ mới tích hợp nhân xử lý AI thông minh. Phiên bản đặc biệt màu thời thượng.', 27710000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(79, 'Oppo Find N3 5G 512GB (Màu Xanh)', 'Thiết kế gập mỏng nhẹ nhất phân khúc, camera Hasselblad đẳng cấp cùng khả năng đa nhiệm 3 ứng dụng mượt mà. Phiên bản đặc biệt màu thời thượng.', 42910000.00, 1, 'uploads/images.jfif'),
(80, 'Acer Swift Go 14 OLED Core i5 (Màu Xanh)', 'Màn hình OLED 90Hz hiển thị màu sắc rực rỡ chuẩn đồ họa, mỏng nhẹ thời trang tiện lợi mang đi lại. Phiên bản đặc biệt màu thời thượng.', 17800000.00, 2, 'uploads/acer.png'),
(81, 'MacBook Pro 14 M3 8GB/512GB (Màu Vàng Gold)', 'Hiệu năng vượt trội từ chip M3 sản xuất trên tiến trình 3nm, màn hình Liquid Retina XDR đẹp hoàn hảo. Phiên bản đặc biệt màu thời thượng.', 40530000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(82, 'iPhone 15 128GB (Màu Xám Space)', 'Đột phá với Dynamic Island, camera chính 48MP siêu nét và thiết kế mặt lưng kính pha màu thời thượng. Phiên bản đặc biệt màu thời thượng.', 19410000.00, 1, 'uploads/images.jfif'),
(83, 'Tai nghe Marshall Motif II A.N.C (Màu Xám Space)', 'Thiết kế đậm chất rock đặc trưng Marshall, kết nối Bluetooth 5.3 LE tiết kiệm pin, thời gian nghe nhạc đến 30 giờ. Phiên bản đặc biệt màu thời thượng.', 4940000.00, 5, 'uploads/artboard_3.png'),
(84, 'HP Spectre x360 14 Core Ultra 7 (Màu Trắng)', 'Laptop 2-trong-1 xoay gập 360 độ cao cấp nhất của HP, đi kèm màn hình OLED sắc nét cảm ứng mượt mà. Phiên bản đặc biệt màu thời thượng.', 51220000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(85, 'Acer Swift Go 14 OLED Core i5 (Màu Xanh) (Màu Trắng)', 'Màn hình OLED 90Hz hiển thị màu sắc rực rỡ chuẩn đồ họa, mỏng nhẹ thời trang tiện lợi mang đi lại. Phiên bản đặc biệt màu thời thượng. Phiên bản đặc biệt màu thời thượng.', 18090000.00, 2, 'uploads/acer.png'),
(86, 'Asus ROG Zephyrus G14 Ryzen 7 (Màu Đen)', 'Laptop gaming mỏng nhẹ đẹp nhất thế giới, màn hình 120Hz OLED, card đồ họa RTX 4060 chiến game đỉnh cao. Phiên bản đặc biệt màu thời thượng.', 38950000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(87, 'Tai nghe AirPods Pro 2 USB-C (Màu Vàng Gold)', 'Chống ồn chủ động ANC đỉnh cao tăng gấp 2 lần, tính năng âm thanh thích ứng cùng cổng sạc Type-C tiện lợi. Phiên bản đặc biệt màu thời thượng.', 6040000.00, 5, 'uploads/artboard_3.png'),
(88, 'iPhone 15 128GB (Màu Xám Space) (Màu Đen)', 'Đột phá với Dynamic Island, camera chính 48MP siêu nét và thiết kế mặt lưng kính pha màu thời thượng. Phiên bản đặc biệt màu thời thượng. Phiên bản đặc biệt màu thời thượng.', 19920000.00, 1, 'uploads/images.jfif'),
(89, 'Asus Zenbook 14 OLED UX3405 (Màu Đen) (Màu Trắng)', 'Đỉnh cao siêu mỏng nhẹ chỉ 1.2kg, cấu hình Core Ultra 5 thế hệ mới tích hợp nhân xử lý AI thông minh. Phiên bản đặc biệt màu thời thượng. Phiên bản đặc biệt màu thời thượng.', 27250000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(90, 'iPad Mini 6 Wifi 64GB (Màu Xám Space)', 'Kích thước siêu nhỏ gọn 8.3 inch nằm gọn trong lòng bàn tay, hiệu năng cực mạnh từ chip A15 Bionic. Phiên bản đặc biệt màu thời thượng.', 12280000.00, 3, 'uploads/tải xuống.webp'),
(91, 'Vivo Y200 5G 128GB (Màu Vàng Gold)', 'Màn hình tần số quét 120Hz mượt mà, thiết kế sang trọng siêu mỏng nhẹ thời trang cá tính. Phiên bản đặc biệt màu thời thượng.', 7300000.00, 1, 'uploads/images.jfif'),
(92, 'Dell Inspiron 16 5630 Core i5 (Màu Vàng Gold)', 'Màn hình 16 inch tỷ lệ 16:10 làm việc văn phòng thoải mái, vỏ nhôm bền bỉ cứng cáp. Phiên bản đặc biệt màu thời thượng.', 17770000.00, 2, 'uploads/acer.png'),
(93, 'Tai nghe chụp tai gaming Kingston HyperX Cloud II (Màu Vàng Gold)', 'Tai nghe gaming huyền thoại tái tạo âm thanh vòm 7.1 giả lập, giúp game thủ định vị tiếng bước chân cực chính xác. Phiên bản đặc biệt màu thời thượng.', 1870000.00, 5, 'uploads/artboard_3.png'),
(94, 'HP Spectre x360 14 Core Ultra 7 (Màu Xanh)', 'Laptop 2-trong-1 xoay gập 360 độ cao cấp nhất của HP, đi kèm màn hình OLED sắc nét cảm ứng mượt mà. Phiên bản đặc biệt màu thời thượng.', 47870000.00, 2, 'uploads/asus_e37701b35e764cf38c6a2aba57eaa38f.png'),
(95, 'Oppo Reno11 5G 256GB (Màu Xám Space)', 'Thiết kế mỏng nhẹ cong tràn viền, sạc siêu tốc SuperVOOC 67W, hiệu năng MediaTek Dimensity 7050. Phiên bản đặc biệt màu thời thượng.', 10310000.00, 1, 'uploads/images.jfif'),
(96, 'Tai nghe AirPods Pro 2 USB-C (Màu Trắng)', 'Chống ồn chủ động ANC đỉnh cao tăng gấp 2 lần, tính năng âm thanh thích ứng cùng cổng sạc Type-C tiện lợi. Phiên bản đặc biệt màu thời thượng.', 5510000.00, 5, 'uploads/artboard_3.png'),
(97, 'Acer Aspire 5 A515 Core i5 (Màu Xanh)', 'Sản phẩm đa dụng mỏng nhẹ vỏ nhôm, cấu hình mạnh với ổ cứng SSD 512GB siêu tốc. Phiên bản đặc biệt màu thời thượng.', 13220000.00, 2, 'uploads/acer.png'),
(98, 'Vivo V30 5G 256GB (Màu Xám Space)', 'Hệ thống đèn Aura Light thế hệ mới hỗ trợ chụp đêm chuyên nghiệp, camera selfie 50MP sắc nét. Phiên bản đặc biệt màu thời thượng.', 12380000.00, 1, 'uploads/images.jfif'),
(99, 'HP EliteBook 840 G10 Core i7 (Màu Đen)', 'Dòng máy doanh nghiệp cao cấp bảo mật thông tin tuyệt đối, độ bền đạt chuẩn quân đội Mỹ. Phiên bản đặc biệt màu thời thượng.', 29690000.00, 2, 'uploads/acer.png'),
(100, 'Tai nghe AirPods Pro 2 USB-C (Màu Vàng Gold) (Màu Xanh)', 'Chống ồn chủ động ANC đỉnh cao tăng gấp 2 lần, tính năng âm thanh thích ứng cùng cổng sạc Type-C tiện lợi. Phiên bản đặc biệt màu thời thượng. Phiên bản đặc biệt màu thời thượng.', 6110000.00, 5, 'uploads/artboard_3.png')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), price=VALUES(price), category_id=VALUES(category_id), image=VALUES(image);
