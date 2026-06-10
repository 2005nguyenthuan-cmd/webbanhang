<?php
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/controllers/api/ApiAccountController.php';

class ApiOrderController {
    private $db;
    private $orderModel;
    private $authController;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
        $this->authController = new ApiAccountController();
    }

    public function handleRequest($id, $subAction) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($id) {
            if ($subAction === 'cancel') {
                if ($method === 'PUT' || $method === 'POST') $this->cancel($id);
                else $this->methodNotAllowed();
            } elseif ($subAction === 'status') {
                if ($method === 'PUT' || $method === 'POST') $this->updateStatus($id);
                else $this->methodNotAllowed();
            } elseif ($subAction === 'payment') {
                if ($method === 'POST') $this->processPayment($id);
                else $this->methodNotAllowed();
            } else {
                if ($method === 'GET') $this->show($id);
                else $this->methodNotAllowed();
            }
        } else {
            if ($method === 'GET') $this->index();
            elseif ($method === 'POST') $this->store();
            else $this->methodNotAllowed();
        }
    }

    // GET /api/orders - List user's orders (or all if admin)
    private function index() {
        $authorizedUser = $this->authController->getAuthorizedUser();
        
        if ($authorizedUser['role'] === 'admin') {
            // Admin can see all orders
            $orders = $this->orderModel->getOrders();
        } else {
            // Normal user can only see their own orders
            $orders = $this->orderModel->getOrders($authorizedUser['sub']);
        }

        echo json_encode([
            'success' => true,
            'data' => $orders
        ]);
    }

    // GET /api/orders/{id} - View specific order details
    private function show($id) {
        $authorizedUser = $this->authController->getAuthorizedUser();
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
            return;
        }

        // Verify ownership (or admin status)
        if ($authorizedUser['role'] !== 'admin' && (int)$order->user_id !== (int)$authorizedUser['sub']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden. Bạn không có quyền xem đơn hàng này.']);
            return;
        }

        $items = $this->orderModel->getOrderDetails($id);

        echo json_encode([
            'success' => true,
            'data' => [
                'order' => $order,
                'items' => $items
            ]
        ]);
    }

    // POST /api/orders - Create order from cart
    private function store() {
        $authorizedUser = $this->authController->getAuthorizedUser();

        // Retrieve cart
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không cho phép đặt hàng vì giỏ hàng đang trống.']);
            return;
        }

        // Decode checkout input
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $name = $data['name'] ?? '';
        $phone = $data['phone'] ?? '';
        $address = $data['address'] ?? '';
        $paymentMethod = $data['payment_method'] ?? 'COD';

        if (empty($name) || empty($phone) || empty($address)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Họ tên, số điện thoại và địa chỉ giao hàng không được để trống.']);
            return;
        }

        // Create order
        $orderId = $this->orderModel->createOrder($authorizedUser['sub'], $name, $phone, $address, $cart);

        if ($orderId) {
            // Update payment method & initial payment status
            $this->orderModel->updatePaymentStatus($orderId, 'Chưa thanh toán', $paymentMethod);

            // Empty cart
            $_SESSION['cart'] = [];

            echo json_encode([
                'success' => true,
                'message' => 'Đặt hàng thành công.',
                'order_id' => $orderId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi tạo đơn hàng.']);
        }
    }

    // PUT /api/orders/{id}/cancel - Cancel order
    private function cancel($id) {
        $authorizedUser = $this->authController->getAuthorizedUser();
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
            return;
        }

        // Verify ownership (or admin status)
        if ($authorizedUser['role'] !== 'admin' && (int)$order->user_id !== (int)$authorizedUser['sub']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden. Bạn không có quyền hủy đơn hàng này.']);
            return;
        }

        // User can only cancel order if it is in pending state ("Đang xử lý")
        if ($authorizedUser['role'] !== 'admin' && $order->status !== 'Đang xử lý') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng đã được xử lý hoặc giao hàng.']);
            return;
        }

        $success = $this->orderModel->cancelOrder($id);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được hủy thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi hủy đơn hàng.']);
        }
    }

    // PUT /api/orders/{id}/status - Update status (Admin only)
    private function updateStatus($id) {
        $this->authController->getAuthorizedUser('admin');

        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $status = $data['status'] ?? '';
        if (empty($status)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Status is required.']);
            return;
        }

        $success = $this->orderModel->updateOrderStatus($id, $status);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật trạng thái.']);
        }
    }

    // POST /api/orders/{id}/payment - Create payment for order
    private function processPayment($id) {
        $authorizedUser = $this->authController->getAuthorizedUser();
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
            return;
        }

        // Verify ownership (or admin status)
        if ($authorizedUser['role'] !== 'admin' && (int)$order->user_id !== (int)$authorizedUser['sub']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden. Bạn không có quyền thanh toán đơn hàng này.']);
            return;
        }

        // Check if already paid
        if ($order->payment_status === 'Đã thanh toán') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Đơn hàng này đã được thanh toán trước đó, không thể thanh toán lại.']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $paymentMethod = $data['payment_method'] ?? 'COD';
        
        // Simulating bank transfer or wallet approvals
        $paymentStatus = 'Đã thanh toán'; 
        if ($paymentMethod === 'COD') {
            $paymentStatus = 'Chưa thanh toán (COD - Thanh toán khi nhận hàng)';
        }

        $success = $this->orderModel->updatePaymentStatus($id, $paymentStatus, $paymentMethod);

        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Xử lý thanh toán thành công (Simulated).',
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi cập nhật thanh toán.']);
        }
    }

    private function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    }
}
?>
