# Panduan Push ke GitHub

## ⚠️ Catatan Penting

**GitHub Pages TIDAK mendukung PHP!** Project ini membutuhkan PHP dan MySQL, jadi tidak bisa langsung di-deploy ke GitHub Pages.

GitHub hanya digunakan untuk **version control** (menyimpan code), bukan untuk hosting website.

## 📝 Langkah-Langkah Push ke GitHub

### 1. Buat Repository di GitHub

1. Login ke GitHub: https://github.com
2. Klik **"New repository"** (tombol hijau di pojok kanan)
3. Isi:
   - **Repository name**: `temanmenulis` (atau nama lain)
   - **Description**: "Website Jasa Profesional dengan PHP"
   - **Visibility**: Public atau Private (sesuai kebutuhan)
   - **JANGAN** centang "Initialize with README" (karena sudah ada file)
4. Klik **"Create repository"**

### 2. Setup Git di Local

Buka terminal/command prompt di folder project:

```bash
# Inisialisasi Git (jika belum)
git init

# Tambahkan remote repository
git remote add origin https://github.com/USERNAME/temanmenulis.git
# Ganti USERNAME dengan username GitHub Anda

# Contoh:
# git remote add origin https://github.com/johndoe/temanmenulis.git
```

### 3. Setup File yang Akan Di-Commit

File `.gitignore` sudah dibuat untuk exclude file sensitif:
- `config.php` (berisi password database) - **TIDAK akan di-commit**
- File log, cache, dll

**Pastikan `config.example.php` ada** (sudah dibuat) sebagai template.

### 4. Commit dan Push

```bash
# Cek status file
git status

# Tambahkan semua file (kecuali yang di .gitignore)
git add .

# Commit dengan pesan
git commit -m "Initial commit: TemanMenulis website"

# Push ke GitHub
git push -u origin main
# atau
git push -u origin master
```

Jika diminta login:
- Username: username GitHub Anda
- Password: **Personal Access Token** (bukan password GitHub)
  - Buat token di: https://github.com/settings/tokens
  - Pilih scope: `repo`

### 5. Verifikasi

1. Buka repository di browser: `https://github.com/USERNAME/temanmenulis`
2. Pastikan semua file sudah ter-upload
3. Pastikan `config.php` **TIDAK** ada di GitHub (karena di .gitignore)

## 🔄 Update Code ke GitHub

Setelah melakukan perubahan:

```bash
# Cek perubahan
git status

# Tambahkan file yang diubah
git add .

# Commit
git commit -m "Update: deskripsi perubahan"

# Push
git push
```

## 👥 Clone Repository (Untuk Developer Lain)

Jika ada developer lain yang ingin clone:

```bash
# Clone repository
git clone https://github.com/USERNAME/temanmenulis.git

# Masuk ke folder
cd temanmenulis

# Copy template config
cp config.example.php config.php

# Edit config.php dengan kredensial lokal
nano config.php
```

## 🔐 Keamanan

### File yang TIDAK di-commit (di .gitignore):
- ✅ `config.php` - Berisi password database
- ✅ `*.sql` - File database
- ✅ `*.log` - File log
- ✅ `.env` - Environment variables

### File yang DI-commit:
- ✅ `config.example.php` - Template tanpa password
- ✅ Semua file PHP lainnya
- ✅ CSS, JS, HTML
- ✅ README.md, dll

## 🌐 Deploy ke Hosting

Setelah push ke GitHub, untuk deploy website:

1. **Clone di server hosting** (jika hosting support Git):
   ```bash
   git clone https://github.com/USERNAME/temanmenulis.git
   ```

2. **Atau upload manual** via FTP/cPanel

3. **Setup config.php** di server dengan kredensial hosting

Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk panduan lengkap.

## 📚 Referensi

- [Git Documentation](https://git-scm.com/doc)
- [GitHub Guides](https://guides.github.com/)
- [GitHub Pages Documentation](https://docs.github.com/en/pages) (untuk static sites)

---

**Ingat**: GitHub hanya untuk menyimpan code, bukan untuk hosting PHP website. Gunakan hosting PHP untuk deploy website.

