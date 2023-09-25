function row_count(table,condition = 'none') {
    var form_data = {
        'function':'row_count',
        'table':table,
        'condition':condition
    }

    //echo("SELECT * FROM "+table+" WHERE "+condition)

    var result = 0;

    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;

            }
        }
    );

    return parseInt(result);

}

// execute query
function exec(query = 'none')
{
    if(query !== 'none')
    {
        // prepare
        form_data = {
            'function':'query','query':query
        }
        $.ajax(
            {
                url: '/backend/process/ajax_tools.php',type: 'POST',data:form_data,success: function (respose) {
                    // cl(`###QUERY EXECUTE : ${query}`)
                    echo(respose)
                }
            }
        );
    }
}

// general query
function FETCH(query) {

    var form_data = {
        'function':'gen_query',
        'query':query,
    }



    var result = 0;

    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                if(isJson(response)){
                    result = JSON.parse(result)
                } else {
                    result = null;
                }

            }
        }
    );

    return result;

}

function get_row(table,condition) {
    var form_data = {
        'function':'get_row',
        'table':table,
        'condition':condition
    }

    var result = 0;

    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                //echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

            }
        }
    );

    return result;
}

function insert(table,data) {
    // cl('obj')
    // ct(data['vars'])
    // cl('obj_end')
    let cols = data['cols']
    let vars = data['vars']

    if(cols.length > vars.length)
    {
        swal_error("Columns are more than values \n Columns : " + cols + "\n Values : "+ vars)
    } else if (vars.length > cols.length)
    {
        swal_error("Values are more than columns \n Columns : " + cols + "\n Values : "+ vars)
    } else {
        // prepare to execute
        let columns = '';
        for (let i = 0; i < cols.length; i++) {

            if(cols.length - i > 1)
            {
                columns += "`"+cols[i]+"`,"
            } else {
                columns += "`"+cols[i]+"`"
            }

        }

        let values = '';
        for (let i = 0; i < vars.length; i++) {

            if (cols.length - i > 1) {
                values += '"'+ vars[i] + '",'
            } else {
                values += '"' + vars[i] + '"'
            }

        }

        let query = "INSERT INTO "+ table + " (" + columns + ") values ("+values+")";
        //echo(query)

        // prepare ajax submission
        var form_data = {
            'function':'insert',
            'query':query
        }
        var result = 0;

        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data:form_data,
                success: function (response)
                {
                    result = response;
                    //echo(result)


                }
            }
        );

        return result;


        ////echo(values)
    }





    ////echo("INSERT INTO " + table + " (" + col +") values ("+data+")")
}

// get user details
function getUser(id,fetch_get='none') {
    var form_data = {
        'function':'getUser',
        'id':id,
    }

    var result = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {


                if(fetch_get === 'none')
                {
                    result = response;
                } else
                {
                    result = JSON.parse(response)[0][fetch_get]
                }
                cl(response)

            }
        }
    );

    return result;
}

// return query row
function return_rows(query) {
    var form_data = {
        'function':'return_rows',
        'query':query,
    }


    var result = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;


            }
        }
    );

    return result;
}

// fetch rows
function fetch_rows(query) {
    var form_data = {
        'function':'fetch_rows',
        'query':query,
    }
    // ct(query)

    var result = 0;

    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                // cl(response)

            }
        }
    );

    return result;
}

function md5(str) {
    var form_data = {
        'function':'make_md5',
        'str':str,
    }


    var result = 0;

    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                cl(response)

            }
        }
    );

    return result;
}