class J_query_supplies {
    setText(data)
    {
        for(let x in data)
        {
            let id = "#"+x;
            let text = data[x]
            $(id).text(text)
        }
    }
}