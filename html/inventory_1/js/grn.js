function searchTrigger(){ // trigger search bar

    $('#po_search').show(500)
    $('#po_search').focus()
}

$('#po_search').on('keyup',function (e) // search for po
{
    let key = e.which, po_number;
    let po_details;

    if (key === 13) {
        // get po value and validate
        po_number = $('#po_search').val()
        var po_exist = row_count('po_hd', "`doc_no` = '" + po_number + "'");
        var grn_made = row_count('grn_hd', "`po_number` = '" + po_number + "'")
        if (po_exist === 1) {

            if (grn_made === 1) {
                swal_error("Goods has been received")
            } else


            if (grn_made === 0) {
                po_details = JSON.parse(
                    get_row('po_hd', "`doc_no` = '" + po_number + "'")
                )[0]

                // check if po is approved
                var po_status = po_details.status;
                if (po_status === 1) {

                    // header details

                    var supplier = JSON.parse(get_row('supp_mast', "`supp_id` = '" + po_details.suppler + "'"))[0].supp_name
                    var loc = po_details.location
                    var location_desc = JSON.parse(get_row('loc', "`loc_id` = '" + loc + "'"))[0].loc_desc


                    // populate header
                    set_value('loc_id', loc)
                    set_text('loc_desc', location_desc)
                    set_value('supp_id', po_details.suppler)
                    set_text('supplier', supplier)
                    set_value('ref_doc', po_number)
                    set_value('remarks', po_details.remarks)
                    arr_enable('tax_grp')

                    // get po trans
                    var po_trans_rows = row_count('po_trans', "`parent` = '" + po_number + "'");
                    echo("#### Transaction Rows : " + po_trans_rows)
                    if (po_trans_rows > 0) {
                        // there is po trans items
                        var po_trans = JSON.parse(
                            get_row('po_trans', "`parent` = '" + po_number + "'")
                        )
                        var sn = 0;
                        var tr = "";
                        var grn_total = 0;
                        for (let t = 0; t < po_trans.length; t++) {
                            var po_tran = po_trans[t]
                            let item_code, barcode, description, pack_id, packing, qty, price, total_amt, tax_amount,
                                net_amount, retail, pack_um, cost, this_cost;
                            item_code = po_tran.item_code
                            barcode = po_tran.barcode
                            description = po_tran.item_description
                            pack_id = po_tran.pack_desc
                            packing = po_tran.packing
                            qty = po_tran.qty
                            price = po_tran.cost
                            total_amt = po_tran.total_cost
                            pack_um = po_tran.pack_um
                            cost = po_tran.cost
                            this_cost = parseFloat(price) / parseFloat(pack_um)
                            tax_amount = 0
                            net_amount = total_amt - tax_amount
                            retail = JSON.parse(
                                get_row('prod_master', "`barcode` = '" + barcode + "'")
                            )[0].retail

                            sn++
                            grn_total += parseFloat(net_amount);
                            echo("##### " + total_amt)


                            // ids
                            var qty_id = "qty_" + sn.toString();
                            var price_id = 'price_' + sn.toString();
                            var total_id = 'total_' + sn.toString();
                            var tr_id = 'row_' + item_code.toString();
                            let cost_id = 'cost_' + sn.toString()
                            let retail_id = 'retail_' + sn.toString()
                            let code_id = 'code_id_' + sn.toString()
                            let net_id = 'net_' + sn.toString()

                            let retail_bg = '';
                            if (this_cost >= retail) {
                                // danger
                                retail_bg = 'bg-danger'
                            }


                            tr += "<tr id='" + tr_id + "'>\n" +
                                "                            <td class='text_xs'><input type='hidden' name='item_code[]' id='" + code_id + "' value='" + item_code + "'>" + sn + "</td>\n" +
                                "                            <td class='text_xs'>" + barcode + "</td>\n" +
                                "                            <td class='text_xs'>" + description + "</td>\n" +
                                "                            <td class='text_xs'>" + pack_id + "</td>\n" +
                                "                            <td class='text_xs'>" + packing + "</td>\n" +
                                "                            <td class='text_xs'><input type='number' onkeyup=\"grn_list_calc(" + sn + ")\" name='qty[]' id='" + qty_id + "' class='grn_nums' value='" + qty + "'></td>\n" +
                                "                            <td class='text_xs'><input type='number' onkeyup=\"grn_list_calc(" + sn + ")\" name='price[]' id='" + price_id + "' class='grn_nums' value='" + price + "'></td>\n" +
                                "                            <td class='text_xs'><input type='number' readonly name='total_amt[]' id='" + total_id + "' class='grn_nums' value='" + total_amt + "'></td>\n" +
                                "                            <td class='text_xs'>" + tax_amount + "</td>\n" +
                                "                            <td class='text_xs' id='" + net_id + "'>" + net_amount.toFixed(2) + "</td>\n" +
                                "                            <td class='text_xs'><input type='number' id='" + cost_id + "' class='grn_nums' onkeyup=\"grn_list_calc(" + sn + ")\" name='cost[]' value='" + this_cost.toFixed(2) + "'></td>\n" +
                                "                            <td class='text_xs'><input type='number' id='" + retail_id + "' class='grn_nums "+retail_bg+"' onkeyup=\"grn_list_calc(" + sn + ")\" name='retail[]' value='" + retail + "'></td>\n" +
                                "                            <td class='text_xs'><i class='fa fa-minus pointer text-danger pointer' onclick='remove_grn_item(\"" + description + "\",\"#" + tr_id + "\")'></i></td>" +
                                "                        </tr>";
                            echo(sn)
                        }
                        $('#po_items_list').html(tr)
                        // get grn total
                        echo(grn_total)
                        set_value('total_amount', grn_total.toFixed(2))
                        arr_enable('new_item')
                        $('#new_item').show()

                    } else {
                        swal_error("No items in PO Transaction")
                    }
                } else if (po_status === 0) {
                    swal_error("Document " + po_number + " Has not been approved")
                } else {
                    swal_error("Document " + po_number + " Not Found")
                }

            }


            // hide po search
            $('#po_search').val('')
            $('#po_search').hide(500)
        } else {
            swal.fire(po_number + " Does Not Exist")
            $('#po_search').focus()
        }

        echo("Search Fired")
    } else {
        // do nothin
        echo("Keep Inputting")
    }
})

// find item for grn
$('#new_grn_item').on('keyup', function (e) {
    let item_detail = $('#new_grn_item').val()
    let supp_id = $('#supp_id').val()

    // var grn_list = JSON.parse(
    //     get_row('prod_mast',"`item_code` like '%"+item_detail+"%' OR item_desc like '%"+item_detail+"%' OR barcode like '%"+item_detail+"%' AND supplier = '"+supp_id+"'")
    // );

    var xx = JSON.parse(
        get_row('prod_master',"`barcode` like '%"+item_detail+"%' OR `item_code` like '%"+item_detail+"%' OR `item_desc` like '%"+item_detail+"%'AND `supplier` = '"+supp_id+"'")
    )

    let product;
    let item_code;
    let barcode;
    let item_desc;
    if (xx.length > 0) {
        var tr = '';
        for (let line = 0; line < xx.length; line++) {
            product = xx[line]
            item_code = product.item_code
            barcode = product.barcode
            item_desc = product.item_desc

            tr += "<tr ondblclick=\"new_line('grn',"+item_code+")\">\n" +
                "                                                <td>"+barcode+"</td>\n" +
                "                                                <td>"+item_desc+"</td>\n" +
                "                                            </tr>"
        }

        // append
        $('#grn_item_search_table').html(tr)
    } else {
        $('#grn_item_search_table').html("No item match for suppler qurying against item_code, barcode, and description")
    }

});


// calculate grn list value
function grn_list_calc(sn) {
    // define ids
    var qty_id = "#qty_"+sn.toString();
    var price_id = '#price_'+sn.toString();
    var total_id = '#total_'+sn.toString();
    let cost_id = '#cost_'+sn.toString()
    let retail_id = '#retail_'+sn.toString()
    let code_id = '#code_id_'+sn.toString()
    let net_id = '#net_'+sn.toString()

    // get field values
    var item_code = $(code_id).val();
    var qty_val = $(qty_id).val();
    var price_val = $(price_id).val()
    var total_val = $(total_id).val()

    // item details
    var item_detail = JSON.parse(
        get_row('prod_master',"`item_code` = '"+item_code+"'")
    )[0]
    let item_pack_details = JSON.parse(get_row('prod_packing',"`item_code` = '"+item_code+"' AND `purpose` = 2"))[0]
    let item_details = JSON.parse(get_row('prod_master',"`item_code` = '"+item_code+"'"))[0]
    let retail = item_details.retail

    var pack_qty = item_pack_details.qty;

    var new_total = qty_val * price_val;

    // cost price = price / un
    var cost_price = price_val / pack_qty


    echo(qty_val + " * " + price_val + " = " + new_total.toFixed(2))

    $(total_id).val(new_total.toFixed(2))
    $(cost_id).val(cost_price.toFixed(2))
    $(net_id).text(new_total.toFixed(2))

    if(cost_price >= retail)
    {
        // danger
        $(retail_id).addClass('bg-danger')
    } else {

        $(retail_id).removeClass('bg-danger')
    }




    // echo("Quantity : " + qty_val.toString() + " Price : " + price_val.toString() + " Total : " + total_val.toString() + " New Total : " + new_total.toFixed())

}

function new_grn_tax_calc(tax) {
    if(tax === '1')
    {
        var supplier = $('#supp_id').val();

        // check if supplier exist
        if(row_count('supp_mast',"`supp_id` = '"+supplier+"'") === 1)
        {

            // get supplier tax group
            var sup_tax_grp = JSON.parse(
                get_row('supp_mast',"`supp_id` = '"+supplier+"'")
            )[0].tax_grp

            // check if tax group exist
            if(row_count('tax_master',"`id` = '"+sup_tax_grp+"'") === 1)
            {
                // get tax class
                var tax_class = JSON.parse(
                    get_row('tax_master',"`id` = '"+sup_tax_grp+"'")
                )[0].cls

                var invoice_amount = $('#total_amount').val();

                var tax_value = tax_input(invoice_amount,tax_class)
                swal_error(tax_value)

            } else {
                swal_error("Tax Group for supplier ( "+supplier+" ) does not exist")
            }

        } else
        {
            swal_error("Cant find suppler")
        }

    }
}

// remove item from grn list
function remove_grn_item(item_description,id)
{
    Swal.fire({

        html: "<p>Are your sure you want remove <i class='text-danger'>"+item_description+"</i> from list?</p>",
        icon: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            // make ajax call
            $(id).remove();
        } else if (result.isDenied) {

        }
    })
}

// save grn
$(document).ready(function (){
    $('#save_grn').on('click', function(){
        // check all grn items have quantity
        let last_row = $('#po_items_list tr').length;
        let qty;
        let price;
        let total;
        let cost;
        let retail;
        var error = 0;
        var error_log = '';
        for (let sn = 1; sn <= last_row; sn++) {
            let tr_id = '#row_' + sn.toString()
            let price_id = '#price_' + sn.toString();
            let qty_id = "#qty_" + sn.toString();
            let total_id = '#total_' + sn.toString();
            let cost_id = '#cost_' + sn.toString()
            let retail_id = '#retail_' + sn.toString()
            let code_id = '#code_id_' + sn.toString()
            let net_id = '#net_' + sn.toString()

            qty = $(qty_id).val()
            price = $(price_id).val()
            total = $(total_id).val()
            cost = parseFloat($(cost_id).val())
            retail = parseFloat($(retail_id).val())

            // check quantity
            if(qty < 1)
            {
                $(qty_id).addClass('bg-warning')
                error ++;
                error_log += "<p class='border border-bottom'>Line "+sn+" : Quantity is less than 1</p>";
            } else
            {
                $(qty_id).removeClass('bg-warning')
            }

            //check price
            if(price < 1)
            {
                $(price_id).addClass('bg-warning')
                error ++;
                error_log += "<p class='border border-bottom'>Line "+sn+" : Peice is less than 1.00</p>";
            } else
            {
                $(price_id).removeClass('bg-warning')
            }

            // check cost retail
            if(cost >= retail)
            {

                $(retail_id).addClass('bg-danger');
                error ++
                if(cost > retail)
                {
                    error_log += "<p class='border border-bottom'>Line "+sn+" : Retail price is Less than cost</p>";
                }
                else
                {
                    error_log += "<p class='border border-bottom'>Line "+sn+" : Retail price is equal to cost</p>";
                }

            } else
            {
                $(retail_id).removeClass('bg-danger');
            }

            cl("Line " + sn + " has " + qty + " item in quantity")
        }

        // check if location exist
        var loc_id = $('#loc_id').val();
        if(row_count('loc',"`loc_id` = '"+loc_id+"'") !== 1)
        {
            error += 1;
            error_log += "Location Does Not Exist";
        }

        // check supplier
        var supp_id = $('#supp_id').val();
        if(row_count('supp_mast',"`supp_id` = '"+supp_id+"'") !== 1)
        {
            error += 1;
            error_log += "Suppler Does Not Exist";
        }

        // check if grn exist
        var ref_doc = $('#ref_doc').val();
        if(row_count('grn_hd',"`po_number` = '"+ref_doc+"'") > 0)
        {
            error += 1;
            error_log += "GRN Made for PO";
        }

        //TODO:: Applying tax

        if(error === 1)
        {
            swal_error("There is "+error+" error " + error_log)
        } else if (error > 1)
        {
            swal_error("There are "+error+" error(s) " + error_log)
        }
        else
        {
            // submit form
            $('#general_form').submit()
        }

    });
});