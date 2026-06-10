<?php
require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/controllers/api/ApiAccountController.php';

class ApiCategoryController {
    private $db;
    private $categoryModel;
    private $authController;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->authController = new ApiAccountController();
    }

    public function handleRequest($id, $subAction) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($id) {
            if ($method === 'GET') $this->show($id);
            elseif ($method === 'PUT' || $method === 'POST') $this->update($id);
            elseif ($method === 'DELETE') $this->delete($id);
            else $this->methodNotAllowed();
        } else {
            if ($method === 'GET') $this->index();
            elseif ($method === 'POST') $this->store();
            else $this->methodNotAllowed();
        }
    }

    private function index() {
        $categories = $this->categoryModel->getCategories();
        echo json_encode([
            'success' => true,
            'data' => $categories
        ]);
    }

    private function show($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $category
        ]);
    }

    private function store() {
        $this->authController->getAuthorizedUser('admin');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống.']);
            return;
        }

        $success = $this->categoryModel->addCategory($name, $description);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Thêm danh mục thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi thêm danh mục.']);
        }
    }

    private function update($id) {
        $this->authController->getAuthorizedUser('admin');

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục.']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $name = $data['name'] ?? $category->name;
        $description = $data['description'] ?? $category->description;

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống.']);
            return;
        }

        $success = $this->categoryModel->updateCategory($id, $name, $description);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật danh mục thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật danh mục.']);
        }
    }

    private function delete($id) {
        $this->authController->getAuthorizedUser('admin');

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục.']);
            return;
        }

        // Check if category has products
        if ($this->categoryModel->hasProducts($id)) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => 'Không thể xóa danh mục này vì vẫn còn sản phẩm thuộc danh mục đó.'
            ]);
            return;
        }

        $success = $this->categoryModel->deleteCategory($id);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Xóa danh mục thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi xóa danh mục.']);
        }
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    }
}
?>
