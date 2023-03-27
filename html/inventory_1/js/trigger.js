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


    $('#z_report').click(function () {
        // take z report
        let recId = $('#shifts').val()
        Swal.fire({

            icon: 'warning',
            text:`This will clear sales for shift with record ${recId}`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: 'OK',
            denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                reports.zReport(recId)
            } else if (result.isDenied) {
                $('#zModal').modal('hide');
            }
        })

    });

    $('#z_modal').click(function (){
        // take z report

        // get all shift
        let open_shifts = Mech.open_shifts();
        if(open_shifts['count'] > 0)
        {
            let options = '';
            let all_shifts = open_shifts['shifts']
            for (let os = 0; os < all_shifts.length; os++) {
                let this_os = all_shifts[os]
                let machine = this_os[0]
                let date = this_os[1]
                let recId = this_os[2]
                options += `<option value="${recId}">MECH #${machine} - ${date}</option>`
            }

            $('#shifts').html(options)
        } else {
            $('#zBody').html(`<div class=" alert alert-info">NO OPEN SHIFT</div>`)
        }

        $('#zModal').modal('show')

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
        // bill.refundBill()
    });

    // submit refund form refundForm
    $(function () {
        $('#refundForm').submit(function (event) {
            event.preventDefault()
            cl("REFUNDING")



            var actionurl = event.currentTarget.action;

            //$("#loader").modal("show");
            let formData = new FormData($(this).parents('form')[0]);

            formData = new FormData($('#refundForm')[0]); // The form with the file inputs.
            const that = $(this),
                url = that.attr('action'),
                type = that.attr('method'),
                data = {};

            //console.log(url)

            that.find('[name]').each(function (index,value){
                var that = $(this), name = that.attr('name');
                data[name] = that.val();
            });

            // submit to jquery
            $.ajax({
                type: type,
                url: url,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                data: formData,
                success: function (response) {
                    cl(response)
                    ct(response)
                    let res = JSON.parse(JSON.stringify(response))
                    let code,message
                    code = res['code']
                    message = res['message']
                    ct(message)
                    let bill_n = message['bill_no']
                    let msg = message['msg']
                    if(code === 200){
                        // print bill
                        bill.payment('refund')
                        // bill.printBill(bill_n,mech_no,toDay)
                    } else {
                        // clear bill
                        exec(`DELETE from bill_trans where bill_number = ${bill_n};DELETE from bill_tax_tran where bill_no = ${bill_n};`)
                        al(msg)
                    }
                },
                error: function (xhr,status,error) {
                    al(xhr.responseText)
                }
            });


        });
    })

})

// EOD

