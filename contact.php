<?php
require_once 'config.php';
$pageTitle = 'Kontak';
include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Hubungi Kami</h1>
        <p>Kami siap membantu Anda kapan saja</p>
    </div>
</section>

<section class="contact-page" style="padding: 3rem 0;">
    <div class="container">
        <div class="contact-wrapper" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 1000px; margin: 0 auto;">
            <div class="contact-info">
                <h2 style="font-size: 2rem; margin-bottom: 1.5rem; color: var(--text-dark);">Informasi Kontak</h2>
                <div class="contact-item" style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-envelope" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                        <h3 style="font-size: 1.25rem; color: var(--text-dark);">Email</h3>
                    </div>
                    <p style="color: var(--text-gray); margin-left: 3rem;">info@temanmenulis.com</p>
                </div>
                <div class="contact-item" style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <i class="fab fa-whatsapp" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                        <h3 style="font-size: 1.25rem; color: var(--text-dark);">WhatsApp</h3>
                    </div>
                    <p style="color: var(--text-gray); margin-left: 3rem;">+62 812-3456-7890</p>
                </div>
                <div class="contact-item" style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-clock" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                        <h3 style="font-size: 1.25rem; color: var(--text-dark);">Jam Operasional</h3>
                    </div>
                    <p style="color: var(--text-gray); margin-left: 3rem;">Senin - Jumat: 09:00 - 18:00 WIB<br>Sabtu: 09:00 - 15:00 WIB</p>
                </div>
            </div>
            <div class="contact-form-section" style="background: var(--bg-white); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md);">
                <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: var(--text-dark);">Kirim Pesan</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label for="contact_name">Nama</label>
                        <input type="text" id="contact_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_email">Email</label>
                        <input type="email" id="contact_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_subject">Subjek</label>
                        <input type="text" id="contact_subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_message">Pesan</label>
                        <textarea id="contact_message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">
                        <span>Kirim Pesan</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

