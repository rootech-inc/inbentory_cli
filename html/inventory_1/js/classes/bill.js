class Bill {
    loadBillsInTrans(){
        b_msg('Loading Bill Transactions...')

        bill.sub_total()
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

                // al(response)
                let res = JSON.parse(response)


                if(res['status'] === 202)
                {

                    let message = res['message']
                    // let header = res['message']['bill_header']
                    let header = bill.billSummary()
                    let count,total,tax,trans,discount,bill_amt,disc_type
                    count = message['count']
                    total = header['total']
                    discount = header['discount']
                    bill_amt = header['bill_amt']
                    tax = header['tax_amt']
                    trans = message['trans']
                    disc_type = header['discount_type'];
                    let sel_count = 0

                    // ct(header)

                    arr_disable('recall,REFUND')
                    arr_enable('cash_payment,momo_payment,cancel,subTotal,hold,discount')
                    enableFields(['load_cust'])




                    // load header
                    // $('#sub_total').text(total)
                    // $('#tax').text(tax)
                    jqh.setText(
                        {
                            'sub_total':total, 'disc_amt':discount,'bill_amt':bill_amt,
                            'tax':tax,'amount_paid':'0.00','amount_balance':'0.00'
                        })





                    let rows = ''
                    // ct(trans)
                    // for (const rowsKey in trans) {
                    //     ct(rowsKey)
                    // }
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
                            barcode = ''
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
                                        <small class="m-0 p-0">${sn}</small>
                                    </div>

                                    <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <div class="w-100"><small>${barcode}</small></div>
                                        <small class="m-0 p-0">${desc}</small>
                                    </div>

                                    <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <small class="m-0 p-0">${qty}</small>
                                    </div>

                                    <!--Cost-->
                                    <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <small class="m-0 p-0">${cost}</small>
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
                    // b_msg("")
                    $('#general_input').val('')



                }
                else if (res['status'] === 404)
                {
                    arr_enable('recall,REFUND')
                    arr_disable('cash_payment,momo_payment,cancel,subTotal,hold,discount')
                    let no_bill = `<div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><i class="fa fa-4x text-muted fa-cart-plus"></i></div>`
                    jqh.setHtml({'bill_loader':no_bill})
                    b_msg("No bill transactions")
                } else {
                  arr_enable('recall,REFUND')
                  arr_disable('cash_payment,momo_payment,cancel,subTotal,hold,discount')
                  let no_bill = `<div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><i class="fa fa-4x text-muted fa-cart-plus"></i></div>`
                  jqh.setHtml({'bill_loader':no_bill})
                  b_msg("Could not load bill. Contact system administrator")
                }

                // load loyalty details
                let bill_ref = $('#bill_ref').val()
                let is_loyalty =  row_count('loyalty_tran',`billRef = '${bill_ref}'`);

                if(is_loyalty === 1){
                    let cust_code_q = JSON.parse(get_row('loyalty_tran',`billRef = '${bill_ref}'`))[0];
                    let cust_code = cust_code_q['cust_code']
                    // disable loyalty button
                    arr_disable('LOYALTY_LOOKUP')
                    arr_enable('LOYALTY_REDEEM')
                    let loyalty = JSON.parse(fetch_rows(`select lc.name as 'customer' from loyalty_tran join loy_customer lc on loyalty_tran.cust_code = lc.cust_code where loyalty_tran.cust_code = '${cust_code}';`))[0];
                    $('#msglegend').html(`LOYALTY CUSTOMER : ${loyalty['customer']}`)
                }
                else {
                    arr_enable('LOYALTY_LOOKUP')
                    arr_disable('LOYALTY_REDEEM')
                    $('#msglegend').html('')
                    //cl(`NO LOYALTY CUSTOMER ${is_loyalty}`)
                }

                // validate if there is credit customer loaded
                

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
            return true
        } else
        {
            // no response
            cl(`resp : ${result} - There is no hold`)
            return false
        }

    }

    holdBill(){ // HOLD A BILL
        b_msg("Holing bill...")
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
        b_msg("Bill Held")
    }

    // make payment
    payment(method){
        console.log('making payment')
        b_msg("Making Payment....")


        // validate there is cash input
        if(method === 'refund'){
            // amount_paid = $('#sub_total').text()
            $('#general_input').val($('#bill_amt').text())
        }




        let amount_paid = $('#general_input').val() ? $('#general_input').val() : $('#bill_amt').text();




        if(amount_paid.length > 0)
        {
            // get total balance
            var balance = $('#bill_amt').text();

            var actual_balance = parseFloat(balance), actual_paid = parseFloat(amount_paid)

            var b_balance = parseFloat(actual_paid) - parseFloat(actual_balance)

            // compare balance
            if(actual_paid >= actual_balance)
            {
                kasa.info("ON")

                // make form data
                form_data = {
                    'function':'payment',
                    'method':method,
                    'amount_paid':amount_paid
                }

                jqh.setText({

                    'amount_paid':actual_paid.toFixed(2),
                    'amount_balance':b_balance.toFixed(2),
                    'bill_num':parseFloat($('#bill_num').text()) + 1

                })

                // send ajax request
                console.log("SENDING BILL0000")
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
                        ct(message)

                        // let bill_num = $('#bill_num').text()
                        let mech_no = Mech.ThisMech()['mechine_number'];



                        if(status === 200){
                            cl("PAYMENT ONE WITHOUT ERROR")
                            // get payment details
                            let bill_amt,tax_amt,taxable_amt,tran_qty,amt_paid,amt_bal,bill_number
                            bill_number = message['bill_number']
                            bill_amt = message['bill_amt']
                            taxable_amt = message['taxable_amt']
                            tax_amt = message['tax_amt']
                            tran_qty = message['tran_qty']
                            amt_paid = message['amt_paid']
                            amt_bal = message['amt_bal']

                            //bill.printBill(bill_number,mech_no,toDay)

                            jqh.setText({
                                'tax':tax_amt,
                                'amount_paid':amt_paid,
                                'bill_num':parseFloat(bill_number) + 1
                            })

                            jqh.setVal({'general_input':''})

                            jqh.setHtml({'bill_loader':''})

                            b_msg('payment complete..')
                            bill.loadBillsInTrans()




                        }
                        else
                        {
                            // bill not saved
                            console.log("BILL COMPLETED WITH ERROR")
                            b_msg('Payment completed with an error')
                            error_handler(`error%%Cound Not Make Bill ${status}`)
                        }

                        //location.reload()


                    }
                });
                console.log('BILL TRANSACTION DONE')

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

        b_msg("")

    }
    //make payment

    recall(){
      b_msg("Recallling Bill...")
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
        b_msg("")
    }

    // void item from bill trans

    void(){
        b_msg("Voiding Selected Items...")
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
        b_msg("")
    }

    // void item from bill trans


    //subtotal
    sub_total(){
      b_msg("Subtotaling...")
        form_settings['url'] = '/backend/process/form_process.php'

        var form = new FormData();
        form.append("function", "subtotal")
        form_settings['data'] = form
        form_settings['success'] = function (response) {
            // cl(response)
            // bill.loadBillsInTrans()
        }
        $.ajax(form_settings)
        b_msg("")
    }
    //subtotal

    // cancel bill
    cancelBill(){
        form_data = {
            'function':'cancel_current_bill'
        }

        Swal.fire({
            title: 'Are you sure you want to cancel bill?',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Yes',
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: '/backend/process/form_process.php',
                    type: 'POST',
                    data: form_data,
                    success: function (response)
                    {
                        console.log(response)
                        get_bill();
                        // Swal.fire('Changes are not saved', '', 'info');
                        // location.reload()
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    }
    // cancel bill

    refundBill(){
        let billRef = $('#general_input').val()
        if (billRef.length > 0)
        {


            // check if bill reference exist
            let b_hd,b_tr,b_count,ref_type;
            let bill_details = {
                'header':null,
                'trans':{
                    'count':0,
                    'list':null
                }
            }

            if(row_count('bill_header',`billRef = '${billRef}'`) === 1 )
            {
                ref_type = 'active_shift'
                // bill is found in trans
                // al("BILL REFERENCE TO EXIST IN CURRENT BILL")
                bill_details['header'] = JSON.parse(get_row('bill_header',`billRef = '${billRef}'`))
                bill_details['trans']['count'] = row_count('bill_trans',`billRef = '${billRef}'`)
                bill_details['trans']['list'] = JSON.parse(get_row('bill_trans',`billRef = '${billRef}'`))


            }
            else if(row_count('bill_history_header',`billRef = '${billRef}'`) === 1)
            {
                ref_type = 'history_shift'
                // there is bill in history
                // al("BILL REFERENCE EXIST IN HISTORY TRANSACTION")
                bill_details['header'] = JSON.parse(get_row('bill_history_header',`billRef = '${billRef}'`))
                bill_details['trans']['count'] = row_count('bill_history_trans',`billRef = '${billRef}'`)
                bill_details['trans']['list'] = JSON.parse(get_row('bill_history_trans',`billRef = '${billRef}'`))
            }
            else
            {
                // bill not found
                al(`Bill with reference ${billRef} does not exist`)
            }


            b_hd = bill_details['header'][0]
            b_tr = bill_details['trans']['list']
            b_count = bill_details['trans']['count']



            if(b_count > 0)
            {
                let tr = "";

                for (let b = 0; b < b_tr.length; b++) {
                    let line = b_tr[b]
                    let b_code,qty,r_price,bill_amt,taxable,tax,id
                    id = line['id']
                    b_code = line['item_barcode']
                    qty = line['item_qty']
                    r_price = line['retail_price']
                    bill_amt = line['bill_amt']
                    taxable = line['tax_amt']
                    tax = r_price - taxable
                    let item_desc = line['item_desc']
                    let req = ''
                    if(b === 0){
                        req = 'required'
                    }
                    tr += `<tr>
                                <td><input ${req} name="refund_item[]" type="checkbox" value="${b_code}|${id}"></td>
                                <td>${b_code}</td>
                                <td>${item_desc}</td>
                                <td>-${qty}</td>
                            </tr>`
                    ct(line)
                }
                ct(b_hd)
                let m_form = `<form id="refundForm" method="post" action="/backend/process/form-processing/billing.php">
                        <input type="hidden" name="function" value="bill_refund">
                        <input type="hidden" name="ref_type" value="${ref_type}">
                        <input type="hidden" name="billRef" value="${billRef}">
                        <div class="w-100 d-flex flex-wrap justify-content-between">
                            <div class="w-50">
                                <strong>Date : </strong>${b_hd['bill_date']} <br>
                                <strong>Time : </strong>${b_hd['bill_time']} <br>
                            </div>
                            
                            <div class="w-50 text-right">
                                <strong>Mech # : </strong>${b_hd['mach_no']} <br>
                                <strong>Clerk : </strong>${b_hd['clerk']} <br>
                            </div>
                        </div>
                        <hr>
                        <table class="table table-sm table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fa fa-check-square"></i></th>
                                    <th>Barcode</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            ${tr}
                        </table>
                        <hr>
                        <button type="button" onclick="$('#refundForm').submit()" class="btn btn-danger">LOAD TRANSACTION</button>
                    </form> `
                $('#ref_type').val(ref_type)
                $('#billRef').val(billRef)
                $('#refDate').text(b_hd['bill_date'])
                $('#refundTime').text(b_hd['bill_time'])
                $('#refMech').text(b_hd['mach_no'])
                $('#refClerk').text(b_hd['clerk'])

                $('#refundBody').html(tr)
                // $('#grn_modal_res').html(m_form)
                // $('#gen_modal_title').text("REFUND")
                $('#refundModal').modal('show')
            }

            // let admin_id_v2,admin_password_v2,result = false;
            // Swal.fire({
            //     title: 'AUTHENTICATE',
            //     html: `<input type="text" autocomplete='off' id="login" class="swal2-input" placeholder="User ID">
            //         <input type="password" id="password" class="swal2-input" placeholder="Password">`,
            //     confirmButtonText: 'AUTH <i class="fa fa-key"></i>',
            //     focusConfirm: false,
            //     backdrop: `
            //     rgba(245, 39, 39, 0.8)
            //     left top
            //     no-repeat
            //   `,
            //
            //     preConfirm: () => {
            //         const login = Swal.getPopup().querySelector('#login').value
            //         const password = Swal.getPopup().querySelector('#password').value
            //         if (!login || !password) {
            //             Swal.showValidationMessage(`Please enter login and password`)
            //         }
            //         return { login_v2: login, password_v2: password }
            //     }
            // }).then((result) => {
            //     admin_id_v2 = result.value.login_v2;
            //     admin_password_v2 = result.value.password_v2;
            //
            //     if(User.adminAuth(admin_id_v2,admin_password_v2))
            //     {
            //         // make refund
            //         let bill_amt,amount_paid;
            //         bill_amt = $('#sub_total').text();
            //         if(bill_amt.length > 0 && bill_amt > 0){
            //
            //             // there is bill
            //             $('#general_input').val(bill_amt);
            //             this.payment('refund')
            //             this.loadBillsInTrans();
            //
            //         } else {
            //             // no bill
            //             swal_error('NO BILL')
            //         }
            //
            //
            //     }
            //     else {
            //         al("NO ACCESS")
            //     }
            //
            // })
        } else {

            al("PLEASE PROVIDE BILL REFERENCE")

        }


    }

    printBill(billNo,mechNo,day = toDay)
    {
        let form_data = {'function':'print_bill','billNo':billNo,'mechNo':mechNo,'day':day};

        ajaxform['url'] = '/backend/process/ajax_tools.php'
        ajaxform['data'] = form_data
        ajaxform['success'] = function (response) {
            ct(response)
        }

        $.ajax(ajaxform)
    }

    billSummary(){
        // set form
        let res = {}
        ajaxform['url'] = '/backend/process/ajax_tools.php'
        ajaxform['data'] = {'function':'bill_summary'}
        ajaxform['type'] = 'POST'
        ajaxform['success'] = function (response) {
            // console.table(response)
            if(isJson(JSON.stringify(response))){
                let resp = JSON.parse(response)

                res = resp
            } else {
                al('INVALID RESPONSE')
            }
        }

        $.ajax(ajaxform)

        return res;


    }

    billSummaryV2(){

    }
}

class Shift {
    CheckShift(mech = Mech.ThisMech()['mechine_number'],day = toDay){
        // check if shift exist
        let shift_detail = {
            'status':false,
            'details':false
        }

        if(row_count('shifts',`shift_date = '${day}' and mech_no = '${mech}'`) === 1)
        {
            // there is shift
            shift_detail['status'] = true
            shift_detail['details'] = JSON.parse(('shifts',`shift_date = '${day}' and mech_no = '${mech}'`))
        } else
        {
            shift_detail['details'] = {
                'mech':mech,
                'date':day,
                'shift_count':row_count('shifts',`shift_date = '${day}' and mech_no = '${mech}'`)
            }
        }

        return shift_detail
    }
}


// initialize object
const bill = new Bill()
const shift = new Shift()
