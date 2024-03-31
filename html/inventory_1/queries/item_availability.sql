create
    definer = root@`%` procedure item_availability(IN loc_to_param int, IN as_of_date_param date, IN show_sock int)
BEGIN
    if show_sock = 0 THEN
        SELECT loc_to                            AS 'loc_id',
               pm.item_code                      AS 'item_code',
               pm.barcode                        AS 'barcode',
               pm.item_desc,
               SUM(IFNULL(stk_tran.tran_qty, 0)) AS 'stock'
        FROM prod_master pm
                 LEFT JOIN stk_tran ON pm.item_code = stk_tran.item_code
        WHERE loc_to = loc_to_param
          AND DATE(stk_tran.date_created) <= as_of_date_param
        GROUP BY pm.item_code, loc_to, pm.barcode, pm.item_desc;
    ELSE
        SELECT loc_to                            AS 'loc_id',
               pm.item_code                      AS 'item_code',
               pm.barcode                        AS 'barcode',
               pm.item_desc,
               SUM(stk_tran.tran_qty) AS 'stock'
        FROM prod_master pm
                 LEFT JOIN stk_tran ON pm.item_code = stk_tran.item_code
        WHERE loc_to = loc_to_param
          AND DATE(stk_tran.date_created) <= as_of_date_param
        GROUP BY pm.item_code, loc_to, pm.barcode, pm.item_desc;
    END IF;
END;



