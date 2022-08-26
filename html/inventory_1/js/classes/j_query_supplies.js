class J_query_supplies {
    setText(data) // set tax in html element
    {
        for(let x in data)
        {
            let id = "#"+x;
            let text = data[x]
            $(id).text(text)
        }
    }

    setVal(data) // set value for form elements
    {
        for(let x in data)
        {
            let id = "#"+x;
            let text = data[x]
            $(id).val(text)
        }
    }

    setHtml(data) // set html
    {
        for(let x in data)
        {
            let id = "#"+x;
            let text = data[x]
            $(id).html(text)

        }

    }

    loadTax(active_tax = 'none') // load taxes
    {

        if(row_count('tax_master','none') > 0)
        {
            // get all tax
            let all_tax = JSON.parse(get_row('tax_master','none'));
            let option = '';
            // loop tax
            for(let tax_row = 0; tax_row < all_tax.length; tax_row++)
            {
                let tax = all_tax[tax_row];
                let tax_description = tax.description;
                let tax_attr = tax.attr
                let tax_id = tax.id

                if(tax_id === active_tax)
                {

                    option += "<option selected value='"+tax_attr+"'>"+tax_description+"</option>";

                }
                else
                {

                    option += "<option value='"+tax_attr+"'>"+tax_description+"</option>";
                }


            }

            let data = {'tax_grp':option}
            this.setHtml(data)

        }
    }

    strLen(ref_id,ref_attr)
    {
        // get attr type
        let id = "#"+ref_id;
        let val = '';
        if(ref_attr === 'val')
        {
            val = $(id).val()
        }
        else if(ref_attr === 'text'){
            val = $(id).text()
        }

        if(val.length < 1 )
        {
            // empty
            $(id).addClass('border-danger')
            return 2;
        } else
        {
            // not empty
            $(id).removeClass('border-danger')
            return 0;
        }


    }

    getId(id)
    {
        return $('#' + id).length > 0;
    }

}