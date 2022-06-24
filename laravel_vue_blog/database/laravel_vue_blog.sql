-- MySQL dump 10.13  Distrib 8.0.29, for Linux (x86_64)
--
-- Host: localhost    Database: laravel_vue_blog
-- ------------------------------------------------------
-- Server version	8.0.29-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blogcategories`
--

DROP TABLE IF EXISTS `blogcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `blog_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  CONSTRAINT `blogcategories_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogcategories`
--

LOCK TABLES `blogcategories` WRITE;
/*!40000 ALTER TABLE `blogcategories` DISABLE KEYS */;
INSERT INTO `blogcategories` VALUES (11,16,18,'2022-03-22 04:18:50','2022-03-22 04:18:50'),(12,15,18,'2022-03-22 04:18:50','2022-03-22 04:18:50'),(17,15,19,'2022-03-24 07:00:42','2022-03-24 07:00:42'),(18,16,19,'2022-03-24 07:00:42','2022-03-24 07:00:42');
/*!40000 ALTER TABLE `blogcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post` text COLLATE utf8mb4_unicode_ci,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jsonData` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (18,'new blog','<ol><li>category 1</li><li>category 2<br></li></ol>','post','new-blog',3,NULL,'description','{\"time\":1647922730096,\"blocks\":[{\"id\":\"ByIfpEVKyj\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"category 1\",\"category 2<br>\"]}}],\"version\":\"2.23.2\"}',0,'2022-03-21 22:48:50','2022-03-21 22:48:50'),(19,'new blog','<h2>My header</h2><h6>new header</h6><ol><li>list 1</li><li>list 2</li><li>list 3<br></li></ol>','post data','new blog',3,NULL,'laravel csss12','{\"time\":1648105242238,\"blocks\":[{\"id\":\"Zvskw1eeyu\",\"type\":\"header\",\"data\":{\"text\":\"My header\",\"level\":2}},{\"id\":\"dajSmqwxH3\",\"type\":\"header\",\"data\":{\"text\":\"new header\",\"level\":6}},{\"id\":\"95bWpN4uof\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"list 1\",\"list 2\",\"list 3<br>\"]}}],\"version\":\"2.23.2\"}',0,'2022-03-21 22:49:51','2022-03-24 01:30:42');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogtags`
--

DROP TABLE IF EXISTS `blogtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogtags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int NOT NULL,
  `blog_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  CONSTRAINT `blogtags_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogtags`
--

LOCK TABLES `blogtags` WRITE;
/*!40000 ALTER TABLE `blogtags` DISABLE KEYS */;
INSERT INTO `blogtags` VALUES (13,24,18,'2022-03-22 04:18:50','2022-03-22 04:18:50'),(14,23,18,'2022-03-22 04:18:50','2022-03-22 04:18:50'),(22,23,19,'2022-03-24 07:00:42','2022-03-24 07:00:42');
/*!40000 ALTER TABLE `blogtags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iconImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (15,'Laravel','/uploads/category/1647851169.png','2022-03-21 02:56:11','2022-03-21 02:56:11'),(16,'Vue js','/uploads/category/1647851189.png','2022-03-21 02:56:31','2022-03-21 02:56:31');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menucategories`
--

DROP TABLE IF EXISTS `menucategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menucategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '0' COMMENT '0-Inactive,1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menucategories`
--

LOCK TABLES `menucategories` WRITE;
/*!40000 ALTER TABLE `menucategories` DISABLE KEYS */;
INSERT INTO `menucategories` VALUES (3,'Admin',1,'2022-05-21 03:28:10','2022-05-21 03:31:57',NULL),(4,'Navigation',1,'2022-05-21 03:32:20','2022-05-21 03:32:20',NULL),(5,'Permissions',1,'2022-05-21 03:34:13','2022-05-21 03:34:13',NULL);
/*!40000 ALTER TABLE `menucategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pid` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'parent id of menu, 0 means no parent',
  `menucategories_id` bigint unsigned DEFAULT NULL,
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '0' COMMENT '0-Inactive,1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `has_menu` bigint unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,0,3,'Menu 1',1,'2022-06-01 12:07:28','2022-06-01 12:08:53',NULL,0),(2,0,3,'Menu 2',1,'2022-06-01 12:07:39','2022-06-01 12:08:56',NULL,0),(3,1,3,'Menu 11',1,'2022-06-01 12:07:57','2022-06-01 12:08:58',NULL,0),(4,2,3,'Menu 22',1,'2022-06-02 12:08:20','2022-06-01 12:09:01',NULL,0),(5,3,3,'Menu 111',1,'2022-06-02 09:09:10','2022-06-02 09:09:10',NULL,0);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2022_02_26_090715_create_categories_table',1),(5,'2022_02_26_090742_create_tags_table',1),(6,'2022_02_26_090754_create_blogs_table',1),(7,'2022_02_26_090815_create_blogtags_table',1),(8,'2022_02_26_090827_create_blogcategories_table',1),(9,'2022_03_11_041135_create_roles_table',2),(10,'2016_06_01_000001_create_oauth_auth_codes_table',3),(11,'2016_06_01_000002_create_oauth_access_tokens_table',3),(12,'2016_06_01_000003_create_oauth_refresh_tokens_table',3),(13,'2016_06_01_000004_create_oauth_clients_table',3),(14,'2016_06_01_000005_create_oauth_personal_access_clients_table',3),(15,'2022_05_20_081524_create_menucategories',4),(16,'2022_05_20_082135_create_menu',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('010bd2d93ce7a63042768f8691ce0f677457f1499f3447338f7fc38cb3e50aeb00385d102183010a',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:34:23','2022-05-18 01:34:23','2023-05-18 07:04:23'),('01c6f6c498033936bb7195653b84a6db80b2ca694f9a10c2e34d71bf470fd0325a037a7d2de76155',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-19 23:26:11','2022-06-19 23:26:11','2023-06-20 04:56:11'),('02be1eabe9f66f41526aa92196111392df1d39c2286fc6b01601066840a6842f20ac518397bb3b08',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:42:19','2022-05-18 01:42:19','2023-05-18 07:12:19'),('072c8e0617a2ac1055d60bed42d980fd5adc4787c5b156e2ca56e4cd3c1dd4a8d67f6fd6ee71926d',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:35:25','2022-05-18 01:35:25','2023-05-18 07:05:25'),('111364c18c1a661b9a8d3e78d9595cda661a8fa5d5bb1da015533f755c3f406fc27b6723a6b50ab9',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-17 03:15:31','2022-05-17 03:15:31','2023-05-17 08:45:31'),('1398ddca2777ad4f1da7eece5f46cc33b0ff98f0db850fdea5286291ccf38d4d3a19445576f4e8ed',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-02 23:42:11','2022-06-02 23:42:11','2023-06-03 05:12:11'),('1c421d367c0010d6210ba897087e16a285296cc449d33e2b92e7cc9436efaace365aa391f1ccadaa',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:38:40','2022-05-18 01:38:40','2023-05-18 07:08:40'),('2490dadc725c087a5475536206ffe28d12d904ef60bd30268c29ee14eb53a1979fb80098d87be3b5',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-18 04:11:35','2022-06-18 04:11:35','2023-06-18 09:41:35'),('34d067eced035f7767e1c32c56ce6f42e2267edd36f55c855f4946b67d791bb4bfecc0a1e7edc8d2',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-17 03:12:37','2022-05-17 03:12:37','2023-05-17 08:42:37'),('3ea402c41a1ad3dbbbba5150572b7c9c6e34d34cdaf827b89cff18a8e5e745a52300736e89af3271',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-19 06:01:11','2022-05-19 06:01:11','2023-05-19 11:31:11'),('40fec93b3758df5994ef79ea8ce4ff293bf459cb3ad109e9fc8c7f07b7127c4fce42be259ea778c7',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-02 23:42:40','2022-06-02 23:42:40','2023-06-03 05:12:40'),('425ec2cca46e204c5276a704c2b82a0f51910c73867a03ef7c9714c2d4f85a8f899e6114816fdb8b',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-31 06:36:32','2022-05-31 06:36:32','2023-05-31 12:06:32'),('4543f3df5aa22445b9e4998b34f0922654c5357ba817b9e4b249fcbf1bfa246a2e8842e86da277b4',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:46:24','2022-05-18 01:46:24','2023-05-18 07:16:24'),('475a2626eb33fb81343567948bb8e033a11fdaaea2d8f25461ef81c6792f208f576c106e4f8f45bd',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:31:46','2022-05-18 01:31:46','2023-05-18 07:01:46'),('48154bfca20480b58b0b013b3731a6feb86cf81bf7a520b0e5300671418a9a5d5c8c582e914f704b',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-01 22:46:34','2022-06-01 22:46:34','2023-06-02 04:16:34'),('5983179bafb45a3e5874a47ff5bb2b2ae791402f8ecfce75c041f68c9e275518bb3b69bb005783a1',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:43:03','2022-05-18 01:43:03','2023-05-18 07:13:03'),('6784fab7b709f98e001ebcb5217b6a62b7ffe26c41fbf30e33fe471f3c5fcfdb9cfcf5d955095fce',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-02 05:24:16','2022-06-02 05:24:16','2023-06-02 10:54:16'),('691cf0600aa505d82c7dcb6698fa08f191575daf2228b5b3dd78dcdc3894a869b9bf07797f95cdec',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:35:45','2022-05-18 01:35:45','2023-05-18 07:05:45'),('7feec664818eafaaa5912ca3f0640deb8819ad7e8f03694809e724ad0df0d0f3de5f00486483e7ad',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:33:27','2022-05-18 01:33:27','2023-05-18 07:03:27'),('818ed2d1df9555a9cc7a94bcd5a0d722d9acebf3e2096454e0c725f4167bc365f0173f00e33c68bd',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-02 23:37:07','2022-06-02 23:37:07','2023-06-03 05:07:07'),('86af89053923144c655a6bd8fdf048519a721f699010cc1a7a1fbde70c3015d5daa57f3c6704852a',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-31 02:04:41','2022-05-31 02:04:41','2023-05-31 07:34:41'),('c2a7889de65020a7696e5d6d0be6cfcb9cd8341b9a2a990f8a871626dac8aa5180d2073cb47fd902',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-01 05:36:48','2022-06-01 05:36:48','2023-06-01 11:06:48'),('c71666f97390a75cda2c529c16451c681ca1ef41babd13807a16c519ca644d375addc6d69ff88406',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-20 06:05:51','2022-05-20 06:05:51','2023-05-20 11:35:51'),('d672fb5d90a75754af98fe28f076cdd694cd5ee367774ae066e086dfa380f0b6dbcf2112ed29531a',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:40:29','2022-05-18 01:40:29','2023-05-18 07:10:29'),('e0bbff5275ffd90ae0b55512dd8193bfb5f3b41661c0772376c529d99033c7c01c08d4a833097cc5',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-06-01 00:06:11','2022-06-01 00:06:11','2023-06-01 05:36:11'),('ea6387f6e36b530d5341ad73f9e81ce5a71777eeeb0f3e0f57a978c21bd6defdfb2c1868921ad540',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-22 22:32:25','2022-05-22 22:32:25','2023-05-23 04:02:25'),('ec731519611bf98cafad7869616b76673c4d04e94d9ae777d47c45c21fb4a44126aea6ba82bc43e9',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-18 01:36:20','2022-05-18 01:36:20','2023-05-18 07:06:20'),('fe3dc4de1b288b26e135ca7b360f960456db5797aef95f9f2c06d61ef870f535f98bb6544b6f3766',3,8,'Personal Access Token','[\"Admin\"]',0,'2022-05-21 03:26:54','2022-05-21 03:26:54','2023-05-21 08:56:54');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (8,NULL,'Laravel Personal Access Client','TaZDJPpjWXCRDTrzSmCVwIoZgjBE0VueZBBWMqhd',NULL,'http://localhost',1,0,0,'2022-03-28 04:17:58','2022-03-28 04:17:58'),(9,NULL,'Laravel Password Grant Client','AFffeYjd6YHvSJhLRPpU39icCeiHN7lYC3BXtbi9','users','http://localhost',0,1,0,'2022-03-28 04:17:58','2022-03-28 04:17:58');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (5,8,'2022-03-28 04:17:58','2022-03-28 04:17:58');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `roleName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` text COLLATE utf8mb4_unicode_ci,
  `isAdmin` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','{\"0\":{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"tags\"},\"1\":{\"resourceName\":\"Category\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"category\"},\"2\":{\"resourceName\":\"Create Blog\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"create-blog\"},\"3\":{\"resourceName\":\"Admin Users\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"adminusers\"},\"4\":{\"resourceName\":\"Role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"role-management\"},\"5\":{\"resourceName\":\"Assign Role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"assign-roles\"},\"6\":{\"resourceName\":\"Dashboard\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"dashboard\"},\"7\":{\"resourceName\":\"Blogs\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"blogs\"}}',1,'2022-03-10 23:43:24','2022-03-21 03:41:51'),(2,'Editor','{\"0\":{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"tags\"},\"1\":{\"resourceName\":\"Category\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"category\"},\"2\":{\"resourceName\":\"Admin Users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"adminusers\"},\"3\":{\"resourceName\":\"Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"role-management\"},\"4\":{\"resourceName\":\"Assign Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"assign-roles\"},\"5\":{\"resourceName\":\"Dashboard\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"dashboard\"}}',1,'2022-03-10 23:59:04','2022-03-11 07:36:44'),(3,'Moderator','',1,'2022-03-10 23:59:14','2022-03-11 06:08:06'),(4,'User','{\"0\":{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"tags\"},\"1\":{\"resourceName\":\"Category\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"category\"},\"2\":{\"resourceName\":\"Admin Users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"adminusers\"},\"3\":{\"resourceName\":\"Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"role-management\"},\"4\":{\"resourceName\":\"Assign Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"assign-roles\"},\"5\":{\"resourceName\":\"Dashboard\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"dashboard\"}}',0,NULL,'2022-03-11 07:43:55');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tagName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (22,'HTML','2022-03-21 02:56:59','2022-03-21 02:56:59'),(23,'CSS','2022-03-21 02:57:03','2022-03-21 02:57:03'),(24,'Vue js','2022-03-21 02:57:08','2022-03-21 02:57:08');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fullName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `isActive` tinyint NOT NULL DEFAULT '0',
  `passwordResetCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activationCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialType` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Admin1e','abc@yahoo.com','$2a$12$XdK/xJEob0Vn4S4X39UFzexjvnZ6D3w/HH8haiJqx2cfZ9atbpFV2',1,1,NULL,NULL,NULL,'2022-03-06 23:21:46','2022-03-08 00:19:02'),(4,'myuser','user@gmail.com','$2a$12$XdK/xJEob0Vn4S4X39UFzexjvnZ6D3w/HH8haiJqx2cfZ9atbpFV2',4,1,NULL,NULL,NULL,'2022-03-09 12:26:16','2022-03-09 12:26:22'),(5,'editor','editor@gmail.com','$2a$12$XdK/xJEob0Vn4S4X39UFzexjvnZ6D3w/HH8haiJqx2cfZ9atbpFV2',2,1,NULL,NULL,NULL,'2022-03-10 12:26:16','2022-03-10 12:26:22'),(6,'Moderator','moderator@gmail.com','$2y$10$SRIuNPstdm32dqjiKo23d.i5E7JPsAxB/PpJecxwG57ev8r8lQDNK',3,0,NULL,NULL,NULL,'2022-03-11 01:12:03','2022-03-11 01:12:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-24 11:30:43
