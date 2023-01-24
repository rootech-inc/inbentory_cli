// REFUND
$(document).ready(function() {
    $("#REFUND").click(function(){
        bill.refundBill()
       // let bill_amt,amount_paid;
       // bill_amt = $('#sub_total').text();
       // if(bill_amt.length > 0 && bill_amt > 0){
       //     // there is bill
       //
       //
       //     if(confirm("Are you sure you want to refund?"))
       //     {
       //         $('#general_input').val(bill_amt);
       //         bill.payment('refund')
       //     }
       //
       // } else {
       //     // no bill
       // }
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
        // take z report
        reports.zReport()
    });

    $('#billing').click(function (){
        // open billing screen
        let mech_no = $('#mech_no').val()
        let shift_cond = `mech_no = '${mech_no}' AND shift_date = '${toDay}' AND end_time is null `;
        if(row_count('shifts',shift_cond) === 1)
        {
            // there is shift
            set_session(['module=billing'])
            location.reload();
        } else
        {
            // no shift
            al("Please Start Shift")
            cl(row_count('shifts',shift_cond))
        }
    });

    $('#start_shift').click(function (){
        // start shift
        Swal.fire({

            icon: 'info',
            title: 'START SHIFT',
            text:`Are you sure you want to start shift for machine number ${$('#mech_no').val()}`,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'YES',
            denyButtonText:'NO',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                sys.StartShift()
            } else if (result.isDenied) {
                Swal.fire({
                    text:"Shift Not Started"
                })
            }
        })
    });

    // save new user
    $('#save_user').click(function (){
        User.SaveNewClerk()
    });

    // refund
    $('#REFUND').click(function (){
        bill.refundBill()
    });

})

// EOD

