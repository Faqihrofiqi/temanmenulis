<?php
// Manual Payment System (QRIS)

/**
 * Generate QRIS string for manual payment
 * Format: QRIS standard dengan informasi pembayaran
 */
function generateQRISString($orderId, $amount, $merchantName = null) {
    $merchantName = $merchantName ?: QRIS_NAME;
    
    // Generate QRIS string yang lebih sederhana dan bisa di-scan
    // Format: Menggunakan format yang lebih universal untuk e-wallet Indonesia
    // Untuk QRIS yang bisa di-scan, kita gunakan format yang lebih sederhana
    
    // Format alternatif: Menggunakan nomor rekening/QRIS + informasi pembayaran
    // Ini akan menghasilkan QR code yang berisi informasi untuk transfer manual
    $paymentInfo = array(
        'merchant' => $merchantName,
        'account' => QRIS_NUMBER,
        'amount' => $amount,
        'order_id' => $orderId,
        'bank' => defined('QRIS_BANK') ? QRIS_BANK : ''
    );
    
    // Generate string untuk QR code (format JSON sederhana yang bisa di-parse)
    $qrString = json_encode($paymentInfo);
    
    // Atau gunakan format yang lebih sederhana untuk e-wallet
    // Format: "PAY:merchant_name:account:amount:order_id"
    $qrString = sprintf(
        'PAY:%s:%s:%s:%s',
        urlencode($merchantName),
        QRIS_NUMBER,
        number_format($amount, 0, '', ''),
        $orderId
    );
    
    return $qrString;
}

/**
 * Get QRIS payment data
 */
function getQRISPayment($transaction) {
    $orderId = $transaction['transaction_details']['order_id'];
    $amount = $transaction['transaction_details']['gross_amount'];
    
    // Generate QRIS string
    $qrString = generateQRISString($orderId, $amount);
    
    return array(
        'status_code' => '200',
        'qr_string' => $qrString,
        'transaction_status' => 'pending',
        'order_id' => $orderId,
        'amount' => $amount,
        'payment_method' => 'QRIS Manual'
    );
}

/**
 * Verify payment status (for manual confirmation)
 */
function verifyPayment($orderId) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        $statusMap = [
            'paid' => 'settlement',
            'pending' => 'pending',
            'cancelled' => 'cancel'
        ];
        
        return array(
            'transaction_status' => isset($statusMap[$order['status']]) ? $statusMap[$order['status']] : 'pending',
            'order_id' => $orderId,
            'status_code' => $order['status'] === 'paid' ? '200' : '201'
        );
    }
    
    return null;
}
?>

