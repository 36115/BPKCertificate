-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               11.6.1-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table bpkcertdb.cert_count
CREATE TABLE IF NOT EXISTS `cert_count` (
  `cert_all` int(11) NOT NULL,
  `cert_t1` int(11) NOT NULL,
  `cert_t2` int(11) NOT NULL,
  `cert_t3` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bpkcertdb.cert_count: ~0 rows (approximately)
REPLACE INTO `cert_count` (`cert_all`, `cert_t1`, `cert_t2`, `cert_t3`) VALUES
	(0, 0, 0, 0);

-- Dumping structure for table bpkcertdb.cert_list
CREATE TABLE IF NOT EXISTS `cert_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cert_sem` varchar(255) NOT NULL,
  `cert_sub` varchar(255) NOT NULL,
  `cert_name` varchar(255) NOT NULL,
  `cert_type` int(11) NOT NULL,
  `cert_tname` varchar(255) NOT NULL,
  `cert_req` int(11) NOT NULL,
  `cert_snum` int(11) NOT NULL,
  `cert_enum` int(11) NOT NULL,
  `cert_sym` varchar(255) NOT NULL,
  `cert_link` text NOT NULL,
  `cert_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bpkcertdb.cert_list: ~0 rows (approximately)

-- Dumping structure for table bpkcertdb.request_users
CREATE TABLE IF NOT EXISTS `request_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bpkcertdb.request_users: ~0 rows (approximately)

-- Dumping structure for table bpkcertdb.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `navbar_mode` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bpkcertdb.settings: ~0 rows (approximately)
REPLACE INTO `settings` (`navbar_mode`) VALUES
	(1);

-- Dumping structure for table bpkcertdb.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `edited_date` datetime NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table bpkcertdb.users: ~2 rows (approximately)
REPLACE INTO `users` (`id`, `username`, `realname`, `role`, `email`, `reg_date`, `edited_date`, `password`) VALUES
	(1, 'admin', 'ผู้ดูแล ระบบ', 'admin', 'ad@m.in', '2024-09-28 13:38:44', '2024-09-28 13:38:44', '$2y$10$yHfQ0Qa0nMzLLAta4flNY.KKp9dKoDWdbVgNGZtWfOeGW6badXLL6'),
	(2, 'user', 'ผู้ขอ เกียรติบัตร', 'user', 'us@e.r', '2024-09-28 13:38:47', '2024-09-28 13:38:47', '$2y$10$sFu0TGGE929P4EGVKxB/reFF0EIfQBhLtxHM2Mn.ZZ9X.MmIpwznW');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
