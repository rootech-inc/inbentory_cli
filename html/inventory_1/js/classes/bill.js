class Bill {
    loadBillsInTrans(){

        var form = new FormData();
        form.append("function", "get_bill");

        var settings = {
            "url": "/backend/process/form_process.php",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form,
            success: function (response) {
                // console.log(response)
                // al(response)
                let res = JSON.parse(response)
                console.table(res)

                if(res['status'] === 202)
                {

                    let message = res['message']
                    let count,total,tax,trans
                    count = message['count']
                    total = message['total']
                    tax = message['tax']
                    trans = message['trans']
                    let sel_count = 0

                    arr_disable('recall')
                    arr_enable('cash_payment,momo_payment,cancel,subTotal,hold,discount,REFUND')


                    // load header
                    // $('#sub_total').text(total)
                    // $('#tax').text(tax)
                    jqh.setText({'sub_total':total, 'tax':tax,'amount_paid':'0.00','amount_balance':'0.00'})



                    let rows = ''
                    ct(trans)
                    for (const rowsKey in trans) {
                        ct(rowsKey)
                    }
                    // loop through trans
                    for (let rowsKey in trans) {

                        let this_tran = trans[rowsKey]
                        // ct(this_tran)

                        let this_row,id,barcode,desc,qty,cost,tax,select,sel_note,tran,r_bg,sn
                        id = this_tran['id']
                        barcode = this_tran['barcode']
                        desc = this_tran['desc']
                        qty = this_tran['qty']
                        cost = this_tran['cost']
                        tax = this_tran['tax']
                        select = this_tran['select']
                        tran = this_tran['tran']
                        sn = parseInt(rowsKey)+ 1

                        if(tran === 'D')
                        {
                            r_bg = 'bg-warning text-danger'
                            sn = ''
                        } else {
                            r_bg = ''
                        }


                        sel_note = 'cart_item'
                        if (select == '1')
                        {
                            sel_note = 'cart_item active'
                            sel_count ++
                        }

                        this_row = `<div 
                                    onclick= "mark_bill_item('${id}')" id='billItem${barcode}'
                                    class="d-flex flex-wrap ${sel_note} ${r_bg} align-content-center justify-content-between border-dotted pb-1 pt-1"
                                    >
                                    
                                    <div class="w-10 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <p class="m-0 p-0">${sn}</p>
                                    </div>
            
                                    <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <div class="w-100"><small>${barcode}</small></div>
                                        <p class="m-0 p-0">${desc}</p>
                                    </div>
            
                                    <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <p class="m-0 p-0">${qty}</p>
                                    </div>
            
                                    <!--Cost-->
                                    <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <p class="m-0 p-0">${cost}</p>
                                    </div>
                                </div>`

                        // append row
                        rows += this_row

                    }
                    // ct(rows)
                    if(sel_count > 0)
                    {
                        arr_enable('void_button')
                    } else {
                        arr_disable('void_button')
                    }
                    $('#bill_loader').html(rows)
                    $("#bill_loader").animate({ scrollTop: $('#bill_loader').prop("scrollHeight")});

                } else
                {
                    arr_enable('recall')
                    arr_disable('cash_payment,momo_payment,cancel,subTotal,hold,discount,REFUND')
                    let no_bill = `<div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><i class="fa fa-4x text-muted fa-cart-plus"></i></div>`
                    jqh.setHtml({'bill_loader':no_bill})
                }
            }
        };

        $.ajax(settings)
        if(this.isHold() === true)
        {
            arr_enable('bill_recall')
        } else
        {
            arr_disable('bill_recall')
        }

    }
    
    isHold()
    {
        let result = ''
        var form = new FormData();
        form.append("function", "isHold");

        form_settings['url'] = '/backend/process/ajax_tools.php'
        form_settings['data'] = form
        form_settings['success'] = function (response) {
            result = response
        }
        $.ajax(form_settings)

        if(result > 0)
        {
            // enable hold
            cl(`resp : ${result} - There is hold`)
            return true
        } else
        {
            // no response
            cl(`resp : ${result} - There is no hold`)
            return false
        }

    }

    holdBill(){ // HOLD A BILL

        var form = new FormData();
        form.append("function", "hold_current_bill")
        form_settings['url'] = form_process
        form_settings['data'] = form

        form_settings['success'] = function (response){
            ct(response)
            let j_res = JSON.parse(response)
            al(j_res['message'])


        }

        Swal.fire({
            title: "Are your sure you want to hold bill?",
            icon: 'info',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // make ajax call
                $.ajax(form_settings);
                this.loadBillsInTrans()
            } else if (result.isDenied) {

            }
        })
    }

    // make payment
    payment(method){

        // validate there is cash input
        let amount_paid = document.getElementById('general_input').value; // gen input field


        if(amount_paid.length > 0)
        {
            // get total balance
            var balance = document.getElementById('sub_total').innerText;

            var actual_balance = parseFloat(balance), actual_paid = parseFloat(amount_paid)

            var b_balance = parseFloat(actual_paid) - parseFloat(actual_balance)

            // compare balance
            if(actual_paid >= actual_balance)
            {

                // make form data
                form_data = {
                    'function':'payment',
                    'method':method,
                    'amount_paid':amount_paid
                }

                // send ajax request
                $.ajax({
                    url: form_process,
                    type:'POST',
                    data:form_data,
                    success: function (response) {
                        ct(response)
                        let result = JSON.parse(JSON.stringify(response))
                        let status,message
                        status = result['status']
                        message = result['message']

                        if(status === 200){
                            // get payment details
                            let bill_amt,tax_amt,taxable_amt,tran_qty,amt_paid,amt_bal
                            bill_amt = message['bill_amt']
                            taxable_amt = message['taxable_amt']
                            tax_amt = message['tax_amt']
                            tran_qty = message['tran_qty']
                            amt_paid = message['amt_paid']
                            amt_bal = message['amt_bal']

                            jqh.setText({
                                'tax':tax_amt,
                                'amount_paid':amt_paid,
                                'amount_balance':amt_paid - bill_amt,
                                'bill_num':parseFloat($('#bill_num').text()) + 1
                            })

                            jqh.setVal({'general_input':''})

                            jqh.setHtml({'bill_loader':''})




                        } else
                        {
                            // bill not saved
                            error_handler(`error%%Cound Not Make Bill ${status}`)
                        }

                        //location.reload()


                    }
                });

            }
            else
            {
                alert('Paid amount less','','warning')
                $('#general_input').addClass('bg-danger');
                setTimeout(function (){$('#general_input').removeClass('bg-danger')},2000)

            }
        }
        else
        {
            alert('Enter Payment Amount','','warning')
            $('#general_input').addClass('bg-danger');
            $('#general_input').prop('autofocus',true)
            setTimeout(function (){$('#general_input').removeClass('bg-danger')},2000)
        }

    }
    //make payment

    recall(){
        var val = document.getElementById('general_input'); // gen input field

        if(val.value.length > 0) // if amout value is greater than zero
        {
            // prapre form for ajax
            let data = {
                'function':'recall_bill',
                'bill_grp':val.value,
                'token':''
            };

            // make ajax function
            $.ajax({
                url: '/backend/process/form_process.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    ct(response)
                    let res = JSON.parse(JSON.stringify(response))
                    if(res['status'] === 200)
                    {
                        bill.loadBillsInTrans()
                    } else
                    {
                        s_response('error','','Cannot Recall Bill')
                    }
                    // get_bill()
                }
            });

        }
        else
        {
            console.log(val.value.length)
            val.style.border = '2px solid red';
            val.style.background = '#eb9783';
            val.placeholder = 'Enter Bill Number';

        }
    }

    // void item from bill trans

    void(){
        var form = new FormData();
        form.append("function", "void");
        var setting = {

            "url": "/backend/process/form_process.php",
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form,
            success: function (response) {
                get_bill()
            }
        }

        Swal.fire({
            text: 'Are you sure you want to void items from bill?',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'YES',
            denyButtonText: `CANCEL`,
            icon:'warning'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax(setting)

            }
        })
    }

    // void item from bill trans
}




// initialize object
const bill = new Bill()