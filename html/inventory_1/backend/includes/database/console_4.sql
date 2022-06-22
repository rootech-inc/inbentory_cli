select * from grn_hd;
select * from loc;
select * from supp_mast;
select * from tax_master;

select * from po_hd;
select * from po_trans;

select * from grn_hd;
select * from grn_trans;

select * from prod_master;

update grn_hd
set status = 0
where entry_no = 'GR1000001';
select * from price_change;


select * from tax_master;

create table grn_trans_06_19_22 select * from grn_trans;

select * from prod_packing where purpose = 2;

-- fetch for po joined
select prod_master.item_code as item_code,barcode,prod_master.item_desc, pp.qty as pack_qty, pp.pack_desc as pack_desc
from prod_master
    right join prod_packing
        pp on prod_master.item_code = pp.item_code where pp.purpose = 2

