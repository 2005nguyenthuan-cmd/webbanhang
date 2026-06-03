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
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'user') DEFAULT 'user'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->conn->exec($queryAccount);

            // Seed default account data
            $stmt = $this->conn->query("SELECT COUNT(*) FROM account");
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                $adminPass = password_hash('admin', PASSWORD_BCRYPT);
                $stmtAdmin = $this->conn->prepare("INSERT INTO account (username, fullname, password, role) VALUES ('admin', 'Administrator', :pass, 'admin')");
                $stmtAdmin->execute([':pass' => $adminPass]);

                $userPass = password_hash('user', PASSWORD_BCRYPT);
                $stmtUser = $this->conn->prepare("INSERT INTO account (username, fullname, password, role) VALUES ('user', 'Khách hàng thử nghiệm', :pass, 'user')");
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