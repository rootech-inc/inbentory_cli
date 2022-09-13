-- MariaDB dump 10.19  Distrib 10.6.9-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: SMHOS
-- ------------------------------------------------------
-- Server version	10.6.9-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin.company_setup`
--

DROP TABLE IF EXISTS `admin.company_setup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin.company_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` text NOT NULL DEFAULT 'DEMO',
  `currency` int(11) NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text DEFAULT NULL,
  `tax_code` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin.company_setup`
--

LOCK TABLES `admin.company_setup` WRITE;
/*!40000 ALTER TABLE `admin.company_setup` DISABLE KEYS */;
INSERT INTO `admin.company_setup` VALUES (1,'HOTEL TEST',1,'BOX 174','#6 Cocoa Street','Ghana','Accra','+233 54 631 0011','test@domain.com','CTJ7V','Welcome TO Hotel Magic\r\nStay Safe on you stay here');
/*!40000 ALTER TABLE `admin.company_setup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `username` text NOT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `password` text NOT NULL,
  `ual` int(11) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `online` int(11) DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `owner` text NOT NULL,
  `db_access` text NOT NULL DEFAULT 'current_user()',
  `last_login_time` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','root','leet','$2y$10$bweYjvpJUBq1mFyTPgpEe.FV5hYpysqoHu/zGjnjnMT339aRaIzyu',1,'2021-04-03 23:38:14',1,'127.0.0.1','mo','0',NULL),(3,'jane','Jane','Doe','$2y$10$tzQO6UhCtb6/WDc0qy/x7.D2U7AFrvb3XmIKoN.HKRtUrDFOr1oTa',1,'2021-04-05 20:41:44',0,'::1','root','current_user()',NULL);
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

-- Dump completed on 2022-09-13 22:12:46
