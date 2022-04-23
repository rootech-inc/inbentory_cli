// PO TABLE SEARCH
$(document).ready(function(){
    $("#poInput").on("keyup", function() {

        let suppler = $('#supplier').val();
        let inputVal = $('#poInput').val();
        let t_row = '';
        let suppler_items = JSON.parse(
            get_row('prod_master', "`supplier` = '" + suppler + "' AND `item_desc` like '%"+inputVal+"%' LIMIT 20")
        )
        let this_item;
        let item_code;
        let item_desc;
        let item_barcode;
        for (let i = 0; i < suppler_items.length; i++) // loop through each supplier item
        {
            this_item = suppler_items[i];
            item_code = this_item.item_code;
            item_desc = this_item.item_desc
            item_barcode = this_item.barcode

            // check if item exist in po trans

            t_row += "<tr ondblclick= \"addToPoTrans('"+item_code+"')\">\n" +
                "                                            <td>"+item_code+"</td>\n" +
                "                                            <td>"+item_barcode+"</td>\n" +
                "                                            <td>"+item_desc+"</td>\n" +
                "                                        </tr>";
        }
        $('#poTable').html(t_row)
        echo(t_row)

        var value = $(this).val().toLowerCase();
        $("#poTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
// PO TABLE SEARCH

$(document).keyup(function (event) {
    var key_pressed = event.which

    //echo(key_pressed + " Pressed ")
    if(key_pressed === 121 ) // f10 for search
    {
        searchTrigger()
    }
});

function loadPoItem(id,e) // load item for po
{
    // split id
    let id_split = id.split('_');
    let item_number = id_split[1];
    echo(e.which)
    let item_code;
    let item;
    if (e.which === 13) {
        // load item
        item_code = $("#" + id.toString()).val();

        item = JSON.parse(
            get_row('prod_master', "`item_code` = '" + item_code + "'")
        );
        echo(item)
    }

    echo(item_number)
}

function selectItemForPo(id) // select item for po
{
    // check if supplier is set
    let suppler = $('#supplier').val()
    if(row_count('supp_mast',"`supp_id` = '"+suppler+"'") > 0)
    {
        // show modal with it
        $('#poInput').focus()
        $('#newPoItem').modal('show')
    } else {
        swal_error("Select Supplier")
    }


}

function addToPoTrans(item_code) {
    let my_user_name = $('#my_user_name').val();
    // check if item with code and user exist in po trans
    let columns;
    let vals;
    let x_data;
    if (row_count('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "' AND `item_code` = '" + item_code + "'") < 1) {
        // item details
        let item_details = JSON.parse(
            get_row('prod_master', "`item_code` = '" + item_code + "'")
        );
        // insert into tabl
        columns = "`item_code`,`barcode`,`item_description`";
        let barcode = item_details[0].barcode;
        let item_desc = item_details[0].item_desc;
        vals = "'" + item_code + "'," + "'" + barcode + "'," + item_desc + "',";
        x_data = {
            'cols': ['item_code','barcode','item_description',`owner`],
            'vars': [item_code,barcode,item_desc,my_user_name]
        }


        if(insert('po_trans', x_data) == 1)
        {
            // inserted
            loadPoTrans()

        } else
        {
            swal_error('Could not add ' + item_desc +" to PO Entry")
        }

    } else {
        swal_error('Item added already')
    }
}

// load new trans entries


function loadPoTrans() {
    let my_user_name = $('#my_user_name').val();
    let po_items;
    let this_entery;
    let item_code;
    let item_desc;
    let t_row = '';
    let this_packing;
    let pack_id;
    let pack_desc;
    let pac_qty;
    let x_pack_desc;
    if (row_count('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "'") > 0) {
        // get po trans items
        po_items = JSON.parse(
            get_row('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "'")
        );

        for (let i = 0; i < po_items.length; i++) {
            this_entery = po_items[i];

            item_code = this_entery.item_code;
            item_desc = this_entery.item_description;

            // get packing for each item
            this_packing = JSON.parse(
                get_row('prod_packing', "`item_code` = '" + item_code + "' AND `purpose` = '2'")
            );
            pack_id = this_packing[0].pack_id;
            x_pack_desc = this_packing[0].pack_desc
            pack_desc = JSON.parse(
                get_row('packaging', "`id` = '" + pack_id + "'")
            )[0].desc;
            pac_qty = this_packing[0].qty
            echo(pac_qty)

            // set ids
            let item_code_id = "itemCode_" + i.toString();
            let item_desc_id = "itemDesc_" + i.toString();
            let item_pack_id = "itemPack_" + i.toString();
            let item_packing_id = "itemPacking_" + i.toString();
            let item_qty_id = "itemQty_" + i.toString();
            let item_cost_id = "itemCost_" + i.toString();
            let item_amount_id = "itemAmount_" + i.toString();

            t_row += "<tr>\n" +
                "                            <td>\n" +
                "                                <button onclick=\"delete_item('po_trans','"+item_code+"')\" class=\"btn-danger pointer\">&minus;</button>\n" +
                "                            </td>\n" +
                "                            <td><input ondblclick=\"selectItemForPo(this.id)\" onkeyup=\"loadPoItem(this.id,event)\" type=\"text\" name=\"item_code[]\" id='" + item_code_id + "' style=\"width: 70px\" value='" + item_code + "' readonly></td>\n" +
                "                            <td>\n" +
                "                                <input type=\"text\" readonly name=\"item_desc[]\" id='" + item_desc_id + "' value='" + item_desc + "'>\n" +
                "                            </td>\n" +
                "                            <td>\n" +
                "                                <select name=\"item_pack[]\" id='" + item_pack_id + "'  style=\"width: 50px\">\n" +
                "                                    <option value='" + x_pack_desc + "'>" + pack_desc +" </option>\n" +
                "                                </select>\n" +
                "                            </td>\n" +
                "                            <td><input style=\"width: 50px\"  type=\"text\" value='"+x_pack_desc+"' readonly name=\"item_qty[]\" id='" + item_qty_id + "'></td>\n" +
            "                               <td><input style=\"width: 50px\" required onkeyup='poItemAmount("+item_cost_id+")' type=\"text\" value='1' name=\"item_packing[]\" id='" + item_packing_id + "'></td>\n" +
                "                            <td><input style=\"width: 50px\" required onkeyup='poItemAmount(this.id)' min='1' type=\"number\" name=\"item_cost[]\" id='" + item_cost_id + "'></td>\n" +
                "                            <td><input style=\"width: 50px\" required type=\"text\" readonly name=\"item_amount[]\" id='" + item_amount_id + "'></td>\n" +
                "                        </tr>";

        }

        $('#po_items_list').html(t_row)


    } else {
        $('#po_items_list').html("Add Item to list")
        echo("no po item")
    }

}

function poItemAmount(id) {
    let id_split = id.split('_');

    if(id_split.length > 0)
    {
        let item_num = id_split[1];
        let pack_qty = "#itemPack_"+item_num.toString();
        let total_amt_id = "#itemAmount_"+item_num.toString()
        let item_qty_id = "#itemQty_"+ item_num.toString()

        // multiple
        // packaging quantity vs quantity
        let pack_vs_quan = parseFloat($(pack_qty).val() * $(item_qty_id).val())
        // quantity vs cost
        let amount = parseFloat($('#'+id).val()) * parseFloat($(item_qty_id).val())
        $(total_amt_id).val(amount)
        echo(amount)
    }
}

function getPoLocation(location) // get location selected
{
    let loc_desc;
    if (row_count('loc', "`loc_id` = '" + location + "'") == 1) {
        // get location description
        loc_desc = JSON.parse(
            get_row('loc', "`loc_id` = '" + location + "'")
        )[0].loc_desc;

        echo(loc_desc)
        $('#location_desc').text(loc_desc)

    } else {
        swal_error("Cannot find location")
    }
}