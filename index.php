<?php
require_once 'config.php';
$pageTitle = 'Home';
include 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="gradient-text">REVISI SAMPAI ACC</span>
                Pendamping Akademik Terpercaya
            </h1>
            <p class="hero-description">
                Spesialis #1 menemani kamu mengerjakan karya ilmiah yang berkualitas. 
                Layanan penulisan Skripsi, Thesis, dan Disertasi dengan garansi revisi sampai ACC.
            </p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">35.000+</div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100.000+</div>
                    <div class="stat-label">Proyek Selesai</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.9/5</div>
                    <div class="stat-label">Rating</div>
                </div>
            </div>
            <div class="hero-actions">
                <a href="<?php echo SITE_URL; ?>/services.php" class="btn-primary">
                    <span>Lihat Paket Jasa</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="btn-outline">
                    <span>Hubungi Kami</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="services-preview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Paket Jasa Populer</h2>
            <p class="section-subtitle">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
        </div>
        <div class="services-grid">
            <?php
            $conn = getDBConnection();
            $stmt = $conn->query("SELECT * FROM services ORDER BY created_at DESC LIMIT 6");
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($services as $service) {
                $formattedPrice = number_format($service['price'], 0, ',', '.');
                $iconMap = [
                    'skripsi' => 'graduation-cap',
                    'thesis' => 'book',
                    'disertasi' => 'university'
                ];
                $icon = $iconMap[$service['category']] ?? 'file-alt';
                echo '
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-' . $icon . '"></i>
                    </div>
                    <h3 class="service-name">' . htmlspecialchars($service['name']) . '</h3>
                    <p class="service-description">' . htmlspecialchars($service['description']) . '</p>
                    <div class="service-price">
                        <span class="currency">Rp</span>
                        <span class="amount">' . $formattedPrice . '</span>
                    </div>
                    <a href="' . SITE_URL . '/order.php?id=' . $service['id'] . '" class="btn-service">
                        Pilih Paket
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>';
            }
            ?>
        </div>
        <div class="text-center" style="margin-top: 3rem;">
            <a href="<?php echo SITE_URL; ?>/services.php" class="btn-outline">
                Lihat Semua Paket
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Mengapa Pilih Kami?</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Terpercaya & Aman</h3>
                <p>Data dan informasi Anda aman dengan kami. Sistem keamanan terbaik untuk melindungi privasi Anda.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Pengerjaan Cepat</h3>
                <p>Tim profesional kami siap mengerjakan proyek Anda dengan cepat tanpa mengorbankan kualitas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Kualitas Terjamin</h3>
                <p>Standar kualitas tinggi untuk setiap layanan. Garansi kepuasan atau uang kembali.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Support 24/7</h3>
                <p>Tim support kami siap membantu Anda kapan saja. Respon cepat untuk setiap pertanyaan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>Harga Terjangkau</h3>
                <p>Paket dengan harga kompetitif dan transparan. Tidak ada biaya tersembunyi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Revisi Gratis</h3>
                <p>Revisi gratis hingga sesuai dengan keinginan Anda. Kepuasan Anda adalah prioritas kami.</p>
            </div>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Apa Kata Pelanggan?</h2>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Pelayanan sangat memuaskan! Jurnal saya selesai tepat waktu dan kualitasnya sangat baik. Recommended!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">A</div>
                    <div class="author-info">
                        <div class="author-name">Ahmad Rizki</div>
                        <div class="author-role">Mahasiswa</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Desain logo yang dibuat sangat profesional dan sesuai dengan brand saya. Terima kasih!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">S</div>
                    <div class="author-info">
                        <div class="author-name">Siti Nurhaliza</div>
                        <div class="author-role">Pengusaha</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Laptop saya diperbaiki dengan cepat dan harga terjangkau. Teknisi sangat berpengalaman."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">B</div>
                    <div class="author-info">
                        <div class="author-name">Budi Santoso</div>
                        <div class="author-role">Karyawan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

