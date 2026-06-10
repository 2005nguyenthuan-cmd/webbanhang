<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

class ApiCartController {
    private $db;
    private $productModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function handleRequest($id, $subAction) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($id) {
            // id represents the cart key to delete/modify
            if ($method === 'DELETE') $this->remove($id);
            else $this->methodNotAllowed();
        } else {
            if ($method === 'GET') $this->index();
            elseif ($method === 'POST') $this->add();
            elseif ($method === 'PUT') $this->update();
            elseif ($method === 'DELETE') $this->clear();
            else $this->methodNotAllowed();
        }
    }

    private function index() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $totalAmount = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'items' => array_values($cart), // convert associative array to indexed
                'total_amount' => $totalAmount,
                'total_items' => $totalItems
            ]
        ]);
    }

    private function add() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $productId = $data['product_id'] ?? null;
        $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
        $color = $data['color'] ?? 'Đen Nhám';
        $storage = $data['storage'] ?? '128 GB';

        if (!$productId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Product ID is required.']);
            return;
        }

        if ($quantity <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Số lượng sản phẩm phải lớn hơn 0.']);
            return;
        }

        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Generate a unique key for the cart item
        $cartKey = $productId . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $color . '_' . $storage);

        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'key' => $cartKey,
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'color' => $color,
                'storage' => $storage
            ];
        }

        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công.',
            'cart_count' => $this->getCartCount()
        ]);
    }

    private function update() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $cartKey = $data['cart_key'] ?? '';
        $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;

        if (empty($cartKey)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cart key is required.']);
            return;
        }

        if ($quantity <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Số lượng sản phẩm phải lớn hơn 0.']);
            return;
        }

        if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$cartKey])) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
            return;
        }

        $_SESSION['cart'][$cartKey]['quantity'] = $quantity;

        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật số lượng giỏ hàng thành công.',
            'cart_count' => $this->getCartCount()
        ]);
    }

    private function remove($cartKey) {
        if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$cartKey])) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng.']);
            return;
        }

        unset($_SESSION['cart'][$cartKey]);

        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'cart_count' => $this->getCartCount()
        ]);
    }

    private function clear() {
        $_SESSION['cart'] = [];
        echo json_encode([
            'success' => true,
            'message' => 'Đã làm trống giỏ hàng.',
            'cart_count' => 0
        ]);
    }

    private function getCartCount() {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        return $count;
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    }
}
?>
