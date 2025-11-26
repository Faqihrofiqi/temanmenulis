<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Kopisusu1212');
define('DB_NAME', 'temanmenulis');

// Site Configuration
define('SITE_NAME', 'TemanMenulis');
define('SITE_URL', 'http://localhost/temanmenulis');

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
            ['Jasa Servis Laptop/PC', 'Perbaikan dan maintenance laptop/PC Anda dengan teknisi berpengalaman', 150000, 'servis', 'servis.jpg'],
            ['Desain Grafis', 'Desain logo, banner, poster, dan media promosi lainnya', 200000, 'desain', 'desain.jpg'],
            ['Tulis Jurnal Ilmiah', 'Jasa penulisan jurnal ilmiah sesuai standar akademik', 500000, 'jurnal', 'jurnal.jpg'],
            ['Jasa Editing & Proofreading', 'Editing dan proofreading dokumen akademik atau bisnis', 100000, 'editing', 'editing.jpg'],
            ['Desain Website', 'Pembuatan website modern dan responsive', 3000000, 'desain', 'website.jpg'],
            ['Jasa Translate', 'Terjemahan dokumen profesional', 150000, 'translate', 'translate.jpg']
        ];
        
        $stmt = $conn->prepare("INSERT INTO services (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $stmt->execute($service);
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

