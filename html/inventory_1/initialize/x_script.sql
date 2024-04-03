-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: venta
-- ------------------------------------------------------
-- Server version	8.0.32

use `u560949065_venta`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `func` text NOT NULL,
  `query` text NOT NULL,
  `source` text NOT NULL,
  `time_exe` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=586 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin.company_setup`
--

DROP TABLE IF EXISTS `admin.company_setup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin.company_setup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `c_name` text NOT NULL,
  `currency` int NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text,
  `tax_code` text,
  `footer` text,
  `code` char(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin.currency`
--

DROP TABLE IF EXISTS `admin.currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin.currency` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descr` text NOT NULL,
  `symbol` text NOT NULL,
  `short` text,
  `active` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_payment_methods`
--

DROP TABLE IF EXISTS `admin_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_payment_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text,
  `status` int DEFAULT '1',
  UNIQUE KEY `admin_payment_methods_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `barcode`
--

DROP TABLE IF EXISTS `barcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barcode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_code` int NOT NULL,
  `barcode` text,
  `item_desc` text,
  `item_desc1` text,
  `retail` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent` varchar(200) DEFAULT 'master',
  PRIMARY KEY (`id`),
  KEY `relation_with_product` (`item_code`),
  CONSTRAINT `relation_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_header`
--

DROP TABLE IF EXISTS `bill_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_header` (
  `mach_no` int DEFAULT NULL,
  `clerk` text,
  `bill_no` int DEFAULT NULL,
  `pmt_type` text,
  `gross_amt` decimal(10,2) DEFAULT '0.00',
  `disc_rate` decimal(10,2) DEFAULT '0.00',
  `disc_amt` decimal(10,0) DEFAULT '0',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `amt_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bill_date` date DEFAULT (curdate()),
  `amt_bal` decimal(10,2) DEFAULT (0.00),
  `bill_time` time DEFAULT (curtime()),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id` int NOT NULL AUTO_INCREMENT,
  `billRef` text,
  `taxable_amt` decimal(10,2) DEFAULT (0.00),
  `non_taxable_amt` decimal(10,2) DEFAULT (0.00),
  `shift` int NOT NULL,
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_type` text,
  `customer` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_history_header`
--

DROP TABLE IF EXISTS `bill_history_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_history_header` (
  `mach_no` int DEFAULT NULL,
  `clerk` text,
  `bill_no` int DEFAULT NULL,
  `pmt_type` text,
  `gross_amt` decimal(10,2) DEFAULT '0.00',
  `disc_rate` decimal(10,2) DEFAULT '0.00',
  `disc_amt` decimal(10,0) DEFAULT '0',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `amt_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bill_date` date DEFAULT (curdate()),
  `amt_bal` decimal(10,2) DEFAULT (0.00),
  `bill_time` time DEFAULT (curtime()),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id` int NOT NULL DEFAULT '0',
  `billRef` text,
  `taxable_amt` decimal(10,2) DEFAULT (0.00),
  `non_taxable_amt` decimal(10,2) DEFAULT (0.00),
  `shift` int NOT NULL,
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_type` text,
  `customer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_history_trans`
--

DROP TABLE IF EXISTS `bill_history_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_history_trans` (
  `id` int NOT NULL COMMENT 'BILL NUMBER',
  `mach` int DEFAULT NULL COMMENT 'machine number',
  `clerk` text,
  `bill_number` int NOT NULL,
  `item_barcode` text NOT NULL,
  `trans_type` text NOT NULL COMMENT 'Transaction Type',
  `retail_price` decimal(10,2) DEFAULT NULL COMMENT 'Value of transaction',
  `date_added` date DEFAULT (curdate()),
  `time_added` time DEFAULT (curtime()),
  `item_qty` decimal(10,2) DEFAULT '0.00',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `bill_amt` decimal(10,2) DEFAULT '0.00',
  `item_desc` varchar(255) DEFAULT NULL,
  `tax_grp` varchar(255) DEFAULT 'NULL',
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int DEFAULT NULL,
  `selected` int DEFAULT '0',
  `billRef` text,
  `gfund` decimal(10,2) DEFAULT (0.00),
  `nhis` decimal(10,2) DEFAULT (0.00),
  `covid` decimal(10,2) DEFAULT (0.00),
  `vat` decimal(10,2) DEFAULT (0.00),
  `tax_code` text,
  `shift` int NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT (0.00),
  `discount` decimal(10,2) DEFAULT (0.00),
  `discount_rate` decimal(10,2) DEFAULT (0.00),
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_time` date DEFAULT (curtime()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_hld_tr`
--

DROP TABLE IF EXISTS `bill_hld_tr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_hld_tr` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_group` char(4) DEFAULT NULL,
  `barcode` text,
  `qty` decimal(10,2) DEFAULT (0.00),
  `tran_date` date DEFAULT (curdate()),
  `tran_time` time DEFAULT (curtime()),
  `clerk` int NOT NULL,
  `billed` int DEFAULT (0),
  `billRef` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_hold`
--

DROP TABLE IF EXISTS `bill_hold`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_hold` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_grp` char(4) NOT NULL,
  `bill_date` date DEFAULT (curdate()),
  `item_barcode` varchar(255) DEFAULT NULL,
  `item_qty` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_pmt`
--

DROP TABLE IF EXISTS `bill_pmt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_pmt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill` int DEFAULT NULL,
  `bill_amount` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `amount_balance` decimal(10,2) DEFAULT NULL,
  `trans_date` date DEFAULT (curdate()),
  `trans_time` time DEFAULT (curtime()),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bill_pmt_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_tax_tran`
--

DROP TABLE IF EXISTS `bill_tax_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_tax_tran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_date` date NOT NULL,
  `clerk_code` int NOT NULL,
  `mech_no` int NOT NULL,
  `bill_no` int NOT NULL,
  `tran_code` int NOT NULL,
  `tran_qty` int NOT NULL,
  `taxableAmt` decimal(10,2) DEFAULT (0.00),
  `tax_code` varchar(3) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT (0.00),
  `billRef` text,
  `shift` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_trans`
--

DROP TABLE IF EXISTS `bill_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bill_trans` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'BILL NUMBER',
  `mach` int DEFAULT NULL COMMENT 'machine number',
  `clerk` text,
  `bill_number` int NOT NULL,
  `item_barcode` text NOT NULL,
  `trans_type` text NOT NULL COMMENT 'Transaction Type',
  `retail_price` decimal(10,2) DEFAULT NULL COMMENT 'Value of transaction',
  `date_added` date DEFAULT (curdate()),
  `time_added` time DEFAULT (curtime()),
  `item_qty` decimal(10,2) DEFAULT '0.00',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `bill_amt` decimal(10,2) DEFAULT '0.00',
  `item_desc` varchar(255) DEFAULT NULL,
  `tax_grp` varchar(255) DEFAULT 'NULL',
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int DEFAULT NULL,
  `selected` int DEFAULT '0',
  `billRef` text,
  `gfund` decimal(10,2) DEFAULT (0.00),
  `nhis` decimal(10,2) DEFAULT (0.00),
  `covid` decimal(10,2) DEFAULT (0.00),
  `vat` decimal(10,2) DEFAULT (0.00),
  `tax_code` text,
  `shift` int NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT (0.00),
  `discount` decimal(10,2) DEFAULT (0.00),
  `discount_rate` decimal(10,2) DEFAULT (0.00),
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_time` time DEFAULT (curtime()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clerk`
--

DROP TABLE IF EXISTS `clerk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clerk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clerk_code` text NOT NULL,
  `clerk_key` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clerk_name` text NOT NULL,
  `user_grp` int NOT NULL,
  `status` int DEFAULT (1) COMMENT 'If 1, clerk is active, else clerk is not active',
  `pin` char(4) NOT NULL,
  `token` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clerk_pk` (`pin`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comp_setup`
--

DROP TABLE IF EXISTS `comp_setup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comp_setup` (
  `id` int NOT NULL DEFAULT '0',
  `c_name` text NOT NULL,
  `currency` int NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text,
  `tax_code` text,
  `footer` text,
  `code` char(3) NOT NULL,
  UNIQUE KEY `comp_setup_pk` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company` (
  `id` int NOT NULL DEFAULT '0',
  `c_name` text NOT NULL,
  `currency` int NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text,
  `tax_code` text,
  `footer` text,
  `vat_code` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer_bill_tran`
--

DROP TABLE IF EXISTS `customer_bill_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_bill_tran` (
  `cust_no` int NOT NULL,
  `billRef` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` char(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `cust_no` char(10) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `total_transactions` decimal(18,6) DEFAULT '0.000000',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `customers_pk2` (`email`),
  UNIQUE KEY `customers_pk` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=10004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customers_trans`
--

DROP TABLE IF EXISTS `customers_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers_trans` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `transaction_date` date NOT NULL DEFAULT (curdate()),
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) DEFAULT NULL,
  `items_purchased` text,
  `transaction_notes` text,
  `entry_no` text,
  `user` int DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `customer_id` (`customer_id`),
  KEY `customers_trans_clerk_id_fk` (`user`),
  CONSTRAINT `customers_trans_clerk_id_fk` FOREIGN KEY (`user`) REFERENCES `clerk` (`id`),
  CONSTRAINT `customers_trans_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disc_mast`
--

DROP TABLE IF EXISTS `disc_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disc_mast` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rate` int NOT NULL,
  `desc` text,
  `disc_uni` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doc_trans`
--

DROP TABLE IF EXISTS `doc_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doc_trans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doc_type` char(3) NOT NULL,
  `entry_no` varchar(13) NOT NULL,
  `trans_func` char(3) NOT NULL,
  `created_by` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eod_serial`
--

DROP TABLE IF EXISTS `eod_serial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eod_serial` (
  `sales_date` date NOT NULL,
  `gross` decimal(20,1) DEFAULT NULL,
  `deductions` decimal(20,1) DEFAULT NULL,
  `tax` decimal(20,1) DEFAULT NULL,
  `net` decimal(20,1) DEFAULT NULL,
  `eod_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `clerk_code` int DEFAULT NULL,
  `status` int DEFAULT (0),
  `shift` int NOT NULL,
  PRIMARY KEY (`sales_date`,`shift`),
  KEY `eod_serial_clerk_id_fk` (`clerk_code`),
  CONSTRAINT `eod_serial_clerk_id_fk` FOREIGN KEY (`clerk_code`) REFERENCES `clerk` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `err_code`
--

DROP TABLE IF EXISTS `err_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `err_code` (
  `code` char(255) DEFAULT NULL COMMENT 'Error Code',
  `description` text COMMENT 'Error Description\n',
  UNIQUE KEY `err_code_code_uindex` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evat_transactions`
--

DROP TABLE IF EXISTS `evat_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evat_transactions` (
  `billRef` char(255) NOT NULL,
  `ysdcid` text NOT NULL,
  `ysdcitems` int NOT NULL,
  `ysdcmrc` text NOT NULL,
  `ysdcmrctim` text NOT NULL,
  `ysdcrecnum` text NOT NULL,
  `ysdctime` text NOT NULL,
  `ysdcintdata` char(255) NOT NULL,
  `ysdcregsig` char(255) NOT NULL,
  `qr_code` text NOT NULL,
  UNIQUE KEY `billRef` (`billRef`),
  UNIQUE KEY `ysdcintdata` (`ysdcintdata`),
  UNIQUE KEY `ysdcregsig` (`ysdcregsig`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grn_hd`
--

DROP TABLE IF EXISTS `grn_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn_hd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_no` varchar(13) DEFAULT NULL,
  `po_number` varchar(13) NOT NULL,
  `loc` varchar(3) NOT NULL,
  `date_received` date NOT NULL,
  `supplier` varchar(12) NOT NULL,
  `remarks` text,
  `invoice_num` varchar(13) NOT NULL,
  `invoice_amt` decimal(10,2) NOT NULL,
  `tax` int DEFAULT '0',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` text,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_num` (`invoice_num`),
  UNIQUE KEY `grn_hd_pk` (`entry_no`),
  KEY `grn_hd_tax_master_id_fk` (`tax`)
) ENGINE=InnoDB AUTO_INCREMENT=1000043 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grn_trans`
--

DROP TABLE IF EXISTS `grn_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn_trans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_no` char(13) DEFAULT NULL,
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `owner` char(13) DEFAULT NULL,
  `date_added` date DEFAULT (curdate()),
  `pack_desc` text,
  `packing` text,
  `pack_um` decimal(10,2) DEFAULT '0.00',
  `qty` decimal(10,2) DEFAULT '0.00',
  `cost` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `status` int DEFAULT '0',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `prod_cost` decimal(10,2) DEFAULT '0.00',
  `ret_amt` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history_header`
--

DROP TABLE IF EXISTS `history_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_header` (
  `mach_no` int DEFAULT NULL,
  `clerk` text,
  `bill_no` int DEFAULT NULL,
  `pmt_type` text,
  `gross_amt` decimal(10,2) DEFAULT '0.00',
  `disc_rate` decimal(10,2) DEFAULT '0.00',
  `disc_amt` decimal(10,0) DEFAULT '0',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `amt_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bill_date` date DEFAULT (curdate()),
  `amt_bal` decimal(10,2) DEFAULT (0.00),
  `bill_time` time DEFAULT (curtime()),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id` int NOT NULL DEFAULT '0',
  `billRef` text,
  `taxable_amt` decimal(10,2) DEFAULT (0.00),
  `non_taxable_amt` decimal(10,2) DEFAULT (0.00),
  `shift` int NOT NULL,
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_type` text,
  `customer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history_tax_tran`
--

DROP TABLE IF EXISTS `history_tax_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_tax_tran` (
  `id` int NOT NULL DEFAULT '0',
  `bill_date` date NOT NULL,
  `clerk_code` int NOT NULL,
  `mech_no` int NOT NULL,
  `bill_no` int NOT NULL,
  `tran_code` int NOT NULL,
  `tran_qty` int NOT NULL,
  `taxableAmt` decimal(10,2) DEFAULT (0.00),
  `tax_code` varchar(3) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT (0.00),
  `billRef` text,
  `shift` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history_trans`
--

DROP TABLE IF EXISTS `history_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_trans` (
  `id` int NOT NULL DEFAULT '0' COMMENT 'BILL NUMBER',
  `mach` int DEFAULT NULL COMMENT 'machine number',
  `clerk` text,
  `bill_number` int NOT NULL,
  `item_barcode` text NOT NULL,
  `trans_type` text NOT NULL COMMENT 'Transaction Type',
  `retail_price` decimal(10,2) DEFAULT NULL COMMENT 'Value of transaction',
  `date_added` date DEFAULT (curdate()),
  `time_added` time DEFAULT (curtime()),
  `item_qty` decimal(10,2) DEFAULT '0.00',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `bill_amt` decimal(10,2) DEFAULT '0.00',
  `item_desc` varchar(255) DEFAULT NULL,
  `tax_grp` varchar(255) DEFAULT 'NULL',
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int DEFAULT NULL,
  `selected` int DEFAULT '0',
  `billRef` text,
  `gfund` decimal(10,2) DEFAULT (0.00),
  `nhis` decimal(10,2) DEFAULT (0.00),
  `covid` decimal(10,2) DEFAULT (0.00),
  `vat` decimal(10,2) DEFAULT (0.00),
  `tax_code` text,
  `shift` int NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT (0.00),
  `discount` decimal(10,2) DEFAULT (0.00),
  `discount_rate` decimal(10,2) DEFAULT (0.00),
  `old_bill_ref` text,
  `sales_date` date DEFAULT (curdate()),
  `sales_time` date DEFAULT (curtime())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_hd`
--

DROP TABLE IF EXISTS `invoice_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_hd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_no` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `customer` int NOT NULL,
  `remarks` text,
  `taxable` int NOT NULL DEFAULT '0',
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `other_cost` decimal(10,2) DEFAULT '0.00',
  `gross_amt` decimal(10,2) DEFAULT '0.00',
  `date_created` date DEFAULT (curdate()),
  `time_created` time DEFAULT (curtime()),
  `created_by` int NOT NULL,
  `valid` int DEFAULT '1',
  `approved` int DEFAULT '0',
  `ref_type` text,
  `ref_no` text,
  `posted` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `invoice_hd_customers_customer_id_fk` (`customer`),
  KEY `invoice_hd_loc_loc_id_fk` (`loc_id`),
  CONSTRAINT `invoice_hd_customers_customer_id_fk` FOREIGN KEY (`customer`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `invoice_hd_loc_loc_id_fk` FOREIGN KEY (`loc_id`) REFERENCES `loc` (`loc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_tran`
--

DROP TABLE IF EXISTS `invoice_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_tran` (
  `entry_no` char(10) NOT NULL,
  `line_no` int NOT NULL,
  `barcode` char(255) NOT NULL,
  `item_desc` text NOT NULL,
  `packing` char(10) NOT NULL,
  `pack_qty` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) DEFAULT '0.00',
  `unit_cost` decimal(10,2) NOT NULL,
  `net_cost` decimal(10,2) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `gross_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_buttons`
--

DROP TABLE IF EXISTS `item_buttons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_buttons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `button_index` int DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_group`
--

DROP TABLE IF EXISTS `item_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_group` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'group id',
  `group_name` text NOT NULL COMMENT 'name of group',
  `date_created` date DEFAULT (curdate()),
  `time_added` time DEFAULT (curtime()),
  `owner` text NOT NULL COMMENT 'who created group',
  `grp_uni` char(255) DEFAULT NULL,
  `modified_by` text,
  `date_modified` date DEFAULT (curdate()),
  `time_modified` time DEFAULT (curtime()),
  `shrt_name` text,
  `tax_grp` int DEFAULT '0',
  `status` int DEFAULT (1),
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_group_grp_uni_uindex` (`grp_uni`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_group_sub`
--

DROP TABLE IF EXISTS `item_group_sub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_group_sub` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent` int DEFAULT '0',
  `description` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`id`,`parent`),
  KEY `route_with_parent` (`parent`),
  CONSTRAINT `route_with_parent` FOREIGN KEY (`parent`) REFERENCES `item_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items_master`
--

DROP TABLE IF EXISTS `items_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_master` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'unique id of each item',
  `barcode` text NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` int NOT NULL DEFAULT '0' COMMENT 'id of tax this belongs oo',
  `item_grp` text NOT NULL,
  `item_uni` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loc`
--

DROP TABLE IF EXISTS `loc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `loc_id` char(3) DEFAULT NULL,
  `loc_desc` text,
  `country` text COMMENT 'country',
  `city` text COMMENT 'city',
  `street` text COMMENT 'street',
  `post_box` text COMMENT 'post box',
  `email` text COMMENT 'email address',
  `phone` text COMMENT 'phone number',
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc_pk` (`loc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text,
  `date_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loy_customer`
--

DROP TABLE IF EXISTS `loy_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loy_customer` (
  `cust_code` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` char(60) DEFAULT NULL,
  `mobile` char(30) NOT NULL COMMENT 'customer mobile number',
  PRIMARY KEY (`cust_code`),
  UNIQUE KEY `loy_customer_pk` (`mobile`),
  UNIQUE KEY `loy_customer_pk2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=100033 DEFAULT CHARSET=utf8mb4 COMMENT='Loyalty Customers';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loyalty_point_stmt`
--

DROP TABLE IF EXISTS `loyalty_point_stmt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_point_stmt` (
  `cust_code` int NOT NULL,
  `value` decimal(10,2) DEFAULT '0.00',
  `billRef` char(20) NOT NULL,
  UNIQUE KEY `loyalty_point_stmt_pk` (`billRef`),
  KEY `loyalty_point_stmt_loy_customer_cust_code_fk` (`cust_code`),
  CONSTRAINT `loyalty_point_stmt_loy_customer_cust_code_fk` FOREIGN KEY (`cust_code`) REFERENCES `loy_customer` (`cust_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Rable keeps loyalty points transactions for a customer';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loyalty_tran`
--

DROP TABLE IF EXISTS `loyalty_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_tran` (
  `cust_code` char(66) NOT NULL,
  `billRef` char(60) NOT NULL,
  `time_stamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `cust_name` text,
  `points_before` decimal(10,2) DEFAULT (0.00),
  `points_earned` decimal(10,2) DEFAULT (0.00),
  `current_points` decimal(10,2) DEFAULT ((`points_before` + `points_earned`)),
  PRIMARY KEY (`billRef`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mech_setup`
--

DROP TABLE IF EXISTS `mech_setup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mech_setup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mech_no` int DEFAULT NULL,
  `descr` text,
  `mac_addr` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `my_table`
--

DROP TABLE IF EXISTS `my_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `my_table` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `my_column` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `packaging`
--

DROP TABLE IF EXISTS `packaging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packaging` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `po_hd`
--

DROP TABLE IF EXISTS `po_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `po_hd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doc_no` char(13) DEFAULT NULL,
  `status` int DEFAULT '0',
  `location` char(3) NOT NULL,
  `suppler` char(13) NOT NULL,
  `type` char(13) NOT NULL,
  `remarks` text,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `owner` text,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edited_by` char(30) DEFAULT NULL,
  `approved_by` char(13) DEFAULT NULL,
  `approved_on` datetime DEFAULT NULL,
  `grn` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000014 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `po_trans`
--

DROP TABLE IF EXISTS `po_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `po_trans` (
  `prefix` varchar(2) NOT NULL DEFAULT 'PO',
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent` char(13) DEFAULT NULL,
  `line` int NOT NULL DEFAULT '0',
  `item_code` char(13) DEFAULT NULL,
  `barcode` char(13) DEFAULT NULL,
  `item_description` text NOT NULL,
  `packing` text,
  `pack_desc` text,
  `pack_um` decimal(10,2) DEFAULT '0.00',
  `qty` decimal(10,2) DEFAULT '0.00',
  `cost` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `date_added` date DEFAULT (curdate()),
  `owner` char(13) DEFAULT NULL,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000020 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `price_change`
--

DROP TABLE IF EXISTS `price_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `price_change` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_code` int NOT NULL,
  `price_type` text,
  `previous` decimal(10,2) NOT NULL,
  `current` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `price_change_with_product` (`item_code`),
  CONSTRAINT `price_change_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_disc`
--

DROP TABLE IF EXISTS `prod_disc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_disc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prod_code` text NOT NULL,
  `rate` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_expiry`
--

DROP TABLE IF EXISTS `prod_expiry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_expiry` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_mast`
--

DROP TABLE IF EXISTS `prod_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_mast` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_grp` int NOT NULL,
  `item_uni` text DEFAULT (md5(`desc`)),
  `barcode` char(255) NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` char(3) NOT NULL DEFAULT '0' COMMENT 'id of tax this belongs oo',
  `discount` int DEFAULT '0',
  `discount_rate` decimal(10,2) DEFAULT '0.00',
  `stock_type` int NOT NULL DEFAULT '1',
  `prev_retail` decimal(10,2) DEFAULT '0.00',
  `sub_grp` int DEFAULT NULL,
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `retail_wo_tax` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode` (`barcode`),
  KEY `stock_typ` (`stock_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1000000219 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_master`
--

DROP TABLE IF EXISTS `prod_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_master` (
  `item_code` int NOT NULL AUTO_INCREMENT,
  `item_uni` text DEFAULT (md5(`item_desc`)),
  `group` int NOT NULL,
  `sub_group` int NOT NULL,
  `supplier` text,
  `barcode` char(255) NOT NULL COMMENT 'barcode of item',
  `item_desc` text NOT NULL COMMENT 'item description',
  `item_desc1` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax` char(3) NOT NULL DEFAULT '0' COMMENT 'tax status',
  `packing` int NOT NULL DEFAULT '0' COMMENT 'Packaging',
  `stock_type` int NOT NULL DEFAULT '0' COMMENT 'Stock Type',
  `special_price` int NOT NULL DEFAULT '0' COMMENT 'Special Price',
  `discount` int DEFAULT '0',
  `discount_rate` decimal(10,2) DEFAULT '0.00',
  `prev_retail` decimal(10,2) DEFAULT '0.00',
  `owner` varchar(200) DEFAULT 'master',
  `created_at` date DEFAULT (curdate()),
  `edited_at` date DEFAULT (curdate()),
  `edited_by` varchar(200) DEFAULT NULL,
  `download_flag` int DEFAULT (1),
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `retail_wo_tax` decimal(10,2) DEFAULT '0.00',
  `expiry_date` date DEFAULT (curdate()) COMMENT 'Expiry date of product',
  PRIMARY KEY (`item_code`),
  UNIQUE KEY `barcode` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=1000000219 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_packing`
--

DROP TABLE IF EXISTS `prod_packing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_packing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `pack_id` char(3) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `purpose` int DEFAULT '1',
  `pack_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prod_supplier`
--

DROP TABLE IF EXISTS `prod_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prod_supplier` (
  `sp_id` int NOT NULL AUTO_INCREMENT,
  `item_code` int DEFAULT NULL,
  `supplier_code` text,
  `level` int DEFAULT '0',
  PRIMARY KEY (`sp_id`),
  KEY `prod_supplier_prod` (`item_code`),
  CONSTRAINT `prod_supplier_prod` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Suppliers for each product';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prof_hd`
--

DROP TABLE IF EXISTS `prof_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prof_hd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_no` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `customer` int NOT NULL,
  `remarks` text,
  `taxable` int NOT NULL DEFAULT '0',
  `iss_date` date NOT NULL,
  `due_date` date NOT NULL,
  `net_amt` decimal(10,2) DEFAULT '0.00',
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `other_cost` decimal(10,2) DEFAULT '0.00',
  `gross_amt` decimal(10,2) DEFAULT '0.00',
  `date_created` date DEFAULT (curdate()),
  `time_created` time DEFAULT (curtime()),
  `created_by` int NOT NULL,
  `valid` int DEFAULT '1',
  `approved` int DEFAULT '0',
  `posted` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `prof_hd_customers_customer_id_fk` (`customer`),
  KEY `prof_hd_clerk_id_fk` (`created_by`),
  CONSTRAINT `prof_hd_clerk_id_fk` FOREIGN KEY (`created_by`) REFERENCES `clerk` (`id`),
  CONSTRAINT `prof_hd_customers_customer_id_fk` FOREIGN KEY (`customer`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prof_tran`
--

DROP TABLE IF EXISTS `prof_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prof_tran` (
  `entry_no` char(10) NOT NULL,
  `line_no` int NOT NULL,
  `barcode` char(255) NOT NULL,
  `item_desc` text NOT NULL,
  `packing` char(10) NOT NULL,
  `pack_qty` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) DEFAULT '0.00',
  `unit_cost` decimal(10,2) NOT NULL,
  `net_cost` decimal(10,2) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `gross_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `refund`
--

DROP TABLE IF EXISTS `refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_number` int DEFAULT NULL,
  `amount_refund` decimal(50,2) DEFAULT NULL,
  `receptionist` text,
  `date` date DEFAULT (curdate()),
  `reason` text,
  `customer` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` double(6,2) DEFAULT NULL,
  `stage` text,
  `day` text,
  `month` text,
  `year` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_hd`
--

DROP TABLE IF EXISTS `sales_hd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_hd` (
  `sales_date` date NOT NULL,
  `gross` decimal(10,0) DEFAULT (0.00),
  `tax` decimal(10,0) DEFAULT (0.00),
  `net` decimal(10,0) DEFAULT (0.00),
  `posted` int DEFAULT (0),
  `check_customer` int DEFAULT '0',
  `shitf` int NOT NULL,
  UNIQUE KEY `sales_date` (`sales_date`,`shitf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_tran`
--

DROP TABLE IF EXISTS `sales_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_tran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mach` int NOT NULL,
  `shift` int NOT NULL,
  `barcode` char(30) NOT NULL,
  `item_desc` text NOT NULL,
  `un_cost` decimal(10,2) DEFAULT (0.00),
  `un_retail` decimal(10,2) DEFAULT (0.00),
  `sold_qty` decimal(10,2) DEFAULT (0.00),
  `total_cost` decimal(10,2) DEFAULT (0.00),
  `total_sold` decimal(10,2) DEFAULT (0.00),
  `total_tax` decimal(10,2) DEFAULT (0.00),
  `bill_date` date DEFAULT (curdate()),
  `bill_no` int DEFAULT (0),
  `check_customer` int DEFAULT '0',
  `shitf` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `screens`
--

DROP TABLE IF EXISTS `screens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `screens` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(45) DEFAULT NULL,
  `sub_module` varchar(45) DEFAULT NULL,
  `created_on` date DEFAULT (curdate()),
  `scr_uni` varchar(50) DEFAULT (md5(concat(`created_on`,`module`))),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shifts` (
  `recId` int NOT NULL AUTO_INCREMENT,
  `shift_no` int NOT NULL,
  `clerk` text,
  `mech_no` int NOT NULL,
  `shift_date` date DEFAULT (curdate()),
  `endate` date DEFAULT NULL,
  `start_time` time DEFAULT (curtime()),
  `end_time` time DEFAULT (NULL),
  `enc` char(255) NOT NULL,
  `pending_eod` int DEFAULT '0',
  PRIMARY KEY (`recId`),
  UNIQUE KEY `recId` (`recId`),
  UNIQUE KEY `enc` (`enc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stk_tran`
--

DROP TABLE IF EXISTS `stk_tran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stk_tran` (
  `entry_no` char(12) NOT NULL,
  `doc` char(2) NOT NULL,
  `item_code` int NOT NULL,
  `loc_fro` char(3) NOT NULL,
  `loc_to` char(3) NOT NULL,
  `pack_desc` text NOT NULL,
  `pack_un` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date_created` date NOT NULL DEFAULT (curdate()),
  `time_created` time NOT NULL DEFAULT (curtime()),
  PRIMARY KEY (`doc`,`entry_no`,`item_code`),
  KEY `stk_check_prod_mast_id_fk` (`item_code`),
  CONSTRAINT `stk_check_prod_mast_id_fk` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `qty` decimal(14,2) NOT NULL,
  `ob_qty` decimal(14,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_master`
--

DROP TABLE IF EXISTS `stock_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `desc` (`desc`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_type`
--

DROP TABLE IF EXISTS `stock_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` int NOT NULL,
  `description` text,
  `tax_group` int NOT NULL,
  `owner` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supp_mast`
--

DROP TABLE IF EXISTS `supp_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supp_mast` (
  `supp_id` char(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supp_name` varchar(255) DEFAULT NULL,
  UNIQUE KEY `supp_mast_pk` (`supp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_config`
--

DROP TABLE IF EXISTS `sys_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_config` (
  `variable` varchar(128) NOT NULL,
  `value` varchar(128) DEFAULT NULL,
  `set_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `set_by` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_settings`
--

DROP TABLE IF EXISTS `sys_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_settings` (
  `set_key` char(255) NOT NULL,
  `set_value` text,
  `set_desc` text,
  `set_status` int DEFAULT (0),
  UNIQUE KEY `set_key` (`set_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_buttons`
--

DROP TABLE IF EXISTS `system_buttons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_buttons` (
  `button_id` int NOT NULL AUTO_INCREMENT,
  `module` text,
  `sub_module` text,
  `sub_sub_module` text,
  `descr` text,
  `elem_id` char(255) DEFAULT NULL,
  `elem_name` char(255) DEFAULT NULL,
  `status` int DEFAULT '1',
  `target_id` text COMMENT 'if there is a target div this will target it on button invoking',
  PRIMARY KEY (`button_id`),
  UNIQUE KEY `elem_id` (`elem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='This will hold buttons of sensitve parts of the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tax_master`
--

DROP TABLE IF EXISTS `tax_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `owner` text NOT NULL,
  `active` int DEFAULT '0' COMMENT '1 means tax is enabled, 0 means not',
  `type` varchar(20) DEFAULT NULL,
  `attr` char(3) NOT NULL,
  `cls` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_master_attr_uindex` (`attr`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tax_trans`
--

DROP TABLE IF EXISTS `tax_trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_trans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_no` varchar(13) DEFAULT NULL,
  `doc` char(3) DEFAULT NULL,
  `item_code` int NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT '0.00',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tax_code` char(2) DEFAULT NULL,
  `tran_amt` decimal(10,2) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `unit_qty` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_trans___fk__with_products` (`item_code`),
  CONSTRAINT `tax_trans___fk__with_products` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_access`
--

DROP TABLE IF EXISTS `user_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_access` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group` int NOT NULL,
  `screen` int NOT NULL,
  `read` int DEFAULT '1',
  `write` int DEFAULT '0',
  `print` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descr` char(45) DEFAULT NULL,
  `created_on` date DEFAULT (curdate()),
  `created_on_time` time DEFAULT (curtime()),
  `remarks` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descr_UNIQUE` (`descr`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_login_log`
--

DROP TABLE IF EXISTS `user_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_login_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` text NOT NULL,
  `func` text NOT NULL,
  `date_created` date NOT NULL DEFAULT (curdate()),
  `time` time DEFAULT (curtime()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_task`
--

DROP TABLE IF EXISTS `user_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `task_status` int NOT NULL DEFAULT '1',
  `task` text NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'user id',
  `username` text NOT NULL,
  `first_name` text,
  `last_name` text,
  `password` text NOT NULL,
  `ual` int NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online` int DEFAULT NULL,
  `ip_address` text,
  `owner` text NOT NULL,
  `db_access` varchar(5999) NOT NULL DEFAULT (_utf8mb4'hello'),
  `last_login_time` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zserial`
--

DROP TABLE IF EXISTS `zserial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zserial` (
  `zSerial` int NOT NULL,
  `mech_no` int NOT NULL,
  `sales_date` date NOT NULL,
  `clerk_code` text NOT NULL,
  `shift_no` int NOT NULL,
  `z_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `gross` decimal(10,2) DEFAULT NULL,
  `deduction` decimal(10,2) DEFAULT NULL,
  `net` decimal(10,2) DEFAULT NULL,
  `eod` int DEFAULT (0),
  PRIMARY KEY (`zSerial`,`mech_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'venta'
--
/*!50003 DROP PROCEDURE IF EXISTS `CheckStockExpiry` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;

/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`%`@`%` PROCEDURE `CheckStockExpiry`(IN loc_to_param VARCHAR(255), IN expiry_date_param DATE)
BEGIN

    IF loc_to_param = '*' THEN

        -- Case when targeting all locations

        SELECT

            expiry_date,

            barcode,

            item_desc,

            retail,

            cost,

            (SELECT SUM(tran_qty) FROM stk_tran WHERE stk_tran.item_code = prod_master.item_code) AS 'stock'

        FROM

            prod_master

        WHERE

            expiry_date <= expiry_date_param;

    ELSE

        -- Case when targeting a specific location

        SELECT

            expiry_date,

            barcode,

            item_desc,

            retail,

            cost,

            (SELECT SUM(tran_qty) FROM stk_tran WHERE stk_tran.item_code = prod_master.item_code AND loc_to = loc_to_param) AS 'stock'

        FROM

            prod_master

        WHERE

            expiry_date <= expiry_date_param;

    END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `copySalesHd` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;

/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`anton`@`%` PROCEDURE `copySalesHd`(IN sales_date_param date)
BEGIN
	INSERT INTO `sales_hd` (`sales_date`, `gross`, `tax`, `net`,shitf) VALUES
	(sales_date_param,
	(SELECT SUM(gross_amt) FROM bill_header where bill_date = sales_date_param),
	 (SELECT SUM(tax_amt) FROM bill_header where bill_date = sales_date_param),
	 (SELECT SUM(gross_amt) - SUM(tax_amt)  FROM bill_header where bill_date = sales_date_param),
	 (SELECT shift from bill_header where bill_header.sales_date = sales_date_param limit 1)
	 );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `copySalesTrans` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;

/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`anton`@`%` PROCEDURE `copySalesTrans`(IN sales_date_param date)
BEGIN
	INSERT INTO `sales_tran` (`mach`, `shift`, `barcode`, `item_desc`, `un_cost`,`un_retail`, `sold_qty`, `total_cost`, `total_sold`, `total_tax`,`bill_date`,shitf)
	SELECT mach,shift, item_barcode as 'barcode',pm.desc,pm.cost as 'cost',
	retail_price as 'retail_price',sum(item_qty) as 'qty_sold',(pm.cost * sum(item_qty)) as 'toal_cost', sum(bill_amt) as 'total_sale',
	sum(vat) as 'tax', date_added as 'bill_date',shift
	FROM bill_trans  right outer join
	prod_mast pm on bill_trans.item_barcode = pm.barcode
	group by item_barcode,pm.desc,retail_price,pm.cost,mach,date_added,bill_trans.shift
    having sum(bill_amt) is not null and
    date_added = sales_date_param;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `item_availability` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;

/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`%`@`%` PROCEDURE `item_availability`(IN loc_to_param int, IN as_of_date_param date, IN show_sock int)
BEGIN
    if show_sock = 0 THEN
        # get all items with stock or not
        select loc_to_param                                            as 'loc_id',
               item_code                                               as 'item_code',
               barcode                                                 as 'barcode',
               item_desc,
               (select SUM(tran_qty)
                from stk_tran
                where loc_to = loc_to_param
                  and stk_tran.item_code = prod_master.item_code
                  and DATE(stk_tran.date_created) <= as_of_date_param) as 'stock'
        from prod_master
        order by stock desc;
    ELSE
        # get only stock items available
        select loc_to_param                                            as 'loc_id',
               item_code                                               as 'item_code',
               barcode                                                 as 'barcode',
               item_desc,
               (select SUM(tran_qty)
                from stk_tran
                where loc_to = loc_to_param
                  and stk_tran.item_code = prod_master.item_code
                  and DATE(stk_tran.date_created) <= as_of_date_param) as 'stock'
        from prod_master
        where (select SUM(tran_qty)
               from stk_tran
               where loc_to = loc_to_param
                 and stk_tran.item_code = prod_master.item_code
                 and DATE(stk_tran.date_created) <= as_of_date_param) > 0
        order by stock desc;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ReportPaymentSummary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;

/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`%`@`%` PROCEDURE `ReportPaymentSummary`(IN mech_no INT, IN sales_date date,IN shift int)
BEGIN
	select  pmt_type, count(pmt_type) as 'pmt_count',sum(net_amt) as 'total' 
    from bill_header where mach_no = mech_no and bill_date = sales_date and 
    `shift` = shift group by pmt_type;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-03  9:37:08
