function loadProduct(prod_id,action)
{
    echo(prod_id)
    // get item as json from database
    let product_row, prod_object, prod_result;

    product_row = get_row('items_master',"`id` = '" + prod_id.toString() + "'")
    prod_object = JSON.parse(product_row)
    prod_result = prod_object[0]

    echo(prod_result.id)

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

function retailWithoutTax(val)
{
    let tax_rate;

    tax_rate = $('#tax').val();

    if(tax_rate !== 'null')
    {
        // calculate oercentage

        let tax_value = percentage(tax_rate,val)
        let retail_with_no_tax = parseFloat(val) - parseFloat(tax_value)
        echo(retail_with_no_tax)
        $('#retail_without_tax').val(retail_with_no_tax)
    }
}

