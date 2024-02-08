class Bill {

    setSale(billing_type = 'sale'){
        switch (billing_type) {
            case 'refund':
                // validate sales
                let oldRef = $('#general_input').val();
                if(oldRef.length > 0){
                    
                    // validate bull reference
                    let bill_count = 0,ref_type='';
                    if(row_count('bill_trans',`billRef = '${oldRef}'`) === 1){
                        bill_count = 1;
                        ref_type = 'active_shift';
                        anton.setCookie('refund_table','bill_trans');
                        anton.setCookie('oldBillRef',oldRef)
                    } else if(row_count('bill_history_trans',`billRef = '${oldRef}'`) === 1){
                        bill_count = 1;
                        ref_type = 'active_shift';
                        anton.setCookie('refund_table','bill_trans')
                        anton.setCookie('oldBillRef',oldRef)
                    } else {
                        al(`Cannot find bill with reference ${oldRef}`)
                    }

                    al("Bill loaded for refunding please add items to cart!!")
                    
                } else {
                    al('Please enter buill reference')
                }
                anton.setCookie('billing_type',billing_type);
                break;
            case 'sale':
                anton.setCookie('billing_type',billing_type);
                break;
            default:
                al("UNKNOWN SALES TYPE");
                break;

            
        }

        bill.checkBillType()
       
        
    }

    checkBillType(){
        if(anton.getCookie('billing_type') === 'refund'){
            $('#cart_display').addClass('bg-danger');
            
        } else {
            $('#cart_display').removeClass('bg-danger');
        }
    }

    validateRefindItem(){
        // bill refund details
        
    }

    loadBillsInTrans(){
        b_msg('Loading Bill Transactions...')
        this.checkBillType()
        //bill.sub_total()
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


                let res = JSON.parse(response)



                if(res['status'] === 202)
                {

                    let message = res['message']
                    let header = message['bill_header']['bill_header']
                    //let header = bill.billSummary()['bill_header']
                    // console.table(message['bill_header']['bill_header']);
                    // console.table(res);

                    let count,total,tax,trans,discount,bill_amt,disc_type,BILL_REF
                    count = message['count']
                    total = header['TOTAL_AMOUNT']
                    discount = header['DISCOUNT'];
                    bill_amt = header['BILL_AMT']
                    tax = header['TOTAL_VAT']
                    trans = message['trans']
                    disc_type = header['discount_type'];
                    BILL_REF = header['BILL_REF'];
                    $('#bill_ref').val(BILL_REF)
                    let sel_count = 0

                    // ct(header)

                    arr_disable('recall,REFUND')
                    arr_enable('cash_payment,momo_payment,credit_payment,cancel,subTotal,hold,discount')
                    enableFields(['load_cust'])




                    // load header
                    // $('#sub_total').text(total)
                    // $('#tax').text(tax)
                    jqh.setText(
                        {
                            'sub_total':total, 'disc_amt':discount,'bill_amt':bill_amt,
                            'tax':tax,'amount_paid':'0.00','amount_balance':'0.00'
                        })





                    //let rows = message['trans_html'];
                    let rows = '';

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
                    arr_disable('cash_payment,momo_payment,credit_payment,cancel,subTotal,hold,discount')
                    let no_bill = `<div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><i class="fa fa-4x text-muted fa-cart-plus"></i></div>`
                    jqh.setHtml({'bill_loader':no_bill})
                    b_msg("No bill transactions")
                } else {
                  arr_enable('recall,REFUND')
                  arr_disable('cash_payment,momo_payment,credit_payment,cancel,subTotal,hold,discount')
                  let no_bill = `<div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><i class="fa fa-4x text-muted fa-cart-plus"></i></div>`
                  jqh.setHtml({'bill_loader':no_bill})
                  b_msg("Could not load bill. Contact system administrator")
                }

                // load loyalty details
                let bill_ref = $('#bill_ref').val();
                let is_loyalty =  row_count('loyalty_tran',`billRef = '${bill_ref}'`);

                if(is_loyalty === 1){

                    let cust_code_q = JSON.parse(get_row('loyalty_tran',`billRef = '${bill_ref}'`))[0];

                    let name, points;
                    name = cust_code_q['cust_name'];
                    points = cust_code_q['points_before'];
                    arr_disable('LOYALTY_LOOKUP')
                    if (points > 1000) {
                        arr_enable('LOYALTY_REDEEM')
                    } else {
                        arr_disable('LOYALTY_REDEEM')
                    }
                    $('#msglegend').html(`LOYALTY CUSTOMER : ${name} with <span class="badge badge-success">${points}</span> points`)

                }
                else {
                    arr_enable('LOYALTY_LOOKUP')
                    arr_disable('LOYALTY_REDEEM')
                    $('#msglegend').html('')

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

        //this.buttonRefresh()
        b_msg("")

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
    make_payment(method,customer='bill'){

        b_msg("Making Payment....")
        Swal.fire({
            html: `<div class='text-center'><strong>Making ${method.toUpperCase()} Payment...</strong></div>
                               <div class="text-center">
                                   <div class="spinner-border text-primary" role="status">
                                       <span class="sr-only">Making ${method.toUpperCase()} Payment...</span>
                                   </div>
                               </div>`,
            showConfirmButton: false, // Hide confirm button
            allowOutsideClick: false, // Prevent click outside to close
            allowEscapeKey: false, // Prevent ESC key to close
        });



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


                // make form data
                form_data = {
                    'function':'payment',
                    'method':method,
                    'amount_paid':amount_paid,
                    'billing_type':anton.getCookie('billing_type'),
                    'old_ref':anton.getCookie('refund_ref'),
                    'customer':customer
                }

                jqh.setText({

                    'amount_paid':actual_paid.toFixed(2),
                    'amount_balance':b_balance.toFixed(2)

                })


                // send ajax request

                $.ajax({
                    url: form_process,
                    type:'POST',
                    data:form_data,
                    success: function (response) {
                        console.log("HELLO")
                        console.log(response)
                        kasa.info("GOOD THINGS ON THE WAY")
                        let result = JSON.parse(response)
                        let status,message
                        status = result['code']
                        message = result['message']
                        
                        alert(response)

                        if(status === 200){

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
                                'bill_num':parseFloat($('#bill_num').text()) + 1
                            })

                            jqh.setVal({'general_input':''})

                            jqh.setHtml({'bill_loader':''})

                            b_msg('payment complete..')


                            // console.log('BILL TRANSACTION DONE')

                            anton.setCookie('billing_type','sale')
                            anton.setCookie('refund_ref',null)
                            anton.setCookie('refund_table',null)
                            anton.setCookie('refund_header',null)
                            bill.checkBillType()
                            bill.loadBillsInTrans()
                            Swal.close()


                        }
                        else
                        {
                            // bill not saved
                            console.log("BILL COMPLETED WITH ERROR")
                            b_msg(message)
                            // error_handler(`error%%Cound Not Make Bill ${status}`)
                            kasa.error(message)
                        }




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

            console.table(data)

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
        let bill_ref = $('#bill_ref').val();
        let query = `DELETE FROM bill_trans where billRef = '${bill_ref}' and selected = '1'`;
        if(row_count('bill_trans',`billRef = '${bill_ref}'`) > 0){

            Swal.fire({
                text: 'Are you sure you want to void items from bill?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'YES',
                denyButtonText: `CANCEL`,
                icon:'warning'
            }).then((result) => {
                if (result.isConfirmed) {
                    exec(query);
                    kasa.success('Selected Items Voided')
                    this.loadBillsInTrans()
                }
            })

        } else {
            kasa.error("NO ITEM SELECTED")
        }
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
            let b_hd,b_tr,b_count,ref_type = '';
            let refund_header,refund_trans;
            let bill_details = {
                'header':null,
                'trans':{
                    'count':0,
                    'list':null
                }
            }

            if(row_count('bill_header',`billRef = '${billRef}'`) === 1 )
            {
                ref_type = 'bill_header'
                refund_header = 'bill_header';
                refund_trans = 'bill_trans';
                bill_details['header'] = JSON.parse(get_row('bill_header',`billRef = '${billRef}'`))
                bill_details['trans']['count'] = row_count('bill_trans',`billRef = '${billRef}'`)
                bill_details['trans']['list'] = JSON.parse(get_row('bill_trans',`billRef = '${billRef}'`))


            }
            else if(row_count('bill_history_header',`billRef = '${billRef}'`) === 1)
            {
                ref_type = 'bill_history_header'
                refund_header = 'bill_history_header';
                refund_trans = 'bill_history_trans';
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

            console.table(bill_details)
            


            if(b_count > 0)
            {
                anton.setCookie('refund_table',ref_type);
                anton.setCookie('refund_ref',billRef)
                anton.setCookie('refund_header',refund_header);
                anton.setCookie('refund_trans',refund_trans)
                let transactions = fetch_rows(`SELECT bt.item_barcode, bt.item_desc, bt.retail_price, SUM(bt.item_qty) AS qty, SUM(bt.bill_amt) AS bill_amt, SUM(gfund) AS gfund, SUM(nhis) AS nhis, SUM(covid) AS covid, SUM(vat) AS vat FROM ` + "`" +refund_trans +"`" + ` bt WHERE bt.billRef = '${billRef}' GROUP BY bt.bill_number, bt.item_barcode, bt.retail_price, bt.item_barcode, bt.retail_price, bt.item_desc;`);
                let trans = JSON.parse(transactions);
                console.table(trans);
                let tr = "";
            
                for (let b = 0; b < trans.length; b++) {
                    let line = trans[b]
                    let b_code,qty,r_price,bill_amt,taxable,tax,id
                    id = line['id']
                    b_code = line['item_barcode']
                    qty = line['qty']
                    r_price = line['retail_price']
                    bill_amt = line['bill_amt']
                    taxable = line['vat']
                    tax = r_price - taxable
                    let item_desc = line['item_desc']
                    let req = ''
                    if(b === 0){
                        req = 'required'
                    }
                    tr += `<tr>
                                <td><input id="refund_item_${b}" required name="refund_item[]" type="checkbox" value="${b_code}"></td>
                                <td id="barcode_${b}">${b_code}</td>
                                <td id="name_${b}">${item_desc}</td>
                                <td><input value="${r_price}" type="number" style="width: 50px" name="price[]" id="price_${b}"></td>
                                <td><input type='number' style="width: 50px" id="ref_qty_${b}" max="${qty}" value='${qty}' name='refund_item[]' /></td>
                                <td><input value="${bill_amt}" type="number" style="width: 50px" name="total_amt[]" id="total_amt_${b}"></td>
                                <td><input value="${taxable}" type="number" style="width: 50px" name="tax_amt" id="tax_amt_${b}"></td>
                            </tr>`
            
                }
                let m_form = `<div id="refundForm" method="post" action="/backend/process/form-processing/billing.php">
                        <input type="hidden" name="function" value="bill_refund">
                        <input type="hidden" name="ref_type" value="${ref_type}">
                        <input type="hidden" name="billRef" value="${billRef}">
                        <div class="w-100 d-flex flex-wrap justify-content-between">
                            <div class="w-50">
                                <strong>Date : </strong> <br>
                                <strong>Time : </strong> <br>
                            </div>
            
                            <div class="w-50 text-right">
                                <strong>Mech # : </strong> <br>
                                <strong>Clerk : </strong> <br>
                            </div>
                        </div>
                        <hr>
                        <table class="table table-sm table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fa fa-check-square"></i></th>
                                    <th>Barcode</th>
                                    <th>Description</th>
                                    <th>PRICE</th>
                                    <th>QTY</th>
                                    <th>TOTAL</th>
                                    <th>TAX </th>
                                </tr>
                            </thead>
                            ${tr}
                        </table>
                        <hr>
                        <button type="button" onclick="bill.addRefundTransactions()" class="btn btn-danger">LOAD TRANSACTION</button>
                    </div> `

                mpop.setBody(m_form);
                mpop.setTitle("BILL REFUND");
                mpop.setSize('lg');
                mpop.show()
            }


        } else {

            al("PLEASE PROVIDE BILL REFERENCE")

        }


    }

    addRefundTransactions(){
        let checkboxes = document.getElementsByName('refund_item[]');
        let isChecked = false;
        let itemCount = 0;
        let refund_table = anton.getCookie('refund_table');
        let refund_ref = anton.getCookie('refund_ref');
        let refund_header = anton.getCookie('refund_header');
        let refund_trans = anton.getCookie('refund_trans');
        let refund_data = {
            table:refund_table,
            bill_reference:refund_ref,
        }
        let trans = [];
        let query = '';
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                let box = checkboxes[i];
                let value = box['value'];
                let id = box['id'];
                let box_number = id.charAt(id.length - 1);
                let qty_id = `ref_qty_${box_number}`;
                let qty = $(`#${qty_id}`).val();
                let mach, clerk, bill_number, trans_type, tax_amt_2, bill_amt, item_desc, tran_type, bill_ref, gfund, nhis, covid;

                let item_qty,retail_price,item_barcode,vat;
                item_qty = $(`#ref_qty_${box_number}`).val();
                retail_price = $(`#price_${box_number}`).val();
                item_barcode = $(`#barcode_${box_number}`).text();
                item_desc = $(`#name_${box_number}`).text();
                vat = $(`#tax_amt_${box_number}`).val();
                bill_amt = retail_price * qty;
                tax_amt_2 = 0;

                gfund = 0;
                nhis = 0;
                covid = 0;
                vat = 0;
                if(vat > 0){
                    let tax = taxMaster.taxInclusive(bill_amt);
                    let taxes = tax['message'];
                    vat = taxes['vat'];
                    nhis = taxes['nh'];
                    gfund = taxes['gf'];
                    covid = taxes['cv']
                }

                // insert into current bill

                mach = mech_no;
                clerk = user_id;
                bill_number = a_sess.get_session('bill_no');
                tran_type = 'RR';
                trans_type = 'i';
                bill_ref = a_sess.get_session('bill_ref');

                let q = `INSERT INTO bill_trans (mach, clerk, bill_number, item_barcode, trans_type, retail_price, item_qty, tax_amt, bill_amt, item_desc, tran_type, billRef, gfund, nhis, covid, vat, shift,old_bill_ref) values ('${mach}','${clerk}','${bill_number}','${item_barcode}','${trans_type}','${retail_price}','${qty}','${tax_amt_2}','${bill_amt}','${name}','SS','${bill_ref}',${gfund},${nhis},${covid},'${vat}','${shiftNO}','${refund_ref}');`;
                exec(q);

                trans.push({
                    barcode:item_barcode,
                    sold_price:retail_price,
                    refund_qty:item_qty
                });
                itemCount += 1;
            }

        }

        refund_data['trans'] = trans;
        console.table(refund_data)
        if (itemCount > 0) {
            bill.loadBillsInTrans();
            mpop.hide();
            kasa.success("Proceed to Cash Out")
            anton.setCookie('billing_type','refund')


        } else {
            al("Please Select Items")
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

    add2bill(){
        let data = {};
        data['billing_type'] = anton.getCookie('billing_type');
        data['ref_header'] = anton.getCookie('ref_header');
        data['ref_trans'] = anton.getCookie('ref_trans');
        data['refund_ref'] = anton.getCookie('refund_ref');
        data['function'] = 'new_item';
        data['barcode'] = $('#general_input').val();

        form_settings['url'] = '/backend/process/form_process.php';
        form_settings['data'] = data;
        form_settings['success'] = function (response) {
            console.table(response)
        }
        $.ajax(form_settings);

    }

    addToBill(){

        let barcode_string = $('#general_input').val();
        let input = ['general_input'];
        if(anton.validateInputs(input)){
            let bill_ref = billREF;
            let qty = 1;
            let barcode_qty = barcode_string.split('*');
            let barcode = barcode_qty[0];
            if(barcode_qty.length === 2){
                qty = barcode_qty[0];
                barcode = barcode_qty[1]
            }

            // check if item exist
            let is_product = row_count('prod_mast',`barcode = '${barcode}'`);

            if(is_product === 1){
                let product_row = get_row('prod_mast',`barcode = '${barcode}'`);
                if(isJson(product_row)){
                    let product = JSON.parse(product_row)[0];
                    let name,retail,retail_wo_tax,tax_amt,taxable;
                    name = product['desc'];
                    retail = product['retail'];
                    retail_wo_tax = product['retail_wo_tax']
                    tax_amt = product['tax_amt'];
                    taxable = product['tax_grp'];


                    let mach, clerk, bill_number, item_barcode, trans_type, retail_price, item_qty, tax_amt_2, bill_amt, item_desc, tran_type, billRef, gfund, nhis, covid, vat;
                    mach = mech_no;
                    clerk = user_id;
                    bill_number = a_sess.get_session('bill_no');
                    item_barcode = barcode;
                    trans_type = 'i';
                    retail_price = retail;
                    item_qty = qty;
                    tax_amt_2 = tax_amt * qty;
                    bill_amt = retail * qty;
                    item_desc = name;
                    tran_type = 'SS';
                    bill_ref = a_sess.get_session('bill_ref');
                    // let row_uni = md5(`${barcode},${qty},${new Date()}`)

                    if(taxable === 'YES'){
                        let tax = taxMaster.taxInclusive(retail * qty);
                        let taxes = tax['message'];
                        vat = taxes['vat'];
                        nhis = taxes['nh'];
                        gfund = taxes['gf'];
                        covid = taxes['cv']

                    } else {

                        gfund = 0;
                        nhis = 0;
                        covid = 0;
                        vat = 0;
                    }

                    let sel_note,r_bg;
                    sel_note = '';r_bg=''

                    let sn = $('#bill_loader div').length + 1;



                    let insert_qeury = `INSERT INTO bill_trans (mach, clerk, bill_number, item_barcode, trans_type, retail_price, item_qty, tax_amt, bill_amt, item_desc, tran_type, billRef, gfund, nhis, covid, vat, shift) value 
                                                                    ('${mach}','${clerk}','${bill_number}','${barcode}','${trans_type}','${retail}','${qty}','${tax_amt_2}','${bill_amt}','${name}','SS','${bill_ref}',${gfund},${nhis},${covid},'${vat}','${shiftNO}')`;

                    let insert = exec(insert_qeury);
                    // if(insert['code'] === 202){
                    //     let row = `<div
                    //                             onclick= "mark_bill_item('${row_uni}')" id='billItem${barcode}'
                    //                             class="d-flex flex-wrap ${sel_note} ${r_bg} align-content-center justify-content-between border-dotted pb-1 pt-1"
                    //                             >
                    //
                    //                             <div class="w-10 h-100 d-flex flex-wrap align-content-center pl-1">
                    //                                 <small class="m-0 p-0">${sn}</small>
                    //                             </div>
                    //
                    //                             <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1">
                    //                                 <div class="w-100"><small>${barcode}</small></div>
                    //                                 <small class="m-0 p-0">${name}</small>
                    //                             </div>
                    //
                    //                             <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                    //                                 <small class="m-0 p-0">${qty}</small>
                    //                             </div>
                    //
                    //                             <!--Cost-->
                    //                             <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                    //                                 <small class="m-0 p-0">${bill_amt}</small>
                    //                             </div>
                    //                    </div>`;
                    //     $('#bill_loader').append(row);
                    // } else {
                    //     al(insert['message']);
                    // }

                    $('#general_input').val('');
                    this.loadBillsInTrans()

                    // bill.loadBillsInTrans();
                    // console.log(insert_qeury)
                    // console.table(product);
                }

            } else {
                kasa.error(`Product with barcode ${barcode} does not exist`)
            }

            // console.table(barcode_qty);
            // console.log(`BARCODE ${barcode}`)
            // console.log(`QTY : ${qty}`)

        } else {
            kasa.error('ENTER BARCODE');
        }





    }

    printScreen(){
        let form = `
            <div class="w-100">
                <input type="date" value="${toDay}" readonly class="form-control rounded-0 mb-2" id="print_date">
                <input onclick="keyboard.showQwerty('print_bill_number')" type="number" class="form-control rounded-0 mb-2" id="print_bill_number">
                <button onclick="bill.printBillTrigger()" class="w-100 btn btn-info rounded-0">PRINT</button>
            </div>
        `;
        mpop.setTitle("BILL PRINT");
        mpop.setBody(form);
        mpop.show();
    }


    printBillTrigger() {
        let id = ['print_date','print_bill_number'];
        if(anton.validateInputs(id)){
            let inputs = anton.Inputs(id);
            let date,bill;
            date = inputs['print_date'];
            bill = inputs['print_bill_number'];
            this.printBill(bill,mech_no,date);
            mpop.hide()
        } else {
            kasa.error("Fill ALL Fields")
        }
    }

    focScreen(){
        let form = `
            <div class="w-100">
                
                <input type="text" placeholder="Barcode" class="form-control text-center rounded-0 mb-2" id="foc_barcode">
                <input type="number" placeholder="Quantity" class="form-control text-center rounded-0 mb-2" id="foc_qty">
                <button onclick="bill.addFoc()" class="w-100 btn btn-info rounded-0">ADD</button>
            </div>
        `;
        mpop.setTitle("ADDING F.O.C");
        mpop.setBody(form);
        mpop.show();
    }

    addFoc() {
        let ids = ['foc_barcode','foc_qty'];
        if(anton.validateInputs(ids)){
            let barcode,qty, inputs;
            inputs = anton.Inputs(ids);
            barcode = inputs['foc_barcode'];
            qty = inputs['foc_qty'];

            // validate product exist
            if(row_count('prod_mast',`barcode = '${barcode}'`) === 1){
                let product = JSON.parse(get_row('prod_mast',`barcode = '${barcode}'`))[0];
                let desc,retail,tax_grp,mach,clerk,bill_number,tran_type,trans_type,bill_ref,
                    gfund,nhis,covid,vat;
                desc = product['desc'];
                retail = product['retail'];
                tax_grp = product['tax_grp'];

                mach = mech_no;
                clerk = user_id;
                bill_number = a_sess.get_session('bill_no');
                tran_type = 'SS';
                trans_type = 'i';
                bill_ref = a_sess.get_session('bill_ref');

                gfund = 0;
                nhis = 0;
                covid = 0;
                vat = 0;
                let foc_q = `INSERT INTO bill_trans (mach, clerk, bill_number, item_barcode, trans_type, retail_price, item_qty, tax_amt, bill_amt, item_desc, tran_type, billRef, gfund, nhis, covid, vat, shift,date_added) values ('${mach}','${clerk}','${bill_number}','${barcode}','${trans_type}','0.00','${qty}','0.00','0.00','${desc}','SS','${bill_ref}',0,0,0,'0','${shiftNO}','${toDay}');`;
                //console.log(foc_q)
                exec(foc_q);
                bill.loadBillsInTrans();
            } else {
                kasa.error("Product does not exist")

            }

            mpop.hide();

        } else {
            kasa.info("Fill ALl Fields")
        }
    }

    buttonRefresh(){
        let buttons = ['foc','LOYALTY_LOOKUP','discount','REFUND','cancel'];
        let admin_lock = anton.getCookie('admin_lock');
        if(admin_lock === 'open'){
            // enable all buttons
            anton.btn_enable(buttons)
        } else {
            // close all buttons
            anton.btn_disable(buttons)
        }
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
