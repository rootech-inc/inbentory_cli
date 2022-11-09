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
                let res = JSON.parse(response)
                console.table(res)

                if(res['status'] === 202)
                {

                    let message = res['message']
                    let count,total,tax,trans
                    count = message['count']
                    total = message['total']
                    tax = total['tax']
                    trans = message['trans']
                    let sel_count = 0

                    arr_disable('recall')
                    arr_enable('cash_payment,momo_payment,cancel,subTotal,hold,discount,REFUND')


                    // load header
                    $('#sub_total').text(total)
                    $('#tax').text(tax)



                    let rows = ''
                    ct(trans)
                    for (const rowsKey in trans) {
                        ct(rowsKey)
                    }
                    // loop through trans
                    for (let rowsKey in trans) {

                        let this_tran = trans[rowsKey]
                        ct(this_tran)

                        let this_row,id,barcode,desc,qty,cost,tax,select,sel_note
                        id = this_tran['id']
                        barcode = this_tran['barcode']
                        desc = this_tran['desc']
                        qty = this_tran['qty']
                        cost = this_tran['cost']
                        tax = this_tran['tax']
                        select = this_tran['select']


                        sel_note = 'cart_item'
                        if (select == '1')
                        {
                            sel_note = 'cart_item active'
                            sel_count ++
                        }

                        this_row = `<div 
                                    onclick= "mark_bill_item('${id}')" id='billItem${barcode}'
                                    class="d-flex flex-wrap ${sel_note} align-content-center justify-content-between border-dotted pb-1 pt-1"
                                    >
                                    
                                    <div class="w-10 h-100 d-flex flex-wrap align-content-center pl-1">
                                        <p class="m-0 p-0">${parseInt(rowsKey)+ 1}</p>
                                    </div>
            
                                    <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1">
                                    <small>${barcode}</small>
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
                    ct(rows)
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
}




// initialize object
const bill = new Bill()