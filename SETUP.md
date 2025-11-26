# Quick Setup Guide - TemanMenulis

## Langkah Instalasi Cepat

### 1. Setup Database
```bash
# Buat database
mysql -u root -p
CREATE DATABASE temanmenulis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Import database (opsional, atau gunakan install.php)
mysql -u root -p temanmenulis < database.sql
```

### 2. Konfigurasi
Edit file `config.php`:
- Sesuaikan kredensial database
- Set URL website Anda
- Masukkan Midtrans keys (Server Key & Client Key)

### 3. Akses Website
- Buka browser: `http://localhost/temanmenulis`
- Atau jalankan `install.php` untuk setup otomatis: `http://localhost/temanmenulis/install.php`

### 4. Setup Midtrans
1. Daftar di https://dashboard.midtrans.com/
2. Dapatkan Server Key dan Client Key
3. Masukkan ke `config.php`
4. Untuk testing, gunakan sandbox mode (set `MIDTRANS_IS_PRODUCTION = false`)

### 5. Testing Payment
Gunakan kartu test Midtrans:
- **Card Number**: 4811 1111 1111 1114
- **CVV**: 123
- **Expiry**: 12/25
- **OTP**: 112233

## Struktur File Penting

- `config.php` - Konfigurasi utama
- `index.php` - Halaman utama
- `services.php` - Daftar paket jasa
- `order.php` - Form pemesanan
- `payment.php` - Halaman pembayaran
- `check-order.php` - Cek status pesanan
- `includes/midtrans.php` - Integrasi payment gateway

## Troubleshooting

**Database Error?**
- Pastikan MySQL berjalan
- Periksa kredensial di config.php
- Pastikan database sudah dibuat

**Payment Gateway Error?**
- Periksa Server Key & Client Key
- Pastikan cURL extension aktif
- Untuk production, pastikan HTTPS aktif

**Page Not Found?**
- Periksa mod_rewrite aktif (Apache)
- Periksa .htaccess
- Periksa SITE_URL di config.php

## Next Steps

1. Customize warna tema di `assets/css/style.css`
2. Tambah/edit paket jasa melalui database
3. Setup email notification (opsional)
4. Setup webhook Midtrans untuk update status otomatis

