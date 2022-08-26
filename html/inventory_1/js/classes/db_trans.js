class Db_trans {
    // add transaction
    new_doc_trans(doc,entry_no,func)
    {
        let form_data = {
            'function':'doc_trans',
            'doc':doc,
            'func': func,
            'entry_no':entry_no
        }

        // ajax call
        $.ajax({
           url:'backend/process/ajax_tools.php',
           type:'POST',
           data: form_data,
           success: function (response) {
               cl("Transaction Added for " + doc)
           } 
        });
    }

    // get item packing
    item_packing(item_code,packing_type)
    {
        let query = `select prod_packing.qty as 'qty_in_pack',prod_packing.pack_desc as 'pack_descr',\`desc\` as 'container_descr' from prod_packing right join packaging pm on prod_packing.pack_id = pm.id where prod_packing.item_code = '${item_code}' and prod_packing.purpose = '${packing_type}'`
        let res = {"firstName":"John", "lastName":"Doe"}

        if(packing_type === 1 || packing_type === 2)
        {
            // change res
            let exe = JSON.parse(fetch_rows(query))[0]
            //ct(exe)
            let qty_in_pack = exe['qty_in_pack']
            let pack_descr = exe['pack_descr']
            let container_desc = exe['container_descr']

            //cl(`${qty_in_pack} | ${pack_descr} | ${container_desc}`)
            // ct(exe_res)
            res = {
                'qty_in_pack': qty_in_pack, 'pack_descr': pack_descr, 'container_desc': container_desc
            }

        }


        return res
    }

}