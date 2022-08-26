class a_session {
    // get session
    get_session(sess_var){
        var form_data = {
            'token':'none',
            'function':'get_session',
            'sess_var':sess_var
        }
        cl("session var : " + sess_var)
        var result = null;
        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: form_data,
                success: function (response) {
                    result = response;
                }
            }
        );
        return result;
    }

    // set session
    set_session(data,reload = 1) {
        var form_data = {
            'token':'none',
            'function':'set_session',
            'session_data':data
        }
        echo(data)
        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                method: 'post',
                data: form_data,
                success: function (response) {
                    if(reload === 1)
                    {
                        location.reload()
                    }
                }
            }
        );
    }

    // unset session
    unset_session(data)
    {
        var form_data = {
            'token':'none',
            'function':'unset_session',
            'sess_var':data
        }
        cl("unset session var : " + session_variable)
        var result = null;
        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: form_data,
                success: function (response) {
                    result = response;
                }
            }
        );
        return result;
    }
}