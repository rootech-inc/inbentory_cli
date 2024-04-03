-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 01, 2024 at 05:30 AM
-- Server version: 10.11.7-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u560949065_venta`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `func` text NOT NULL,
  `query` text NOT NULL,
  `source` text NOT NULL,
  `time_exe` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin.company_setup`
--

DROP TABLE IF EXISTS `admin.company_setup`;
CREATE TABLE IF NOT EXISTS `admin.company_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` text NOT NULL,
  `currency` int(11) NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text DEFAULT NULL,
  `tax_code` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `code` char(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin.currency`
--

DROP TABLE IF EXISTS `admin.currency`;
CREATE TABLE IF NOT EXISTS `admin.currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` text NOT NULL,
  `symbol` text NOT NULL,
  `short` text DEFAULT NULL,
  `active` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_payment_methods`
--

DROP TABLE IF EXISTS `admin_payment_methods`;
CREATE TABLE IF NOT EXISTS `admin_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  UNIQUE KEY `admin_payment_methods_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barcode`
--

DROP TABLE IF EXISTS `barcode`;
CREATE TABLE IF NOT EXISTS `barcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) NOT NULL,
  `barcode` text DEFAULT NULL,
  `item_desc` text DEFAULT NULL,
  `item_desc1` text DEFAULT NULL,
  `retail` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent` varchar(200) DEFAULT 'master',
  PRIMARY KEY (`id`),
  KEY `relation_with_product` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_header`
--

DROP TABLE IF EXISTS `bill_header`;
CREATE TABLE IF NOT EXISTS `bill_header` (
  `mach_no` int(11) DEFAULT NULL,
  `clerk` text DEFAULT NULL,
  `bill_no` int(11) DEFAULT NULL,
  `pmt_type` text DEFAULT NULL,
  `gross_amt` decimal(10,2) DEFAULT 0.00,
  `disc_rate` decimal(10,2) DEFAULT 0.00,
  `disc_amt` decimal(10,0) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `amt_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bill_date` date DEFAULT curdate(),
  `amt_bal` decimal(10,2) DEFAULT 0.00,
  `bill_time` time DEFAULT curtime(),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billRef` text DEFAULT NULL,
  `taxable_amt` decimal(10,2) DEFAULT 0.00,
  `non_taxable_amt` decimal(10,2) DEFAULT 0.00,
  `shift` int(11) NOT NULL,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_type` text DEFAULT NULL,
  `customer` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_history_header`
--

DROP TABLE IF EXISTS `bill_history_header`;
CREATE TABLE IF NOT EXISTS `bill_history_header` (
  `mach_no` int(11) DEFAULT NULL,
  `clerk` text DEFAULT NULL,
  `bill_no` int(11) DEFAULT NULL,
  `pmt_type` text DEFAULT NULL,
  `gross_amt` decimal(10,2) DEFAULT 0.00,
  `disc_rate` decimal(10,2) DEFAULT 0.00,
  `disc_amt` decimal(10,0) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `amt_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bill_date` date DEFAULT curdate(),
  `amt_bal` decimal(10,2) DEFAULT 0.00,
  `bill_time` time DEFAULT curtime(),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id` int(11) NOT NULL DEFAULT 0,
  `billRef` text DEFAULT NULL,
  `taxable_amt` decimal(10,2) DEFAULT 0.00,
  `non_taxable_amt` decimal(10,2) DEFAULT 0.00,
  `shift` int(11) NOT NULL,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_type` text DEFAULT NULL,
  `customer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_history_trans`
--

DROP TABLE IF EXISTS `bill_history_trans`;
CREATE TABLE IF NOT EXISTS `bill_history_trans` (
  `id` int(11) NOT NULL COMMENT 'BILL NUMBER',
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
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT 0,
  `billRef` text DEFAULT NULL,
  `gfund` decimal(10,2) DEFAULT 0.00,
  `nhis` decimal(10,2) DEFAULT 0.00,
  `covid` decimal(10,2) DEFAULT 0.00,
  `vat` decimal(10,2) DEFAULT 0.00,
  `tax_code` text DEFAULT NULL,
  `shift` int(11) NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_time` date DEFAULT curtime(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_hld_tr`
--

DROP TABLE IF EXISTS `bill_hld_tr`;
CREATE TABLE IF NOT EXISTS `bill_hld_tr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_group` char(4) DEFAULT NULL,
  `barcode` text DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT 0.00,
  `tran_date` date DEFAULT curdate(),
  `tran_time` time DEFAULT curtime(),
  `clerk` int(11) NOT NULL,
  `billed` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_hold`
--

DROP TABLE IF EXISTS `bill_hold`;
CREATE TABLE IF NOT EXISTS `bill_hold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_grp` char(4) NOT NULL,
  `bill_date` date DEFAULT curdate(),
  `item_barcode` varchar(255) DEFAULT NULL,
  `item_qty` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_pmt`
--

DROP TABLE IF EXISTS `bill_pmt`;
CREATE TABLE IF NOT EXISTS `bill_pmt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill` int(11) DEFAULT NULL,
  `bill_amount` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `amount_balance` decimal(10,2) DEFAULT NULL,
  `trans_date` date DEFAULT curdate(),
  `trans_time` time DEFAULT curtime(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `bill_pmt_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_tax_tran`
--

DROP TABLE IF EXISTS `bill_tax_tran`;
CREATE TABLE IF NOT EXISTS `bill_tax_tran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_date` date NOT NULL,
  `clerk_code` int(11) NOT NULL,
  `mech_no` int(11) NOT NULL,
  `bill_no` int(11) NOT NULL,
  `tran_code` int(11) NOT NULL,
  `tran_qty` int(11) NOT NULL,
  `taxableAmt` decimal(10,2) DEFAULT 0.00,
  `tax_code` varchar(3) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `billRef` text DEFAULT NULL,
  `shift` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_trans`
--

DROP TABLE IF EXISTS `bill_trans`;
CREATE TABLE IF NOT EXISTS `bill_trans` (
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
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT 0,
  `billRef` text DEFAULT NULL,
  `gfund` decimal(10,2) DEFAULT 0.00,
  `nhis` decimal(10,2) DEFAULT 0.00,
  `covid` decimal(10,2) DEFAULT 0.00,
  `vat` decimal(10,2) DEFAULT 0.00,
  `tax_code` text DEFAULT NULL,
  `shift` int(11) NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_time` date DEFAULT curtime(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clerk`
--

DROP TABLE IF EXISTS `clerk`;
CREATE TABLE IF NOT EXISTS `clerk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clerk_code` text NOT NULL,
  `clerk_key` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `clerk_name` text NOT NULL,
  `user_grp` int(11) NOT NULL,
  `status` int(11) DEFAULT 1 COMMENT 'If 1, clerk is active, else clerk is not active',
  `pin` char(4) NOT NULL,
  `token` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clerk_pk` (`pin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL DEFAULT 0,
  `c_name` text NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comp_setup`
--

DROP TABLE IF EXISTS `comp_setup`;
CREATE TABLE IF NOT EXISTS `comp_setup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `c_name` text NOT NULL,
  `currency` int(11) NOT NULL,
  `box` text NOT NULL,
  `street` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `phone` text NOT NULL,
  `email` text DEFAULT NULL,
  `tax_code` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `code` char(3) NOT NULL,
  UNIQUE KEY `comp_setup_pk` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` char(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `cust_no` char(10) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `total_transactions` decimal(18,6) DEFAULT 0.000000,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `customers_pk2` (`email`),
  UNIQUE KEY `customers_pk` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_trans`
--

DROP TABLE IF EXISTS `customers_trans`;
CREATE TABLE IF NOT EXISTS `customers_trans` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `transaction_date` date NOT NULL DEFAULT curdate(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `items_purchased` text DEFAULT NULL,
  `transaction_notes` text DEFAULT NULL,
  `entry_no` text DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `customer_id` (`customer_id`),
  KEY `customers_trans_clerk_id_fk` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_bill_tran`
--

DROP TABLE IF EXISTS `customer_bill_tran`;
CREATE TABLE IF NOT EXISTS `customer_bill_tran` (
  `cust_no` int(11) NOT NULL,
  `billRef` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disc_mast`
--

DROP TABLE IF EXISTS `disc_mast`;
CREATE TABLE IF NOT EXISTS `disc_mast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` int(11) NOT NULL,
  `desc` text DEFAULT NULL,
  `disc_uni` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doc_trans`
--

DROP TABLE IF EXISTS `doc_trans`;
CREATE TABLE IF NOT EXISTS `doc_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_type` char(3) NOT NULL,
  `entry_no` varchar(13) NOT NULL,
  `trans_func` char(3) NOT NULL,
  `created_by` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eod_serial`
--

DROP TABLE IF EXISTS `eod_serial`;
CREATE TABLE IF NOT EXISTS `eod_serial` (
  `sales_date` date NOT NULL,
  `gross` decimal(20,1) DEFAULT NULL,
  `deductions` decimal(20,1) DEFAULT NULL,
  `tax` decimal(20,1) DEFAULT NULL,
  `net` decimal(20,1) DEFAULT NULL,
  `eod_time` datetime DEFAULT current_timestamp(),
  `clerk_code` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`sales_date`),
  KEY `eod_serial_clerk_id_fk` (`clerk_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `err_code`
--

DROP TABLE IF EXISTS `err_code`;
CREATE TABLE IF NOT EXISTS `err_code` (
  `code` char(255) DEFAULT NULL COMMENT 'Error Code',
  `description` text DEFAULT NULL COMMENT 'Error Description\n',
  UNIQUE KEY `err_code_code_uindex` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evat_transactions`
--

DROP TABLE IF EXISTS `evat_transactions`;
CREATE TABLE IF NOT EXISTS `evat_transactions` (
  `billRef` char(255) NOT NULL,
  `ysdcid` text NOT NULL,
  `ysdcitems` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grn_hd`
--

DROP TABLE IF EXISTS `grn_hd`;
CREATE TABLE IF NOT EXISTS `grn_hd` (
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
  UNIQUE KEY `grn_hd_pk` (`entry_no`),
  KEY `grn_hd_tax_master_id_fk` (`tax`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grn_trans`
--

DROP TABLE IF EXISTS `grn_trans`;
CREATE TABLE IF NOT EXISTS `grn_trans` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_header`
--

DROP TABLE IF EXISTS `history_header`;
CREATE TABLE IF NOT EXISTS `history_header` (
  `mach_no` int(11) DEFAULT NULL,
  `clerk` text DEFAULT NULL,
  `bill_no` int(11) DEFAULT NULL,
  `pmt_type` text DEFAULT NULL,
  `gross_amt` decimal(10,2) DEFAULT 0.00,
  `disc_rate` decimal(10,2) DEFAULT 0.00,
  `disc_amt` decimal(10,0) DEFAULT 0,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `amt_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bill_date` date DEFAULT curdate(),
  `amt_bal` decimal(10,2) DEFAULT 0.00,
  `bill_time` time DEFAULT curtime(),
  `tran_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id` int(11) NOT NULL DEFAULT 0,
  `billRef` text DEFAULT NULL,
  `taxable_amt` decimal(10,2) DEFAULT 0.00,
  `non_taxable_amt` decimal(10,2) DEFAULT 0.00,
  `shift` int(11) NOT NULL,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_type` text DEFAULT NULL,
  `customer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_tax_tran`
--

DROP TABLE IF EXISTS `history_tax_tran`;
CREATE TABLE IF NOT EXISTS `history_tax_tran` (
  `id` int(11) NOT NULL DEFAULT 0,
  `bill_date` date NOT NULL,
  `clerk_code` int(11) NOT NULL,
  `mech_no` int(11) NOT NULL,
  `bill_no` int(11) NOT NULL,
  `tran_code` int(11) NOT NULL,
  `tran_qty` int(11) NOT NULL,
  `taxableAmt` decimal(10,2) DEFAULT 0.00,
  `tax_code` varchar(3) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `billRef` text DEFAULT NULL,
  `shift` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_trans`
--

DROP TABLE IF EXISTS `history_trans`;
CREATE TABLE IF NOT EXISTS `history_trans` (
  `id` int(11) NOT NULL DEFAULT 0 COMMENT 'BILL NUMBER',
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
  `tran_type` char(2) DEFAULT NULL,
  `tax_rate` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT 0,
  `billRef` text DEFAULT NULL,
  `gfund` decimal(10,2) DEFAULT 0.00,
  `nhis` decimal(10,2) DEFAULT 0.00,
  `covid` decimal(10,2) DEFAULT 0.00,
  `vat` decimal(10,2) DEFAULT 0.00,
  `tax_code` text DEFAULT NULL,
  `shift` int(11) NOT NULL,
  `loyalty_points` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `old_bill_ref` text DEFAULT NULL,
  `sales_date` date DEFAULT curdate(),
  `sales_time` date DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_hd`
--

DROP TABLE IF EXISTS `invoice_hd`;
CREATE TABLE IF NOT EXISTS `invoice_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_no` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `customer` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `taxable` int(11) NOT NULL DEFAULT 0,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `other_cost` decimal(10,2) DEFAULT 0.00,
  `gross_amt` decimal(10,2) DEFAULT 0.00,
  `date_created` date DEFAULT curdate(),
  `time_created` time DEFAULT curtime(),
  `created_by` int(11) NOT NULL,
  `valid` int(11) DEFAULT 1,
  `approved` int(11) DEFAULT 0,
  `ref_type` text DEFAULT NULL,
  `ref_no` text DEFAULT NULL,
  `posted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `invoice_hd_customers_customer_id_fk` (`customer`),
  KEY `invoice_hd_loc_loc_id_fk` (`loc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_tran`
--

DROP TABLE IF EXISTS `invoice_tran`;
CREATE TABLE IF NOT EXISTS `invoice_tran` (
  `entry_no` char(10) NOT NULL,
  `line_no` int(11) NOT NULL,
  `barcode` char(255) NOT NULL,
  `item_desc` text NOT NULL,
  `packing` char(10) NOT NULL,
  `pack_qty` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) DEFAULT 0.00,
  `unit_cost` decimal(10,2) NOT NULL,
  `net_cost` decimal(10,2) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `gross_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items_master`
--

DROP TABLE IF EXISTS `items_master`;
CREATE TABLE IF NOT EXISTS `items_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique id of each item',
  `barcode` text NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` int(11) NOT NULL DEFAULT 0 COMMENT 'id of tax this belongs oo',
  `item_grp` text NOT NULL,
  `item_uni` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_buttons`
--

DROP TABLE IF EXISTS `item_buttons`;
CREATE TABLE IF NOT EXISTS `item_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `button_index` int(11) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_group`
--

DROP TABLE IF EXISTS `item_group`;
CREATE TABLE IF NOT EXISTS `item_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'group id',
  `group_name` text NOT NULL COMMENT 'name of group',
  `date_created` date DEFAULT curdate(),
  `time_added` time DEFAULT curtime(),
  `owner` text NOT NULL COMMENT 'who created group',
  `grp_uni` char(255) DEFAULT NULL,
  `modified_by` text DEFAULT NULL,
  `date_modified` date DEFAULT curdate(),
  `time_modified` time DEFAULT curtime(),
  `shrt_name` text DEFAULT NULL,
  `tax_grp` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_group_grp_uni_uindex` (`grp_uni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_group_sub`
--

DROP TABLE IF EXISTS `item_group_sub`;
CREATE TABLE IF NOT EXISTS `item_group_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `owner` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`id`,`parent`),
  KEY `route_with_parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loc`
--

DROP TABLE IF EXISTS `loc`;
CREATE TABLE IF NOT EXISTS `loc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_id` char(3) DEFAULT NULL,
  `loc_desc` text DEFAULT NULL,
  `country` text DEFAULT NULL COMMENT 'country',
  `city` text DEFAULT NULL COMMENT 'city',
  `street` text DEFAULT NULL COMMENT 'street',
  `post_box` text DEFAULT NULL COMMENT 'post box',
  `email` text DEFAULT NULL COMMENT 'email address',
  `phone` text DEFAULT NULL COMMENT 'phone number',
  PRIMARY KEY (`id`),
  UNIQUE KEY `loc_pk` (`loc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text DEFAULT NULL,
  `date_time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_point_stmt`
--

DROP TABLE IF EXISTS `loyalty_point_stmt`;
CREATE TABLE IF NOT EXISTS `loyalty_point_stmt` (
  `cust_code` int(11) NOT NULL,
  `value` decimal(10,2) DEFAULT 0.00,
  `billRef` char(20) NOT NULL,
  UNIQUE KEY `loyalty_point_stmt_pk` (`billRef`),
  KEY `loyalty_point_stmt_loy_customer_cust_code_fk` (`cust_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Rable keeps loyalty points transactions for a customer';

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_tran`
--

DROP TABLE IF EXISTS `loyalty_tran`;
CREATE TABLE IF NOT EXISTS `loyalty_tran` (
  `cust_code` char(66) NOT NULL,
  `billRef` char(60) NOT NULL,
  `time_stamp` datetime DEFAULT current_timestamp(),
  `cust_name` text DEFAULT NULL,
  `points_before` decimal(10,2) DEFAULT 0.00,
  `points_earned` decimal(10,2) DEFAULT 0.00,
  `current_points` decimal(10,2) DEFAULT (`points_before` + `points_earned`),
  PRIMARY KEY (`billRef`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loy_customer`
--

DROP TABLE IF EXISTS `loy_customer`;
CREATE TABLE IF NOT EXISTS `loy_customer` (
  `cust_code` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` char(60) DEFAULT NULL,
  `mobile` char(30) NOT NULL COMMENT 'customer mobile number',
  PRIMARY KEY (`cust_code`),
  UNIQUE KEY `loy_customer_pk` (`mobile`),
  UNIQUE KEY `loy_customer_pk2` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Loyalty Customers';

-- --------------------------------------------------------

--
-- Table structure for table `mech_setup`
--

DROP TABLE IF EXISTS `mech_setup`;
CREATE TABLE IF NOT EXISTS `mech_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mech_no` int(11) DEFAULT NULL,
  `descr` text DEFAULT NULL,
  `mac_addr` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `my_table`
--

DROP TABLE IF EXISTS `my_table`;
CREATE TABLE IF NOT EXISTS `my_table` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `my_column` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packaging`
--

DROP TABLE IF EXISTS `packaging`;
CREATE TABLE IF NOT EXISTS `packaging` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_hd`
--

DROP TABLE IF EXISTS `po_hd`;
CREATE TABLE IF NOT EXISTS `po_hd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_trans`
--

DROP TABLE IF EXISTS `po_trans`;
CREATE TABLE IF NOT EXISTS `po_trans` (
  `prefix` varchar(2) NOT NULL DEFAULT 'PO',
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_change`
--

DROP TABLE IF EXISTS `price_change`;
CREATE TABLE IF NOT EXISTS `price_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) NOT NULL,
  `price_type` text DEFAULT NULL,
  `previous` decimal(10,2) NOT NULL,
  `current` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `price_change_with_product` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_disc`
--

DROP TABLE IF EXISTS `prod_disc`;
CREATE TABLE IF NOT EXISTS `prod_disc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_code` text NOT NULL,
  `rate` decimal(10,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_expiry`
--

DROP TABLE IF EXISTS `prod_expiry`;
CREATE TABLE IF NOT EXISTS `prod_expiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_mast`
--

DROP TABLE IF EXISTS `prod_mast`;
CREATE TABLE IF NOT EXISTS `prod_mast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_grp` int(11) NOT NULL,
  `item_uni` text DEFAULT md5(`desc`),
  `barcode` char(255) NOT NULL COMMENT 'barcode of item',
  `desc` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax_grp` char(3) NOT NULL DEFAULT '0' COMMENT 'id of tax this belongs oo',
  `discount` int(11) DEFAULT 0,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `stock_type` int(11) NOT NULL DEFAULT 1,
  `prev_retail` decimal(10,2) DEFAULT 0.00,
  `sub_grp` int(11) DEFAULT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `retail_wo_tax` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barcode` (`barcode`),
  KEY `stock_typ` (`stock_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_master`
--

DROP TABLE IF EXISTS `prod_master`;
CREATE TABLE IF NOT EXISTS `prod_master` (
  `item_code` int(11) NOT NULL AUTO_INCREMENT,
  `item_uni` text DEFAULT md5(`item_desc`),
  `group` int(11) NOT NULL,
  `sub_group` int(11) NOT NULL,
  `supplier` text DEFAULT NULL,
  `barcode` char(255) NOT NULL COMMENT 'barcode of item',
  `item_desc` text NOT NULL COMMENT 'item description',
  `item_desc1` text NOT NULL COMMENT 'item description',
  `cost` decimal(10,2) NOT NULL COMMENT 'cost price of the item from supplier',
  `retail` decimal(10,2) NOT NULL COMMENT 'how much is it sold for',
  `tax` char(3) NOT NULL DEFAULT '0' COMMENT 'tax status',
  `packing` int(11) NOT NULL DEFAULT 0 COMMENT 'Packaging',
  `stock_type` int(11) NOT NULL DEFAULT 0 COMMENT 'Stock Type',
  `special_price` int(11) NOT NULL DEFAULT 0 COMMENT 'Special Price',
  `discount` int(11) DEFAULT 0,
  `discount_rate` decimal(10,2) DEFAULT 0.00,
  `prev_retail` decimal(10,2) DEFAULT 0.00,
  `owner` varchar(200) DEFAULT 'master',
  `created_at` date DEFAULT curdate(),
  `edited_at` date DEFAULT curdate(),
  `edited_by` varchar(200) DEFAULT NULL,
  `download_flag` int(11) DEFAULT 1,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `retail_wo_tax` decimal(10,2) DEFAULT 0.00,
  `expiry_date` date DEFAULT curdate() COMMENT 'Expiry date of product',
  PRIMARY KEY (`item_code`),
  UNIQUE KEY `barcode` (`barcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_packing`
--

DROP TABLE IF EXISTS `prod_packing`;
CREATE TABLE IF NOT EXISTS `prod_packing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `pack_id` char(3) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `purpose` int(11) DEFAULT 1,
  `pack_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prod_supplier`
--

DROP TABLE IF EXISTS `prod_supplier`;
CREATE TABLE IF NOT EXISTS `prod_supplier` (
  `sp_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` int(11) DEFAULT NULL,
  `supplier_code` text DEFAULT NULL,
  `level` int(11) DEFAULT 0,
  PRIMARY KEY (`sp_id`),
  KEY `prod_supplier_prod` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Suppliers for each product';

-- --------------------------------------------------------

--
-- Table structure for table `prof_hd`
--

DROP TABLE IF EXISTS `prof_hd`;
CREATE TABLE IF NOT EXISTS `prof_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_no` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `customer` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `taxable` int(11) NOT NULL DEFAULT 0,
  `iss_date` date NOT NULL,
  `due_date` date NOT NULL,
  `net_amt` decimal(10,2) DEFAULT 0.00,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `other_cost` decimal(10,2) DEFAULT 0.00,
  `gross_amt` decimal(10,2) DEFAULT 0.00,
  `date_created` date DEFAULT curdate(),
  `time_created` time DEFAULT curtime(),
  `created_by` int(11) NOT NULL,
  `valid` int(11) DEFAULT 1,
  `approved` int(11) DEFAULT 0,
  `posted` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `prof_hd_customers_customer_id_fk` (`customer`),
  KEY `prof_hd_clerk_id_fk` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prof_tran`
--

DROP TABLE IF EXISTS `prof_tran`;
CREATE TABLE IF NOT EXISTS `prof_tran` (
  `entry_no` char(10) NOT NULL,
  `line_no` int(11) NOT NULL,
  `barcode` char(255) NOT NULL,
  `item_desc` text NOT NULL,
  `packing` char(10) NOT NULL,
  `pack_qty` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) DEFAULT 0.00,
  `unit_cost` decimal(10,2) NOT NULL,
  `net_cost` decimal(10,2) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `gross_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

DROP TABLE IF EXISTS `refund`;
CREATE TABLE IF NOT EXISTS `refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_number` int(11) DEFAULT NULL,
  `amount_refund` decimal(50,2) DEFAULT NULL,
  `receptionist` text DEFAULT NULL,
  `date` date DEFAULT curdate(),
  `reason` text DEFAULT NULL,
  `customer` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` double(6,2) DEFAULT NULL,
  `stage` text DEFAULT NULL,
  `day` text DEFAULT NULL,
  `month` text DEFAULT NULL,
  `year` text DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_hd`
--

DROP TABLE IF EXISTS `sales_hd`;
CREATE TABLE IF NOT EXISTS `sales_hd` (
  `sales_date` date NOT NULL,
  `gross` decimal(10,0) DEFAULT 0,
  `tax` decimal(10,0) DEFAULT 0,
  `net` decimal(10,0) DEFAULT 0,
  `posted` int(11) DEFAULT 0,
  `check_customer` int(11) DEFAULT 0,
  `shitf` int(11) NOT NULL,
  UNIQUE KEY `sales_date` (`sales_date`,`shitf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_tran`
--

DROP TABLE IF EXISTS `sales_tran`;
CREATE TABLE IF NOT EXISTS `sales_tran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mach` int(11) NOT NULL,
  `shift` int(11) NOT NULL,
  `barcode` char(30) NOT NULL,
  `item_desc` text NOT NULL,
  `un_cost` decimal(10,2) DEFAULT 0.00,
  `un_retail` decimal(10,2) DEFAULT 0.00,
  `sold_qty` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `total_sold` decimal(10,2) DEFAULT 0.00,
  `total_tax` decimal(10,2) DEFAULT 0.00,
  `bill_date` date DEFAULT curdate(),
  `bill_no` int(11) DEFAULT 0,
  `check_customer` int(11) DEFAULT 0,
  `shitf` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

DROP TABLE IF EXISTS `screens`;
CREATE TABLE IF NOT EXISTS `screens` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(45) DEFAULT NULL,
  `sub_module` varchar(45) DEFAULT NULL,
  `created_on` date DEFAULT curdate(),
  `scr_uni` varchar(50) DEFAULT md5(concat(`created_on`,`module`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE IF NOT EXISTS `shifts` (
  `recId` int(11) NOT NULL AUTO_INCREMENT,
  `shift_no` int(11) NOT NULL,
  `clerk` text DEFAULT NULL,
  `mech_no` int(11) NOT NULL,
  `shift_date` date DEFAULT curdate(),
  `endate` date DEFAULT NULL,
  `start_time` time DEFAULT curtime(),
  `end_time` time DEFAULT NULL,
  `enc` char(255) NOT NULL,
  `pending_eod` int(11) DEFAULT 0,
  PRIMARY KEY (`recId`),
  UNIQUE KEY `recId` (`recId`),
  UNIQUE KEY `enc` (`enc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stk_tran`
--

DROP TABLE IF EXISTS `stk_tran`;
CREATE TABLE IF NOT EXISTS `stk_tran` (
  `entry_no` char(12) NOT NULL,
  `doc` char(2) NOT NULL,
  `item_code` int(11) NOT NULL,
  `loc_fro` char(3) NOT NULL,
  `loc_to` char(3) NOT NULL,
  `pack_desc` text NOT NULL,
  `pack_un` decimal(10,2) NOT NULL,
  `tran_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date_created` date NOT NULL DEFAULT curdate(),
  `time_created` time NOT NULL DEFAULT curtime(),
  PRIMARY KEY (`doc`,`entry_no`,`item_code`),
  KEY `stk_check_prod_mast_id_fk` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` char(10) NOT NULL,
  `loc_id` char(3) NOT NULL,
  `qty` decimal(14,2) NOT NULL,
  `ob_qty` decimal(14,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_master`
--

DROP TABLE IF EXISTS `stock_master`;
CREATE TABLE IF NOT EXISTS `stock_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `desc` (`desc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_type`
--

DROP TABLE IF EXISTS `stock_type`;
CREATE TABLE IF NOT EXISTS `stock_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE IF NOT EXISTS `sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `tax_group` int(11) NOT NULL,
  `owner` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supp_mast`
--

DROP TABLE IF EXISTS `supp_mast`;
CREATE TABLE IF NOT EXISTS `supp_mast` (
  `supp_id` char(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `supp_name` varchar(255) DEFAULT NULL,
  `tax_grp` int(11) DEFAULT 0,
  UNIQUE KEY `supp_mast_pk` (`supp_id`),
  KEY `supp_mast_tax_master_id_fk` (`tax_grp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_buttons`
--

DROP TABLE IF EXISTS `system_buttons`;
CREATE TABLE IF NOT EXISTS `system_buttons` (
  `button_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` text DEFAULT NULL,
  `sub_module` text DEFAULT NULL,
  `sub_sub_module` text DEFAULT NULL,
  `descr` text DEFAULT NULL,
  `elem_id` char(255) DEFAULT NULL,
  `elem_name` char(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `target_id` text DEFAULT NULL COMMENT 'if there is a target div this will target it on button invoking',
  PRIMARY KEY (`button_id`),
  UNIQUE KEY `elem_id` (`elem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This will hold buttons of sensitve parts of the system';

-- --------------------------------------------------------

--
-- Table structure for table `sys_config`
--

DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE IF NOT EXISTS `sys_config` (
  `variable` varchar(128) NOT NULL,
  `value` varchar(128) DEFAULT NULL,
  `set_time` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `set_by` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_settings`
--

DROP TABLE IF EXISTS `sys_settings`;
CREATE TABLE IF NOT EXISTS `sys_settings` (
  `set_key` char(255) NOT NULL,
  `set_value` text DEFAULT NULL,
  `set_desc` text DEFAULT NULL,
  `set_status` int(11) DEFAULT 0,
  UNIQUE KEY `set_key` (`set_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_master`
--

DROP TABLE IF EXISTS `tax_master`;
CREATE TABLE IF NOT EXISTS `tax_master` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_trans`
--

DROP TABLE IF EXISTS `tax_trans`;
CREATE TABLE IF NOT EXISTS `tax_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_no` varchar(13) DEFAULT NULL,
  `doc` char(3) DEFAULT NULL,
  `item_code` int(11) NOT NULL,
  `tax_amt` decimal(10,2) DEFAULT 0.00,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `tax_code` char(2) DEFAULT NULL,
  `tran_amt` decimal(10,2) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `unit_qty` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_trans___fk__with_products` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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
  `db_access` varchar(5999) NOT NULL DEFAULT 'hello',
  `last_login_time` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

DROP TABLE IF EXISTS `user_access`;
CREATE TABLE IF NOT EXISTS `user_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `screen` int(11) NOT NULL,
  `read` int(11) DEFAULT 1,
  `write` int(11) DEFAULT 0,
  `print` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` char(45) DEFAULT NULL,
  `created_on` date DEFAULT curdate(),
  `created_on_time` time DEFAULT curtime(),
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `descr_UNIQUE` (`descr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_log`
--

DROP TABLE IF EXISTS `user_login_log`;
CREATE TABLE IF NOT EXISTS `user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `func` text NOT NULL,
  `date_created` date NOT NULL DEFAULT curdate(),
  `time` time DEFAULT curtime(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_task`
--

DROP TABLE IF EXISTS `user_task`;
CREATE TABLE IF NOT EXISTS `user_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `task_status` int(11) NOT NULL DEFAULT 1,
  `task` text NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zserial`
--

DROP TABLE IF EXISTS `zserial`;
CREATE TABLE IF NOT EXISTS `zserial` (
  `zSerial` int(11) NOT NULL,
  `mech_no` int(11) NOT NULL,
  `sales_date` date NOT NULL,
  `clerk_code` text NOT NULL,
  `shift_no` int(11) NOT NULL,
  `z_time` datetime DEFAULT current_timestamp(),
  `gross` decimal(10,2) DEFAULT NULL,
  `deduction` decimal(10,2) DEFAULT NULL,
  `net` decimal(10,2) DEFAULT NULL,
  `eod` int(11) DEFAULT 0,
  PRIMARY KEY (`zSerial`,`mech_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barcode`
--
ALTER TABLE `barcode`
  ADD CONSTRAINT `relation_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers_trans`
--
ALTER TABLE `customers_trans`
  ADD CONSTRAINT `customers_trans_clerk_id_fk` FOREIGN KEY (`user`) REFERENCES `clerk` (`id`),
  ADD CONSTRAINT `customers_trans_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `eod_serial`
--
ALTER TABLE `eod_serial`
  ADD CONSTRAINT `eod_serial_clerk_id_fk` FOREIGN KEY (`clerk_code`) REFERENCES `clerk` (`id`);

--
-- Constraints for table `invoice_hd`
--
ALTER TABLE `invoice_hd`
  ADD CONSTRAINT `invoice_hd_customers_customer_id_fk` FOREIGN KEY (`customer`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `invoice_hd_loc_loc_id_fk` FOREIGN KEY (`loc_id`) REFERENCES `loc` (`loc_id`);

--
-- Constraints for table `item_group_sub`
--
ALTER TABLE `item_group_sub`
  ADD CONSTRAINT `route_with_parent` FOREIGN KEY (`parent`) REFERENCES `item_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `loyalty_point_stmt`
--
ALTER TABLE `loyalty_point_stmt`
  ADD CONSTRAINT `loyalty_point_stmt_loy_customer_cust_code_fk` FOREIGN KEY (`cust_code`) REFERENCES `loy_customer` (`cust_code`);

--
-- Constraints for table `price_change`
--
ALTER TABLE `price_change`
  ADD CONSTRAINT `price_change_with_product` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prod_supplier`
--
ALTER TABLE `prod_supplier`
  ADD CONSTRAINT `prod_supplier_prod` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`) ON UPDATE CASCADE;

--
-- Constraints for table `prof_hd`
--
ALTER TABLE `prof_hd`
  ADD CONSTRAINT `prof_hd_clerk_id_fk` FOREIGN KEY (`created_by`) REFERENCES `clerk` (`id`),
  ADD CONSTRAINT `prof_hd_customers_customer_id_fk` FOREIGN KEY (`customer`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `stk_tran`
--
ALTER TABLE `stk_tran`
  ADD CONSTRAINT `stk_check_prod_mast_id_fk` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`);

--
-- Constraints for table `supp_mast`
--
ALTER TABLE `supp_mast`
  ADD CONSTRAINT `supp_mast_tax_master_id_fk` FOREIGN KEY (`tax_grp`) REFERENCES `tax_master` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tax_trans`
--
ALTER TABLE `tax_trans`
  ADD CONSTRAINT `tax_trans___fk__with_products` FOREIGN KEY (`item_code`) REFERENCES `prod_master` (`item_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
