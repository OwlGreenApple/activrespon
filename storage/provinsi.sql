-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.37-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table wilayah.wilayah_provinsi: ~34 rows (approximately)
DELETE FROM `provinces`;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` (`id`, `nama`) VALUES
	('11', 'Aceh'),
	('12', 'Sumatera Utara'),
	('13', 'Sumatera Barat'),
	('14', 'Riau'),
	('15', 'Jambi'),
	('16', 'Sumatera Selatan'),
	('17', 'Bengkulu'),
	('18', 'Lampung'),
	('19', 'Kepulauan Bangka Belitung'),
	('21', 'Kepulauan Riau'),
	('31', 'Dki Jakarta'),
	('32', 'Jawa Barat'),
	('33', 'Jawa Tengah'),
	('34', 'Di Yogyakarta'),
	('35', 'Jawa Timur'),
	('36', 'Banten'),
	('51', 'Bali'),
	('52', 'Nusa Tenggara Barat'),
	('53', 'Nusa Tenggara Timur'),
	('61', 'Kalimantan Barat'),
	('62', 'Kalimantan Tengah'),
	('63', 'Kalimantan Selatan'),
	('64', 'Kalimantan Timur'),
	('65', 'Kalimantan Utara'),
	('71', 'Sulawesi Utara'),
	('72', 'Sulawesi Tengah'),
	('73', 'Sulawesi Selatan'),
	('74', 'Sulawesi Tenggara'),
	('75', 'Gorontalo'),
	('76', 'Sulawesi Barat'),
	('81', 'Maluku'),
	('82', 'Maluku Utara'),
	('91', 'Papua Barat'),
	('94', 'Papua');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
