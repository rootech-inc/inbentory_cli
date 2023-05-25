class Tax {

    getTax(uni = '*'){
        let t_query = ''
        if(uni === '*'){
            t_query = return_rows("SELECT * FROM tax_master")
        } else {
            t_query = return_rows(`SELECT * FROM tax_master where id = ${uni}`)
        }

        if(isJson(t_query)){
            let tax_response = JSON.parse(t_query)
            ct(tax_response)
            al("RESULT AS EXPECT")
        } else {
            al("RESPONSE IS NOT JSON")
        }
    }
}

const tax = new Tax()