
$(document).keyup(function (event) {
    var key_pressed = event.which

    //echo(key_pressed + " Pressed ")
    if(key_pressed === 121 ) // f10 for search
    {
        searchTrigger()
    }
});

function loadProduct(prod_id,action='view')
{
    // echo(prod_id)
    // get item as json from database



    // disable next and back
    if(row_count('prod_master',"`item_code` > '" + prod_id +"'") > 0)
    {
        // enable next
        arr_enable('sort_right')
        // set next value
        $('#sort_right').val(
            JSON.parse(get_row('prod_master',"`item_code` > '" + prod_id + "' LIMIT 1"))[0].item_code
        )

    } else
    {
        // disable next
        arr_disable('sort_right')
    }
    if(row_count('prod_master',"`item_code` < '" + prod_id +"'") > 0)
    {
        // enable prev
        arr_enable('sort_left');
        // set previous value
        $('#sort_left').val(
            JSON.parse(get_row('prod_master',"`item_code` < '" + prod_id + "' order by `item_code` desc LIMIT 1"))[0].item_code
        )

    } else
    {
        // disable prev
        arr_disable('sort_left');
    }


    let product_row, prod_object, prod_result;

    product_row = get_row('prod_master',"`item_code` = '" + prod_id.toString() + "'")
    prod_object = JSON.parse(product_row)
    prod_result = prod_object[0]
    var item_code = product_row.item_code

    //echo(product_row)
    // load values

    if (action === 'edit') {
        $('#edit_prod').val(prod_id)
        $("#group").val(
            JSON.parse(get_row('item_group', "`id` = '" + prod_result.group + "'"))[0].group_name
        )
        $("#sub_group").val(
            JSON.parse(get_row('item_group_sub', "`id` = '" + prod_result.sub_group + "'"))[0].description
        )
        $("#supplier").val(
            JSON.parse(get_row('supp_mast', "`supp_id` = '" + prod_result.supplier + "'"))[0].supp_name
        )
        $('#barcode').val(prod_result.barcode)
        $('#item_desc').val(prod_result.item_desc)
        $('#item_desc1').val(prod_result.item_desc1)

        // $("#packing").val(
        //     JSON.parse(get_row('packaging',"`id` = '" + prod_result.packing + "'"))[0].desc
        // )

        // get packing
        let packing_id = prod_result.packing;
        let all_packing = JSON.parse(get_row('packaging', "none"));
        var pack_opt = '';
        for (let i = 0, p_id, p_desc; i < all_packing.length; i++) {
            p_id = all_packing[i].id;
            p_desc = all_packing[i].desc
            if (packing_id == p_id) {
                pack_opt += "<option selected value='" + p_id + "'>" + p_desc + "</option>";
            } else {
                pack_opt += "<option value='" + p_id + "'>" + p_desc + "</option>";
            }

        }
        $('#packing').html(pack_opt)
        //echo(pack_opt)

        // get groups
        let all_grp, active_grp, grp_opt = '', grp_name, grp_id;
        all_grp = JSON.parse(
            // all groups query
            get_row('item_group', 'none')
        );

        // loop through grps obj
        for (let i = 0; i < all_grp.length; i++) {
            grp_id = all_grp[i].id
            grp_name = all_grp[i].group_name

            if (grp_id == prod_result.group) {
                grp_opt += "<option selected value='" + grp_id + "'>" + grp_name + "</option>";
            } else {
                grp_opt += "<option value='" + grp_id + "'>" + grp_name + "</option>";
            }


        }
        $('#group').html(grp_opt)
        //echo(grp_opt)

        // load sub groups
        let all_sub_grps = JSON.parse(
            // get sub groups
            get_row('item_group_sub', "`parent` = '" + prod_result.group + "'")
        )
        // loop through groups
        let all_sub_grps_opt = '';
        for (let i = 0; i < all_sub_grps.length; i++) {
            let id = all_sub_grps[i].id;
            let desc = all_sub_grps[i].description;

            // append option
            if (id == prod_result.sub_group) {
                all_sub_grps_opt += "<option selected value='" + id + "'>" + desc + "</option>";
            } else {
                all_sub_grps_opt += "<option value='" + id + "'>" + desc + "</option>";
            }
        }
        $('#sub_category').html(all_sub_grps_opt)
        //echo(all_sub_grps_opt)

        // get suppliers
        let all_supp = JSON.parse(
            // get sub groups
            get_row('supp_mast', "none")
        )
        // loop through groups
        let supp_sub = '';
        for (let i = 0; i < all_supp.length; i++) {
            let id = all_supp[i].supp_id;
            let desc = all_supp[i].supp_name;

            // append option
            if (id == prod_result.supplier) {
                supp_sub += "<option selected value='" + id + "'>" + desc + "</option>";
            } else {
                supp_sub += "<option value='" + id + "'>" + desc + "</option>";
            }
        }
        $('#supplier').html(supp_sub)
        //echo(supp_sub)


        // get tax
        let all_tax = JSON.parse(
            // get sub groups
            get_row('tax_master', "none")
        )
        // loop through groups
        let tax_row = '';
        console.table(all_tax)
        for (let i = 0; i < all_tax.length; i++) {
            let rate = all_tax[i].rate + '% ';
            let id = all_tax[i].id;
            let desc = all_tax[i].description;
            let tax_code = all_tax[i].attr



            // append option
            if (id == prod_result.tax) {
                tax_row += `<option selected value="${id}">${tax_code}</option>`
                $('#tax_descr').text(desc)
            } else {
                tax_row += `<option value="${id}">${tax_code}</option>`
            }
        }
        console.log(tax_row)
        $('#prod_tax').html(tax_row)



        $('#expiry').val(prod_result.expiry_date)
        $('#owner').text(prod_result.owner)
        $('#created_at').text(prod_result.created_at)
        $('#edited_at').text(prod_result.edited_at)
        $('#edited_by').text(prod_result.edited_by)

        // get tax details
        var tax = JSON.parse(get_row('tax_master', "`id` = '" + prod_result.tax + "'"))[0];
        // cl('#####TAX')
        // ct(tax)
        // cl('#####TAX')
        $('#tax_rate').text(tax.rate.toString() + "%")
        $('#tax_descr').html(tax.description)
        $('#cost_price').val(prod_result.cost)
        let retail_price = prod_result.retail;
        $('#retail_with_tax').val(retail_price)


        let tax_details = taxMaster.getTax(tax['attr'],prod_result.retail)['details'];
        let taxableAmt = tax_details['taxableAmt'];


        $('#retail_without_tax').val(taxableAmt)

        // get stock for various branches
        if (row_count('stock', "`item_code` = '" + prod_id + "'") > 0) {
            var row = '';
            let loc_id, location, qty;
            // get stock in json
            var stock = JSON.parse(
                get_row('stock', "`item_code` = '" + prod_id + "'")
            );
            for (let i = 0; i < stock.length; i++) {

                qty = stock[i].qty;
                loc_id = stock[i].loc_id
                location = JSON.parse(get_row('loc', "`loc_id` = '" + loc_id + "'"))[0].loc_desc


                //echo(stock[i].qty)

                row += "<div class=\"w-100 d-flex flex-wrap prod_inp_container\">\n" +
                    "                            <div class=\"prod_inp_descriptio d-flex flex-wrap align-content-center\">\n" +
                    "                                <p class=\"m-0 p-0 text-elipse\">" + loc_id + " - " + location + "</p>\n" +
                    "                            </div>\n" +
                    "                            <div class=\"prod_inp_view\">" + qty + "</div>\n" +
                    "                        </div>";

            }
            $('#stock').html(row)
        } else {
            $('#stock').html("No Stock")
        }

        // get packing
        let pac_id = prod_result.packing
        const packing = JSON.parse(get_row('prod_packing', "`item_code` = '" + prod_id + "'"));

        let pack_desc;
        var package_row = '';
        let index_id = '';
        let purp;
        for (let i = 0; i < packing.length; i++) {
            let pack = packing[i], pack_id, qty, purpose;
            pack_id = JSON.parse(get_row('packaging', "`id` = '" + pack.pack_id + "'"))[0].desc;
            qty = pack.qty
            purpose = pack.purpose
            pack_desc = pack.pack_desc
            index_id += 'pack_id'+pack.id+',';
            //echo(index_id)


            if (purpose === 1) {
                purp = 'Sell UN';
            } else if (purpose === 2) {
                purp = 'Purch UN'
            }

            package_row += "<tr class=\"thead-light\">\n" + "<td class=\"p-1\">" + purp + "</td>\n" +
                "                                        <td class=\"p-1\"><select name='packaging_id[]' id='pack_id" + index_id + "'>" +pack_opt +
                "</select></td>\n" +
                "                                        <td class=\"p-1\"><input style='width: 100px' name='packaging_desc[]' value=' " + pack_desc + "' required</td>\n" +
                "                                        <td class=\"p-1\"><input style='width: 100px' name='packaging_qty[]' value=' " + qty + "' required</td>\n" +
                "                                    </tr>";


        }
        //echo(package_row)
        $('#packaging_row').html(package_row)
        $('#pack_id').html(pack_opt)

        // load stock type
        let all_stock = '', stock_option ='',
            active_stock = prod_result.stock_type

        all_stock = JSON.parse(
            get_row('stock_type','none')
        )
        for (let stock_id ='', stock_desc ='', i = 0; i < all_stock.length; i++) {
            stock_id = all_stock[i].id;
            stock_desc = all_stock[i].description
            //echo(active_stock)
            if(active_stock == stock_id)
            {
                stock_option += "<option selected value='"+stock_id+"'>"+stock_desc+"</option>";
            } else {
                stock_option += "<option value='"+stock_id+"'>"+stock_desc+"</option>";
            }


        }
        //echo(stock_option)
        $('#stock_type').html(stock_option)

    }
    else
    {

        $('#edit_prod').val(prod_id)
        $("#group").text(
            JSON.parse(get_row('item_group', "`id` = '" + prod_result.group + "'"))[0].group_name
        )
        $("#sub_group").text(
            JSON.parse(get_row('item_group_sub', "`id` = '" + prod_result.sub_group + "'"))[0].description
        )
        $("#supplier").text(
            JSON.parse(get_row('supp_mast', "`supp_id` = '" + prod_result.supplier + "'"))[0].supp_name
        )
        $('#barcode').text(prod_result.barcode)
        $('#item_desc').text(prod_result.item_desc)
        $('#item_desc1').text(prod_result.item_desc1)

        $("#packing").text(
            JSON.parse(get_row('packaging', "`id` = '" + prod_result.packing + "'"))[0].desc
        )
        $('#expiry').text(prod_result.expiry_date)
        $('#owner').text(prod_result.owner)
        $('#created_at').text(prod_result.created_at)
        $('#edited_at').text(prod_result.edited_at)
        $('#edited_by').text(prod_result.edited_by)

        // get tax details
        var tax = JSON.parse(get_row('tax_master', "`id` = '" + prod_result.tax + "'"))[0];
        let taxx = taxMaster.getTax(tax.attr,prod_result.retail);
        let tax_code, tax_detail,tax_header
        tax_header = taxx['header']
        tax_code = tax_header['code']
        tax_detail = taxx['details']


        $('#tax_rate').text(tax['attr'])
        $('#tax_desc').html(tax['description'])
        $('#cost_price').text(prod_result.cost)
        let retail_price = prod_result.retail;
        $('#retail_price').text(retail_price)


        $('#retail_price_without_tax').text(tax_detail['taxableAmt'].toFixed(2))

        // get stock for various branches
        if (row_count('stock', "`item_code` = '" + prod_id + "'") > 0) {
            var stock_row = '';
            let loc_id, location, qty,loc_desc;
            // get stock in json
            // var stock = JSON.parse(
            //     get_row('stock', "`item_code` = '" + prod_id + "'")
            // );
            let stock_query = return_rows(`select loc_to as 'loc_id',(select loc_desc from loc where loc_id = stk_tran.loc_to) as 'loc_desc',item_code,SUM(tran_qty) as 'qty' from stk_tran where item_code = '${prod_id}' group by loc_to, item_code;`);
            let stock = JSON.parse(stock_query);

            if(stock.length > 0){
                for (let i = 0; i < stock.length; i++) {

                    qty = stock[i].qty;
                    loc_id = stock[i].loc_id
                    loc_desc = stock[i].loc_desc



                    stock_row += "<div class=\"w-100 d-flex flex-wrap prod_inp_container\">\n" +
                        "                            <div class=\"prod_inp_descriptio d-flex flex-wrap align-content-center\">\n" +
                        "                                <p class=\"m-0 p-0 text-elipse\">" + loc_id + " - " + loc_desc + "</p>\n" +
                        "                            </div>\n" +
                        "                            <div class=\"prod_inp_view\">" + qty + "</div>\n" +
                        "                        </div>";

                }
            } else {
                stock_row = `<p class="text-dark display-4 font-weight-bolder">NO STOCK</p>`
            }


            $('#stock').html(stock_row)
        } else {
            $('#stock').html("No Stock")
        }

        // get packing
        let pac_id = prod_result.packing
        const packing = JSON.parse(get_row('prod_packing', "`item_code` = '" + prod_id + "'"));

        let pack_desc;
        var package_row = '';
        let purp;
        for (let i = 0; i < packing.length; i++) {
            let pack = packing[i], pack_id, qty, purpose;
            pack_id = JSON.parse(get_row('packaging', "`id` = '" + pack.pack_id + "'"))[0].desc;
            qty = pack.qty
            purpose = pack.purpose
            pack_desc = pack.pack_desc
            if (purpose === 1) {
                purp = 'Selling Unit';
            } else if (purpose === 2) {
                purp = 'Purchasing Unit'
            }

            package_row += "<tr class=\"thead-light\">\n" +
                "                                        <td class=\"p-1\">" + pack_id + "</td>\n" +
                "                                        <td class=\"p-1\">" + pack_desc + "</td>\n" +
                "                                        <td class=\"p-1\">" + qty + "</td>\n" +
                "                                        <td class=\"p-1\">" + purp + "</td>\n" +
                "                                    </tr>";


        }
        $('#packaginf_row').html(package_row)

        // get barcodes
        let barcode_row = "";
        let barcodes = JSON.parse(get_row('barcode',`item_code = '${prod_result.item_code}'`));
        for (let i = 0; i < barcodes.length; i++) {
            let this_barcode = barcodes[i]
            let this_barcode_barcode, this_barcode_item
            this_barcode_barcode = this_barcode.barcode
            this_barcode_item = this_barcode.item_desc
            barcode_row += `<tr><td class="p-1">${this_barcode_barcode}</td><td class="p-1">${this_barcode_item}</td></tr>`
        }
        $('#more_barcode_row').html(barcode_row)

        //get suppliers
        let supp_row = "";
        var suppliers = JSON.parse(fetch_rows(`select supplier_code,supp_name,level from supp_mast right join prod_supplier ps on supp_mast.supp_id = ps.supplier_code where item_code = '${prod_result.item_code}' order by ps.level;`));
        for (let i = 0; i < suppliers.length; i++) {
            let supplier = suppliers[i]
            let supp_code,supp_desc,supp_lev
            supp_code = supplier.supplier_code;
            supp_desc = supplier.supp_name
            supp_lev = supplier.level

            supp_row = `<tr><td class="p-1">${supp_code}</td><td class="p-1">${supp_desc}</td><td class="p-1">${supp_lev}</td></tr>`
        }
        $('#more_supplier_row').html(supp_row)

    }



    // show price
    
    arr_hide('stock,packing_tab');arr_show('price')


}

function catDesc(val) {
    if($('#short_description').val(val).length <= 10)
    {
        $('#short_description').val(val.substr(0,15))
    }
}
//lleho
function newProductSubGroup(group) // load sub groups for selected group
{
    let sub_row, sub_object, sub_result,sub_id,sub_desc;

    // get subsjh
    sub_row = get_row('item_group_sub',"`parent` = '" + group + "'")
    sub_object = JSON.parse(sub_row)

    let option = '';
    for (let i = 0; i < sub_object.length; i++) {
        sub_id = sub_object[i].id;
        sub_desc = sub_object[i].description

        option += "<option value='" + sub_id + "'>" + sub_desc + "</option>"


    }
    //echo(option)
    $('#sub_category').html(option)


}

function newProductTaxCalculate(val)
{
    let tax_rate;

    tax_rate = $('#tax').val();

    if(tax_rate !== 'null')
    {
        // calculate oercentage

        let retail_with_tax = percentage(tax_rate,val)

        //echo(parseFloat(retail_with_tax) + parseFloat(val))
    }


}





function retailWithoutTax()
{

    let tax_rate,tax_id,val,tax;

    val = $('#retail_with_tax').val()

    tax_id = $('#prod_tax').val();

    tax = JSON.parse(get_row('tax_master',`id = '${tax_id}'`))[0]
    if(row_count('tax_master',`id = '${tax_id}'`) === 1)
    {

        tax_rate = tax.rate
        let tax_desc = tax.description
        $('#tax_descr').text(tax_desc)


        if(tax_rate !== 'null')
        {
            // calculate oercentage
            let form_data = {
                'function':'get_tax_val',
                'tax_code':tax.attr,
                'amount':val
            }

            //ctform_data)

            $.ajax({
                url:'/backend/process/form-processing/sys.php',
                data:form_data,
                type:'POST',
                success: function (response) {
                    ct(response)
                    let r = JSON.parse(response)
                    let details = r['details']
                    let code = r['code']
                    let withoutTax = details['withoutTax']

                    //cl("NEW TAX VAL")
                    //ctdetails)
                    //cl('NEW TAX VALUE')

                    $('#retail_without_tax').val(details['taxableAmt'].toFixed(2))

                }
            });


        }
    } else {
        al("CANNOT FIND TAX")
    }
}

function searchTrigger(){ // trigger search bar

    $('#barcode_search').show(500)
    $('#barcode_search').focus()
}

$('#barcode_search').on('keyup',function (e) {
    let key = e.which, barcode, item_code;
    if(key === 13)
    {
        // search
        barcode = $('#barcode_search').val();
        //echo(barcode)
        if(row_count('prod_master',"`barcode` = '" + barcode + "'") === '1')
        {
            // get item code
            item_code = JSON.parse(get_row('prod_master',"`barcode` = '" + barcode + "'"))[0].item_code;

            // hide search input anf clear
            $('#barcode_search').val('')
            $('#barcode_search').fadeOut(500)
            // load item
            loadProduct(item_code)


            //echo(item_code)
        }
        else {
            $('#barcode_search').val('')
            alert("Item Not Found")

        }
    }
})



