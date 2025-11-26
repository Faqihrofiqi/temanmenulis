<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: ' . SITE_URL . '/services.php');
    exit;
}

$serviceId = $_GET['id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header('Location: ' . SITE_URL . '/services.php');
    exit;
}

$pageTitle = 'Pesan ' . $service['name'];
include 'includes/header.php';

$formattedPrice = number_format($service['price'], 0, ',', '.');
?>

<section class="order-page">
    <div class="container">
        <div class="order-wrapper">
            <div class="order-form-section">
                <div class="order-header">
                    <h1>Pesan Layanan</h1>
                    <p>Isi formulir di bawah ini untuk memesan layanan</p>
                </div>
                
                <form id="orderForm" class="order-form" method="POST" action="<?php echo SITE_URL; ?>/payment.php">
                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                    
                    <div class="form-group">
                        <label for="customer_name">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" required 
                               placeholder="Masukkan nama lengkap Anda">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_email">Email <span class="required">*</span></label>
                            <input type="email" id="customer_email" name="customer_email" required 
                                   placeholder="nama@email.com">
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Nomor WhatsApp <span class="required">*</span></label>
                            <input type="tel" id="customer_phone" name="customer_phone" required 
                                   placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_details">Detail Pesanan</label>
                        <textarea id="order_details" name="order_details" rows="4" 
                                  placeholder="Jelaskan detail kebutuhan Anda (opsional)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="exclude_bibliography" checked>
                            <span>Exclude Bibliography</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="exclude_quotes" checked>
                            <span>Exclude Quotes</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary btn-large">
                            <span>Lanjutkan ke Pembayaran</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="order-summary-section">
                <div class="order-summary-card">
                    <h3>Ringkasan Pesanan</h3>
                    <div class="summary-item">
                        <div class="summary-service">
                            <div class="summary-service-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="summary-service-info">
                                <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                                <p><?php echo htmlspecialchars($service['description']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>Rp <?php echo $formattedPrice; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Pembayaran</span>
                            <span>Rp <?php echo $formattedPrice; ?></span>
                        </div>
                    </div>
                    
                    <div class="payment-methods">
                        <p class="payment-methods-title">Metode Pembayaran:</p>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa" title="Visa"></i>
                            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                            <i class="fab fa-cc-paypal" title="PayPal"></i>
                            <i class="fas fa-university" title="Bank Transfer"></i>
                            <i class="fas fa-mobile-alt" title="E-Wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

