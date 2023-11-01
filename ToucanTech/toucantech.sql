-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.27 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6265
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for toucantech
CREATE DATABASE IF NOT EXISTS `toucantech` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `toucantech`;

-- Dumping structure for table toucantech.emails
CREATE TABLE IF NOT EXISTS `emails` (
  `emailID` int NOT NULL,
  `UserRefID` int NOT NULL,
  `emailaddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `default` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toucantech.emails: ~11 rows (approximately)
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
INSERT INTO `emails` (`emailID`, `UserRefID`, `emailaddress`, `default`) VALUES
	(567, 100567, 'j.smith@zmail.com', 1),
	(568, 100567, 'j.smith@zmail.com', 0),
	(569, 100567, 'j.smith@zmail.com', 0),
	(570, 100567, 'j.smith@gmail.com', 0),
	(571, 100567, 'j.smith@gmail.com', 0),
	(572, 100567, 'j.smith@lmail.com', 0),
	(573, 100568, 'ryan.anatao@webmail.com', 1),
	(574, 100569, 'anatole.farci@webmail.com', 0),
	(575, 100570, 'adrina@webmail.com', 1),
	(576, 100568, 'ryan.anatao@webmail.com', 0),
	(576, 100570, 'adrina@webmail.com', 0);
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;

-- Dumping structure for table toucantech.members
CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `school_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table toucantech.members: ~10 rows (approximately)
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` (`member_id`, `name`, `email_address`, `school_id`) VALUES
	(1, 'dev', 'abc@yahoo.com', 1),
	(2, 'devs2', 'abc@gmail.com', 1),
	(3, 'terst', 'abc@yahoo.com', 2),
	(4, 'new_member', 'new@uk.com', 3),
	(5, 'member details', 'abc@ukmail.com', 4),
	(6, 'reboot memeber', 'test@gmail.com', 2),
	(7, 'new member', 'abc@tye.com', 3),
	(8, 'mem 1', 'mem@gmail.com', 3),
	(9, 'new 3', 'new@gmail.com', 2),
	(10, 'new 4', 'new4@gmail.com', 1);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;

-- Dumping structure for table toucantech.profiles
CREATE TABLE IF NOT EXISTS `profiles` (
  `UserRefID` int NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `Deceased` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table toucantech.profiles: ~4 rows (approximately)
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` (`UserRefID`, `Firstname`, `Surname`, `Deceased`) VALUES
	(100567, 'John', 'Smith', 0),
	(100568, 'Ryan', 'Antao', 0),
	(100569, 'Anatole', 'Farci', 0),
	(100570, 'Adrina', 'Hammer', 0);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;

-- Dumping structure for table toucantech.schools
CREATE TABLE IF NOT EXISTS `schools` (
  `school_id` int unsigned NOT NULL AUTO_INCREMENT,
  `school_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table toucantech.schools: ~5 rows (approximately)
/*!40000 ALTER TABLE `schools` DISABLE KEYS */;
INSERT INTO `schools` (`school_id`, `school_name`) VALUES
	(1, 'D.H.Public School'),
	(2, 'Essendine School'),
	(3, 'St.Merry Public School'),
	(4, 'St.Merry Convent'),
	(5, 'Essex County Primary School');
/*!40000 ALTER TABLE `schools` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
