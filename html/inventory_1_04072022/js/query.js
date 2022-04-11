function row_count(table,condition = 'none') {
    var form_data = {
        'function':'row_count',
        'table':table,
        'condition':condition
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
                url: 'backend/process/ajax_tools.php',type: 'POST',data:form_data,success: function (respose) {
                    echo(respose)
                }
            }
        );
    }
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
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                echo("SELECT * FROM " + table + " WHERE " + condition.toString())

            }
        }
    );

    return result;
}