<?php
require_once 'config.php';
$pageTitle = 'FAQ';
include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <p>Pertanyaan yang sering diajukan</p>
    </div>
</section>

<section style="padding: 3rem 0;">
    <div class="container" style="max-width: 800px;">
        <div style="background: var(--bg-white); border-radius: var(--radius-xl); padding: 3rem; box-shadow: var(--shadow-md);">
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-dark);">Bagaimana cara memesan layanan?</h3>
                <p style="color: var(--text-gray); line-height: 1.8;">Pilih paket jasa yang diinginkan, isi formulir pemesanan, lalu lakukan pembayaran melalui payment gateway yang tersedia.</p>
            </div>
            <div style="margin-bottom: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-dark);">Metode pembayaran apa saja yang tersedia?</h3>
                <p style="color: var(--text-gray); line-height: 1.8;">Kami menerima pembayaran melalui kartu kredit/debit, transfer bank, e-wallet (OVO, GoPay, DANA), dan berbagai metode pembayaran lainnya melalui Midtrans.</p>
            </div>
            <div style="margin-bottom: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-dark);">Apakah ada garansi revisi?</h3>
                <p style="color: var(--text-gray); line-height: 1.8;">Ya, kami memberikan garansi revisi gratis hingga hasil sesuai dengan keinginan Anda (maksimal 3x revisi).</p>
            </div>
            <div style="margin-bottom: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-dark);">Berapa lama waktu pengerjaan?</h3>
                <p style="color: var(--text-gray); line-height: 1.8;">Waktu pengerjaan bervariasi tergantung jenis layanan. Rata-rata 3-7 hari kerja. Detail akan dikonfirmasi setelah pembayaran diterima.</p>
            </div>
            <div style="padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--text-dark);">Bagaimana cara cek status pesanan?</h3>
                <p style="color: var(--text-gray); line-height: 1.8;">Gunakan fitur "Cek Pesanan" di menu atas, lalu masukkan Order ID yang Anda terima setelah melakukan pemesanan.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

