// APPLYING DISCOUNT //
function discount() {
    // validate there is cash input
    const val = document.getElementById('general_input');  // gen input field

    if(val.value.length > 0) // if amout value is greater than zero
    {
        let admin_id,admin_password;
        // authenticate
        Swal.fire({
            title: 'AUTHENTICATE',
            html: `<input type="text" id="login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
                const login = Swal.getPopup().querySelector('#login').value
                const password = Swal.getPopup().querySelector('#password').value
                if (!login || !password) {
                    Swal.showValidationMessage(`Please enter login and password`)
                }
                return { login: login, password: password }
            }
        }).then((result) => {
            admin_id = result.value.login;
            admin_password = result.value.password;

            var form_data = {
                'function':'discount',
                'user_id':admin_id,
                'password':admin_password,
                'rate':val.value
            }

            echo(form_data)

            // make ajax function
            $.ajax({
                url: '/backend/process/form_process.php',
                type: 'POST',
                data: form_data,
                success: function(response) {
                    echo(response)
                    if(response.split('%%').length > 1)
                    {
                        var type = response.split('%%')[0];
                        var mesg = response.split('%%')[1];

                        if(type === 'error')
                        {
                            error_handler(response)
                        }
                        else if(type === 'done')
                        {
                            // apply discount

                            Swal.fire(mesg)
                            $('#general_input').val('')
                            get_bill()
                        }
                    }

                    //Swal.fire(response)
                }
            });

            //Swal.fire(form_data.function)
        })

        // prapre form for ajax


        // make ajax function
        // $.ajax({
        //     url: '/backend/process/form_process.php',
        //     type: 'POST',
        //     data: data,
        //     success: function(response) {
        //         echo(response)
        //         error_handler(response)
        //         get_bill()
        //     }
        // });

    }
    else
    {
        console.log(val.value.length)
        val.style.border = '2px solid red';
        val.style.background = '#eb9783';
        val.placeholder = 'Discount Rate';
    }
}
// APPLYING DISCOUNT //