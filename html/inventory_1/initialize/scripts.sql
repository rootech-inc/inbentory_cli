use venta;
SELECT * FROm eod_serial;

truncate table user_group;
insert into user_group (descr,remarks) values ('System Administrators','Adminsirative permissions'),('Clerks','Sales Personnel');
DELETE FROM clerk where id > 0;
insert into clerk (id,clerk_code,clerk_key,clerk_name,user_grp,pin,token) values 
(1,'411','17d63b1625c816c22647a73e1482372b','Admin',1,1444,1444);
DELETE FROM tax_master where id > 0;
INSERT INTO tax_master (id,description,rate,owner,active,attr) values (1,'Not Taxable',0.00,1,1,'NON');
DELETE FROM packaging where id > 0;
INSERT INTO packaging (id,`desc`) values (1,'PCS'),(2,'CTN'),(3,'LIT'),(4,'KG');
DELETE FROM stock_type where id > 0;
INSERT INTO stock_type (id,description) values (1,'Regular'),(2,'Non-Stock'),(3,'Discontinued');
TRUNCATE table system_buttons;
INSERT INTO system_buttons (button_id, module, sub_module, sub_sub_module, descr, elem_id, elem_name, status, target_id) VALUES 
(1, 'inventory', 'products', 'product_details', 'PRICES', 'inv_prod_prices', 'inv_prod_prices', 1, 'price'), 
(2, 'inventory', 'products', 'product_details', 'STOCK', 'inv_prod_stock', 'inv_prod_stock', 1, 'stock'),
(3, 'inventory', 'products', 'product_details', 'PACKING', 'inv_prod_packing_tab', 'inv_prod_packing_tab', 1, 'packing_tab'),
(4, 'inventory', 'products', 'product_details', 'BARCODE', 'inv_prod_more_barcode', 'inv_prod_more_barcode', 1, 'more_barcode'),
(5, 'inventory', 'products', 'product_details', 'SUPPLIER', 'inv_prod_more_supplier', 'inv_prod_more_supplier', 1, 'more_supplier');


