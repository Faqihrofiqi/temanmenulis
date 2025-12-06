# TemanMenulis - Website Jasa Profesional

Website bisnis untuk berbagai jasa profesional seperti jasa servis, desain grafis, penulisan jurnal, dan layanan lainnya dengan sistem pembayaran QRIS manual.

## Fitur

- ✅ **UI/UX Modern & Responsive** - Desain menarik dengan tema modern (teal/orange)
- ✅ **Paket Jasa Lengkap** - Menampilkan berbagai paket jasa dengan kategori
- ✅ **Sistem Pembayaran QRIS** - Pembayaran manual dengan QRIS yang bisa di-scan langsung
- ✅ **Sistem Pemesanan** - Form pemesanan yang mudah digunakan
- ✅ **Cek Status Pesanan** - Fitur untuk mengecek status pesanan
- ✅ **Dark Mode** - Toggle dark/light mode
- ✅ **Fully Responsive** - Tampilan optimal di semua perangkat (mobile, tablet, desktop)

## Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Payment System**: Manual QRIS
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Inter (Google Fonts)
- **QR Code**: QRCode.js library

## Instalasi

### 1. Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi / MariaDB 10.3 atau lebih tinggi
- Web Server (Apache/Nginx)
- Extension PHP: PDO, cURL, JSON

### 2. Clone atau Download Project

```bash
git clone [repository-url]
cd temanmenulis
```

### 3. Setup Database

1. Buat database baru:
```sql
CREATE DATABASE temanmenulis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Edit file `config.php` dan sesuaikan konfigurasi database:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'temanmenulis');
```

3. Database akan otomatis dibuat tabel-tabelnya saat pertama kali diakses.

### 4. Konfigurasi Pembayaran

Edit file `config.php` dan sesuaikan informasi pembayaran:
```php
define('QRIS_NUMBER', '081234567890'); // Nomor rekening/QRIS Anda
define('QRIS_NAME', 'Nama Anda'); // Nama penerima pembayaran
define('QRIS_BANK', 'Bank BCA'); // Nama bank (opsional)
define('PAYMENT_MANUAL_CONFIRMATION', true); // Konfirmasi manual oleh admin
```

### 5. Konfigurasi URL

Edit file `config.php` dan sesuaikan URL website:
```php
define('SITE_URL', 'http://localhost/temanmenulis');
```

### 6. Set Permissions (Linux/Mac)

```bash
chmod -R 755 .
chmod -R 777 uploads/ # jika ada folder uploads
```

## Struktur Folder

```
temanmenulis/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── midtrans.php
├── config.php
├── index.php
├── services.php
├── order.php
├── payment.php
├── payment-success.php
├── payment-failed.php
├── payment-pending.php
├── check-order.php
├── .htaccess
└── README.md
```

## Halaman Website

- **Home** (`index.php`) - Halaman utama dengan hero section, preview layanan, fitur, dan testimoni
- **Paket Jasa** (`services.php`) - Daftar semua paket jasa dengan filter kategori
- **Pemesanan** (`order.php`) - Form untuk memesan layanan
- **Pembayaran** (`payment.php`) - Halaman pembayaran dengan Midtrans
- **Cek Pesanan** (`check-order.php`) - Cek status pesanan berdasarkan Order ID
- **Payment Success** (`payment-success.php`) - Halaman sukses pembayaran
- **Payment Failed** (`payment-failed.php`) - Halaman gagal pembayaran
- **Payment Pending** (`payment-pending.php`) - Halaman pending pembayaran

## Paket Jasa Default

Website sudah dilengkapi dengan beberapa paket jasa default:

1. **Jasa Servis Laptop/PC** - Rp 150.000
2. **Desain Grafis** - Rp 200.000
3. **Tulis Jurnal Ilmiah** - Rp 500.000
4. **Jasa Editing & Proofreading** - Rp 100.000
5. **Desain Website** - Rp 3.000.000
6. **Jasa Translate** - Rp 150.000

Anda dapat menambahkan atau mengedit paket jasa melalui database atau membuat halaman admin.

## Deployment

⚠️ **Penting**: Project ini menggunakan PHP dan MySQL, jadi **tidak bisa di-deploy ke GitHub Pages**. GitHub Pages hanya mendukung static HTML.

Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk panduan lengkap tentang:
- Alternatif hosting gratis dan berbayar
- Cara deploy ke hosting PHP
- Setup database di hosting
- Troubleshooting

### Hosting Gratis yang Direkomendasikan:
- **000webhost**: https://www.000webhost.com/
- **InfinityFree**: https://www.infinityfree.net/

### Hosting Berbayar (Recommended untuk Production):
- **Hostinger**: Mulai dari Rp 9.000/bulan
- **Niagahoster**: Mulai dari Rp 12.000/bulan

## Konfigurasi Pembayaran

### Manual QRIS System
- Gunakan untuk testing
- Set `MIDTRANS_IS_PRODUCTION = false`
- Gunakan Server Key dan Client Key dari dashboard sandbox

### Production Mode
- Set `MIDTRANS_IS_PRODUCTION = true`
- Gunakan Server Key dan Client Key dari dashboard production
- Pastikan website sudah menggunakan HTTPS

## Customization

### Mengubah Warna Tema

Edit file `assets/css/style.css` dan ubah variabel CSS di bagian `:root`:

```css
:root {
    --primary-color: #6366f1;
    --secondary-color: #8b5cf6;
    /* ... */
}
```

### Menambahkan Paket Jasa Baru

1. Masuk ke database
2. Insert ke tabel `services`:
```sql
INSERT INTO services (name, description, price, category, image) 
VALUES ('Nama Jasa', 'Deskripsi jasa', 250000, 'kategori', 'image.jpg');
```

## Troubleshooting

### Database Connection Error
- Pastikan MySQL/MariaDB berjalan
- Periksa kredensial database di `config.php`
- Pastikan database sudah dibuat

### Midtrans Error
- Pastikan Server Key dan Client Key benar
- Periksa apakah cURL extension aktif
- Untuk production, pastikan website menggunakan HTTPS

### Page Not Found
- Pastikan mod_rewrite aktif (Apache)
- Periksa file `.htaccess`
- Periksa konfigurasi `SITE_URL` di `config.php`

## Support

Untuk bantuan lebih lanjut, silakan hubungi:
- Email: support@temanmenulis.com
- WhatsApp: +62 xxx-xxxx-xxxx

## License

Proyek ini dibuat untuk keperluan bisnis TemanMenulis.

## Changelog

### Version 1.0.0
- Initial release
- Fitur dasar website jasa
- Integrasi Midtrans payment gateway
- Dark mode toggle
- Responsive design

