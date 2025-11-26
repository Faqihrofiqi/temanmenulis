-- Database Setup for TemanMenulis
-- Run this SQL file if you prefer manual database setup

CREATE DATABASE IF NOT EXISTS temanmenulis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE temanmenulis;

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(100) UNIQUE NOT NULL,
    service_id INT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    order_details TEXT,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_token VARCHAR(255),
    payment_method VARCHAR(100),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Sample Services
INSERT INTO services (name, description, price, category, image) VALUES
('Jasa Servis Laptop/PC', 'Perbaikan dan maintenance laptop/PC Anda dengan teknisi berpengalaman. Meliputi perbaikan hardware, software, upgrade, dan troubleshooting.', 150000, 'servis', 'servis.jpg'),
('Desain Grafis', 'Desain logo, banner, poster, brosur, dan media promosi lainnya dengan kualitas profesional. Revisi gratis hingga sesuai keinginan.', 200000, 'desain', 'desain.jpg'),
('Tulis Jurnal Ilmiah', 'Jasa penulisan jurnal ilmiah sesuai standar akademik. Format sesuai template, referensi terpercaya, dan plagiarism check.', 500000, 'jurnal', 'jurnal.jpg'),
('Jasa Editing & Proofreading', 'Editing dan proofreading dokumen akademik atau bisnis. Perbaikan grammar, struktur kalimat, dan konsistensi format.', 100000, 'editing', 'editing.jpg'),
('Desain Website', 'Pembuatan website modern dan responsive dengan teknologi terbaru. SEO friendly, fast loading, dan mobile optimized.', 3000000, 'website', 'website.jpg'),
('Jasa Translate', 'Terjemahan dokumen profesional dari/ke berbagai bahasa. Akurat, natural, dan sesuai konteks. Termasuk proofreading.', 150000, 'translate', 'translate.jpg');

