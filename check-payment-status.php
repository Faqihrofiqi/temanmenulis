<?php
require_once 'config.php';
require_once 'includes/midtrans.php';

header('Content-Type: application/json');

if (!isset($_GET['order_id'])) {
    echo json_encode(['error' => 'Order ID required']);
    exit;
}

$orderId = $_GET['order_id'];

// Check payment status from database (manual system)
$paymentStatus = verifyPayment($orderId);

if ($paymentStatus) {
    $status = $paymentStatus['transaction_status'];
    
    echo json_encode([
        'status' => $status,
        'order_id' => $orderId,
        'message' => $status === 'settlement' ? 'Pembayaran berhasil' : 'Menunggu pembayaran'
    ]);
} else {
    echo json_encode([
        'status' => 'pending',
        'order_id' => $orderId,
        'message' => 'Menunggu pembayaran'
    ]);
}
?>

