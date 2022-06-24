-- MySQL dump 10.13  Distrib 8.0.29, for Linux (x86_64)
--
-- Host: localhost    Database: laravel_vue
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'test3','1637128415.jpeg','2021-11-17 00:23:35','2021-11-17 00:23:41'),(2,'test5','1637134253.jpg','2021-11-17 02:00:53','2021-11-17 02:00:53'),(3,'new category12','1637134294.webp','2021-11-17 02:01:34','2021-11-24 01:51:26');
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
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (21,'2014_10_12_000000_create_users_table',1),(22,'2014_10_12_100000_create_password_resets_table',1),(23,'2016_06_01_000001_create_oauth_auth_codes_table',1),(24,'2016_06_01_000002_create_oauth_access_tokens_table',1),(25,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(26,'2016_06_01_000004_create_oauth_clients_table',1),(27,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(28,'2019_08_19_000000_create_failed_jobs_table',1),(29,'2019_12_14_000001_create_personal_access_tokens_table',1),(30,'2021_11_12_041251_create_categories_table',1),(31,'2021_11_19_173323_create_products_table',2),(32,'2021_12_07_164923_to_do_list_category',3),(33,'2021_12_08_070355_tasklist',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
INSERT INTO `oauth_access_tokens` VALUES ('0c1704fe00146886f5c11baf3324789e295c3573b718384100f1c6fd6144c38d85674f92e1720d28',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-10 05:01:15','2021-12-10 05:01:15','2022-12-10 10:31:15'),('0e244aee794fab3ce736db28081bc7c0cc61cfd7a0e8ac8014ded0d26a033725fd8dd1d6191289c3',1,3,'Personal Access Token','[\"admin\"]',1,'2022-02-04 05:54:46','2022-02-04 05:54:46','2023-02-04 11:24:46'),('12d4334bca08cc31dda130a17bba211f8d26f74b82331d39ef2cf167ddd37b961567e40bc8653e97',6,1,'Personal Access Token','[\"user\"]',0,'2021-11-23 05:49:41','2021-11-23 05:49:41','2022-11-23 11:19:41'),('21c57e473e03ee89964a0c6f268248de77337607fb83d0c41ba7c59335b782ea0ad4942d4eb24925',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:42:02','2021-11-17 06:42:02','2022-11-17 12:12:02'),('257f75631077b5b0a9b817940e68fd1273a6b80a407976f4cbdb6d61147c3f4f79ee074a597ab9b6',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-20 00:59:30','2021-12-20 00:59:30','2022-12-20 06:29:30'),('2b6246385c449ae9585a44d408f2b0fe5123671e4c513ab90f711684b2033e1c8bced19f0eb3dafa',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-18 00:50:03','2021-11-18 00:50:03','2022-11-18 06:20:03'),('2d1d43fd68b6956f439b0063046cb4ba688a73ff20bd650bc4bec413d67882cf47e8a0ff6acc617c',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:14:21','2021-11-17 06:14:21','2022-11-17 11:44:21'),('2f3b6f0c16da0f111025165a530a62e7b7f33e501ef2ed5dd90bd51d815f69a0c4e549c027df7153',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:43:59','2021-11-17 06:43:59','2022-11-17 12:13:59'),('308494beade7e34578e57f31eb5e84a7ddcadcbb44c9fb3d9d938be800d3caf410850524af711881',6,1,'Personal Access Token','[\"user\"]',1,'2021-11-24 03:09:49','2021-11-24 03:09:49','2022-11-24 08:39:49'),('321e0e5b3c632c5f20721767f99d462326502c4eb56d994f5dbff49fca30e874ffcef33d1f152280',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-14 22:40:13','2021-12-14 22:40:13','2022-12-15 04:10:13'),('3d9d136997e796831bcf51daeb2c65498413767a8b19cf25ebe72c8634b1c95a5dd46ca7f736769b',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:15:13','2021-11-17 06:15:13','2022-11-17 11:45:13'),('4770996e5a161881792b01565660be60ede3cedba4d29f60e27f44d94012911e730742c2bdce68bf',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-18 01:08:02','2021-11-18 01:08:02','2022-11-18 06:38:02'),('485c3acdcea67527450c66c13245439d72832649564f941ba292209387a0726b67457c3053bd3403',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:48:54','2021-11-17 06:48:54','2022-11-17 12:18:54'),('4ce2d0f218770b1d17c73f375f8c704589417f74fc2344dc80e57772b568150857dd962ee5f88565',4,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-16 06:39:33','2021-11-16 06:39:33','2021-11-23 12:09:33'),('4dc99b57c1112d84ed4631f66268b617c468d1ddb36291977b435d57fd2bc0848b5ac92929a41793',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 02:57:56','2021-11-17 02:57:56','2022-11-17 08:27:56'),('54903f835975efca7a20ac8c51948aab1f73e75a9fcd132fe3853e831293eb57654abf7fda63b9e2',5,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:16:20','2021-11-17 06:16:20','2022-11-17 11:46:20'),('59a65945a8775c2b56044537a7ff39910ce5122f9474bd3e9171845e711c3f3f7cf54dd79453735a',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-31 05:25:30','2021-12-31 05:25:30','2022-12-31 10:55:30'),('620510797211b4895d1c749194accead97e93404a11678177944262b4915a79a7b401874c1c462f6',6,1,'Personal Access Token','[\"user\"]',1,'2021-11-24 03:06:52','2021-11-24 03:06:52','2022-11-24 08:36:52'),('6515ddeab1ad4064c7ce0ec82ca545248b75b01815e241fca205ae95b113136bb84cfcfd762a919b',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-06 02:46:30','2021-12-06 02:46:30','2022-12-06 08:16:30'),('662a6cd8968f5b0c1e6f16403826c33fc07ca7489a51429ecb4dd94fbb7f426d3e354ef4e34c3f91',4,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-16 06:38:40','2021-11-16 06:38:40','2021-11-23 12:08:40'),('6646ee9604f9d9e40461e85cbd1813cbaec5b82fcfca83e93ff83bfdf26fc812ef6d9e3883a81b31',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:16:53','2021-11-17 06:16:53','2022-11-17 11:46:53'),('6699c7ddbd367c9b2f029ac0b0eb47175c67efb49c36a4c0257bf0246895b2801e441d0e6c56b1b6',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-12 22:36:25','2021-12-12 22:36:25','2022-12-13 04:06:25'),('68d7a8f7dececffd82b666f04ee18a87bc616196d480bf8c7b52051c8197dd3d7b9d936efbfe5d48',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:39:53','2021-11-17 06:39:53','2022-11-17 12:09:53'),('69fd4cb46ef0a0a935d3f57c288c88aac2920ba00fe740623998d3863201634a7d0fff6e37b5cd52',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:48:36','2021-11-17 06:48:36','2022-11-17 12:18:36'),('6c85b6b812f3c7f77431f35b90a0bd6080bd87ab75c0edf50ba1fa6d6ac2479cdfe1c2462dd0ba45',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-28 23:50:18','2021-12-28 23:50:18','2022-12-29 05:20:18'),('6ee79b41b884f905e30706592d6ea38586949568bda1e505a50c0bf846aff9660fe7188b4b956692',6,1,'Personal Access Token','[\"user\"]',1,'2021-11-23 23:37:41','2021-11-23 23:37:41','2022-11-24 05:07:41'),('7c1afe0d82a627a1128fbc4105c0718c04b81e52b862233b353cd3c89b21d8c03254861b48d74a93',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-20 22:43:36','2021-12-20 22:43:36','2022-12-21 04:13:36'),('7ef8016a0d4d3f4e0bb136dd3e53c606a3dc0fdf8d9f871b575401494a7f2371dd136d901fee6b36',6,1,'Personal Access Token','[\"user\"]',1,'2021-11-24 03:12:59','2021-11-24 03:12:59','2022-11-24 08:42:59'),('8d16a26d69bcb50819b61529248e946c6840b89fc7738ac3add480e81c275c3fdc434d1d4dc65e12',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-06 02:29:51','2021-12-06 02:29:51','2022-12-06 07:59:51'),('8ee89a2585ff29821ffea8d50f82a018e0dd547b5991665870d9867eacef50b0f6a6a0c91cc6a97c',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-10 06:52:59','2021-12-10 06:52:59','2022-12-10 12:22:59'),('90bcd4988c9f44a66a4e0c31ac916982ae1aa6902c04027483d130efa1d025350df6a056ac0742be',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 03:24:43','2021-11-17 03:24:43','2022-11-17 08:54:43'),('9dafcddd23545992b89ff288c8a0692d7a5acb23051602e8208efcb494670efa13ff072619705a94',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:40:31','2021-11-17 06:40:31','2022-11-17 12:10:31'),('a20a2d5358fe058205724f4091a9bc0793cebc068c1114c3b4f03bf14bdd50f50bd22dcd51c57528',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 02:59:14','2021-11-17 02:59:14','2022-11-17 08:29:14'),('a5a9d1fbbd9d0509649dc107ec55cb6ab7cc40558dd2a7f195a5cb33a8a2e08dbb04e8454325b619',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:42:31','2021-11-17 06:42:31','2022-11-17 12:12:31'),('a5e4a7e752c495c2171ce5cea400df1a212eded620b9246c9e123233a5ca54f5c298bf71fdb185c6',1,1,'Personal Access Token','[\"admin\"]',1,'2021-12-06 02:38:21','2021-12-06 02:38:21','2022-12-06 08:08:21'),('abc00f6318cd67657bddc67cd3a40797bce42443a6a02d95f8bcaae0186b019cff328b3d5afd0d03',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-06 22:42:20','2021-12-06 22:42:20','2022-12-07 04:12:20'),('b8e5adada69f0094ae7729a8b361e606189dc1020467cd1c83aa2065b9552ff1b3452119a45640b0',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-17 06:40:13','2021-12-17 06:40:13','2022-12-17 12:10:13'),('bc296f1bdc1bc67d5e59d65c35c56aa6dd3fc7ad9f0173782a45d1b11a8ab09c77a523fe61f0022c',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-27 22:31:59','2021-12-27 22:31:59','2022-12-28 04:01:59'),('be61a14cbe3b07ebbf167184b27c8e6776b05019ee4341e01398ffe7c3ce647c3f221f48644919e2',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-07 23:30:52','2021-12-07 23:30:52','2022-12-08 05:00:52'),('c1cdb5786ab98979df663e6f894f20075196f2691191cab5053216e5af1aa67541be1cf841cb4cd3',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:38:57','2021-11-17 06:38:57','2022-11-17 12:08:57'),('c48da4c1a9126606b708a5beeded4d9731341ad96ee7013c2853b4d94a01ccfaa5cc30cb0a939b88',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-28 22:29:16','2021-12-28 22:29:16','2022-12-29 03:59:16'),('ca75c3f9e1e7cb55f0e0e484500bf2fc6f2536b9592b0cfd8e4be241651b3bc194e720cb1ed3b2fb',6,1,'Personal Access Token','[\"user\"]',1,'2021-11-24 03:04:47','2021-11-24 03:04:47','2022-11-24 08:34:47'),('d3678792183125593bcec2a569f475b8cb7b000ccd7cc03096ba308693438d5fde4c650f809c2119',1,3,'Personal Access Token','[\"admin\"]',0,'2022-06-24 01:18:17','2022-06-24 01:18:17','2023-06-24 06:48:17'),('d45fe7fa290804f41e276ba9385a9241fa12e0a9d65d253700ef459e3a300a06bc59cbd2202c0f3c',1,1,'Personal Access Token','[\"admin\"]',0,'2021-12-23 02:35:13','2021-12-23 02:35:13','2022-12-23 08:05:13'),('d57322cd05ab8e2e18055b2073c497aca2cca9f81dbb7835667f64c069a91595da3ecc62a9949f14',1,1,'Personal Access Token','[\"admin\"]',1,'2021-12-06 02:45:30','2021-12-06 02:45:30','2022-12-06 08:15:30'),('df55cf5558749685d009a5444616c1ee99df24f55629b7e2deaef78a42223e90a4f195dc200c26eb',1,3,'Personal Access Token','[\"admin\"]',0,'2022-02-04 06:26:15','2022-02-04 06:26:15','2023-02-04 11:56:15'),('f335616ae30a5315733caf70e377bb518476ba04bc9205b9c0589ae7754dc7d358301ef3c51f58bc',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:11:40','2021-11-17 06:11:40','2022-11-17 11:41:40'),('f76eaf4de5d47c5389844f006841a034044e1fedbfd172163641238511b6b319f28b9ac6eeef37e2',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 03:22:09','2021-11-17 03:22:09','2022-11-17 08:52:09'),('f92d576f93f8c63e7ccccbdf5cea6586742256a8ee5cf005baa37b29e026e8772d90743f474dbaa5',6,1,'Personal Access Token','[\"can_create\"]',0,'2021-11-17 06:37:42','2021-11-17 06:37:42','2022-11-17 12:07:42');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,NULL,'Laravel Personal Access Client','WTUY8pFaLIxpykGzGjpwMpyCliN1movN9qchBZyb',NULL,'http://localhost',1,0,0,'2021-11-16 01:08:11','2021-11-16 01:08:11'),(2,NULL,'Laravel Password Grant Client','QVNUCj6tBoSGbbqsV8gCrPwuEstbwj7sOwb4Jpuq','users','http://localhost',0,1,0,'2021-11-16 01:08:11','2021-11-16 01:08:11'),(3,NULL,'Laravel Personal Access Client','Xt1ApjNKgQX2fDwYSQ92biaE1bEAa1396BTg52Uy',NULL,'http://localhost',1,0,0,'2022-02-04 05:54:37','2022-02-04 05:54:37'),(4,NULL,'Laravel Password Grant Client','DbogRote3yBcn8IjeN6kFUP2QUTucqpHEatMDM6D','users','http://localhost',0,1,0,'2022-02-04 05:54:37','2022-02-04 05:54:37');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (1,1,'2021-11-16 01:08:11','2021-11-16 01:08:11'),(2,3,'2022-02-04 05:54:37','2022-02-04 05:54:37');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,1,'product 1','1637739268.jpg','2021-11-24 02:04:28','2021-11-24 02:04:28'),(4,2,'product 2','1637739548.jpeg','2021-11-24 02:09:08','2021-11-24 02:09:08'),(5,3,'product 3','1637739728.jpeg','2021-11-24 02:12:08','2021-11-24 02:12:08'),(6,2,'product 43','1637739843.jpg','2021-11-24 02:14:03','2021-12-13 00:24:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_order` int NOT NULL DEFAULT '0',
  `priority` tinyint NOT NULL DEFAULT '0',
  `type` tinyint NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,1,'Add Discount page to open','cacacdvdvsdvdd',0,2,1,1,'2021-12-10 05:18:40','2021-12-29 00:20:05','2021-12-29 00:20:05'),(2,2,'eeee66696s66','fqwfwqf',1,2,1,1,'2021-12-10 05:27:27','2022-02-07 03:27:05',NULL),(3,2,'dsdsd6','sdgdsg',3,2,1,1,'2021-12-10 05:29:36','2021-12-31 05:45:09',NULL),(4,3,'fbdfb','bfdbdfbdfbfdb',0,2,0,1,'2021-12-13 06:31:00','2021-12-29 02:30:22',NULL),(5,2,'vdsvdsv','dsvdsv',2,1,0,1,'2021-12-13 06:35:12','2022-02-06 23:32:13',NULL),(6,1,'test','vwevwe',0,2,1,1,'2021-12-23 05:20:28','2021-12-29 00:33:21','2021-12-29 00:33:21'),(7,1,'wegewgweg','wegewg',0,2,1,1,'2021-12-23 05:44:17','2021-12-29 00:37:50','2021-12-29 00:37:50'),(8,1,'gergergerg','ererg',0,2,1,1,'2021-12-23 05:45:50','2021-12-29 00:38:52','2021-12-29 00:38:52'),(9,1,'dfdfhd','fhdfhfdh',0,2,1,1,'2021-12-23 05:48:11','2021-12-29 00:41:58','2021-12-29 00:41:58'),(10,1,'fdhh','dfhdfh',0,2,1,1,'2021-12-23 05:48:30','2021-12-29 00:42:41','2021-12-29 00:42:41'),(11,1,'kkuk','yukyuk',0,2,1,1,'2021-12-23 05:48:55','2021-12-29 00:43:10','2021-12-29 00:43:10'),(12,1,'gwegweg','wegweg',0,1,1,1,'2021-12-23 06:03:56','2021-12-29 00:57:44','2021-12-29 00:57:44'),(13,1,'4444444444444444','ttttt',0,2,1,1,'2021-12-23 06:04:39','2021-12-29 01:01:35','2021-12-29 01:01:35'),(14,2,'new','new',5,1,1,1,'2021-12-31 06:58:43','2022-02-06 23:43:01',NULL),(15,2,'Add Discount page to open 1','as',4,2,2,1,'2021-12-31 07:10:42','2022-02-06 23:46:02',NULL),(16,2,'aDAD','adAD',6,2,2,1,'2021-12-31 07:12:50','2022-02-07 00:04:33',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todolist_category`
--

DROP TABLE IF EXISTS `todolist_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `todolist_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todolist_category`
--

LOCK TABLES `todolist_category` WRITE;
/*!40000 ALTER TABLE `todolist_category` DISABLE KEYS */;
INSERT INTO `todolist_category` VALUES (1,'Backlog','2021-12-08 06:19:28','2021-12-08 06:19:53'),(2,'In Progress','2021-12-08 06:19:43','2021-12-08 06:19:56'),(3,'Tested','2021-12-08 06:19:47','2021-12-08 06:19:59'),(4,'Done','2021-12-08 06:19:50','2021-12-08 06:20:02');
/*!40000 ALTER TABLE `todolist_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'author',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','abc@email',NULL,'$2y$10$rv44qDGiX80aZkKZASoR6euy1JPEyqtAoA0Exp.eTJR3wsPiLq4hq','admin',NULL,'2021-11-16 01:07:04','2021-11-16 01:07:04'),(2,'Tosif','abqc@gmail.com',NULL,'$2y$10$g5m/0kAQpv.5oN/GpjPwJ.ltMWcvEkUJ5WF6tTXwr/r1DWTq7aQ7i','user',NULL,'2021-11-16 04:24:01','2021-11-16 04:24:01'),(4,'qwe','abc1@email',NULL,'$2y$10$VkvbLYRocQAZ46EgNQPQuuKXdzx/wmzJldCVv7EwYXUuExKS27IKW','user',NULL,'2021-11-16 06:23:45','2021-11-16 06:23:45'),(5,'mike','mike@gmail.com',NULL,'$2y$10$k6Nxdt7MZTrXBjX.lAp3O.Ndyuz5hG4eB24EfhQkJONJWkhFJIMLy','user',NULL,'2021-11-17 02:16:12','2021-11-17 02:16:12'),(6,'qwe','mikey@gmail.com',NULL,'$2y$10$P9zeh8gakXXspTaozOTmLu/1Yp1he2Mfx1vV5bIlMAmnAXOn5wVau','user',NULL,'2021-11-17 02:18:25','2021-11-17 02:18:25');
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

-- Dump completed on 2022-06-24 12:21:19
