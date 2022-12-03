$(function() {
    //hang on event of form with id=myform
    $("#add_to_bill_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#add_to_bill_form')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                // echo(response);
                i_hide('numericKeyboard')
                $('#general_input').val('');
                if(response.split('%%')[0] === 'error')
                {
                    let er_msg = response.split('%%')[1]
                    alert(`Could not add to bill <p class="text-danger">${er_msg}</p>`,'error')
                } else {
                    get_bill()
                }
                // alert(response.split('%%')[1]);

                // clear input
                $('#general_input').val('')

            },

        });

        return false;

    });

});



// check for void
function checkVoud() {
    let table = 'bill_trans';
    let condition = "`clerk` = '" + $('#clerk').val() + "' AND `date_added` = '" + toDay + "' AND `bill_number` = '" + $('#bill_number').val() + "' AND `selected` = 1" ;

    let query = row_count(table,condition);
    //echo(query);

    if(query > 0)
    {
        // enable void
        arr_enable('void_button');
        //swal_error('there is void')
    }
    else
    {
        // disable void button
        arr_disable('void_button')
        //swal_error('there is nothin to voud')
    }
}

function void_bill_item()
{

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
        title: 'Are you sure you want to void items from bill?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: 'YES',
        denyButtonText: `CANCEL`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax(setting)

        }
    })



    //
    // let query = "delete from `bill_trans` where " +
    //     "`clerk` = " + "'" + clerk +
    //     "' AND `bill_number` = '" + bill_number +
    //     "' AND `date_added` = '" + toDay +
    //     "' AND `selected` = 1"  ;
    //
    // // execute query
    // exec(query);

}
// check for void

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

// ITEM LOOKUP
function itemLookup() {
    hideKboard()
    let query_str,form_data;

    query_str = $("#general_input").val();
    if(query_str.length > 0 )
    {
        form_data = {
            'function':'LKUP',
            'query_str':query_str
        }

        // send ajax request
        $.ajax(
            {
                url: 'backend/process/form-processing/billing.php',
                type: 'POST',
                data: form_data,
                success: function (response) {

                    if(responseType(response) === 'done')
                    {
                        // get response message
                        let mesg =responseMessage(response)
                        var taBle = "<table class='table table-bordered'>" +
                            "<thead class='thead-dark'><tr><th>Barcode</th><th>Description</th><th>Retail</th><th>Qty</th><th>Func</th></tr></thead>" +
                            "<tbody>" +
                            mesg +
                            "</tbody>"+
                            "</table>";
                        // update modal table
                        // show modal
                        gen_modal('LKUP','',taBle)
                        pass(mesg)
                    }
                    else
                    {
                        fail()
                    }
                }
            }
        );

        pass()
    }
    else
    {
        al('Input Cannot Be Empty')

    }
}

function lookupAddToBill(lit) {

    let qty, barcode, qtyValue, barcodeValue, doneBarcode;

    qty = "#qty" + lit.toString();
    barcode = "#barcode" + lit.toString();

    qtyValue = $(qty).val();
    barcodeValue = $(barcode).text();

    doneBarcode = qtyValue.toString() + "*" + barcodeValue.toString();

    // add item to bill
    $('#general_input').val(qtyValue.toString() + '*')
    add_item_to_bill(barcodeValue)
}

// ITEM LOOKUP

// voiding bill
// mark bill item
function mark_bill_item(id) {

    var form_data = {
        'function':'mark_bill','id':id
    }



    $.ajax({
        url: 'backend/process/form-processing/billing.php',
        type: 'POST',
        data: form_data,
        success: function (response)
        {
            console.log(response);
            get_bill();
        }
    });

    get_bill();
}
// voiding bill