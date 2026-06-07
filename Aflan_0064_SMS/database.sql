CREATE DATABASE IF NOT EXISTS `userlogin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `userlogin`;

CREATE TABLE IF NOT EXISTS `userd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `U_name` varchar(100) NOT NULL,
  `U_email` varchar(150) NOT NULL,
  `U_pass` varchar(255) NOT NULL,
  `role` enum('admin','member','secretary','treasurer') NOT NULL DEFAULT 'member',
  `member_id_ref` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `U_email` (`U_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `membership_type` varchar(50) NOT NULL,
  `join_date` date NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  UNIQUE KEY `nic_number` (`nic_number`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `members_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `userd` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('announcement','secretary','treasurer') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `userd` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Default Admin Account
-- ============================================================
-- Username: admin
-- Password: password
-- IMPORTANT: Change password after first login!
INSERT INTO `userd` (`U_name`, `U_email`, `U_pass`, `role`) VALUES
('admin', 'admin@sms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
