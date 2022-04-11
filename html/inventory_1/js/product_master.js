
$(document).keyup(function (event) {
    var key_pressed = event.which

    echo(key_pressed + " Pressed ")
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

    echo(product_row)
    // load values
    $("#group").text(
        JSON.parse(get_row('item_group',"`id` = '" + prod_result.group + "'"))[0].group_name
    )
    $("#sub_group").text(
        JSON.parse(get_row('item_group_sub',"`id` = '" + prod_result.sub_group + "'"))[0].description
    )
    $("#supplier").text(
        JSON.parse(get_row('supp_mast',"`supp_id` = '" + prod_result.supplier + "'"))[0].supp_name
    )
    $('#barcode').text(prod_result.barcode)
    $('#item_desc').text(prod_result.item_desc)
    $('#item_desc1').text(prod_result.item_desc1)

    $("#packing").text(
        JSON.parse(get_row('packaging',"`id` = '" + prod_result.packing + "'"))[0].desc
    )
    $('#expiry').text(prod_result.expiry_date)
    $('#owner').text(prod_result.owner)
    $('#created_at').text(prod_result.created_at)
    $('#edited_at').text(prod_result.edited_at)
    $('#edited_by').text(prod_result.edited_by)

    // get tax details
    var tax = JSON.parse(get_row('tax_master',"`id` = '" + prod_result.tax + "'"))[0];
    $('#tax_rate').text(tax.rate.toString() + "%")
    $('#tax_desc').html(tax.description)
    $('#cost_price').text(prod_result.cost)
    let retail_price = prod_result.retail;
    $('#retail_price').text(retail_price)


    let tax_value = percentage(tax.rate,retail_price)

    $('#retail_price_without_tax').text(parseFloat(retail_price) - parseFloat(tax_value))

    // get stock for various branches
    if(row_count('stock',"`item_code` = '"+prod_id+"'") > 0)
    {
        var row = '';
        let loc_id,location, qty;
        // get stock in json
        var stock = JSON.parse(
            get_row('stock',"`item_code` = '"+prod_id+"'")
        );
        for (let i = 0; i < stock.length; i++) {

            qty = stock[i].qty;
            loc_id = stock[i].loc_id
            location = JSON.parse(get_row('loc',"`loc_id` = '"+loc_id+"'"))[0].loc_desc


            echo(stock[i].qty)

            row += "<div class=\"w-100 d-flex flex-wrap prod_inp_container\">\n" +
                "                            <div class=\"prod_inp_descriptio d-flex flex-wrap align-content-center\">\n" +
                "                                <p class=\"m-0 p-0 text-elipse\">"+ loc_id + " - " +location+"</p>\n" +
                "                            </div>\n" +
                "                            <div class=\"prod_inp_view\">"+qty+"</div>\n" +
                "                        </div>";

        }
        $('#stock').html(row)
    } else
    {
        $('#stock').html("No Stock")
    }

    // get packing
    let pac_id = prod_result.packing
    const packing = JSON.parse(get_row('prod_packing', "`item_code` = '" + prod_id + "'"));

    let pack_desc;
    var package_row =  '';
    let purp;
    for (let i = 0; i < packing.length; i++) {
        let pack = packing[i], pack_id, qty, purpose;
        pack_id = JSON.parse(get_row('packaging',"`id` = '"+pack.pack_id+"'"))[0].desc;
        qty = pack.qty
        purpose = pack.purpose
        pack_desc = pack.pack_desc
        if (purpose === 1) {
            purp = 'Selling Unit';
        } else if (purpose === 2)
        {
            purp = 'Purchasing Unit'
        }

        package_row += "<tr class=\"thead-light\">\n" +
            "                                        <td class=\"p-1\">" + pack_id + "</td>\n" +
            "                                        <td class=\"p-1\">" + pack_desc + "</td>\n" +
            "                                        <td class=\"p-1\">" + qty + "</td>\n" +
            "                                        <td class=\"p-1\">"+purp+"</td>\n" +
            "                                    </tr>";


    }
    $('#packaginf_row').html(package_row)


    // show price
    
    arr_hide('stock,packing_tab');arr_show('price')

}

function catDesc(val) {
    if($('#short_description').val(val).length <= 10)
    {
        $('#short_description').val(val.substr(0,15))
    }
}

function newProductSubGroup(group) // load sub groups for selected group
{
    let sub_row, sub_object, sub_result,sub_id,sub_desc;

    // get subs
    sub_row = get_row('item_group_sub',"`parent` = '" + group + "'")
    sub_object = JSON.parse(sub_row)

    let option = '';
    for (let i = 0; i < sub_object.length; i++) {
        sub_id = sub_object[i].id;
        sub_desc = sub_object[i].description

        option += "<option value='" + sub_id + "'>" + sub_desc + "</option>"


    }
    echo(option)
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

        echo(parseFloat(retail_with_tax) + parseFloat(val))
    }


}

function retailWithoutTax()
{

    let tax_rate,val;
    val = $('#retail_with_tax').val()

    tax_rate = $('#tax').val();

    if(tax_rate !== 'null')
    {
        // calculate oercentage

        let tax_value = percentage(tax_rate,val)
        let retail_with_no_tax = parseFloat(val) - parseFloat(tax_value)
        echo(retail_with_no_tax)
        if(isNaN(retail_with_no_tax))
        {
            $('#retail_without_tax').val(0.00)
        } else
        {
            $('#retail_without_tax').val(retail_with_no_tax)
        }

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
        echo(barcode)
        if(row_count('prod_master',"`barcode` = '" + barcode + "'") === '1')
        {
            // get item code
            item_code = JSON.parse(get_row('prod_master',"`barcode` = '" + barcode + "'"))[0].item_code;

            // hide search input anf clear
            $('#barcode_search').val('')
            $('#barcode_search').fadeOut(500)
            // load item
            loadProduct(item_code)


            echo(item_code)
        }
        else {
            $('#barcode_search').val('')
            alert("Item Not Found")

        }
    }
})

