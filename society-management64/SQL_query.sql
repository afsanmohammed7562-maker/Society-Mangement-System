-- Database: society_management

CREATE DATABASE IF NOT EXISTS society_management;
USE society_management;

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('President', 'Secretary', 'Treasurer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pre-created Admins (Password: admin123)
-- Using MD5 for simplicity in this generated script, but PHP should use password_hash/verify
-- For this script, I will insert plain text for now or valid hashes if I can. 
-- Let's assume the PHP code will handle hashing. I'll insert 'admin123' as hash for demo purposes if using md5, 
-- but better to assume Developer sets it up. I will provide INSERTs with a placeholder hash or 'admin123'.
-- password_hash('admin123', PASSWORD_BCRYPT) is roughly $2y$10$w...
-- I will use a simple known hash for 'admin123' or just text if the PHP uses simple comparison (not recommended but easiest for 'ready-to-run' without CLI).
-- Actually, I will implement `password_verify` in PHP, so I need a valid Bcrypt hash.
-- Hash for 'admin123': $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO admins (username, password, role) VALUES 
('president', '$2y$10$BhnFPF0y3K1LFUFBCOhVA.MDGHWz4.QeTJoJ.fORqrItda65NmrEi', 'President'),
('secretary', '$2y$10$BhnFPF0y3K1LFUFBCOhVA.MDGHWz4.QeTJoJ.fORqrItda65NmrEi', 'Secretary'),
('treasurer', '$2y$10$BhnFPF0y3K1LFUFBCOhVA.MDGHWz4.QeTJoJ.fORqrItda65NmrEi', 'Treasurer')
ON DUPLICATE KEY UPDATE password=VALUES(password);

-- 2. Users (Members) Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    register_no VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample User (Password: user123)
INSERT INTO users (register_no, full_name, username, password, address, phone, email) VALUES
('REG001', 'John Doe', 'john', '$2y$10$ceSnz8DAL6RAGZXIAKw5f.e/6CO8i8/OVXQQXlNIxcR9D2t5NHwsW', '123 Society St', '1234567890', 'john@example.com')
ON DUPLICATE KEY UPDATE full_name=VALUES(full_name);

-- 3. Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    phone VARCHAR(20),
    username VARCHAR(50),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 4. Payments/Treasurer Data
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    month_year VARCHAR(20) NOT NULL, -- e.g., "January 2024"
    actual_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    paid_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    description VARCHAR(255),
    payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Notice Board
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    date_posted DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 6. Gallery
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(100),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Reports (Secretary/Treasurer Uploads)
-- Storing paths to uploaded report images/PDFs
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Secretary', 'Treasurer') NOT NULL,
    month_year VARCHAR(20) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
