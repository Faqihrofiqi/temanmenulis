<?php
// Manual Payment System (QRIS)

/**
 * Generate QRIS string for manual payment
 * Format: QRIS standard dengan informasi pembayaran
 */
function generateQRISString($orderId, $amount, $merchantName = null) {
    $merchantName = $merchantName ?: QRIS_NAME;
    $amountFormatted = number_format($amount, 0, '', '');
    
    // Generate QRIS string (simplified version)
    // Format: 000201010212 + merchant info + amount + checksum
    // Note: Ini adalah format sederhana, untuk production gunakan library QRIS yang proper
    
    $qrString = sprintf(
        '00020101021226650009%s0118936009%s0215%s030393274041404%s5802ID5913%s6014Jakarta Pusat61051234563040140',
        substr($merchantName, 0, 9),
        QRIS_NUMBER,
        $merchantName,
        $amountFormatted,
        substr($merchantName, 0, 13)
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

