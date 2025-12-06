<?php
require_once 'config.php';
$pageTitle = 'Paket Jasa';
include 'includes/header.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$conn = getDBConnection();

if ($category) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE category = ? ORDER BY price ASC");
    $stmt->execute([$category]);
} else {
    $stmt = $conn->query("SELECT * FROM services ORDER BY category, price ASC");
}

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categories = ['skripsi' => 'Skripsi', 'thesis' => 'Thesis', 'disertasi' => 'Disertasi'];
?>

<section class="page-header">
    <div class="container">
        <h1>Paket Jasa Kami</h1>
        <p>Pilih layanan yang sesuai dengan kebutuhan Anda</p>
    </div>
</section>

<section class="services-page">
    <div class="container">
        <div class="category-filter">
            <a href="<?php echo SITE_URL; ?>/services.php" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">
                Semua Kategori
            </a>
            <?php foreach ($categories as $catKey => $catName): ?>
                <a href="<?php echo SITE_URL; ?>/services.php?category=<?php echo $catKey; ?>" 
                   class="filter-btn <?php echo $category == $catKey ? 'active' : ''; ?>">
                    <?php echo $catName; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="services-list">
            <?php if (empty($services)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Tidak ada layanan ditemukan</h3>
                    <p>Coba pilih kategori lain atau hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            <?php else: ?>
                <?php foreach ($services as $service): 
                    $formattedPrice = number_format($service['price'], 0, ',', '.');
                    $iconMap = [
                        'skripsi' => 'graduation-cap',
                        'thesis' => 'book',
                        'disertasi' => 'university'
                    ];
                    $icon = $iconMap[$service['category']] ?? 'file-alt';
                ?>
                    <div class="service-item-card">
                        <div class="service-item-icon">
                            <i class="fas fa-<?php echo $icon; ?>"></i>
                        </div>
                        <div class="service-item-content">
                            <div class="service-item-header">
                                <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                                <span class="service-category"><?php echo $categories[$service['category']] ?? ucfirst($service['category']); ?></span>
                            </div>
                            <p class="service-item-description"><?php echo htmlspecialchars($service['description']); ?></p>
                            <div class="service-item-footer">
                                <div class="service-item-price">
                                    <span class="price-label">Mulai dari</span>
                                    <span class="price-amount">Rp <?php echo $formattedPrice; ?></span>
                                </div>
                                <a href="<?php echo SITE_URL; ?>/order.php?id=<?php echo $service['id']; ?>" class="btn-primary">
                                    Pilih Paket
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

