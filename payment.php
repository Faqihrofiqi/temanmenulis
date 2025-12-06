<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . SITE_URL . '/services.php');
    exit;
}

$serviceId = $_POST['service_id'];
$customerName = $_POST['customer_name'];
$customerEmail = $_POST['customer_email'];
$customerPhone = $_POST['customer_phone'];
$orderDetails = isset($_POST['order_details']) ? $_POST['order_details'] : '';

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die('Service not found');
}

// Generate unique order ID
$orderId = 'TM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));

// Insert order to database
$stmt = $conn->prepare("INSERT INTO orders (order_id, service_id, customer_name, customer_email, customer_phone, order_details, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
$stmt->execute([$orderId, $serviceId, $customerName, $customerEmail, $customerPhone, $orderDetails, $service['price']]);

// Prepare Midtrans payment
require_once 'includes/midtrans.php';

$transaction_details = array(
    'order_id' => $orderId,
    'gross_amount' => $service['price']
);

$customer_details = array(
    'first_name' => $customerName,
    'email' => $customerEmail,
    'phone' => $customerPhone
);

$item_details = array(
    array(
        'id' => $service['id'],
        'price' => $service['price'],
        'quantity' => 1,
        'name' => $service['name']
    )
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details
);

// Generate QRIS payment (manual)
$qrisResponse = getQRISPayment($transaction);

// Update order with payment token and QRIS data
$qrisString = isset($qrisResponse['qr_string']) ? $qrisResponse['qr_string'] : '';
$stmt = $conn->prepare("UPDATE orders SET payment_token = ? WHERE order_id = ?");
$stmt->execute([$qrisString, $orderId]);

$pageTitle = 'Pembayaran QRIS';
include 'includes/header.php';
?>

<section class="payment-page">
    <div class="container">
        <div class="payment-wrapper">
            <div class="payment-info">
                <div class="payment-success-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h1>Pesanan Berhasil Dibuat</h1>
                <p class="order-id">Order ID: <strong><?php echo $orderId; ?></strong></p>
                <p>Silakan scan QRIS di bawah ini untuk menyelesaikan pembayaran.</p>
                
                <div class="order-summary-box">
                    <h3>Detail Pesanan</h3>
                    <div class="summary-detail">
                        <span>Layanan:</span>
                        <span><?php echo htmlspecialchars($service['name']); ?></span>
                    </div>
                    <div class="summary-detail">
                        <span>Total:</span>
                        <span class="total-amount">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="payment-gateway">
                <h2>Scan QRIS untuk Pembayaran</h2>
                <div class="qris-container">
                    <div class="qris-code">
                        <?php if (!empty($qrisString)): ?>
                            <div id="qris-qr-code" style="min-height: 300px; display: flex; align-items: center; justify-content: center; background: white; border-radius: 0.75rem; margin-bottom: 1rem;">
                                <div style="text-align: center; padding: 1rem;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #0ea5e9;"></i>
                                    <p style="margin-top: 1rem; color: #64748b;">Memuat QR Code...</p>
                                </div>
                            </div>
                            <p class="qris-instruction">Scan QR Code di atas atau gunakan informasi rekening di bawah untuk transfer manual</p>
                            
                            <div class="payment-info-box">
                                <h4>Informasi Pembayaran</h4>
                                <div class="payment-detail">
                                    <span class="detail-label">Nomor Rekening/QRIS:</span>
                                    <span class="detail-value"><?php echo QRIS_NUMBER; ?></span>
                                </div>
                                <div class="payment-detail">
                                    <span class="detail-label">Nama Penerima:</span>
                                    <span class="detail-value"><?php echo QRIS_NAME; ?></span>
                                </div>
                                <?php if (defined('QRIS_BANK') && QRIS_BANK): ?>
                                <div class="payment-detail">
                                    <span class="detail-label">Bank:</span>
                                    <span class="detail-value"><?php echo QRIS_BANK; ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="payment-detail total">
                                    <span class="detail-label">Jumlah Transfer:</span>
                                    <span class="detail-value">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                            
                            <div class="qris-payment-methods">
                                <span class="payment-method-badge"><i class="fas fa-mobile-alt"></i> E-Wallet</span>
                                <span class="payment-method-badge"><i class="fas fa-university"></i> Mobile Banking</span>
                                <span class="payment-method-badge"><i class="fas fa-qrcode"></i> QRIS</span>
                            </div>
                        <?php else: ?>
                            <div class="qris-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Gagal memuat QRIS. Silakan coba lagi.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="payment-status">
                        <div class="status-indicator pending">
                            <i class="fas fa-clock"></i>
                            <span>Menunggu Pembayaran</span>
                        </div>
                        <p class="status-note">
                            <?php if (PAYMENT_MANUAL_CONFIRMATION): ?>
                                Setelah melakukan pembayaran, silakan konfirmasi ke admin atau tunggu konfirmasi manual. 
                                Anda juga dapat mengecek status pembayaran di bawah ini.
                            <?php else: ?>
                                Setelah melakukan pembayaran, status akan diperbarui otomatis.
                            <?php endif; ?>
                        </p>
                        <button class="btn-primary" onclick="checkPaymentStatus()" style="margin-top: 1rem;">
                            <i class="fas fa-sync-alt"></i> Cek Status Pembayaran
                        </button>
                        <a href="<?php echo asset_url('check-order.php'); ?>" class="btn-outline" style="margin-top: 0.75rem; display: inline-block;">
                            <i class="fas fa-search"></i> Cek Pesanan Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    var orderId = '<?php echo $orderId; ?>';
    var qrisString = '<?php echo addslashes($qrisString); ?>';
    
    // Generate QR Code
    if (qrisString && typeof QRCode !== 'undefined') {
        var qrElement = document.getElementById('qris-qr-code');
        if (qrElement) {
            QRCode.toCanvas(qrElement, qrisString, {
                width: 300,
                margin: 2,
                color: {
                    dark: '#0f172a',
                    light: '#ffffff'
                }
            }, function (error) {
                if (error) {
                    console.error('QR Code generation error:', error);
                    qrElement.innerHTML = '<div style="padding: 2rem; text-align: center;"><i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i><p style="color: #92400e;">Gagal membuat QR Code. Silakan gunakan informasi rekening di bawah ini untuk transfer manual.</p></div>';
                }
            });
        }
    } else {
        // Fallback jika QRCode library tidak ter-load
        var qrElement = document.getElementById('qris-qr-code');
        if (qrElement) {
            qrElement.innerHTML = '<div style="padding: 2rem; text-align: center;"><i class="fas fa-info-circle" style="font-size: 3rem; color: #0ea5e9; margin-bottom: 1rem;"></i><p>Gunakan informasi rekening di bawah ini untuk transfer manual.</p></div>';
        }
    }
    
    // Auto check payment status every 10 seconds (less frequent for manual system)
    var statusCheckInterval = setInterval(checkPaymentStatus, 10000);
    var checkCount = 0;
    var maxChecks = 90; // 15 minutes (90 * 10 seconds)
    
    function checkPaymentStatus() {
        checkCount++;
        if (checkCount > maxChecks) {
            clearInterval(statusCheckInterval);
            return;
        }
        
        fetch('<?php echo asset_url('check-payment-status.php'); ?>?order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'settlement' || data.status === 'capture') {
                    clearInterval(statusCheckInterval);
                    clearInterval(statusCheckInterval);
                    window.location.href = '<?php echo asset_url('payment-success.php'); ?>?order_id=' + orderId;
                } else if (data.status === 'expire' || data.status === 'cancel') {
                    clearInterval(statusCheckInterval);
                    window.location.href = '<?php echo asset_url('payment-failed.php'); ?>?order_id=' + orderId;
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }
    
    // Manual check button
    window.checkPaymentStatus = checkPaymentStatus;
</script>

<?php include 'includes/footer.php'; ?>

