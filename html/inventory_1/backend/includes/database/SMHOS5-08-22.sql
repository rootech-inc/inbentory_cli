-- MariaDB dump 10.19  Distrib 10.6.7-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: SMHOS
-- ------------------------------------------------------
-- Server version	10.6.7-MariaDB-3

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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `func` text NOT NULL,
  `query` text NOT NULL,
  `source` text NOT NULL,
  `time_exe` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=586 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `admin.currency`
--

DROP TABLE IF EXISTS `admin.currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin.currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` text NOT NULL,
  `symbol` text NOT NULL,
  `short` text DEFAULT NULL,
  `active` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin.currency`
--

LOCK TABLES `admin.currency` WRITE;
/*!40000 ALTER TABLE `admin.currency` DISABLE KEYS */;
INSERT INTO `admin.currency` VALUES (2,'Ghana Cedi','₵','GHS',1),(6,'Nigerian Naira','₦','NGN',0),(7,'West African CFA','CFA','XOF',0);
/*!40000 ALTER TABLE `admin.currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_payment_methods`
--

DROP TABLE IF EXISTS `admin_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  UNIQUE KEY `admin_payment_methods_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_payment_methods`
--

LOCK TABLES `admin_payment_methods` WRITE;
/*!40000 ALTER TABLE `admin_payment_methods` DISABLE KEYS */;
INSERT INTO `admin_payment_methods` VALUES (1,'Cash',1),(2,'Mobile Money',1),(3,'Card',1);
/*!40000 ALTER TABLE `admin_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barcode`
--

DROP TABLE IF EXISTS `barcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) NOT NULL,
  `barcode` text DEFAULT NULL,
  `item_desc` text DEFAULT NULL,
  `item_desc1` text DEFAULT NULL,
  `retail` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent` varchar(200) DEFAULT 'master',
  PRIMARY KEY (`id`),
  KEY `relation_with_product` (`item_code`),
  CONSTRAINT `relation_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barcode`
--

LOCK TABLES `barcode` WRITE;
/*!40000 ALTER TABLE `barcode` DISABLE KEYS */;
INSERT INTO `barcode` VALUES (12,1000000001,'6034000351255','BAL~MALT 500ML','BAL~MALT 500ML',4.00,'2022-04-15 08:31:25','master'),(13,1000000002,'6033000340085','Storm Energy LG','Storm Energy LG',4.50,'2022-04-15 08:36:39','master'),(14,1000000003,'6164003477475','LUCOZADE SML','LUCOZADE SML',8.00,'2022-05-07 10:25:58','master'),(15,1000000004,'026169059802','SWEET SODA','SWEET SODA',35.00,'2022-05-07 10:29:03','master');
/*!40000 ALTER TABLE `barcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bill_hold`
--

DROP TABLE IF EXISTS `bill_hold`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_hold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_grp` text NOT NULL,
  `bill_date` date DEFAULT curdate(),
  `item_barcode` varchar(255) DEFAULT NULL,
  `item_qty` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_hold`
--

LOCK TABLES `bill_hold` WRITE;
/*!40000 ALTER TABLE `bill_hold` DISABLE KEYS */;
INSERT INTO `bill_hold` VALUES (8,'4C9i','2022-03-16','123456789','1.00'),(9,'smlu','2022-03-16','123456789','1.00');
/*!40000 ALTER TABLE `bill_hold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bill_pmt`
--

DROP TABLE IF EXISTS `bill_pmt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_pmt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill` int(11) DEFAULT NULL,
  `bill_amount` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `amount_balance` decimal(10,2) DEFAULT NULL,
  `trans_date` date DEFAULT curdate(),
  `trans_time` time DEFAULT curtime(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bill_pmt_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_pmt`
--

LOCK TABLES `bill_pmt` WRITE;
/*!40000 ALTER TABLE `bill_pmt` DISABLE KEYS */;
/*!40000 ALTER TABLE `bill_pmt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bill_trans`
--

DROP TABLE IF EXISTS `bill_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'BILL NUMBER',
  `mach` int(11) DEFAULT NULL COMMENT 'machine number',
  `clerk` text DEFAULT NULL,
  `bill_number` int(11) NOT NULL,
  `item_barcode` text NOT NULL,
  `trans_type` text NOT NULL COMMENT 'Transaction Type',
  `retail_price` decimal(10,2) DEFAULT NULL COMMENT 'Value of transaction',
  `date_added` date DEFAULT curdate(),
  `time_added` time DEFAULT curtime(),
  `item_qty` decimal(10,2) DEFAULT 0.00,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `bill_amt` decimal(10,2) DEFAULT 0.00,
  `item_desc` varchar(255) DEFAULT NULL,
  `tax_grp` varchar(255) DEFAULT 'NULL',
  `tax_rate` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=322 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_trans`
--

LOCK TABLES `bill_trans` WRITE;
/*!40000 ALTER TABLE `bill_trans` DISABLE KEYS */;
INSERT INTO `bill_trans` VALUES (296,1,'Demo User',1,'5489654','i',25.00,'2022-03-25','03:58:04',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(297,1,'Demo User',1,'not_item','C',NULL,'2022-03-25','04:02:42',0.00,0.00,0.00,'bill_canced','NULL',NULL,0),(298,1,'Demo User',1,'5489658','i',15.00,'2022-04-07','13:47:28',1.00,0.00,15.00,'Non Taxable','DEFAULT',0,0),(299,1,'Demo User',1,'5489654','i',25.00,'2022-04-21','09:41:21',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(300,1,'Demo User',1,'5489654','i',25.00,'2022-04-21','09:45:44',2.00,1.94,48.50,'Mango Juice','VAT &amp; COVID19',4,0),(301,1,'Demo User',1,'5489654','i',25.00,'2022-04-21','09:47:55',5.00,4.85,121.25,'Mango Juice','VAT &amp; COVID19',4,0),(302,1,'Demo User',1,'5489654','i',25.00,'2022-04-21','09:53:42',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(303,1,'Demo User',1,'5489654','i',25.00,'2022-04-21','09:54:01',5.00,4.85,121.25,'Mango Juice','VAT &amp; COVID19',4,0),(304,1,'Demo User',1,'not_item','C',NULL,'2022-04-21','16:13:44',0.00,0.00,0.00,'bill_canced','NULL',NULL,0),(305,1,'Demo User',2,'5489654','i',25.00,'2022-04-21','16:14:03',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(306,1,'Demo User',2,'DICOUNT','D',NULL,'2022-04-21','16:14:20',0.00,0.00,10.00,'DISCOUNT','NULL',NULL,0),(307,1,'Demo User',2,'not_item','C',NULL,'2022-04-21','16:14:36',0.00,0.00,0.00,'bill_canced','NULL',NULL,0),(308,1,'Demo User',3,'5489658','i',15.00,'2022-04-21','16:14:39',1.00,0.00,15.00,'Non Taxable','DEFAULT',0,0),(309,1,'Demo User',3,'5489654','i',25.00,'2022-04-21','16:14:42',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(310,1,'Demo User',3,'DICOUNT','D',NULL,'2022-04-21','16:15:02',0.00,0.00,10.00,'DISCOUNT','NULL',NULL,0),(311,1,'Demo User',1,'5489654','i',25.00,'2022-05-05','13:29:04',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(312,1,'Demo User',1,'5489654','i',25.00,'2022-05-05','13:29:14',2.00,1.94,48.50,'Mango Juice','VAT &amp; COVID19',4,0),(313,1,'Demo User',1,'DICOUNT','D',NULL,'2022-05-05','13:30:09',0.00,0.00,5.00,'DISCOUNT','NULL',NULL,0),(314,1,'Demo User',1,'not_item','C',NULL,'2022-05-05','13:30:37',0.00,0.00,0.00,'bill_canced','NULL',NULL,0),(315,1,'Demo User',2,'5489654','i',25.00,'2022-05-05','13:30:40',1.00,0.97,24.25,'Mango Juice','VAT &amp; COVID19',4,0),(316,1,'Demo User',2,'5489658','i',15.00,'2022-05-05','13:30:42',1.00,0.00,15.00,'Non Taxable','DEFAULT',0,0),(317,1,'Demo User',2,'DICOUNT','D',NULL,'2022-05-05','13:31:00',0.00,0.00,5.00,'DISCOUNT','NULL',NULL,0),(318,1,'Demo User',2,'PAYMENT','P',NULL,'2022-05-05','13:31:52',0.00,0.00,0.00,'cash','NULL',NULL,0),(319,1,'Demo User',3,'5489658','i',15.00,'2022-05-05','13:32:03',1.00,0.00,15.00,'Non Taxable','DEFAULT',0,0),(320,1,'Demo User',3,'5489658','i',15.00,'2022-05-05','13:32:03',1.00,0.00,15.00,'Non Taxable','DEFAULT',0,0),(321,1,'Demo User',3,'DICOUNT','D',NULL,'2022-05-05','13:32:18',0.00,0.00,5.00,'DISCOUNT','NULL',NULL,0);
/*!40000 ALTER TABLE `bill_trans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_booked` date NOT NULL DEFAULT current_timestamp(),
  `bill` int(11) DEFAULT NULL COMMENT 'Bill number to group a bill bookings',
  `fac_category` text DEFAULT 'none',
  `facility` text DEFAULT 'none',
  `quantity` int(11) DEFAULT 0,
  `receptionist` text DEFAULT 'unknown',
  `time_booked` time DEFAULT curtime(),
  `paid` int(11) DEFAULT 0,
  `checkin` int(11) DEFAULT NULL,
  `cust_first_name` text DEFAULT 'unknown',
  `cust_last_name` text DEFAULT 'unknown',
  `cust_phone` text DEFAULT '+233 xx xxx xxxx',
  `cust_email` text DEFAULT 'none',
  `cost` decimal(50,2) DEFAULT NULL,
  `days` text DEFAULT '0',
  `arri_date` text DEFAULT 'not set',
  `dep_date` text DEFAULT '\'not set\'',
  `arr_time` time DEFAULT NULL,
  `dep_time` time DEFAULT NULL,
  `fac_number` int(11) DEFAULT 0,
  `hold` int(11) DEFAULT 0,
  `date_modified` text DEFAULT 'not modified',
  `time_modified` text DEFAULT 'not modified',
  `modified_by` text DEFAULT 'not modified',
  `special_request` text DEFAULT 'None',
  `refund` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookins_trans`
--

DROP TABLE IF EXISTS `bookins_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookins_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_number` int(11) DEFAULT NULL,
  `machine` text NOT NULL,
  `owner` text NOT NULL,
  `facility` text DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `price` text DEFAULT NULL,
  `tax_desc` text DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL,
  `taxable_amount` text DEFAULT NULL,
  `total_amount` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `sub` text DEFAULT 'None',
  `check_in_date` text DEFAULT NULL,
  `check_out_date` text DEFAULT NULL,
  `start_time` text DEFAULT 'not effective',
  `end_time` text DEFAULT 'not effective',
  `session` text DEFAULT 'unknown',
  `date_booked` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookins_trans`
--

LOCK TABLES `bookins_trans` WRITE;
/*!40000 ALTER TABLE `bookins_trans` DISABLE KEYS */;
INSERT INTO `bookins_trans` VALUES (61,1,'1','411','Test Facility',NULL,1,'100.00','DEFAULT',0,'100.00','100.00',1,'101','2021-09-21','2021-09-22','not effective','not effective','Full','2021-09-21'),(62,2,'1','411','Test Facility',NULL,2,'100.00','DEFAULT',0,'200.00','200.00',1,'101','2021-09-21','2021-09-23','not effective','not effective','Full','2021-09-21'),(63,3,'1','411','Test Facility',NULL,3,'100.00','DEFAULT',0,'300.00','300.00',1,'101','2021-09-21','2021-09-24','not effective','not effective','Full','2021-09-22'),(64,1,'1','411','Test Facility',NULL,1,'100.00','DEFAULT',0,'100.00','100.00',1,'101','2021-09-21','2021-09-22','not effective','not effective','Full','2021-09-22'),(65,1,'1','411','Test Facility',NULL,1,'100.00','DEFAULT',0,'50.00','50.00',1,'101','2021-09-22','2021-09-23','not effective','not effective','Half','2021-09-22'),(66,1,'1','411','Test Facility',NULL,1,'100.00','DEFAULT',0,'100.00','100.00',1,'101','2021-09-21','2021-09-22','not effective','not effective','Full','2021-09-23'),(67,1,'1','411','Test Facility',NULL,1,'100.00','DEFAULT',0,'50.00','50.00',1,'101','2021-09-22','2021-09-23','not effective','not effective','Half','2021-09-23'),(68,1,'1','411','Test Facility',NULL,2,'100.00','DEFAULT',0,'100.00','100.00',1,'101','2021-09-23','2021-09-25','not effective','not effective','Half','2021-09-23'),(69,1,'1','411','Test Facility',NULL,3,'100.00','DEFAULT',0,'150.00','150.00',1,'101','2021-09-23','2021-09-26','not effective','not effective','Half','2021-09-23'),(70,1,'1','411','Test Facility',NULL,2,'100.00','DEFAULT',0,'100.00','100.00',1,'101','2021-09-23','2021-09-25','not effective','not effective','Half','2021-09-23');
/*!40000 ALTER TABLE `bookins_trans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `check_in`
--

DROP TABLE IF EXISTS `check_in`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `check_in` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `booking` int(11) DEFAULT NULL,
  `receptionist` text NOT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `check_in`
--

LOCK TABLES `check_in` WRITE;
/*!40000 ALTER TABLE `check_in` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_in` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `check_out`
--

DROP TABLE IF EXISTS `check_out`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `check_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking` int(11) DEFAULT NULL COMMENT 'ID of booking checked out',
  `receptionist` text DEFAULT NULL COMMENT 'Receptionist who checked customer out',
  `time_checked_out` time DEFAULT current_timestamp(),
  `date_recorded` date DEFAULT current_timestamp() COMMENT 'date checked out',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `check_out`
--

LOCK TABLES `check_out` WRITE;
/*!40000 ALTER TABLE `check_out` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_out` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clerk`
--

DROP TABLE IF EXISTS `clerk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clerk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clerk_code` text NOT NULL,
  `clerk_key` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `clerk_name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clerk`
--

LOCK TABLES `clerk` WRITE;
/*!40000 ALTER TABLE `clerk` DISABLE KEYS */;
INSERT INTO `clerk` VALUES (2,'411','4b82ba17481acc7ad9fec620bd960b80','2021-05-15 19:49:45','Demo User');
/*!40000 ALTER TABLE `clerk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL DEFAULT 0,
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
  `vat_code` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'XCELL MART',1,'PO BOX 174','#6 Cocoa Street','Ghana','Accra','+233 54 631 0011','test@domain.com','CTJ7V','Welcome TO Hotel Magic\r\nStay Safe on you stay here','VT0125451');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compare`
--

DROP TABLE IF EXISTS `compare`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_target` int(11) NOT NULL,
  `checkin_target` int(11) NOT NULL,
  `daily_earning` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compare`
--

LOCK TABLES `compare` WRITE;
/*!40000 ALTER TABLE `compare` DISABLE KEYS */;
INSERT INTO `compare` VALUES (1,10,12,'100');
/*!40000 ALTER TABLE `compare` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disc_mast`
--

DROP TABLE IF EXISTS `disc_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disc_mast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` int(11) NOT NULL,
  `desc` text DEFAULT `rate`,
  `disc_uni` text DEFAULT md5(`rate`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disc_mast`
--

LOCK TABLES `disc_mast` WRITE;
/*!40000 ALTER TABLE `disc_mast` DISABLE KEYS */;
INSERT INTO `disc_mast` VALUES (1,3,'Mini','eccbc87e4b5ce2fe28308fd9f2a7baf3'),(2,5,'Med','e4da3b7fbbce2345d7772b0674a318d5'),(3,10,'Mega','d3d9446802a44259755d38e6d163e820');
/*!40000 ALTER TABLE `disc_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `err_code`
--

DROP TABLE IF EXISTS `err_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `err_code` (
  `code` text DEFAULT NULL COMMENT 'Error Code',
  `description` text DEFAULT NULL COMMENT 'Error Description\n',
  UNIQUE KEY `err_code_code_uindex` (`code`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `err_code`
--

LOCK TABLES `err_code` WRITE;
/*!40000 ALTER TABLE `err_code` DISABLE KEYS */;
INSERT INTO `err_code` VALUES ('CLI25X','Category has not been configured, make sure you have a category'),('GFX54V','Can\'t Determine network state. The network state is supposed to be Network Or Local, anything else will throw an error'),('GTMX57','Trying to perform an action that does not make sense. User is trying to move to a next tax item, but thats the last'),('GTMX5K','Trying to perform an action that does not make sense. User is trying to move to a previous tax item, but thats the first'),('500VG','Could not delete row in database'),('0INS9','Could not insert row in database');
/*!40000 ALTER TABLE `err_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stage` text DEFAULT NULL,
  `day` text NOT NULL,
  `month` text NOT NULL,
  `year` text NOT NULL,
  `title` text NOT NULL,
  `reason` text NOT NULL,
  `amount` double(10,2) NOT NULL,
  `time_created` time NOT NULL DEFAULT current_timestamp(),
  `owner` text NOT NULL,
  `stat` int(11) NOT NULL DEFAULT 0,
  `date_approved` date DEFAULT NULL,
  `time_approved` time DEFAULT NULL,
  `approver` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (3,'month','06','Jan','2021','Water','Bought two poly tanks of water',120.56,'23:58:10','root',0,NULL,NULL,NULL),(4,'month','06','Feb','2021',' Mower','Rented A Mower to weed around',200.00,'00:01:53','root',0,NULL,NULL,NULL),(5,'month','06','Mar','2021','Carpenter','Paid for gate fixing',20.00,'00:04:19','root',0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facCat`
--

DROP TABLE IF EXISTS `facCat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facCat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text DEFAULT NULL,
  `tax_group` int(11) DEFAULT NULL,
  `owner` text DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `charges_type` text DEFAULT 'd',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facCat`
--

LOCK TABLES `facCat` WRITE;
/*!40000 ALTER TABLE `facCat` DISABLE KEYS */;
INSERT INTO `facCat` VALUES (33,'Bed Room','General category for all bed rooms',0,'root','2021-10-05 08:03:15','x'),(34,'Auditorium','None',0,'root','2021-10-08 06:50:46','x');
/*!40000 ALTER TABLE `facCat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `sub_category` int(11) NOT NULL,
  `description` text NOT NULL,
  `short_description` text NOT NULL,
  `kids` int(11) NOT NULL,
  `adults` int(11) NOT NULL,
  `booking_unit` char(1) NOT NULL,
  `booking_type` char(1) NOT NULL,
  `break_fast` text NOT NULL,
  `lunch` text NOT NULL,
  `supper` text NOT NULL,
  `tax_group` int(11) NOT NULL,
  `price_wo_tax` text NOT NULL,
  `price_w_tax` text NOT NULL,
  `floor` int(11) NOT NULL,
  `owner` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `util_WiFi` text NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (41,33,11,'101','101',0,0,'d','0','0','0','0',0,'101','101.00',1,'root','2021-10-15 13:20:43','1:1');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility_sub`
--

DROP TABLE IF EXISTS `facility_sub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `util_Wi-Fi` int(11) DEFAULT 0,
  `util_Smart Tv` int(11) DEFAULT 0,
  `util_Air Condition` int(11) DEFAULT 0,
  `util_DSTV Access` int(11) DEFAULT 0,
  `util_Ironing Board` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility_sub`
--

LOCK TABLES `facility_sub` WRITE;
/*!40000 ALTER TABLE `facility_sub` DISABLE KEYS */;
/*!40000 ALTER TABLE `facility_sub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_group`
--

DROP TABLE IF EXISTS `item_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'group id',
  `group_name` text NOT NULL COMMENT 'name of group',
  `date_created` date DEFAULT curdate(),
  `time_added` time DEFAULT curtime(),
  `owner` text NOT NULL COMMENT 'who created group',
  `grp_uni` text DEFAULT NULL,
  `modified_by` text DEFAULT NULL,
  `date_modified` date DEFAULT curdate(),
  `time_modified` time DEFAULT curtime(),
  `shrt_name` text DEFAULT NULL,
  `tax_grp` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_group_grp_uni_uindex` (`grp_uni`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_group`
--

LOCK TABLES `item_group` WRITE;
/*!40000 ALTER TABLE `item_group` DISABLE KEYS */;
INSERT INTO `item_group` VALUES (1,'Taxable','2021-12-25','18:29:56','james','c4ca4238a0b923820dcc509a6f75849b','Demo User','2022-04-06','07:25:53','Taxable',0),(2,'Non-Tax','2021-12-25','18:29:56','james','c81e728d9d4c2f636f067f89cc14862c',NULL,'2022-03-27','10:01:36',NULL,0),(3,'Discont','2021-12-25','18:29:56','james','c81e728d9d4c2f63f067f89cc14862c',NULL,'2022-03-27','10:01:36',NULL,0),(4,'Non-Disc','2021-12-25','18:29:56','james','c81e728d9d4c2f14862c',NULL,'2022-03-27','10:01:36',NULL,0),(5,'xxxx','2021-12-25','18:29:56','james','c81e728d9d4c2f14862csdf',NULL,'2022-03-27','10:01:36',NULL,0),(6,'Drinks','2022-03-27','10:09:21','Demo User','2e0523fc740b10436cc10da617372ac0','Demo User','2022-03-27','10:09:21','Drinks',0),(7,'Rice','2022-03-27','10:10:18','Demo User','9d91dc55807b436882dee73c7a8746dc','Demo User','2022-04-06','07:25:24','Rice',0),(8,'Meat','2022-04-07','10:11:08','Demo User','f881e30c8dc8901e919d6b7fe2c0fcaf','Demo User','2022-04-07','10:11:08','Meat',0);
/*!40000 ALTER TABLE `item_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_group_sub`
--

DROP TABLE IF EXISTS `item_group_sub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_group_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `owner` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `route_with_parent` (`parent`),
  CONSTRAINT `route_with_parent` FOREIGN KEY (`parent`) REFERENCES `item_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_group_sub`
--

LOCK TABLES `item_group_sub` WRITE;
/*!40000 ALTER TABLE `item_group_sub` DISABLE KEYS */;
INSERT INTO `item_group_sub` VALUES (9,7,'Plain Rice','2022-04-03 10:05:29','Demo User'),(10,7,'Friens','2022-04-07 06:16:46','Demo User'),(11,6,'Non Alcohol','2022-04-07 06:42:58','Demo User'),(12,6,'Alcoholic','2022-04-07 06:43:09','Demo User'),(13,7,'Roastes','2022-04-07 10:10:52','Demo User'),(14,8,'Fried','2022-04-07 10:11:30','Demo User'),(15,7,'hjhgs','2022-04-07 10:11:48','Demo User'),(16,6,'Energy','2022-04-15 08:32:48','Demo User');
/*!40000 ALTER TABLE `item_group_sub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_master`
--

DROP TABLE IF EXISTS `items_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique id of each item',
  `barcode` text NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` int(11) NOT NULL DEFAULT 0 COMMENT 'id of tax this belongs oo',
  `item_grp` text NOT NULL,
  `item_uni` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_master`
--

LOCK TABLES `items_master` WRITE;
/*!40000 ALTER TABLE `items_master` DISABLE KEYS */;
INSERT INTO `items_master` VALUES (6,'123456789','Test Item',120.00,150.00,12,'1','hello_world');
/*!40000 ALTER TABLE `items_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loc`
--

DROP TABLE IF EXISTS `loc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_id` char(3) DEFAULT NULL,
  `loc_desc` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loc`
--

LOCK TABLES `loc` WRITE;
/*!40000 ALTER TABLE `loc` DISABLE KEYS */;
INSERT INTO `loc` VALUES (1,'001','Main Shop'),(2,'002','Northern Branch'),(3,'003','Eastern Branch'),(4,'004','Southern Branch'),(5,'005','Western Branch'),(6,'006','Main Warehouse');
/*!40000 ALTER TABLE `loc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packaging`
--

DROP TABLE IF EXISTS `packaging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `packaging` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packaging`
--

LOCK TABLES `packaging` WRITE;
/*!40000 ALTER TABLE `packaging` DISABLE KEYS */;
INSERT INTO `packaging` VALUES (1,'2022-04-07 06:45:31','PCS'),(2,'2022-04-07 06:45:31','CTN'),(3,'2022-04-07 06:45:31','KG'),(4,'2022-04-07 06:45:31','LTR');
/*!40000 ALTER TABLE `packaging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_bill` int(11) DEFAULT NULL,
  `amount_paid` decimal(50,2) DEFAULT NULL,
  `date_paid` date NOT NULL DEFAULT current_timestamp(),
  `time_paid` time NOT NULL DEFAULT current_timestamp(),
  `level` text DEFAULT 'Primary',
  `method` text DEFAULT 'unknown',
  `booking` int(11) DEFAULT NULL,
  `refund` int(11) DEFAULT 0,
  `master` int(11) DEFAULT 0,
  `p_count` int(11) DEFAULT 1,
  `customer` text DEFAULT NULL,
  `receptionist` text DEFAULT NULL,
  `facility` text DEFAULT NULL,
  `amount_owed` decimal(50,2) DEFAULT NULL,
  `amount_balance` decimal(50,2) DEFAULT NULL,
  `card_type` text DEFAULT NULL,
  `card_number` int(11) DEFAULT NULL,
  `momo_carrier` text DEFAULT NULL,
  `momo_sender` text DEFAULT NULL,
  `momo_number` text DEFAULT NULL,
  `momo_trans_id` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (26,1,100.00,'2021-09-21','23:03:33','Primary','Cash',NULL,0,0,1,'Jane Doe','411',NULL,100.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,2,200.00,'2021-09-21','23:04:40','Primary','Cash',NULL,0,0,1,'Information','411',NULL,200.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,3,300.00,'2021-09-22','08:03:52','Primary','Cash',NULL,0,0,1,'Jane Doe','411',NULL,300.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,1,50.00,'2021-09-22','08:22:29','Primary','Cash',NULL,0,0,1,'Accurate Bill Customer','411',NULL,50.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,1,300.00,'2021-09-23','04:50:51','Primary','Cash',NULL,0,0,1,'Jane Doe','411',NULL,100.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po_hd`
--

DROP TABLE IF EXISTS `po_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `po_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_no` char(13) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `location` char(3) NOT NULL,
  `suppler` char(13) NOT NULL,
  `type` char(13) NOT NULL,
  `remarks` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `owner` text DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edited_by` char(30) DEFAULT NULL,
  `approved_by` char(13) DEFAULT NULL,
  `approved_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po_hd`
--

LOCK TABLES `po_hd` WRITE;
/*!40000 ALTER TABLE `po_hd` DISABLE KEYS */;
INSERT INTO `po_hd` VALUES (1000001,'PO1000001',0,'003','SOO1','direct','First Purchase Order Edited',3000.00,'Demo User','2022-05-07 10:30:54','2022-05-08 12:05:12','Demo User',NULL,NULL),(1000002,'PO1000002',1,'003','SOO1','direct','Second PO',1560.00,'Demo User','2022-05-08 16:10:18','2022-05-08 16:05:44','Demo User','Demo User','2022-05-08 17:55:47'),(1000003,'PO1000003',0,'001','SOO1','direct','Calculate and update done',0.00,'Demo User','2022-05-08 20:43:00','0000-00-00 00:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `po_hd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po_trans`
--

DROP TABLE IF EXISTS `po_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `po_trans` (
  `prefix` varchar(2) NOT NULL DEFAULT 'PO',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `owner` char(13) DEFAULT NULL,
  `date_added` date DEFAULT curdate(),
  `pack_desc` text DEFAULT NULL,
  `packing` text DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `parent` char(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000020 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po_trans`
--

LOCK TABLES `po_trans` WRITE;
/*!40000 ALTER TABLE `po_trans` DISABLE KEYS */;
INSERT INTO `po_trans` VALUES ('PO',1000003,'1000000004','026169059802','SWEET SODA','Demo User','2022-05-07','CTN','20 in 1',15.00,150.00,2250.00,'PO1000001'),('PO',1000004,'1000000003','6164003477475','LUCOZADE SML','Demo User','2022-05-07','CTN','12 in 1',12.00,50.00,600.00,'PO1000001'),('PO',1000012,'1000000001','6034000351255','BAL~MALT 500ML','Demo User','2022-05-07','CTN','1*8  PCS',15.00,10.00,150.00,'PO1000001'),('PO',1000013,'1000000001','6034000351255','BAL~MALT 500ML','Demo User','2022-05-08','CTN','1*8  PCS',15.00,20.00,300.00,'PO1000002'),('PO',1000014,'1000000002','6033000340085','Storm Energy LG','Demo User','2022-05-08','CTN','1*8 PCS',19.00,15.00,285.00,'PO1000002'),('PO',1000015,'1000000003','6164003477475','LUCOZADE SML','Demo User','2022-05-08','CTN','12 in 1',25.00,35.00,875.00,'PO1000002'),('PO',1000016,'1000000004','026169059802','SWEET SODA','Demo User','2022-05-08','CTN','20 in 1',10.00,10.00,100.00,'PO1000002'),('PO',1000017,'1000000001','6034000351255','BAL~MALT 500ML','Demo User','2022-05-08','CTN','1*8  PCS',10.00,10.00,100.00,NULL),('PO',1000018,'1000000002','6033000340085','Storm Energy LG','Demo User','2022-05-08','CTN','1*8 PCS',150.00,12.00,1800.00,NULL),('PO',1000019,'1000000003','6164003477475','LUCOZADE SML','Demo User','2022-05-08','CTN','12 in 1',190.00,2.00,380.00,NULL);
/*!40000 ALTER TABLE `po_trans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `price_change`
--

DROP TABLE IF EXISTS `price_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) NOT NULL,
  `price_type` text DEFAULT NULL,
  `previous` decimal(10,2) NOT NULL,
  `current` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `price_change_with_product` (`item_code`),
  CONSTRAINT `price_change_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_change`
--

LOCK TABLES `price_change` WRITE;
/*!40000 ALTER TABLE `price_change` DISABLE KEYS */;
INSERT INTO `price_change` VALUES (12,1000000001,'r',4.00,4.00),(13,1000000002,'r',4.50,4.50),(14,1000000003,'r',8.00,8.00),(15,1000000004,'r',35.00,35.00);
/*!40000 ALTER TABLE `price_change` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_disc`
--

DROP TABLE IF EXISTS `prod_disc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_disc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_code` text NOT NULL,
  `rate` decimal(10,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_disc`
--

LOCK TABLES `prod_disc` WRITE;
/*!40000 ALTER TABLE `prod_disc` DISABLE KEYS */;
INSERT INTO `prod_disc` VALUES (1,'3',0),(2,'1000000001',0);
/*!40000 ALTER TABLE `prod_disc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_expiry`
--

DROP TABLE IF EXISTS `prod_expiry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_expiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_expiry`
--

LOCK TABLES `prod_expiry` WRITE;
/*!40000 ALTER TABLE `prod_expiry` DISABLE KEYS */;
INSERT INTO `prod_expiry` VALUES (4,'1000000001','2022-04-29'),(5,'1000000002','2022-04-15'),(6,'1000000003','2023-10-18'),(7,'1000000004','2023-05-26');
/*!40000 ALTER TABLE `prod_expiry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_mast`
--

DROP TABLE IF EXISTS `prod_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_mast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_grp` int(11) NOT NULL,
  `item_uni` text DEFAULT md5(`desc`),
  `barcode` text NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` int(11) NOT NULL DEFAULT 0 COMMENT 'id of tax this belongs oo',
  `discount` int(11) DEFAULT 0,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `stock_type` int(11) NOT NULL DEFAULT 1,
  `prev_retail` decimal(10,2) DEFAULT 0.00,
  `sub_grp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode` (`barcode`) USING HASH,
  KEY `stock_typ` (`stock_type`),
  CONSTRAINT `stock_typ` FOREIGN KEY (`stock_type`) REFERENCES `stock_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1000000005 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_mast`
--

LOCK TABLES `prod_mast` WRITE;
/*!40000 ALTER TABLE `prod_mast` DISABLE KEYS */;
INSERT INTO `prod_mast` VALUES (1000000002,1,'358e5db66e4c49db8c131302c259c777','5489654','Mango Juice',20.00,25.00,1,1,3.00,1,0.00,NULL),(1000000003,2,'asdasdasdasdasdasd','5489658','Non Taxable',10.00,15.00,0,0,0.00,1,0.00,NULL),(1000000004,3,'sdfsd','5489655','Disc Item',10.00,15.00,13,1,2.00,1,0.00,NULL);
/*!40000 ALTER TABLE `prod_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_master`
--

DROP TABLE IF EXISTS `prod_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_master` (
  `item_code` int(11) NOT NULL AUTO_INCREMENT,
  `item_uni` text DEFAULT md5(`item_desc`),
  `group` int(11) NOT NULL,
  `sub_group` int(11) NOT NULL,
  `supplier` text DEFAULT NULL,
  `barcode` text NOT NULL COMMENT 'barcode of item',
  `item_desc` text NOT NULL COMMENT 'item description',
  `item_desc1` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax` int(11) NOT NULL DEFAULT 0 COMMENT 'id of tax this belongs oo',
  `packing` int(11) NOT NULL DEFAULT 0 COMMENT 'Packaging',
  `stock_type` int(11) NOT NULL DEFAULT 0 COMMENT 'Stock Type',
  `expiry_date` char(10) DEFAULT NULL COMMENT 'Date Expiring',
  `special_price` int(11) NOT NULL DEFAULT 0 COMMENT 'Special Price',
  `discount` int(11) DEFAULT 0,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `prev_retail` decimal(10,2) DEFAULT 0.00,
  `owner` varchar(200) DEFAULT 'master',
  `created_at` date DEFAULT curdate(),
  `edited_at` date DEFAULT curdate(),
  `edited_by` varchar(200) DEFAULT `owner`,
  PRIMARY KEY (`item_code`),
  UNIQUE KEY `barcode` (`barcode`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1000000005 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_master`
--

LOCK TABLES `prod_master` WRITE;
/*!40000 ALTER TABLE `prod_master` DISABLE KEYS */;
INSERT INTO `prod_master` VALUES (1000000001,'df60f5911f03a92062129e0bc16e9288',6,11,'SOO1','6034000351255','BAL~MALT 500ML','BAL~MALT 500ML',0.00,4.00,1,1,1,'2022-04-29',0,0,0.00,4.00,'Demo User','2022-04-15','2022-04-18','Demo User'),(1000000002,'a4f3921e75affc6a45f003533f4ade4c',6,11,'SOO1','6033000340085','Storm Energy LG','Storm Energy LG',0.00,4.50,1,1,1,'2022-04-27',0,0,0.00,4.00,'Demo User','2022-04-15','2022-04-18','Demo User'),(1000000003,'c9a680f76f9e25c95ad6a17c74dd9e72',6,11,'SOO1','6164003477475','LUCOZADE SML','LUCOZADE SML',0.00,8.00,1,1,1,'2023-10-18',0,0,0.00,8.00,'Demo User','2022-05-07','2022-05-07','Demo User'),(1000000004,'a7a2845c4faf25ad5ef01dbcc55bef1d',6,11,'SOO1','026169059802','SWEET SODA','SWEET SODA',0.00,35.00,1,1,1,'2023-05-26',0,0,0.00,35.00,'Demo User','2022-05-07','2022-05-07','Demo User');
/*!40000 ALTER TABLE `prod_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_packing`
--

DROP TABLE IF EXISTS `prod_packing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_packing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `pack_id` char(3) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `purpose` int(11) DEFAULT 1,
  `pack_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_packing`
--

LOCK TABLES `prod_packing` WRITE;
/*!40000 ALTER TABLE `prod_packing` DISABLE KEYS */;
INSERT INTO `prod_packing` VALUES (8,'1000000001','1',1.00,1,' PCS'),(9,'1000000001','2',8.00,2,'1*8  PCS'),(10,'1000000002','1',1.00,1,'PCS'),(11,'1000000002','2',8.00,2,'1*8 PCS'),(12,'1000000003','1',1.00,1,'PCS'),(13,'1000000003','2',12.00,2,'12 in 1'),(14,'1000000004','1',1.00,1,' PCS'),(15,'1000000004','2',20.00,2,' 20 in 1');
/*!40000 ALTER TABLE `prod_packing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund`
--

DROP TABLE IF EXISTS `refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_number` int(11) DEFAULT NULL,
  `amount_refund` decimal(50,2) DEFAULT NULL,
  `receptionist` text DEFAULT NULL,
  `date` date DEFAULT curtime(),
  `reason` text DEFAULT NULL,
  `customer` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund`
--

LOCK TABLES `refund` WRITE;
/*!40000 ALTER TABLE `refund` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` double(6,2) DEFAULT NULL,
  `stage` text DEFAULT NULL,
  `day` text DEFAULT NULL,
  `month` text DEFAULT NULL,
  `year` text DEFAULT NULL,
  `db_user` text DEFAULT current_user(),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,254.00,'month','15','Jan','2021','root@localhost'),(3,150.00,'month','10','Feb','2021','root@localhost'),(4,850.00,'month','2','Mar','2021','root@localhost');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `qty` decimal(14,2) NOT NULL,
  `ob_qty` decimal(14,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (1,'1000000010','001',10.00,10.00);
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_master`
--

DROP TABLE IF EXISTS `stock_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `desc` (`desc`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_master`
--

LOCK TABLES `stock_master` WRITE;
/*!40000 ALTER TABLE `stock_master` DISABLE KEYS */;
INSERT INTO `stock_master` VALUES (1,'Stock'),(2,'Non Stock'),(3,'Discontinued');
/*!40000 ALTER TABLE `stock_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_type`
--

DROP TABLE IF EXISTS `stock_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_type`
--

LOCK TABLES `stock_type` WRITE;
/*!40000 ALTER TABLE `stock_type` DISABLE KEYS */;
INSERT INTO `stock_type` VALUES (1,'Regulary'),(2,'Non-Stock'),(3,'Discountinued'),(4,'Expiry');
/*!40000 ALTER TABLE `stock_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `tax_group` int(11) NOT NULL,
  `owner` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (11,33,'Single Bed Room',0,'root','2021-10-05 08:03:49'),(12,34,'Church Services',0,'root','2021-10-08 06:51:03'),(13,34,'Conference',0,'root','2021-10-08 06:51:13');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supp_mast`
--

DROP TABLE IF EXISTS `supp_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supp_mast` (
  `supp_id` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `supp_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supp_mast`
--

LOCK TABLES `supp_mast` WRITE;
/*!40000 ALTER TABLE `supp_mast` DISABLE KEYS */;
INSERT INTO `supp_mast` VALUES ('SOO1','2022-04-07 06:35:05','CASH PURCHASE');
/*!40000 ALTER TABLE `supp_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_master`
--

DROP TABLE IF EXISTS `tax_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `rate` int(11) NOT NULL,
  `date_added` text NOT NULL,
  `time_added` text NOT NULL,
  `owner` text NOT NULL,
  `active` int(11) DEFAULT 0 COMMENT '1 means tax is enabled, 0 means not',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_master`
--

LOCK TABLES `tax_master` WRITE;
/*!40000 ALTER TABLE `tax_master` DISABLE KEYS */;
INSERT INTO `tax_master` VALUES (0,'DEFAULT',0,'2021-06-28','03:09:04','root',1),(1,'VAT &amp; COVID19',4,'10-10-2021','19:10:13pm','root',1),(13,'Latest Tax',19,'10-10-2021','19:10:13pm','root',1);
/*!40000 ALTER TABLE `tax_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_access_level`
--

DROP TABLE IF EXISTS `user_access_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_access_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `access_level` int(11) NOT NULL DEFAULT 0,
  `Perm_dashboard` int(11) NOT NULL DEFAULT 0 COMMENT 'permission for dashboard',
  `Perm_company_setup` int(11) NOT NULL DEFAULT 0 COMMENT 'permission for company setup view',
  `Perm_tax` int(11) NOT NULL DEFAULT 0,
  `Perm_payment_method` int(11) NOT NULL DEFAULT 0,
  `Perm_backup` int(11) NOT NULL DEFAULT 0,
  `Perm_modify_company` int(11) NOT NULL DEFAULT 0,
  `Perm_facility_management` int(11) NOT NULL DEFAULT 0,
  `Perm_user_management` int(11) NOT NULL DEFAULT 0,
  `Perm_reports` int(11) NOT NULL DEFAULT 0,
  `Perm_booking` int(11) NOT NULL DEFAULT 0,
  `Perm_check_in` int(11) NOT NULL DEFAULT 0,
  `Perm_payment` int(11) NOT NULL DEFAULT 0,
  `owner` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_level` (`access_level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_access_level`
--

LOCK TABLES `user_access_level` WRITE;
/*!40000 ALTER TABLE `user_access_level` DISABLE KEYS */;
INSERT INTO `user_access_level` VALUES (1,'Administrator',1,1,1,1,1,1,1,1,1,1,1,1,1,'root'),(2,'test',0,1,1,0,0,0,1,0,0,0,0,0,0,'root');
/*!40000 ALTER TABLE `user_access_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login_log`
--

DROP TABLE IF EXISTS `user_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `func` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `time` time DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login_log`
--

LOCK TABLES `user_login_log` WRITE;
/*!40000 ALTER TABLE `user_login_log` DISABLE KEYS */;
INSERT INTO `user_login_log` VALUES (1,1,'root','logout','2021-04-04 06:06:58','01:15:58'),(2,1,'root','login','2021-04-04 06:09:03','01:15:58'),(3,1,'root','login','2021-04-04 15:15:57','01:15:58'),(4,1,'root','logout','2021-04-04 16:16:47','01:15:58'),(5,1,'root','login','2021-04-04 16:17:51','01:15:58'),(6,1,'root','logout','2021-04-04 16:41:27','01:15:58'),(7,1,'root','login','2021-04-04 16:41:31','01:15:58'),(8,1,'root','logout','2021-04-04 17:01:19','01:15:58'),(9,1,'root','login','2021-04-04 17:01:26','01:15:58'),(10,1,'root','logout','2021-04-04 17:13:38','01:15:58'),(11,1,'root','login','2021-04-04 17:13:43','01:15:58'),(12,1,'root','logout','2021-04-04 17:16:15','01:15:58'),(13,1,'root','login','2021-04-04 17:16:19','01:15:58'),(14,1,'root','logout','2021-04-04 17:16:55','01:15:58'),(15,1,'root','login','2021-04-04 17:17:00','01:15:58'),(16,1,'root','login','2021-04-05 14:28:07','01:15:58'),(17,1,'root','logout','2021-04-05 19:11:46','01:15:58'),(18,1,'root','login','2021-04-05 20:40:54','01:15:58'),(19,1,'root','logout','2021-04-05 20:42:15','01:15:58'),(20,1,'root','login','2021-04-05 20:42:28','01:15:58'),(21,1,'root','logout','2021-04-05 20:42:31','01:15:58'),(22,3,'jane','login','2021-04-05 21:01:26','01:15:58'),(23,3,'jane','logout','2021-04-05 21:01:52','01:15:58'),(24,1,'root','login','2021-04-05 21:02:03','01:15:58'),(25,1,'root','logout','2021-04-06 00:41:31','01:15:58'),(26,1,'root','login','2021-04-06 01:10:10','01:15:58'),(27,1,'root','logout','2021-04-06 01:17:24','01:17:24'),(28,1,'root','login','2021-04-06 05:32:34','05:32:34'),(29,1,'root','login','2021-04-07 06:06:17','06:06:17'),(30,1,'root','logout','2021-04-07 08:59:55','08:59:55'),(31,1,'root','login','2021-04-07 22:54:27','22:54:27'),(32,1,'root','logout','2021-04-09 08:48:18','08:48:18'),(33,1,'root','login','2021-04-09 08:49:00','08:49:00'),(34,1,'root','logout','2021-04-09 09:02:27','09:02:27'),(35,1,'root','login','2021-04-09 11:34:12','11:34:12'),(36,1,'root','login','2021-04-12 11:21:56','11:21:56'),(37,1,'root','login','2021-04-14 22:57:32','22:57:32'),(38,1,'root','login','2021-04-14 23:19:46','23:19:46'),(39,1,'root','logout','2021-04-14 23:19:53','23:19:53'),(40,1,'root','login','2021-04-15 12:32:17','12:32:17'),(41,1,'root','logout','2021-04-20 18:55:27','18:55:27'),(42,1,'root','login','2021-04-20 19:19:51','19:19:51'),(43,1,'root','logout','2021-04-20 19:19:53','19:19:53'),(44,1,'root','login','2021-04-20 20:15:44','20:15:44'),(45,1,'root','logout','2021-04-20 20:19:45','20:19:45'),(46,1,'root','login','2021-04-20 20:25:24','20:25:24'),(47,1,'root','logout','2021-04-20 20:41:30','20:41:30'),(48,1,'root','login','2021-04-20 20:42:34','20:42:34'),(49,1,'root','logout','2021-04-20 20:54:39','20:54:39'),(50,1,'root','login','2021-04-20 20:54:46','20:54:46'),(51,1,'root','logout','2021-04-20 21:49:23','21:49:23'),(52,1,'root','login','2021-04-24 00:59:27','00:59:27'),(53,1,'root','login','2021-04-26 09:31:55','09:31:55'),(54,1,'root','logout','2021-04-29 11:42:47','11:42:47'),(55,1,'root','login','2021-04-29 11:43:28','11:43:28'),(56,1,'root','logout','2021-04-29 18:36:27','18:36:27'),(57,1,'root','login','2021-04-29 18:36:33','18:36:33'),(58,1,'root','logout','2021-04-30 08:00:41','08:00:41'),(59,1,'root','login','2021-04-30 09:59:53','09:59:53'),(60,1,'mina','logout','2021-05-06 00:46:37','00:46:37'),(61,1,'root','login','2021-05-06 01:01:02','01:01:02'),(62,1,'root','logout','2021-05-06 06:46:05','06:46:05'),(63,1,'root','login','2021-05-06 06:46:34','06:46:34'),(64,1,'root','logout','2021-05-06 06:47:41','06:47:41'),(65,1,'root','login','2021-05-06 10:03:40','10:03:40'),(66,1,'root','logout','2021-05-06 10:06:10','10:06:10'),(67,1,'root','login','2021-05-06 10:06:15','10:06:15'),(68,1,'root','logout','2021-05-06 12:34:16','12:34:16'),(69,1,'root','login','2021-05-21 08:15:05','08:15:05'),(70,1,'root','login','2021-05-24 06:47:47','06:47:47'),(71,1,'root','login','2021-05-24 09:50:39','09:50:39'),(72,1,'root','login','2021-05-24 14:48:37','14:48:37'),(73,1,'root','logout','2021-05-24 17:37:08','17:37:08'),(74,1,'root','login','2021-05-24 17:37:18','17:37:18'),(75,1,'root','login','2021-06-14 19:44:28','19:44:28'),(76,1,'root','logout','2021-06-14 19:46:15','19:46:15'),(77,1,'root','login','2021-06-15 06:33:16','06:33:16'),(78,1,'root','logout','2021-06-15 06:35:28','06:35:28'),(79,1,'root','login','2021-06-15 11:42:59','11:42:59'),(80,1,'root','login','2021-06-15 18:45:51','18:45:51'),(81,1,'root','login','2021-06-15 23:41:05','23:41:05'),(82,1,'root','login','2021-06-22 18:21:47','18:21:47'),(83,1,'root','login','2021-06-23 05:05:10','05:05:10'),(84,1,'root','login','2021-06-23 06:28:44','06:28:44'),(85,1,'root','login','2021-06-24 15:29:33','15:29:33'),(86,1,'root','login','2021-06-25 04:10:03','04:10:03'),(87,1,'root','logout','2021-06-25 05:46:28','05:46:28'),(88,1,'root','login','2021-06-25 05:47:11','05:47:11'),(89,1,'root','logout','2021-06-25 05:48:08','05:48:08'),(90,1,'root','login','2021-06-25 08:42:04','08:42:04'),(91,1,'root','login','2021-06-28 03:04:35','03:04:35'),(92,1,'root','login','2021-06-28 18:44:40','18:44:40'),(93,1,'root','login','2021-07-07 06:11:22','06:11:22'),(94,1,'root','logout','2021-07-07 10:00:59','10:00:59'),(95,1,'root','login','2021-07-07 19:06:08','19:06:08'),(96,1,'root','login','2021-07-10 06:43:16','06:43:16'),(97,1,'root','login','2021-07-30 06:12:54','06:12:54'),(98,1,'root','login','2021-08-03 05:47:50','05:47:50'),(99,1,'root','login','2021-08-03 23:48:00','23:48:00'),(100,1,'root','logout','2021-08-10 20:37:07','20:37:07'),(101,1,'root','logout','2021-08-10 20:52:52','20:52:52'),(102,1,'root','logout','2021-08-10 20:55:34','20:55:34'),(103,1,'root','logout','2021-08-10 20:57:06','20:57:06'),(104,1,'root','login','2021-08-10 20:57:16','20:57:16'),(105,1,'root','logout','2021-08-10 20:58:09','20:58:09'),(106,1,'root','login','2021-09-13 05:12:49','05:12:49'),(107,1,'root','login','2021-09-19 19:31:08','19:31:08'),(108,1,'root','login','2021-09-22 00:36:10','00:36:10'),(109,1,'root','login','2021-09-23 06:20:42','06:20:42'),(110,1,'root','login','2021-09-24 05:45:46','05:45:46'),(111,1,'root','login','2021-09-26 21:52:05','21:52:05'),(112,1,'root','login','2021-09-29 05:51:02','05:51:02'),(113,1,'root','login','2021-09-30 13:55:05','13:55:05'),(114,1,'root','login','2021-10-04 01:19:57','01:19:57'),(115,1,'root','login','2021-10-04 14:22:07','14:22:07'),(116,1,'root','logout','2021-10-04 14:23:28','14:23:28'),(117,1,'root','login','2021-10-04 14:23:56','14:23:56'),(118,1,'root','login','2021-10-07 13:11:23','13:11:23'),(119,1,'root','login','2021-10-08 05:04:26','05:04:26'),(120,1,'root','login','2021-10-10 06:41:17','06:41:17'),(121,1,'root','login','2021-10-12 06:43:38','06:43:38'),(122,1,'root','login','2021-10-13 20:35:22','20:35:22'),(123,1,'root','login','2021-10-14 23:19:50','23:19:50'),(124,1,'root','login','2021-10-15 10:57:43','10:57:43'),(125,1,'root','login','2021-10-15 11:02:48','11:02:48'),(126,1,'root','login','2021-10-28 18:31:20','18:31:20'),(127,1,'root','login','2022-03-12 00:12:48','00:12:48'),(128,1,'root','login','2022-03-12 00:13:51','00:13:51');
/*!40000 ALTER TABLE `user_login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_task`
--

DROP TABLE IF EXISTS `user_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `task_status` int(11) NOT NULL DEFAULT 1,
  `task` text NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_task`
--

LOCK TABLES `user_task` WRITE;
/*!40000 ALTER TABLE `user_task` DISABLE KEYS */;
INSERT INTO `user_task` VALUES (4,'root',0,'2','Password Reset','2021-04-06 00:38:13'),(5,'root',0,'2','Password Reset','2021-04-06 00:41:23'),(6,'Mina',1,'1','Initialize your account','2021-04-09 08:59:04');
/*!40000 ALTER TABLE `user_task` ENABLE KEYS */;
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

-- Dump completed on 2022-05-08 20:45:36
