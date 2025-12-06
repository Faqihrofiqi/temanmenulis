<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Kopisusu1212');
define('DB_NAME', 'temanmenulis');

// Site Configuration
define('SITE_NAME', 'TemanMenulis');
define('SITE_URL', 'http://localhost/temanmenulis/');

// Asset URL helper function
function asset_url($path) {
    return SITE_URL . $path;
}

// Payment Configuration (Manual QRIS)
define('QRIS_NUMBER', '081234567890'); // Nomor rekening/QRIS untuk pembayaran
define('QRIS_NAME', 'TemanMenulis'); // Nama penerima pembayaran
define('QRIS_BANK', 'Bank BCA'); // Nama bank (opsional)
define('PAYMENT_MANUAL_CONFIRMATION', true); // Set true untuk konfirmasi manual oleh admin

// Create database connection
function getDBConnection() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Initialize database tables
function initDatabase() {
    $conn = getDBConnection();
    
    // Services table
    $conn->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category VARCHAR(100),
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Orders table
    $conn->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(100) UNIQUE NOT NULL,
        service_id INT,
        customer_name VARCHAR(255),
        customer_email VARCHAR(255),
        customer_phone VARCHAR(50),
        order_details TEXT,
        amount DECIMAL(10,2),
        status VARCHAR(50) DEFAULT 'pending',
        payment_token VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (service_id) REFERENCES services(id)
    )");
    
    // Add order_details column if it doesn't exist (for existing databases)
    try {
        $conn->exec("ALTER TABLE orders ADD COLUMN order_details TEXT AFTER customer_phone");
    } catch(PDOException $e) {
        // Column already exists, ignore error
    }
    
    // Insert sample services if table is empty
    $stmt = $conn->query("SELECT COUNT(*) FROM services");
    if ($stmt->fetchColumn() == 0) {
        $services = [
            // Skripsi Packages
            ['SEMPRO SKRIPSI', 'Proposal BAB 1-3', 799000, 'skripsi', 'skripsi.jpg'],
            ['SEMHAS SKRIPSI', 'Pembahasan BAB 4-5', 1499000, 'skripsi', 'skripsi.jpg'],
            ['WISUDA SKRIPSI', 'FullBab 1-5 Skripsi', 2499000, 'skripsi', 'skripsi.jpg'],
            ['WISUDA++ SKRIPSI', 'FullBab 1-5 dan Full Bimbingan', 2699000, 'skripsi', 'skripsi.jpg'],
            // Thesis Packages
            ['SEMPRO THESIS', 'Proposal BAB 1-3', 1499000, 'thesis', 'thesis.jpg'],
            ['SEMHAS THESIS', 'Pembahasan BAB 4-5', 1999000, 'thesis', 'thesis.jpg'],
            ['WISUDA++ THESIS', 'FullBab 1-6', 2599000, 'thesis', 'thesis.jpg'],
            ['WISUDA+++ THESIS', 'FullBab 1-6 dan Full Bimbingan', 3099000, 'thesis', 'thesis.jpg'],
            // Disertasi Packages
            ['TES MASUK DISERTASI', 'Proposal BAB 1', 1199000, 'disertasi', 'disertasi.jpg'],
            ['SEMPRO DISERTASI', 'Proposal BAB 1-3', 2899000, 'disertasi', 'disertasi.jpg'],
            ['SEMHAS DISERTASI', 'Pembahasan BAB 4-5', 4099000, 'disertasi', 'disertasi.jpg'],
            ['WISUDA DISERTASI', 'FullBAB 1-5', 6099000, 'disertasi', 'disertasi.jpg']
        ];
        
        $stmt = $conn->prepare("INSERT INTO services (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $stmt->execute($service);
        }
    } else {
        // Update existing services to match new packages
        $updateServices = [
            // Skripsi Packages
            ['SEMPRO SKRIPSI', 'Proposal BAB 1-3', 799000, 'skripsi'],
            ['SEMHAS SKRIPSI', 'Pembahasan BAB 4-5', 1499000, 'skripsi'],
            ['WISUDA SKRIPSI', 'FullBab 1-5 Skripsi', 2499000, 'skripsi'],
            ['WISUDA++ SKRIPSI', 'FullBab 1-5 dan Full Bimbingan', 2699000, 'skripsi'],
            // Thesis Packages
            ['SEMPRO THESIS', 'Proposal BAB 1-3', 1499000, 'thesis'],
            ['SEMHAS THESIS', 'Pembahasan BAB 4-5', 1999000, 'thesis'],
            ['WISUDA++ THESIS', 'FullBab 1-6', 2599000, 'thesis'],
            ['WISUDA+++ THESIS', 'FullBab 1-6 dan Full Bimbingan', 3099000, 'thesis'],
            // Disertasi Packages
            ['TES MASUK DISERTASI', 'Proposal BAB 1', 1199000, 'disertasi'],
            ['SEMPRO DISERTASI', 'Proposal BAB 1-3', 2899000, 'disertasi'],
            ['SEMHAS DISERTASI', 'Pembahasan BAB 4-5', 4099000, 'disertasi'],
            ['WISUDA DISERTASI', 'FullBAB 1-5', 6099000, 'disertasi']
        ];
        
        // Delete all existing services and insert new ones
        $conn->exec("DELETE FROM services");
        $stmt = $conn->prepare("INSERT INTO services (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        foreach ($updateServices as $service) {
            $stmt->execute([$service[0], $service[1], $service[2], $service[3], strtolower($service[3]) . '.jpg']);
        }
    }
}

// Initialize database on first run
try {
    initDatabase();
} catch(Exception $e) {
    // Database might not exist yet, that's okay
}
?>

