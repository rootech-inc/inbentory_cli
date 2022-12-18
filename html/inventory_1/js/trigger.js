// REFUND
$(document).ready(function() {
    $("#REFUND").click(function(){
       let bill_amt,amount_paid;
       bill_amt = $('#sub_total').text();
       if(bill_amt.length > 0 && bill_amt > 0){
           // there is bill


           if(confirm("Are you sure you want to refund?"))
           {
               $('#general_input').val(bill_amt);
               bill.payment('refund')
           }

       } else {
           // no bill
       }
    });
});
//REFUND

//ADMIN AUTH
$(document).ready(function() {
    $("#admin_auth").click(function(){
        //
        let admin_auth_username,admin_auth_password,err_c = 0,err_m = ' ',result = false;
        admin_auth_username = $('#admin_auth_username').val()
        admin_auth_password = $('#admin_auth_password').val()

        if(admin_auth_username.length < 1)
        {
            err_c ++
            err_m += "Provide User ID | "
        }
        if(admin_auth_password.length < 1)
        {
            err_c ++
            err_m += "Provide Password | "
        }

        if (err_c > 0)
        {
            $('#adminAuthErr').text(err_m)
        } else {
            let dataToSend = {
                'function':'mj',
                'user_id':admin_auth_username,
                'password':admin_auth_password
            }

            $.ajax({
                url: '/backend/process/form_process.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: dataToSend,
                success: function(response) {
                    // echo(response)
                    let resp = JSON.parse(response)
                    if(resp['status'] === 200)
                    {
                        result = true

                    } else {
                        result = false
                    }

                }
            });
        }

        return result


    });
});
//ADMIN AUTH


// EOD

$(document).ready(function() {
    $("#eod").click(function(){
        reports.EndOfDay()
    });
});

$(document).ready(function() {
    $("#sales_report").click(function(){
        reports.SalesReport()
    });
});

$(document).ready(function (){
    $('#z_report').click(function (){
        reports.zReport()
    });
})

// EOD