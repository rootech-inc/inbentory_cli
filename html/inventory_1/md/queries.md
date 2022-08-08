### Queries

>**Item purchasing Packing Details** 
``select prod_packing.qty as 'qty_in_pack',prod_packing.pack_desc as 'pack_descr',`desc` as 'container_descr' from prod_packing
right join packaging pm on prod_packing.pack_id = pm.id
where prod_packing.item_code = 'item_code' and prod_packing.purpose = 2``

