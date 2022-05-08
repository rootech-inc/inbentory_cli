## reset PO
drop table po_hd;
CREATE TABLE `po_hd` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doc_no` char(13) DEFAULT NULL,
    `status` int(11) DEFAULT 0,
    `location` char(3) NULL,
    `suppler` char(13) NOT NULL,
    `type` char(13) NOT NULL,
    `remarks` text DEFAULT NULL,
    `total_amount` decimal(10, 2) DEFAULT NULL,
    `owner` text DEFAULT NULL,
    `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
    `edited_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `edited_by` char(30) DEFAULT NULL,
    `approved_by` char(13) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1000001 DEFAULT CHARSET = utf8mb4;

drop table po_trans;
CREATE TABLE `po_trans` (
    `prefix` varchar(2) NOT NULL DEFAULT 'PO',
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `item_code` char(13) DEFAULT NULL,
    `barcode` char(13) DEFAULT NULL,
    `item_description` text,
    `owner` char(13) DEFAULT NULL,
    `date_added` date DEFAULT curdate(),
    `pack_desc` char(13) DEFAULT NULL,
    `packing` text DEFAULT NULL,
    `qty` decimal(10, 2) DEFAULT 0.00,
    `cost` decimal(10, 2) DEFAULT 0.00,
    `total_cost` decimal(10, 2) DEFAULT 0.00,
    `parent` char(13) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1000001 DEFAULT CHARSET = utf8mb4

# select from po_hd
select * from po_hd;

# select from po_trans
select * from po_trans;


