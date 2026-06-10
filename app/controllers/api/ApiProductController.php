<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/controllers/api/ApiAccountController.php';

class ApiProductController {
    private $db;
    private $productModel;
    private $categoryModel;
    private $authController;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->authController = new ApiAccountController();
    }

    public function handleRequest($id, $subAction) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($id === 'upload-image') {
            if ($method === 'POST') $this->uploadImage();
            else $this->methodNotAllowed();
            return;
        }

        if ($id) {
            if ($method === 'GET') $this->show($id);
            elseif ($method === 'POST') $this->update($id); // POST allows file upload
            elseif ($method === 'DELETE') $this->delete($id);
            else $this->methodNotAllowed();
        } else {
            if ($method === 'GET') $this->index();
            elseif ($method === 'POST') $this->store();
            else $this->methodNotAllowed();
        }
    }

    // GET /api/products
    private function index() {
        // Query parameters
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';
        $sortBy = $_GET['sort_by'] ?? ''; // price_asc, price_desc
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;
        $offset = ($page - 1) * $limit;

        // Build query
        $query = "SELECT p.*, c.name as category_name FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE 1=1";
        $countQuery = "SELECT COUNT(*) FROM product p WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND (p.name LIKE :keyword OR p.description LIKE :keyword)";
            $countQuery .= " AND (p.name LIKE :keyword OR p.description LIKE :keyword)";
            $params[':keyword'] = "%{$keyword}%";
        }

        if (!empty($categoryId)) {
            $query .= " AND p.category_id = :category_id";
            $countQuery .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        if ($minPrice !== '' && is_numeric($minPrice)) {
            $query .= " AND p.price >= :min_price";
            $countQuery .= " AND p.price >= :min_price";
            $params[':min_price'] = $minPrice;
        }

        if ($maxPrice !== '' && is_numeric($maxPrice)) {
            $query .= " AND p.price <= :max_price";
            $countQuery .= " AND p.price <= :max_price";
            $params[':max_price'] = $maxPrice;
        }

        // Total count
        $stmtCount = $this->db->prepare($countQuery);
        $stmtCount->execute($params);
        $totalItems = (int)$stmtCount->fetchColumn();
        $totalPages = ceil($totalItems / $limit);

        // Sorting
        if ($sortBy === 'price_asc') {
            $query .= " ORDER BY p.price ASC";
        } elseif ($sortBy === 'price_desc') {
            $query .= " ORDER BY p.price DESC";
        } else {
            $query .= " ORDER BY p.id DESC";
        }

        // Limit & Offset
        $query .= " LIMIT :offset, :limit";
        
        $stmt = $this->db->prepare($query);
        // Bind parameters
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode([
            'success' => true,
            'data' => $products,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total_items' => $totalItems,
                'total_pages' => $totalPages
            ]
        ]);
    }

    // GET /api/products/{id}
    private function show($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $product
        ]);
    }

    // POST /api/products (Create)
    private function store() {
        // Enforce Admin role
        $this->authController->getAuthorizedUser('admin');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $categoryId = $data['category_id'] ?? '';
        $image = $data['image'] ?? '';

        // Validation
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên sản phẩm không được rỗng.']);
            return;
        }

        if (!is_numeric($price) || $price <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Giá phải là số và lớn hơn 0.']);
            return;
        }

        // Validate category
        if (!empty($categoryId)) {
            $cat = $this->categoryModel->getCategoryById($categoryId);
            if (!$cat) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Danh mục sản phẩm không hợp lệ.']);
                return;
            }
        }

        // Upload image from file if exists
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadFile($_FILES['image']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
        }

        $result = $this->productModel->addProduct($name, $description, $price, $categoryId, $image);
        if ($result && !is_array($result)) {
            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi thêm sản phẩm.', 'errors' => $result]);
        }
    }

    // POST /api/products/{id} (Update)
    private function update($id) {
        // Enforce Admin role
        $this->authController->getAuthorizedUser('admin');

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
            return;
        }

        // Decode input
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $name = $data['name'] ?? $product->name;
        $description = $data['description'] ?? $product->description;
        $price = $data['price'] ?? $product->price;
        $categoryId = $data['category_id'] ?? $product->category_id;
        $image = $data['image'] ?? $product->image;

        // Validation
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên sản phẩm không được rỗng.']);
            return;
        }

        if (!is_numeric($price) || $price <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Giá phải là số và lớn hơn 0.']);
            return;
        }

        if (!empty($categoryId)) {
            $cat = $this->categoryModel->getCategoryById($categoryId);
            if (!$cat) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Danh mục sản phẩm không hợp lệ.']);
                return;
            }
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadFile($_FILES['image']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
        }

        $success = $this->productModel->updateProduct($id, $name, $description, $price, $categoryId, $image);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật sản phẩm.']);
        }
    }

    // DELETE /api/products/{id}
    private function delete($id) {
        // Enforce Admin role
        $this->authController->getAuthorizedUser('admin');

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
            return;
        }

        if ($this->productModel->deleteProduct($id)) {
            echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi xóa sản phẩm.']);
        }
    }

    // POST /api/products/upload-image
    private function uploadImage() {
        $this->authController->getAuthorizedUser('admin');

        if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hình ảnh để tải lên.']);
            return;
        }

        try {
            $path = $this->uploadFile($_FILES['image']);
            echo json_encode(['success' => true, 'image_url' => $path]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function uploadFile($file) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($file["name"]);
        $target_file = $target_dir . $fileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn (tối đa 10MB).");
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG, WEBP và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    }
}
?>
