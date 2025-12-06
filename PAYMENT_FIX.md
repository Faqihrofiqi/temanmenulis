# Fix Masalah Pembayaran

## Masalah yang Sudah Diperbaiki

### 1. QRIS String Generation
- ✅ Format QRIS diperbaiki menjadi lebih sederhana dan universal
- ✅ Menggunakan format yang bisa di-scan oleh e-wallet Indonesia
- ✅ Fallback jika QR code tidak bisa di-generate

### 2. Path Assets
- ✅ Menggunakan `asset_url()` untuk semua path di payment.php
- ✅ Path check-payment-status.php sudah diperbaiki
- ✅ Path payment-success/failed sudah diperbaiki

### 3. QR Code Library
- ✅ Menambahkan error handling untuk QR code generation
- ✅ Fallback UI jika library tidak ter-load
- ✅ Loading indicator saat QR code sedang di-generate

## Cara Test Pembayaran

### 1. Test Flow Pembayaran

1. **Buka halaman services**: `https://temanmenulis.rf.gd/services.php`
2. **Pilih paket jasa** → Klik "Pilih Paket"
3. **Isi form order**:
   - Nama lengkap
   - Email
   - Nomor WhatsApp
   - Detail pesanan (opsional)
4. **Submit form** → Akan redirect ke halaman payment
5. **Cek halaman payment**:
   - QR Code harus muncul
   - Informasi rekening harus terlihat
   - Status "Menunggu Pembayaran"

### 2. Test QR Code

**Cek di Browser Console (F12)**:
```javascript
// Cek apakah QRCode library ter-load
console.log(typeof QRCode); // Harus: "function"

// Cek apakah qrisString ada
console.log(qrisString); // Harus menampilkan string
```

**Jika QR Code tidak muncul**:
- Cek Network tab → Cari `qrcode.min.js`
- Status harus **200 OK**
- Jika 404, berarti CDN tidak bisa diakses

### 3. Test Payment Status Check

**Manual test**:
```
https://temanmenulis.rf.gd/check-payment-status.php?order_id=TM-20241127-XXXXXXXX
```

Harus return JSON:
```json
{
    "status": "pending",
    "order_id": "TM-20241127-XXXXXXXX",
    "message": "Menunggu pembayaran"
}
```

## Troubleshooting

### QR Code Tidak Muncul

**Penyebab**:
1. QRCode.js library tidak ter-load dari CDN
2. JavaScript error di console
3. qrisString kosong

**Solusi**:
1. **Cek Network tab** → Pastikan `qrcode.min.js` ter-load
2. **Gunakan CDN alternatif**:
   ```html
   <!-- Ganti di payment.php -->
   <script src="https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js"></script>
   ```
3. **Cek console error** → F12 → Console tab

### Payment Status Tidak Update

**Penyebab**:
1. Database tidak ter-update
2. Path check-payment-status.php salah
3. CORS error

**Solusi**:
1. **Cek database**:
   ```sql
   SELECT * FROM orders WHERE order_id = 'TM-20241127-XXXXXXXX';
   ```
2. **Update status manual** (untuk testing):
   ```sql
   UPDATE orders SET status = 'paid' WHERE order_id = 'TM-20241127-XXXXXXXX';
   ```
3. **Cek path** → Pastikan menggunakan `asset_url()`

### QRIS Tidak Bisa Di-Scan

**Catatan Penting**:
- QRIS yang di-generate adalah format sederhana untuk testing
- Untuk production, gunakan QRIS yang valid dari bank/e-wallet provider
- Atau gunakan nomor rekening untuk transfer manual

**Solusi**:
1. **Gunakan transfer manual** → Informasi rekening sudah ditampilkan
2. **Admin update status** → Setelah pembayaran diterima
3. **Untuk production** → Integrasikan dengan QRIS provider yang valid

## Format QRIS yang Digunakan

Format saat ini:
```
PAY:merchant_name:account_number:amount:order_id
```

Contoh:
```
PAY:TemanMenulis:081234567890:150000:TM-20241127-ABC12345
```

**Catatan**: Format ini untuk testing. Untuk production, gunakan QRIS yang valid dari provider.

## Update Status Pembayaran Manual

### Via Database (cPanel phpMyAdmin):

```sql
-- Update status menjadi paid
UPDATE orders SET status = 'paid' WHERE order_id = 'TM-20241127-XXXXXXXX';

-- Cek status
SELECT order_id, status, amount FROM orders WHERE order_id = 'TM-20241127-XXXXXXXX';
```

### Via File (Buat halaman admin):

Buat file `admin/update-payment.php`:
```php
<?php
require_once '../config.php';

// Simple auth (ganti dengan auth yang proper)
$admin_password = 'your_admin_password_here';

if ($_POST['password'] !== $admin_password) {
    die('Unauthorized');
}

$orderId = $_POST['order_id'];
$status = $_POST['status']; // 'paid', 'pending', 'cancelled'

$conn = getDBConnection();
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
$stmt->execute([$status, $orderId]);

echo json_encode(['success' => true, 'message' => 'Status updated']);
?>
```

## Checklist

- [ ] QR Code muncul di halaman payment
- [ ] Informasi rekening terlihat jelas
- [ ] Tombol "Cek Status Pembayaran" berfungsi
- [ ] check-payment-status.php return JSON yang benar
- [ ] Database order tersimpan dengan benar
- [ ] Status bisa di-update manual

---

**Catatan**: Untuk production, sangat disarankan menggunakan payment gateway yang valid atau sistem QRIS yang proper dari bank/e-wallet provider.

