<?php
require_once 'config.php';

$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$pageTitle = 'Pembayaran Pending';
include 'includes/header.php';
?>

<section class="payment-result-page">
    <div class="container">
        <div class="payment-result-card pending">
            <div class="result-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h1>Menunggu Pembayaran</h1>
            <p>Pesanan Anda telah dibuat. Silakan selesaikan pembayaran untuk melanjutkan proses.</p>
            <?php if ($orderId): ?>
                <p class="order-id">Order ID: <strong><?php echo htmlspecialchars($orderId); ?></strong></p>
            <?php endif; ?>
            <div class="result-actions">
                <a href="<?php echo SITE_URL; ?>/check-order.php?order_id=<?php echo $orderId; ?>" class="btn-primary">
                    Cek Status Pesanan
                </a>
                <a href="<?php echo SITE_URL; ?>/index.php" class="btn-outline">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

