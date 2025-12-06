# Panduan Deployment - TemanMenulis

## ⚠️ Penting: GitHub Pages Tidak Mendukung PHP

**GitHub Pages hanya mendukung static HTML/CSS/JS**, tidak mendukung PHP, MySQL, atau server-side scripting lainnya. Project ini membutuhkan **PHP dan MySQL**, jadi tidak bisa langsung di-deploy ke GitHub Pages.

## 🚀 Alternatif Hosting untuk PHP

Berikut adalah beberapa alternatif hosting yang mendukung PHP:

### 1. **Hosting Gratis (Free Hosting)**

#### a. **000webhost** (Recommended untuk Free)
- **URL**: https://www.000webhost.com/
- **Fitur**: 
  - PHP 7.4+
  - MySQL Database
  - SSL Gratis
  - No Ads
- **Cara Deploy**:
  1. Daftar di 000webhost
  2. Buat website baru
  3. Upload semua file via File Manager atau FTP
  4. Setup database di cPanel
  5. Edit `config.php` dengan kredensial database baru

#### b. **InfinityFree**
- **URL**: https://www.infinityfree.net/
- **Fitur**: Unlimited bandwidth, MySQL, PHP 7.4+
- **Cara**: Similar dengan 000webhost

#### c. **Freehostia**
- **URL**: https://www.freehostia.com/
- **Fitur**: 250MB storage, PHP, MySQL

### 2. **Hosting Berbayar (Recommended untuk Production)**

#### a. **Hostinger** (Murah & Recommended)
- **URL**: https://www.hostinger.co.id/
- **Harga**: Mulai dari Rp 9.000/bulan
- **Fitur**: 
  - PHP 8.0+
  - MySQL Database
  - SSL Gratis
  - cPanel
  - Support Indonesia

#### b. **Niagahoster**
- **URL**: https://www.niagahoster.co.id/
- **Harga**: Mulai dari Rp 12.000/bulan
- **Fitur**: Similar dengan Hostinger

#### c. **Domainesia**
- **URL**: https://www.domainesia.com/
- **Fitur**: PHP, MySQL, cPanel

### 3. **Cloud Hosting**

#### a. **Heroku** (Free Tier Available)
- **URL**: https://www.heroku.com/
- **Cara**: 
  - Install Heroku CLI
  - Buat `Procfile` dengan: `web: vendor/bin/heroku-php-apache2`
  - Deploy via Git: `git push heroku main`
- **Note**: Perlu setup database (PostgreSQL/MySQL)

#### b. **Railway**
- **URL**: https://railway.app/
- **Fitur**: Free tier dengan limit
- **Cara**: Connect GitHub repo, auto-deploy

#### c. **Render**
- **URL**: https://render.com/
- **Fitur**: Free tier untuk static sites, paid untuk PHP

## 📝 Cara Push ke GitHub (Tanpa Deploy ke GitHub Pages)

Meskipun tidak bisa deploy ke GitHub Pages, Anda tetap bisa push code ke GitHub untuk version control:

### 1. **Setup Git Repository**

```bash
# Inisialisasi Git
git init

# Tambahkan remote repository
git remote add origin https://github.com/username/temanmenulis.git

# Tambahkan semua file
git add .

# Commit
git commit -m "Initial commit: TemanMenulis website"

# Push ke GitHub
git push -u origin main
```

### 2. **Setup .gitignore**

File `.gitignore` sudah dibuat untuk exclude:
- `config.php` (berisi password database)
- File sensitif lainnya

### 3. **Setup config.php di Server**

Setelah clone di server hosting:
```bash
# Copy template
cp config.example.php config.php

# Edit config.php dengan kredensial server
nano config.php
```

## 🔧 Langkah-Langkah Deploy ke Hosting

### Persiapan:

1. **Backup Database** (jika sudah ada data)
2. **Export Database**:
   ```bash
   mysqldump -u root -p temanmenulis > database_backup.sql
   ```

### Deploy ke Hosting:

1. **Upload Files**:
   - Via FTP (FileZilla, WinSCP)
   - Via cPanel File Manager
   - Via Git (jika hosting support)

2. **Setup Database**:
   - Buat database baru di cPanel
   - Import `database.sql` atau biarkan auto-create
   - Catat: DB_HOST, DB_USER, DB_PASS, DB_NAME

3. **Edit config.php**:
   ```php
   define('DB_HOST', 'localhost'); // atau IP database
   define('DB_USER', 'username_database');
   define('DB_PASS', 'password_database');
   define('DB_NAME', 'nama_database');
   define('SITE_URL', 'https://yourdomain.com');
   ```

4. **Set Permissions** (Linux):
   ```bash
   chmod 644 config.php
   chmod 755 .
   ```

5. **Test Website**:
   - Buka: `https://yourdomain.com`
   - Cek apakah database auto-create berjalan

## 🌐 Setup Domain (Opsional)

Jika menggunakan hosting berbayar:

1. **Point Domain** ke hosting:
   - A Record: `@` → IP hosting
   - CNAME: `www` → domain hosting

2. **Setup SSL**:
   - Gunakan Let's Encrypt (gratis)
   - Atau SSL dari hosting provider

3. **Update SITE_URL**:
   ```php
   define('SITE_URL', 'https://yourdomain.com');
   ```

## 📋 Checklist Sebelum Deploy

- [ ] File `.gitignore` sudah benar
- [ ] `config.php` tidak di-commit (ada di .gitignore)
- [ ] `config.example.php` sudah dibuat
- [ ] Database backup sudah dibuat
- [ ] Semua file sudah di-upload
- [ ] Database sudah dibuat di hosting
- [ ] `config.php` sudah di-edit dengan kredensial hosting
- [ ] SITE_URL sudah di-update
- [ ] Test website berjalan dengan baik

## 🆘 Troubleshooting

### Error: Database Connection Failed
- Periksa kredensial di `config.php`
- Pastikan database sudah dibuat
- Cek apakah hosting allow remote MySQL

### Error: 500 Internal Server Error
- Periksa error log di cPanel
- Pastikan PHP version >= 7.4
- Periksa file permissions

### Error: Page Not Found
- Periksa `.htaccess` sudah di-upload
- Pastikan mod_rewrite aktif
- Periksa SITE_URL di config.php

## 📚 Referensi

- [PHP Hosting Guide](https://www.php.net/manual/en/install.php)
- [MySQL Setup Guide](https://dev.mysql.com/doc/)
- [cPanel Documentation](https://docs.cpanel.net/)

---

**Catatan**: Untuk production, sangat disarankan menggunakan hosting berbayar yang lebih stabil dan memiliki support yang baik.

