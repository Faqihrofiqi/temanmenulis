<?php
require_once 'config.php';
$pageTitle = 'Cek Pesanan';
include 'includes/header.php';

$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$order = null;

if ($orderId) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT o.*, s.name as service_name FROM orders o LEFT JOIN services s ON o.service_id = s.id WHERE o.order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<section class="check-order-page">
    <div class="container">
        <div class="check-order-wrapper">
            <div class="check-order-header">
                <h1>Cek Status Pesanan</h1>
                <p>Masukkan Order ID untuk melihat status pesanan Anda</p>
            </div>
            
            <form method="GET" class="check-order-form">
                <div class="form-group">
                    <label for="order_id">Order ID</label>
                    <input type="text" id="order_id" name="order_id" 
                           value="<?php echo htmlspecialchars($orderId); ?>"
                           placeholder="TM-20240101-XXXXXXXX" required>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i>
                    Cari Pesanan
                </button>
            </form>
            
            <?php if ($order): ?>
                <div class="order-status-card">
                    <div class="order-status-header">
                        <h2>Detail Pesanan</h2>
                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                            <?php 
                            $statusLabels = [
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'processing' => 'Sedang Diproses',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                            echo $statusLabels[$order['status']] ?? ucfirst($order['status']);
                            ?>
                        </span>
                    </div>
                    
                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Order ID:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['order_id']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Layanan:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['service_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Nama:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['customer_email']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">No. WhatsApp:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total:</span>
                            <span class="detail-value total">Rp <?php echo number_format($order['amount'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Tanggal Pesanan:</span>
                            <span class="detail-value"><?php echo date('d F Y H:i', strtotime($order['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <?php if ($order['status'] == 'pending'): ?>
                        <div class="order-actions">
                            <a href="<?php echo SITE_URL; ?>/payment.php?order_id=<?php echo $order['order_id']; ?>" class="btn-primary">
                                Lanjutkan Pembayaran
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($orderId): ?>
                <div class="order-not-found">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Pesanan tidak ditemukan</h3>
                    <p>Pastikan Order ID yang Anda masukkan benar.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

