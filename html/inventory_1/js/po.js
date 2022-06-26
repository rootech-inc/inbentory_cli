// PO TABLE SEARCH

/* search po item to add */
$(document).ready(function(){
    $("#poInput").on("keyup", function() {

        var po_number = $('#po_number').val()


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

            if(po_number.length > 0)
            {

                t_row += "<tr ondblclick= \"appendToPoTrans('"+item_code+"','"+po_number+"')\">\n" +
                    "                                            <td>"+item_code+"</td>\n" +
                    "                                            <td>"+item_barcode+"</td>\n" +
                    "                                            <td>"+item_desc+"</td>\n" +
                    "                                        </tr>";
            } else
            {

                t_row += "<tr ondblclick= \"addToPoTransV2('"+item_code+"')\">\n" +
                    "                                            <td>"+item_code+"</td>\n" +
                    "                                            <td>"+item_barcode+"</td>\n" +
                    "                                            <td>"+item_desc+"</td>\n" +
                    "                                        </tr>";
            }

        }
        $('#poTable').html(t_row)
        echo(t_row)

        var value = $(this).val().toLowerCase();
        $("#poTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

/*TRIGGER PO SEARCH*/
function searchTrigger(){ // trigger search bar

    $('#po_search').show(500)
    $('#po_search').focus()
}

/*PO SEARCH INPUT*/
$('#po_search').on('keyup',function (e) {
    let key = e.which, po_number;
    if(key === 13)
    {
        // get po value and validate
        po_number = $('#po_search').val()
        if(row_count('po_hd',"`doc_no` = '"+po_number+"'") === 1)
        {
            // load po in view
            previewPoTrans(po_number)
            // hide po search
            $('#po_search').val('')
            $('#po_search').hide(500)
        }
        else
        {
            swal.fire(po_number+" Does Not Exist")
        }

        echo("Search Fired")
    } else
    {
        // do nothin
        echo("Keep Inputing")
    }
})

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

function addToPoTrans(item_code) // ADD NEW PO ITEM IN CREATION MOOD
{
    let my_user_name = $('#my_user_name').val();
    // check if item with code and user exist in po trans

    let x_data;
    if (row_count('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "' AND `item_code` = '" + item_code + "' AND `parent` is null") < 1) {
        // item details
        let item_details = JSON.parse(
            get_row('prod_master', "`item_code` = '" + item_code + "'")
        );
        // insert into tabl
        let barcode = item_details[0].barcode;
        let item_desc = item_details[0].item_desc;
        // get item packing
        var item_packing = JSON.parse(
            get_row('prod_packing',"`item_code` = '"+item_code+"' AND `purpose` = '2'")
        )[0]
        var package_description = item_packing.pack_desc
        var pack_id_id = item_packing.pack_id
        var pack_um = item_packing.qty



        // get pack_id
        var pack_id = JSON.parse(
            get_row('packaging',"`id` = '"+pack_id_id+"'")
        )[0]
        var pack_id_desc = pack_id.desc
        echo(pack_id_desc)

        x_data = {
            'cols': ['item_code','barcode','item_description','owner','packing','pack_desc','pack_um'],
            'vars': [item_code,barcode,item_desc,my_user_name,package_description,pack_id_desc,pack_um]
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

function addToPoTransV2(item_code) {
    cl(item_code)
    /* todo Adding an item to po trans
    * 1. if item with item code exist, get item details else show an item not found error
    * 2. get needed details for trans record (barcode,item_description,pack_desc,packing,packing_um)
    * 4. put it in a table row and append it to the list with other input fields (trans_qty,cost,total_cost)
    * */
    if(row_count('prod_master',"`item_code` = '"+item_code+"'") === 1) // 1
    {
        // 2
        let product,barcode,item_desc,item_packing,pack_desc,pack_id,packing,packing_um;
        product = JSON.parse(get_row('prod_master',"`item_code` = '"+item_code+"'"))[0]
        barcode = product.barcode;
        item_desc = product.item_desc
        item_packing = JSON.parse(
            get_row('prod_packing',"`item_code` = '"+item_code+"' AND `purpose` = '2'")
        )[0]
        pack_desc = item_packing.pack_desc
        pack_id = item_packing.pack_id
        packing_um = item_packing.qty
        pack_id = JSON.parse(
            get_row('packaging',"`id` = '"+pack_id+"'"))[0]
        var pack_id_desc = pack_id.desc

        let last_row = $('#po_items_list tr').length + 1;

        let item_code_id = "itemCode_" + last_row.toString();
        let item_desc_id = "itemDesc_" + last_row.toString();
        let item_pack_id = "itemPack_" + last_row.toString();
        let item_packing_id = "itemPacking_" + last_row.toString();
        let item_qty_id = "itemQty_" + last_row.toString();
        let item_cost_id = "itemCost_" + last_row.toString();
        let item_amount_id = "itemAmount_" + last_row.toString();
        let row_id = 'row_' + last_row.toString();

        let t_row = "<tr id='"+row_id+"'>\n" +
            "                            <td>\n" +
            "                                <button onclick=\"delete_item('po_trans','" + row_id + "')\" class=\"btn-danger pointer\">&minus;</button>\n" +
            "                            </td>\n" +
            "                            <td><input ondblclick=\"selectItemForPo(this.id)\" onkeyup=\"loadPoItem(this.id,event)\" type=\"text\" name=\"item_code[]\" id='" + item_code_id + "' value='" + barcode + "' readonly></td>\n" +
            "                            <td>\n" +
            "                                <input type=\"text\" readonly name=\"item_desc[]\" id='" + item_desc_id + "' value='" + item_desc + "'>\n" +
            "                            </td>\n" +
            "                            <td>\n" +
            "                                <select name=\"item_pack[]\" id='" + item_pack_id + "'  style=\"width: 50px\">\n" +
            "                                    <option value='" + pack_id_desc + "'>" + pack_id_desc + " </option>\n" +
            "                                </select>\n" +
            "                            </td>\n" +
            "                            <td><input style=\"width: 50px\"  type=\"text\" value='" + pack_desc + "' readonly name=\"item_qty[]\" id='" + item_qty_id + "'></td>\n" +
            "                               <td><input style=\"width: 50px\" required onkeyup=\"poItemAmount(" + "'" + item_cost_id + "'" + ")\" type=\"text\" value='0' name=\"item_packing[]\" id='" + item_packing_id + "'></td>\n" +
            "                            <td><input style=\"width: 50px\" required onkeyup='poItemAmount(this.id)' min='1' value='0' type=\"number\" name=\"item_cost[]\" id='" + item_cost_id + "'></td>\n" +
            "                            <td><input style=\"width: 50px\" required type=\"text\" readonly name=\"item_amount[]\" value='0' id='" + item_amount_id + "'></td>\n" +
            "                        </tr>";
            $('#po_items_list').append(t_row)
    } else
    {
        swal_error("Item not found")
    }
}

function appendToPoTrans(item_code,po_number) // ADD NEW PO ITEM IN EDIT MOOD
{
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
        let barcode = item_details[0].barcode;
        let item_desc = item_details[0].item_desc;
        // get item packing
        var item_packing = JSON.parse(
            get_row('prod_packing',"`item_code` = '"+item_code+"' AND `purpose` = '2'")
        )[0]
        var package_description = item_packing.pack_desc
        var pack_id_id = item_packing.pack_id
        var pack_um = item_packing.qty



        // get pack_id
        var pack_id = JSON.parse(
            get_row('packaging',"`id` = '"+pack_id_id+"'")
        )[0]
        var pack_id_desc = pack_id.desc
        echo(pack_id_desc)

        x_data = {
            'cols': ['item_code','barcode','item_description',`owner`,'parent','packing','pack_desc','pack_um'],
            'vars': [item_code,barcode,item_desc,my_user_name,po_number,package_description,pack_id_desc,pack_um]
        }


        if(insert('po_trans', x_data) == 1)
        {
            // inserted
            editPoTrans(po_number)

        } else
        {
            swal_error('Could not add ' + item_desc +" to PO Entry")
        }

    } else {
        swal_error('Item added already')
    }
}

// load new trans entries


function loadPoTrans() // LOAD PO ITEMS WHEN CREATING A NEW PO
{
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
    let item_packagin;
    let item_qty;
    let item_total_cost;
    let item_cost;
    var po_total_amount = 0;
    if (row_count('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "' AND `parent` is null") > 0) {
        // get po trans items
        po_items = JSON.parse(
            get_row('po_trans', "`owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "' AND `parent` is null")
        );

        for (let i = 0; i < po_items.length; i++) {
            this_entery = po_items[i];
            barcode = this_entery.barcode
            item_code = this_entery.item_code;
            item_desc = this_entery.item_description;
            item_packagin = this_entery.packing;
            item_qty = this_entery.qty;
            item_cost = this_entery.cost;
            item_total_cost = this_entery.total_cost;

            po_total_amount += parseInt(item_total_cost)

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
                "                                <button onclick=\"delete_item('po_trans','" + item_code + "')\" class=\"btn-danger pointer\">&minus;</button>\n" +
                "                            </td>\n" +
                "                            <td><input ondblclick=\"selectItemForPo(this.id)\" onkeyup=\"loadPoItem(this.id,event)\" type=\"text\" name=\"item_code[]\" id='" + item_code_id + "' value='" + barcode + "' readonly></td>\n" +
                "                            <td>\n" +
                "                                <input type=\"text\" readonly name=\"item_desc[]\" id='" + item_desc_id + "' value='" + item_desc + "'>\n" +
                "                            </td>\n" +
                "                            <td>\n" +
                "                                <select name=\"item_pack[]\" id='" + item_pack_id + "'  style=\"width: 50px\">\n" +
                "                                    <option value='" + pack_desc + "'>" + pack_desc + " </option>\n" +
                "                                </select>\n" +
                "                            </td>\n" +
                "                            <td><input style=\"width: 50px\"  type=\"text\" value='" + x_pack_desc + "' readonly name=\"item_qty[]\" id='" + item_qty_id + "'></td>\n" +
                "                               <td><input style=\"width: 50px\" required onkeyup=\"poItemAmount(" + "'" + item_cost_id + "'" + ")\" type=\"text\" value='"+item_qty+"' name=\"item_packing[]\" id='" + item_packing_id + "'></td>\n" +
                "                            <td><input style=\"width: 50px\" required onkeyup='poItemAmount(this.id)' min='1' value='"+item_cost+"' type=\"number\" name=\"item_cost[]\" id='" + item_cost_id + "'></td>\n" +
                "                            <td><input style=\"width: 50px\" required type=\"text\" readonly name=\"item_amount[]\" value='"+item_total_cost+"' id='" + item_amount_id + "'></td>\n" +
                "                        </tr>";

        }

        $('#po_items_list').html(t_row)
        $('#total_amount').val(po_total_amount.toFixed(2))


    } else {
        // $('#po_items_list').html("Add Item to list")
        echo("no po item")
    }

}

// preview po trans
function previewPoTrans(po_number) // LOAD PO ITEMS IN FOR PREVIEW IN VIEW MOOD
{
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
    let item_packagin;
    let item_qty;
    let item_total_cost;
    let item_cost;
    var po_total_amount = 0;

    // get PO header
    let po_header = JSON.parse(
        get_row('po_hd', "`doc_no` = '" + po_number + "'")
    )[0];

    $('#po_number').text(po_header.doc_no)
    $('#loc_id').text(po_header.location)
    $('#supplier').text(po_header.suppler)
    $('#po_type').text(po_header.type)
    $('#remarks').text(po_header.remarks)

    $('#total_amount').text(po_header.total_amount)
    $('#owner').text(po_header.owner)
    $('#created_at').text(po_header.created_on)
    $('#edited_by').text(po_header.edited_by)
    $('#edited_on').text(po_header.edited_on)


    let po_hd_id;
    let next;
    let prev;
    let next_po_number;
    let prev_po_number;

    // approve not approved
    var status = po_header.status
    if(status === 1)
    {
        /*
        * approved
        * disable edit
        * disable approved
        * show approved
        * */
        arr_disable('approve_button,edit_button,delete_button')
        arr_enable('print_po')
        $('#document_stat').text("Approved")
        $('#document_stat').removeClass('text-muted text-danger')
        $('#document_stat').addClass('text-success')
        $('#approved_msg').html(
            po_header.approved_by + " <i class=\"fas fa-user\"></i><br>"
            + po_header.approved_on + " <i class='fas fa-calendar-check'></i>"
        )
        echo("###### Approved")
    }
    else if(status === -1)
    {
        /*
        * DELETED
        * */
        arr_disable('approve_button,edit_button,delete_button')

        $('#document_stat').text("Deleted")
        $('#document_stat').removeClass('text-muted text-success')
        $('#document_stat').addClass('text-danger')
        $('#approved_msg').html(
            po_header.approved_by + " <i class=\"fas fa-user\"></i><br>"
            + po_header.approved_on + " <i class='fas fa-calendar-check'></i>"
        )
        echo("###### Deleted")
    }
    else if (status === 0)
    {
        /*
        * not approved
        * enable approved
        * show not approved
        * */
        arr_enable('approve_button,edit_button,delete_button')
        $('#document_stat').text("Pending")
        $('#document_stat').removeClass('text-success text-danger')
        $('#document_stat').addClass('text-muted')
        $('#approved_msg').text('')

        echo("##### Not Approved")
    }

    // check if po is deleted
    echo(status)
    $('#po_items_list').html('')
    if(status !== -1)
    {
        // load po trans
        if (row_count('po_trans', "`parent` = '" + po_number + "'") > 0) {
            // get po trans items
            po_items = JSON.parse(
                get_row('po_trans', "`parent` = '" + po_number + "'")
            );
            var sn = 0;
            for (let i = 0; i < po_items.length; i++) {

                sn += 1
                this_entery = po_items[i];

                item_code = this_entery.item_code;
                item_desc = this_entery.item_description;
                item_packagin = this_entery.packing;
                item_qty = this_entery.qty;
                item_cost = this_entery.cost;
                item_total_cost = this_entery.total_cost;

                po_total_amount += parseInt(item_total_cost)

                // get packing for each item
                this_packing = JSON.parse(
                    get_row('prod_packing', "`item_code` = '" + item_code + "' AND `purpose` = '2'")
                );
                pack_id = this_packing[0].pack_id;
                x_pack_desc = this_packing.packing
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

                t_row += "<tr>" +
                    "<td>" + sn + "</td>" +
                    "<td>" + item_code + "</td>" +
                    "<td>" + item_desc + "</td>" +
                    "<td>" + pack_desc + "</td>" +
                    "<td>" + item_packagin + "</td>" +
                    "<td>" + item_qty + "</td>" +
                    "<td>" + item_cost + "</td>" +
                    "<td>" + item_total_cost + "</td>" +
                    "</tr>";

            }

            $('#po_items_list').html(t_row)
            $('#total_amount').val(po_total_amount.toFixed(2))


        }
        else
        {
            $('#po_items_list').html("NO PO TRANS ITEMS")
            echo("no po item")
        }
    }

    // check if there is more po
    po_hd_id = po_header.id
    next = row_count('po_hd', "`id` > '" + po_hd_id + "'")
    prev = row_count('po_hd', "`id` < '" + po_hd_id + "'")

    if (next > 0) // if there is next
    {

        // get next po number
        next_po_number = JSON.parse(
            get_row('po_hd', "`id` > '" + po_hd_id + " LIMIT 1'")
        )[0].doc_no

        $('#sort_right').val(next_po_number)

        // enable next
        arr_enable('sort_right')

    }
    else
    {
        //disable next button
        arr_disable('sort_right')
    }

    if (prev > 0) // if there is prev
    {

        // get next po number
        prev_po_number = JSON.parse(
            get_row('po_hd', "`id` < " + po_hd_id + " order by `id` desc LIMIT 1")
        )[0].doc_no

        echo("###### previous : " + prev_po_number)

        $('#sort_left').val(prev_po_number)

        // enable next
        arr_enable('sort_left')

    }
    else {
        //disable next button
        arr_disable('sort_left')
    }




}

// edit po
function editPoTrans(po_number) // LOAD PO ITEMS FOR EDITING WHEN IN EDIT MOOD
{
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
    let item_packagin;
    let item_qty;
    let item_total_cost;
    let item_cost;
    var po_total_amount = 0;

    // get PO header
    let po_header = JSON.parse(
        get_row('po_hd', "`doc_no` = '" + po_number + "'")
    )[0];
    let loc = po_header.location

    $('#po_number').val(po_header.doc_no)

    // LOCATION DETAILS

    $('#loc_id').text(po_header.location) // set location it
    var loc_desc = JSON.parse(get_row('loc',"`loc_id` = '"+loc+"'"))[0].loc_desc // get this po loc description
    $('#location').text(loc_desc)

    // al locations
    var all_locations = JSON.parse(get_row('loc',"none"));
    let i_loc;
    let l_id;
    let l_desc;
    var l_option = "";

    for (let all_i = 0; all_i < all_locations.length; all_i++) {
        i_loc = all_locations[all_i]
        l_id = i_loc.loc_id;
        l_desc = i_loc.loc_desc
        if(l_id === loc)
        {
            l_option += "<option selected value='"+l_id+"'>"+l_id+" - "+l_desc+"</option>";
        }
        else
        {
            l_option += "<option value='"+l_id+"'>"+l_id+" - "+l_desc+"</option>";
        }

    }
    $('#loc_id').html(l_option)
    echo(l_option)

    $('#supplier').val(po_header.suppler)
    $('#po_type').text(po_header.type)
    $('#remarks').val(po_header.remarks)

    $('#total_amount').text(po_header.total_amount)
    $('#owner').text(po_header.owner)
    $('#created_at').text(po_header.created_on)
    $('#edited_by').text(po_header.edited_by)
    $('#edited_on').text(po_header.edited_on)


    if (row_count('po_trans', "`parent` = '" + po_number + "'") > 0) {
        // get po trans items
        po_items = JSON.parse(
            get_row('po_trans', "`parent` = '" + po_number + "'")
        );
        var sn =0;
        for (let i = 0; i < po_items.length; i++) {

            sn += 1
            this_entery = po_items[i];
            barcode = this_entery.barcode
            item_code = this_entery.item_code;
            item_desc = this_entery.item_description;
            item_packagin = this_entery.packing;
            item_qty = this_entery.qty;
            item_cost = this_entery.cost;
            item_total_cost = this_entery.total_cost;

            po_total_amount += parseInt(item_total_cost)

            // get packing for each item
            this_packing = JSON.parse(
                get_row('prod_packing', "`item_code` = '" + item_code + "' AND `purpose` = '2'")
            );
            pack_id = this_packing[0].pack_id;
            x_pack_desc = this_packing.pack_desc
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
                "                                <button onclick=\"delete_item('po_trans','" + item_code + "')\" class=\"btn-danger pointer\">&minus;</button>\n" +
                "                            </td>\n" +
                "                            <td>" + barcode + "</td>\n" +
                "                            <td>\n" +
                "                                " + item_desc  +
                "                            </td>\n" +
                "                            <td>\n" +
                "                                <select name=\"item_pack[]\" id='" + item_pack_id + "'  style=\"width: 50px\">\n" +
                "                                    <option value='" + pack_desc + "'>" + pack_desc + " </option>\n" +
                "                                </select>\n" +
                "                            </td>\n" +
                "                            <td><input style=\"width: 50px\"  type=\"text\" value='" + item_packagin + "' readonly name=\"item_qty[]\" id='" + item_qty_id + "'></td>\n" +
                "                               <td><input style=\"width: 50px\" required onkeyup=\"poItemAmount(" + "'" + item_cost_id + "'" + ")\" type=\"text\" value='"+item_qty+"' name=\"item_packing[]\" id='" + item_packing_id + "'></td>\n" +
                "                            <td><input style=\"width: 50px\" required onkeyup='poItemAmount(this.id)' min='1' value='"+item_cost+"' type=\"number\" name=\"item_cost[]\" id='" + item_cost_id + "'></td>\n" +
                "                            <td><input type='hidden' value='"+barcode+"' id='"+item_code_id+"'>" +
                "<input style=\"width: 50px\" required type=\"text\" readonly name=\"item_amount[]\" value='"+item_total_cost+"' id='" + item_amount_id + "'></td>\n" +
                "                        </tr>";


        }

        $('#po_items_list').html(t_row)
        $('#total_amount').val(po_total_amount.toFixed(2))



    }
    else {
        $('#po_items_list').html("Add Item to list")
        echo("no po item")
    }

}


function poItemAmount(id) // CALCULATE PO ITEM TOTAL COST AND UPDATE VALUES
{
    echo(id)
    let id_split = id.split('_');

    var po_number = $('#po_number').val()

    if(id_split.length > 0)
    {
        let item_num = id_split[1];
        let pack_qty = "#itemPacking_"+item_num.toString();
        let total_amt_id = "#itemAmount_"+item_num.toString()
        let item_qty_id = "#itemQty_"+ item_num.toString()
        let item_code_id = "#itemCode_"+ item_num.toString()
        let pack = "#itemPack_"+ item_num.toString()
        let packag_id = $(pack).val()

        // total amount = quantity * cost value
        var pack_desc = $(item_qty_id).val().trim()
        var qty = $(pack_qty).val()
        var each_cost = $('#itemCost_'+item_num.toString()).val()
        var total_cost = qty * each_cost;

        echo(pack_desc)

        // update
        let item_code = $(item_code_id).val()
        let my_user_name = $('#my_user_name').val();
        // swal_error(po_number.length)
        var update = '';
        if(po_number.length > 0)
        {
            // update with po number
            update = "UPDATE po_trans SET `packing` = '"+pack_desc+"' , qty = '"+qty+"' ,`cost` = '"+each_cost+"', `total_cost` = '"+total_cost+"' " + " WHERE `parent` = '" + po_number + "' AND `barcode` = '"+item_code+"' "
        }
        else
        {
            update = "UPDATE po_trans SET `packing` = '"+pack_desc+"' , qty = '"+qty+"' ,`cost` = '"+each_cost+"', `total_cost` = '"+total_cost+"' WHERE `owner` = '" + my_user_name + "' AND `date_added` = '" + toDay + "' AND `barcode` = '"+item_code+"' AND `parent` is null"
        }

        echo(update)

        if(update.length > 5)
        {
            exec(update)
        } else
        {
            swal_error("Cannot make changes because query `"+update+"` with length "+po_number.length+" is not valid")
        }



        $(total_amt_id).val(total_cost.toFixed(2))
        let last_row = $('#po_items_list tr').length;

        let tot_amt = 0;
        for(let sn = 0; sn < last_row; sn++)
        {
            let line_amt = "#itemAmount_"+sn.toString();
            let line_val = parseFloat($(line_amt).val())
            tot_amt += line_val
            cl(line_amt)
            cl(line_val)
        }
        cl(tot_amt)
        jqh.setVal({'total_amount':tot_amt.toFixed(2)})

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
        set_new_po_remarks()

    } else {
        swal_error("Cannot find location")
    }
}

function print_po() // PRINT_PO
{
    var po_numer = $('#po_number').text()
    var form_data = {
        'function':'print_po','po_number':po_numer
    }

    $.ajax({
        url:'backend/process/form-processing/po.php',
        type:'POST',
        data:form_data,
        success: function (response) {
            if(response === 'done')
            {
                // set ifram
                $('#pdf_body').html(
                    "<embed src=\"backend/process/form-processing/test.pdf\" width=\"100%\" height=\"100%\"\n" +
                    "                           type=\"application/pdf\">"
                )
                // show pdf modal
                $('#pdf_modal').modal('show')
            } else
            {
                swal_error(response)
            }
        }
    });
}

function approve_po() // approve po
{
    if(confirm("Are you sure you want to approve PO?"))
    {
        /*GET PO NUMBER
        * UPDATE PO status = 0
        * load po
        * */
        var po_number = $('#po_number').text()
        if(row_count('po_hd',"`doc_no` = '"+po_number+"'") === 1)
        {
            let my_user_name = $('#my_user_name').val();

            // update po and load po
            var update_quer = "UPDATE `po_hd` SET `status` = 1, `approved_by` = '"+my_user_name+"', approved_on = '"+current_time_stamp+"' WHERE `doc_no` = '"+po_number+"'";
            exec(update_quer)
            previewPoTrans(po_number)
        }
        else
        {
            // cant find PO
            swal.fire("Can't Find PO ( " + po_number +" )")
        }
    }
}

function delete_po() // delete po
{
    Swal.fire({
        title: "Are you sure you want to delete document?",
        icon: 'info',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
    }).then((result) => {
        if (result.isConfirmed)
        {
            // get po number
            var po_number = $("#po_number").text()


            // comfirm if po exist
            if(row_count('po_hd',"`doc_no` = '"+po_number+"'") === 1)
            {
                echo("Document Found")
                // marke all po_trans as deleted
                let my_user_name = $('#my_user_name').val();
                var update_trans_query = "UPDATE `po_trans` SET `status` = -1 where `parent` = '"+po_number+"'";
                var update_hd_query = "UPDATE `po_hd` SET `status` = -1 , `approved_by` = '"+my_user_name+"',`approved_on` = '"+current_time_stamp+"' where `doc_no` = '"+po_number+"'";

                exec(update_trans_query)
                exec(update_hd_query)
                error_handler('done%%done_reload')
            }
            else
            {
                swal_error("Can't find document " + po_number)
            }
        }
        else
        {
            echo("Canceled Deletion")
        }

    })
}


function set_new_po_remarks()
{
    var location = $('#location_desc').text()
    var supp_id = $('#supplier').val()
    var supp_desc = "";
    if(row_count('supp_mast',"`supp_id` = '"+supp_id+"'") === 1)
    {
        supp_desc = JSON.parse(
            get_row('supp_mast',"`supp_id` = '"+supp_id+"'")
        )[0].supp_name
    }

    $('#remarks').val("Purchase from "+supp_desc+" and delivered to " +location)

}


// save po
$(document).ready(function(){
   $('#save_po').on('click',function () {
        /*TODO SAVE PO DOCUMENT
        * 1. Check if there are items in po list else show error
        * 2. Check po header to make sure, lic, suppler and remarks ae not empty
        * 3. get last po hd id, add 1 to it to create entry number (PO+last_id+1)
        * 4. insert header details
        * 5. loop through po items and save it
        * */
       // check po trans
       let po_trans_count = $('#po_items_list tr').length
       if(po_trans_count > 0) // 1
       {

           //2
           let loc_id,loc_desc,suppler,remarks;
           loc_id = $('#location').val();
           loc_desc = $('#location_desc').text();
           suppler = $('#supplier').val();
           remarks = $('#remarks').val();

       } else // 1 error
       {
           swal_error("Cannot save an empty document")
       }
   })
});
