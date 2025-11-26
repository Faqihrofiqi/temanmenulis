<?php
/**
 * Installation Script
 * Run this file once to initialize the database
 */

require_once 'config.php';

try {
    // Create database if not exists
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database created successfully<br>";
    
    // Connect to the database
    $conn = getDBConnection();
    
    // Create services table
    $conn->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(100),
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Services table created<br>";
    
    // Create orders table
    $conn->exec("CREATE TABLE IF NOT EXISTS orders (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Orders table created<br>";
    
    // Insert sample services
    $stmt = $conn->query("SELECT COUNT(*) FROM services");
    if ($stmt->fetchColumn() == 0) {
        $services = [
            ['Jasa Servis Laptop/PC', 'Perbaikan dan maintenance laptop/PC Anda dengan teknisi berpengalaman. Meliputi perbaikan hardware, software, upgrade, dan troubleshooting.', 150000, 'servis', 'servis.jpg'],
            ['Desain Grafis', 'Desain logo, banner, poster, brosur, dan media promosi lainnya dengan kualitas profesional. Revisi gratis hingga sesuai keinginan.', 200000, 'desain', 'desain.jpg'],
            ['Tulis Jurnal Ilmiah', 'Jasa penulisan jurnal ilmiah sesuai standar akademik. Format sesuai template, referensi terpercaya, dan plagiarism check.', 500000, 'jurnal', 'jurnal.jpg'],
            ['Jasa Editing & Proofreading', 'Editing dan proofreading dokumen akademik atau bisnis. Perbaikan grammar, struktur kalimat, dan konsistensi format.', 100000, 'editing', 'editing.jpg'],
            ['Desain Website', 'Pembuatan website modern dan responsive dengan teknologi terbaru. SEO friendly, fast loading, dan mobile optimized.', 3000000, 'website', 'website.jpg'],
            ['Jasa Translate', 'Terjemahan dokumen profesional dari/ke berbagai bahasa. Akurat, natural, dan sesuai konteks. Termasuk proofreading.', 150000, 'translate', 'translate.jpg']
        ];
        
        $stmt = $conn->prepare("INSERT INTO services (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $stmt->execute($service);
        }
        echo "✓ Sample services inserted<br>";
    } else {
        echo "✓ Services table already has data<br>";
    }
    
    echo "<br><strong>Installation completed successfully!</strong><br>";
    echo "<br><a href='index.php'>Go to Website</a> | <a href='services.php'>View Services</a>";
    echo "<br><br><small>For security, please delete or rename this install.php file after installation.</small>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo "<br><br>Please check your database configuration in config.php";
}

