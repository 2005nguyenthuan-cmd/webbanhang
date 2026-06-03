<?php
// Require necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/AccountModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        // Chặn người dùng không phải Admin
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối. Bạn phải là Quản trị viên để thực hiện thao tác này.";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        // Chặn người dùng không phải Admin
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối.";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['image_url'] ?? '';
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: ' . BASE_URL . '/Product');
            }
        }
    }

    public function edit($id)
    {
        // Chặn người dùng không phải Admin
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối. Bạn phải là Quản trị viên để thực hiện thao tác này.";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        // Chặn người dùng không phải Admin
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối.";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = (!empty($_POST['image_url'])) ? $_POST['image_url'] : ($_POST['existing_image'] ?? '');
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: ' . BASE_URL . '/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        // Chặn người dùng không phải Admin
        if (!AccountModel::isAdmin()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['auth_error'] = "Quyền truy cập bị từ chối. Bạn phải là Quản trị viên để thực hiện thao tác này.";
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . BASE_URL . '/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    // Chức năng Tìm kiếm sản phẩm
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchProducts($keyword);
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/list.php';
    }

    // Chức năng Lọc sản phẩm theo danh mục
    public function category($categoryId)
    {
        $products = $this->productModel->getProductsByCategory($categoryId);
        $categories = (new CategoryModel($this->db))->getCategories();

        $activeCategory = null;
        foreach ($categories as $cat) {
            if ($cat->id == $categoryId) {
                $activeCategory = $cat;
                break;
            }
        }
        include 'app/views/product/list.php';
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $color = isset($_GET['color']) && $_GET['color'] !== '' ? $_GET['color'] : 'Đen Nhám';
        $storage = isset($_GET['storage']) && $_GET['storage'] !== '' ? $_GET['storage'] : '128 GB';

        $cartKey = $id . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $color . '_' . $storage);

        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity']++;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'color' => $color,
                'storage' => $storage
            ];
        }
        header('Location: ' . BASE_URL . '/Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->beginTransaction();
            try {
                // Lấy user_id dựa trên tài khoản đang đăng nhập trong session (bảng account)
                $userId = null;
                if (isset($_SESSION['username'])) {
                    $queryAccount = "SELECT id FROM account WHERE username = :username";
                    $stmtAccount = $this->db->prepare($queryAccount);
                    $stmtAccount->execute([':username' => $_SESSION['username']]);
                    $account = $stmtAccount->fetch(PDO::FETCH_OBJ);
                    if ($account) {
                        $userId = $account->id;
                    }
                }
                
                $query = "INSERT INTO orders (user_id, name, phone, address, status) VALUES (:user_id, :name, :phone, :address, 'Đang xử lý')";

                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                $cart = $_SESSION['cart'];
                foreach ($cart as $cartKey => $item) {
                    $product_id = isset($item['id']) ? $item['id'] : $cartKey;
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";

                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                unset($_SESSION['cart']);
                $this->db->commit();
                header('Location: ' . BASE_URL . '/Product/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>