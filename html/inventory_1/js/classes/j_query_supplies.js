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
}