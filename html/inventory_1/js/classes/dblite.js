class Dblite {

    constructor() {
        this.form = '/backend/process/form-processing/dblite.php'
    }

    insert(data,table)
    {
        let cols = data['cols']
        let vars = data['vars']

        if(cols.length > vars.length)
        {
            swal_error("Columns are more than values \n Columns : " + cols + "\n Values : "+ vars)
        } else if (vars.length > cols.length)
        {
            swal_error("Values are more than columns \n Columns : " + cols + "\n Values : "+ vars)
        } else
        {
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

            // prepare ajax submission
            var form_data = {
                'function':'insert',
                'query':query
            }
            var result = 0;

            $.ajax(
                {
                    url:this.form,
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'html',
                    data:form_data,
                    success: function (response)
                    {
                        result = response;
                        if(response === 'done')
                        {
                            // known response
                        } else {
                            // unknown
                        }

                    }
                }
            );

            return result;

        }
    }

}