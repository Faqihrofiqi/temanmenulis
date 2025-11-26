<?php
require_once 'config.php';

$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if ($orderId) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE order_id = ?");
    $stmt->execute([$orderId]);
}

$pageTitle = 'Pembayaran Berhasil';
include 'includes/header.php';
?>

<section class="payment-result-page">
    <div class="container">
        <div class="payment-result-card success">
            <div class="result-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Pembayaran Berhasil!</h1>
            <p>Terima kasih telah melakukan pembayaran. Pesanan Anda sedang diproses.</p>
            <?php if ($orderId): ?>
                <p class="order-id">Order ID: <strong><?php echo htmlspecialchars($orderId); ?></strong></p>
            <?php endif; ?>
            <div class="result-actions">
                <a href="<?php echo SITE_URL; ?>/check-order.php?order_id=<?php echo $orderId; ?>" class="btn-primary">
                    Lihat Detail Pesanan
                </a>
                <a href="<?php echo SITE_URL; ?>/index.php" class="btn-outline">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

