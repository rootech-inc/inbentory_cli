-- MariaDB dump 10.19  Distrib 10.6.9-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 192.168.2.245    Database: SMHOS
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=362 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `doc_trans`
--

DROP TABLE IF EXISTS `doc_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_type` char(3) NOT NULL,
  `entry_no` varchar(13) NOT NULL,
  `trans_func` char(3) NOT NULL,
  `created_by` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `grn_hd`
--

DROP TABLE IF EXISTS `grn_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_no` varchar(13) DEFAULT NULL,
  `po_number` varchar(13) NOT NULL,
  `loc` varchar(3) NOT NULL,
  `date_received` date NOT NULL,
  `supplier` varchar(12) NOT NULL,
  `remarks` text DEFAULT NULL,
  `invoice_num` varchar(13) NOT NULL,
  `invoice_amt` decimal(10,2) NOT NULL,
  `tax` int(11) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_num` (`invoice_num`),
  KEY `grn_hd_tax_master_id_fk` (`tax`),
  CONSTRAINT `grn_hd_tax_master_id_fk` FOREIGN KEY (`tax`) REFERENCES `tax_master` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1000004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grn_trans`
--

DROP TABLE IF EXISTS `grn_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_no` char(13) DEFAULT NULL,
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `owner` char(13) DEFAULT NULL,
  `date_added` date DEFAULT curdate(),
  `pack_desc` text DEFAULT NULL,
  `packing` text DEFAULT NULL,
  `pack_um` decimal(10,2) DEFAULT 0.00,
  `qty` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `prod_cost` decimal(10,2) DEFAULT 0.00,
  `ret_amt` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grn_trans_06_19_22`
--

DROP TABLE IF EXISTS `grn_trans_06_19_22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn_trans_06_19_22` (
  `id` int(11) NOT NULL DEFAULT 0,
  `entry_no` char(13) DEFAULT NULL,
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `owner` char(13) DEFAULT NULL,
  `date_added` date DEFAULT curdate(),
  `pack_desc` text DEFAULT NULL,
  `packing` text DEFAULT NULL,
  `pack_um` decimal(10,2) DEFAULT 0.00,
  `qty` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `prod_cost` decimal(10,2) DEFAULT 0.00,
  `ret_amt` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_buttons`
--

DROP TABLE IF EXISTS `item_buttons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `button_index` int(11) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `grn` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000013 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `po_trans`
--

DROP TABLE IF EXISTS `po_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `po_trans` (
  `prefix` varchar(2) NOT NULL DEFAULT 'PO',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` char(13) DEFAULT NULL,
  `line` int(11) NOT NULL DEFAULT 0,
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `packing` text DEFAULT NULL,
  `pack_desc` text DEFAULT NULL,
  `pack_um` decimal(10,2) DEFAULT 0.00,
  `qty` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `date_added` date DEFAULT curdate(),
  `owner` char(13) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000289 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `prod_supplier`
--

DROP TABLE IF EXISTS `prod_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_supplier` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) DEFAULT NULL,
  `supplier_code` text DEFAULT NULL,
  `level` int(11) DEFAULT 0,
  PRIMARY KEY (`sp_id`),
  KEY `prod_supplier_prod` (`item_code`),
  CONSTRAINT `prod_supplier_prod` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='Suppliers for each product';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `supp_mast`
--

DROP TABLE IF EXISTS `supp_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supp_mast` (
  `supp_id` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `supp_name` varchar(255) DEFAULT NULL,
  `tax_grp` int(11) DEFAULT 0,
  KEY `supp_mast_tax_master_id_fk` (`tax_grp`),
  CONSTRAINT `supp_mast_tax_master_id_fk` FOREIGN KEY (`tax_grp`) REFERENCES `tax_master` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_buttons`
--

DROP TABLE IF EXISTS `system_buttons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_buttons` (
  `button_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` text DEFAULT NULL,
  `sub_module` text DEFAULT NULL,
  `sub_sub_module` text DEFAULT NULL,
  `descr` text DEFAULT NULL,
  `elem_id` text DEFAULT NULL,
  `elem_name` text DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `target_id` text DEFAULT NULL COMMENT 'if there is a target div this will target it on button invoking',
  PRIMARY KEY (`button_id`),
  UNIQUE KEY `elem_id` (`elem_id`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8mb4 COMMENT='This will hold buttons of sensitve parts of the system';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `type` varchar(20) DEFAULT NULL,
  `attr` char(3) NOT NULL,
  `cls` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_master_attr_uindex` (`attr`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tax_trans`
--

DROP TABLE IF EXISTS `tax_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc` char(3) DEFAULT NULL,
  `entry_no` varchar(13) DEFAULT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-13 17:23:59
