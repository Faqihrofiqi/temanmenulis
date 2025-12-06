    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-pen-fancy"></i>
                        <span><?php echo SITE_NAME; ?></span>
                    </div>
                    <p>Platform terpercaya untuk berbagai kebutuhan jasa profesional Anda.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/services.php?category=servis">Jasa Servis</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php?category=desain">Desain Grafis</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php?category=jurnal">Tulis Jurnal</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php?category=editing">Editing & Proofreading</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Perusahaan</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">Tentang Kami</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php">Kontak</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog.php">Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/tutorial.php">Tutorial</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/faq.php">FAQ</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/check-order.php">Cek Pesanan</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/terms.php">Syarat & Ketentuan</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/privacy.php">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo asset_url('assets/js/main.js'); ?>"></script>
    <?php if (isset($additionalScripts)) echo $additionalScripts; ?>
</body>
</html>

