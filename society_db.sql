-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `society_db`;
USE `society_db`;

-- Drop tables if they exist to avoid conflicts on import
DROP TABLE IF EXISTS `monthly_reports`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `members`;

-- Table structure for table `members`
CREATE TABLE `members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reg_no` VARCHAR(50) NOT NULL UNIQUE,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `contact_messages`
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `payments`
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reg_no` VARCHAR(50) NOT NULL,
  `month` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  FOREIGN KEY (`reg_no`) REFERENCES `members`(`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `monthly_reports`
CREATE TABLE `monthly_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `month` VARCHAR(50) NOT NULL,
  `report_file` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for table `members`
INSERT INTO `members` (`reg_no`, `username`, `fullname`, `phone`, `email`, `address`) VALUES
('REG001', 'atheeb_94', 'Seiyidhu Atheeb', '+94 761929402', 'seiyidhuatheeb@gmail.com', '268/C School Road Nintavur-11'),
('REG002', 'john_doe', 'John Doe', '1234567890', 'john@example.com', '123 Main Street, Cityville'),
('REG003', 'jane_smith', 'Jane Smith', '0987654321', 'jane@example.com', '456 Oak Avenue, Townsford');

-- Insert sample data for table `contact_messages`
INSERT INTO `contact_messages` (`name`, `username`, `phone`, `message`) VALUES
('Alice Johnson', 'alice_j', '5551234567', 'Hello Admin, how can I access my account statement?'),
('Bob Carter', 'bob_c', '5559876543', 'There is a typo in my address in the directory. Please update it to 789 Elm St.');

-- Insert sample data for table `payments`
INSERT INTO `payments` (`reg_no`, `month`, `amount`, `status`) VALUES
('REG001', 'January 2026', 1500.00, 'Paid'),
('REG001', 'February 2026', 1500.00, 'Paid'),
('REG001', 'March 2026', 1500.00, 'Pending'),
('REG002', 'January 2026', 1500.00, 'Paid'),
('REG002', 'February 2026', 1500.00, 'Pending'),
('REG003', 'January 2026', 1500.00, 'Pending');

-- Insert sample data for table `monthly_reports`
INSERT INTO `monthly_reports` (`month`, `report_file`) VALUES
('January 2026', 'sam.pdf'),
('February 2026', 'sam.pdf');
