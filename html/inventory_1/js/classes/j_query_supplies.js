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
}