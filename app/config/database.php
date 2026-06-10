<?php
class Database {
    private $host = "localhost";
    private $db_name = "my_store";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Ensure the database exists before connecting to dbname
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $temp_conn = null;

            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Automatically initialize tables and default data
            $this->initializeDatabase();
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    private function initializeDatabase() {
        try {
            // 1. Create account table
            $queryAccount = "CREATE TABLE IF NOT EXISTS account (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                fullname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NULL,
                avatar VARCHAR(255) NULL DEFAULT 'uploads/avatars/default.png',
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'user') DEFAULT 'user',
                is_locked TINYINT(1) DEFAULT 0,
                is_verified TINYINT(1) DEFAULT 0,
                remember_token VARCHAR(255) NULL,
                reset_token VARCHAR(255) NULL,
                verification_token VARCHAR(255) NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryAccount);

            // Add columns to account table if they do not exist (migration for existing table)
            $columns = [
                'email' => "ALTER TABLE account ADD COLUMN email VARCHAR(255) NULL AFTER fullname",
                'avatar' => "ALTER TABLE account ADD COLUMN avatar VARCHAR(255) NULL DEFAULT 'uploads/avatars/default.png' AFTER email",
                'is_locked' => "ALTER TABLE account ADD COLUMN is_locked TINYINT(1) DEFAULT 0 AFTER role",
                'failed_attempts' => "ALTER TABLE account ADD COLUMN failed_attempts INT DEFAULT 0 AFTER is_locked",
                'locked_until' => "ALTER TABLE account ADD COLUMN locked_until DATETIME NULL AFTER failed_attempts",
                'is_verified' => "ALTER TABLE account ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER locked_until",
                'remember_token' => "ALTER TABLE account ADD COLUMN remember_token VARCHAR(255) NULL AFTER is_verified",
                'refresh_token' => "ALTER TABLE account ADD COLUMN refresh_token VARCHAR(255) NULL AFTER remember_token",
                'reset_token' => "ALTER TABLE account ADD COLUMN reset_token VARCHAR(255) NULL AFTER refresh_token",
                'verification_token' => "ALTER TABLE account ADD COLUMN verification_token VARCHAR(255) NULL AFTER reset_token",
            ];

            foreach ($columns as $col => $alterQuery) {
                $checkQuery = $this->conn->query("SHOW COLUMNS FROM account LIKE '$col'");
                if ($checkQuery->rowCount() == 0) {
                    $this->conn->exec($alterQuery);
                }
            }

            // Seed default account data
            $stmt = $this->conn->query("SELECT COUNT(*) FROM account");
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                $adminPass = password_hash('admin', PASSWORD_BCRYPT);
                $stmtAdmin = $this->conn->prepare("INSERT INTO account (username, fullname, email, password, role, is_verified) VALUES ('admin', 'Administrator', 'admin@techstore.com', :pass, 'admin', 1)");
                $stmtAdmin->execute([':pass' => $adminPass]);

                $userPass = password_hash('user', PASSWORD_BCRYPT);
                $stmtUser = $this->conn->prepare("INSERT INTO account (username, fullname, email, password, role, is_verified) VALUES ('user', 'Khách hàng thử nghiệm', 'user@techstore.com', :pass, 'user', 1)");
                $stmtUser->execute([':pass' => $userPass]);
            }

            // 2. Create category table
            $queryCategory = "CREATE TABLE IF NOT EXISTS category (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryCategory);

            // 3. Create product table
            $queryProduct = "CREATE TABLE IF NOT EXISTS product (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                category_id INT NULL,
                image VARCHAR(255) NULL,
                FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryProduct);

            // 4. Create orders table and ensure columns exist
            $queryOrdersTable = "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                address TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryOrdersTable);

            // Add user_id column if not exists
            $checkUserQuery = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'user_id'");
            if ($checkUserQuery->rowCount() == 0) {
                $this->conn->exec("ALTER TABLE orders ADD COLUMN user_id INT NULL AFTER id");
            }

            // Add status column if not exists
            $checkStatusQuery = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'status'");
            if ($checkStatusQuery->rowCount() == 0) {
                $this->conn->exec("ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'Đang xử lý' AFTER address");
            }

            // Add payment_method column if not exists
            $checkPaymentMethodQuery = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'payment_method'");
            if ($checkPaymentMethodQuery->rowCount() == 0) {
                $this->conn->exec("ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT 'COD' AFTER status");
            }

            // Add payment_status column if not exists
            $checkPaymentStatusQuery = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'payment_status'");
            if ($checkPaymentStatusQuery->rowCount() == 0) {
                $this->conn->exec("ALTER TABLE orders ADD COLUMN payment_status VARCHAR(50) DEFAULT 'Chưa thanh toán' AFTER payment_method");
            }

            // 5. Create order_details table
            $queryOrderDetails = "CREATE TABLE IF NOT EXISTS order_details (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryOrderDetails);

        } catch (PDOException $e) {
            // Ignore errors here to avoid interrupting the connection lifecycle
        }
    }
}
?>