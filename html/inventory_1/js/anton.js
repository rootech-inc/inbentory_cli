
// usefull root functions
function cl(params) { // console log
    console.log(params + '\n')
}

ct = (param) => console.table(param);
// ct = (param) => '';

function s_response(icon = 'info',title = '',message = '') {
    Swal.fire({
        icon:icon,
        title:title,
        text:message
    })
}


// date
const today = new Date();
const dd = String(today.getDate()).padStart(2, '0');
const mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
const hh = String(today.getHours())
const mmm = String(today.getMinutes())
const sss = String(today.getSeconds())
const yyyy = today.getFullYear();

const time_x = `${hh}:${mmm}:${sss}`
const current_time_stamp = yyyy + "-" + mm +"-" + dd + " " + hh + ":" + mmm + ":" + sss

// instantiate classes
const a_sess = new a_session();
const jqh = new J_query_supplies();
const db = new Db_trans();
const sys = new System()
const taxMaster = new TaxMaster()
const pCategory = new ProductCategory()
const prod_master = new ProductCategory()
const User = new UserConfig()
const screens = new screenMaster()
const Mech = new MechConfig()
const mech_no = Mech.ThisMech()['mechine_number']
let toDay = ''

if(Mech.is_shift())
{
    var my_shift = Mech.my_shift()
    let x_valid,x_shift
    x_valid = my_shift['valid']
    x_shift = my_shift['shift']



    if(x_valid === 1){
        toDay  = x_shift['shift_date'];
        cl('valid shift')
    } else {
        toDay = yyyy + '-' + mm + '-' + dd;
        cl('invalid shift')
    }
} else {
    toDay = yyyy + '-' + mm + '-' + dd;
    cl('No SHift')
}

const user_id = a_sess.get_session('clerk_id')

var form_settings = {
    "url": "/backend/process/form_process.php",
    "method": "POST",
    'async': false,
    'global': false,
    'dataType': 'html',
    "timeout": 0,
    "processData": false,
    "mimeType": "multipart/form-data",
    "contentType": false,
    "data": '',
    'success': ''
}

let ajaxform = {
    url:'/backend/process/ajax_tools.php',
    'async': false,
    'type': "POST",
    'global': false,
    'dataType': 'html',
    data:form_data,
    success: function (response)
    {
        ct(response)
    }
}





// send j text
function setText(id,text)
{
    jqh.setText(id,text)
}

// check string
function empti(str)
{
    return str.length >= 1;
}


//
function recursiveEach($element){
    $element.children().each(function () {
        var $currentElement = $(this);
        // Show element
        console.info($currentElement[0]);

        // Loop her children
        recursiveEach($currentElement);
    });
}

function loader(action)
{
    if(action === 'show')
    {
        $('#loader').modal('show')
    } else
    {
        $('#loader').removeClass('fade');
        $('#loader').modal('hide')
    }

}

var form_data;
var form_process = "/backend/process/form_process.php";

// bill screen MESSAGE
function b_msg(message = 'none'){
    $('#process_message').text(message)

}

// swal confirm
function swal_confirm(message = 'Continue?')
{
    var r;
    Swal.fire({
        title: 'Do you want to save the changes?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Save',
        denyButtonText: `Don't save`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            r = true;
        } else if (result.isDenied) {
            r = false;
        }
    })


    return r;
}

function percentage(rate,vale)
{
    return rate/100 * vale;
}

function swal_error(message = 'there is an error')
{
    Swal.fire({
        icon: 'error',
        html: "<textarea class='form-control form-control-sm' style='font-size: small' rows='5'>"+message+"</textarea>",
    })
}

function reload()
{
    location.reload();
}



if(document.getElementById('token'))
{
    const token = document.getElementById('token').value
}
else
{
    const token = '';
}

function echo(str)
{
    cl(str + "\n");
}

function pass(message = 'looks good')
{
    echo(message + '\n');
}

function fail(fail_message = "Operation Not Successful!!") {
    swal_error(fail_message)
}

function alert(text ='',title = '',icon='info') {
    swal.fire({
        icon: icon,
        title: title,
        html:text,
    })
}

function responseType(response) {
    var resp_split = response.split('%%');
    if(resp_split.length > 0)
    {
        return resp_split[0];
    }
    else
    {
        return 'unknown_type';
    }
}

function responseMessage(response)
{
    var resp_split = response.split('%%');
    if(resp_split.length > 0)
    {
        return resp_split[1];
    }
    else
    {
        return 'unknown_type';
    }
}




// alert function
function al(message)
{
    s_response('info','',message)
}

function if_id(id)
{
    return !!document.getElementById(id);
}

function show_modal(id) {// show modal
    $('#'+id).modal({
        backdrop: 'static',
        keyboard: false
    })
}
function hide_modal(id) {// show modal
    $('#'+id).modal('hide')
}

// sort function
function item_sort(module, direction, uni = 0) {
    //cl(module);cl(direction);cl(uni);

    if(module === 'product_category') // item category
    {
        let table, condition, res, id;
        table = 'item_group';
        if(direction === 'left') // sort left
        {
            condition = "`id` < '" + uni.toString() + "' order by `id` desc LIMIT 1";
        }
        else if (direction === 'right')
        {

            condition = "`id` > '" + uni.toString() + "' order by `id` LIMIT 1";
        }


        res = get_row(table,condition);
        var obj = JSON.parse(res);
        id = obj[0].id

        $('#sort_left').val(id)
        loadCategory(id)
        // echo(res)
        // echo(obj[0].id);


    }

}

// delete item
function delete_item(module,item) {

    if(confirm('Are you sure you want to execute  task'))
    {

        if(module === 'po_trans')
        {
            // delete from module trans
            $('#'+item).remove()
            // let item_code = item;
            // let my_user_name = $('#my_user_name').val();
            // // delete from item row
            // let query = "DELETE FROM `po_trans` WHERE `item_code` = '"+item_code+"' AND `owner` = '"+my_user_name+"' AND `date_added` = '"+toDay+"' AND `parent` is null";
            // exec(query)
            // loadPoTrans()
        }

    } else {
        cl("Execution canceled")
    }

}

// show hide
function i_show(params) {
    cl("Showing " + params)
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        if(document.getElementById(element))
        {
            document.getElementById(element).style.display = '';
        }


    }
}

// hide
function i_hide(params) {
    echo("Hiding " + params)
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        if(document.getElementById(element))
        {
            document.getElementById(element).style.display = 'none';
        }


    }
}

// enable
function en(param) {
    if(document.getElementById(param))
    {
        document.getElementById(param).disabled = false;
    }

}
function dis(param) {
    document.getElementById(param).disabled = true;
}

// disable forms
function form_toggle(func,form_id) {
    if(func === 'en')
    {
        if(document.getElementById(form_id))
        {
            var form = document.getElementById(form_id);
            var elements = form.elements;

            for(var i = 0, len = elements.length; i < len; i++)
            {
                elements[i].disabled = false;
            }
        }

    } else
    {
        if(document.getElementById(form_id)){
            var form = document.getElementById(form_id);

            var elements = form.elements;

            for(var i = 0, len = elements.length; i < len; i++)
            {
                elements[i].disabled = true;
            }
        }

    }


}

// enable form

// enable item
function element_toggle(func,param)
{
    if(document.getElementById(param))
    {
        document.getElementById(param).disabled = func !== 'en';
    }
}

//          toggle elements
//          USAGE
//          tog_ele('id_of_element=action')
//          execute more toggles in one run by separating them with commas
//          tog_ele('id_of_element=action,id_of_element2=action2')
function tog_ele(data){

    const tog_ele_sep = data.split(',');// split parameter into an array by comma
    // loop throgh each sep
    for (let i = 0; i < tog_ele_sep.length; i++) {
        var data = tog_ele_sep[i].split('=');
        var target = data[0];
        var action = data[1];

        // action definitions
        // sh = show content
        // hd = hide content
        // f_dis = disable form
        // f_en enable form

        let form;
        let elements;
        switch (action) {

            // show element
            case 'sh':
                cl("showing " + target)
                if(if_id(target)) // check if id exist
                {
                    i_show(target);
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // hide element
            case 'hd':
                cl("Hiding " + target)
                if(if_id(target))
                {
                    i_hide(target)
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // disable form
            case "f_dis":
                cl("Disabling form with id " + target)
                if(if_id(target)) // check if id exist
                {
                    form = document.getElementById(target);
                    elements = form.elements;

                    for(let i = 0, len = elements.length; i < len; i++)
                    {
                        elements[i].disabled = true;
                    }
                }
                else
                {
                    cl("Can't find id " + target)
                }

                break;

            // enable form
            case 'f_en':
                cl("Enabling form with id " + target)
                if(if_id(target))
                {
                    form = document.getElementById(target);
                    elements = form.elements;

                    for(let i = 0, len = elements.length; i < len; i++)
                    {
                        elements[i].disabled = false;
                    }
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // enable element
            case 'en':
                if(if_id(target))
                {
                    document.getElementById(target).disabled = false;
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // disable element
            case 'dis':
                if(if_id(target))
                {
                    document.getElementById(target).disabled = true;
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            default:
                cl("nothing to do with target " + action);
        }
    }
}

if(document.getElementById('category_form'))
{
    form_toggle('dis','category_form');
}

element_toggle('dis','save_button')

// disable defauls
function disableselect(e) {return false}
// get time
function time() {
  var d = new Date();
  var s = d.getSeconds();
  var m = d.getMinutes();
  var h = d.getHours();
  document.getElementById('bill_time').textContent =
    ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);

  var current_date = d.getDate()+ '/' + d.getMonth() + '/' + d.getFullYear();
  document.getElementById('date').textContent = current_date;
}
// validate scrren size
function validateSize(reload) {
    var screen_width = window.innerWidth
    || document.documentElement.clientWidth
    || document.body.clientWidth;

    var screen_height = window.innerHeight
    || document.documentElement.clientHeight
    || document.body.clientHeight;

    var body_existing_content = document.getElementsByTagName('body')[0].innerHTML;

    if(screen_width === 1024 && screen_height === 678)
    {
        if(reload === 'yes')
        {
            location.reload();
        }

    } else {
        var content = "<div class='bg-light w-100 vh-100 text-danger d-flex flex-wrap align-content-center justify-content-center'>" +
            "<div class='alert alert-danger'>Unsupported Screen Dimension <small>i.e <kbd>"+screen_width+"</kbd> x <kbd>"+screen_height+"</kbd></small> Not " + screen_width
            "</div>" +
            "</div>";
        document.getElementsByTagName('body')[0].innerHTML = content;
    }
}

// initialize page
function initialize(params) {
    //validateSize('no');
    i_hide('numericKeyboard');

}

function arr_disable(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {
        let id = "#"+spl[i];
        // cl(`${id} disabled`)
        $(id).prop('disabled',true)
        //echo(id)
    }
}

function arr_hide(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {
        let id = "#"+spl[i];
        $(id).hide()
        //echo(id)
    }
}

function arr_show(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {
        let id = "#"+spl[i];
        $(id).show()
        //echo(id)
    }
}

function arr_enable(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {

        let id = "#"+spl[i];
        // cl(`${id} enabled`)
        $(id).prop('disabled',false)
        //echo(id)
    }
}

// set session
function set_session(array_data,reload = 1) {
    loader('show')
    var form_data = {
        'token':'none',
        'function':'set_session',
        'session_data':array_data
    }
    echo(array_data)
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
    loader('show')
}

// get session
function get_session(sess_var) {
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

// unset session

// scroll categories
function custom_scroll(id,direction) {
    var target = document.getElementById(id); // get div
    if(direction === 'up') // if direction is scrolling up?
    {
        target.scrollBy(0, -50); // scroll up withing target div thats by -50 pixels
    } else if(direction === 'down'){
        target.scrollBy(0, 50); // else? we scroll down by 50 px
    }

    console.log(direction); // for debuging
}

// sub total
function subTotal() {
    form_data = {'function':'sub_total'};

    $.ajax({
        url: form_process,
        type: "POST",
        data: form_data,
        success: function (response)
        {
            echo(response);
            if(response.split('%%').length ===2)
            {
                var action = response.split('%%')[0], message = response.split('%%')[1];

                if(action === 'done')
                {
                    // splie message
                    if(message.split('()').length === 2)
                    {
                        $('#sub_total').text(message.split('()')[0]);
                        $('#tax').text(message.split('()')[1]);

                        jqh.setText({
                            'amount_paid':0.00,
                            'amount_balance':0.00
                        })
                    }

                }
            }
        }

    });
}

// get bill items
function get_bill_v1()
{


    var form_data = {
        'function':'get_bill_items'
    }



    // send ajax request
    $.ajax({
        url:'/backend/process/form_process.php',
        type: 'POST',
        data: form_data,
        success: function (response) {
            console.log(response);
            if(response.split('%%').length === 2)
            {
                var action = response.split('%%')[0];
                var message = response.split('%%')[1];
                //console.log(action)

                if(action === 'done')
                {
                    if(message.length > 0)
                    {

                        var disable_list = 'recall';
                        var enable_list = "momo_payment,cash_payment,discount,hold,cancel,LKUP,subTotal";

                        arr_disable(disable_list);
                        arr_enable(enable_list);


                    }
                    else
                    {
                        var disable_list = "cash_payment,momo_payment";
                        arr_disable(disable_list);


                    }


                    console.log(message)
                    // populate
                    $('#bill_loader').html(message);
                    subTotal()
                }
                else
                {
                    // disable buttons if there is no bill
                    var disable_list = 'momo_payment,cash_payment,cancel,hold,discount,REFUND';

                    arr_disable(disable_list)

                    var cust_html = "<div class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>" +
                        "<p class='fa fa-shopping-cart f-xxlg text-muted'></p>" +
                        "</div>";
                    $('#bill_loader').html(cust_html);
                }

            }

            $("#bill_loader").animate({ scrollTop: $('#bill_loader').prop("scrollHeight")}, 1000);

        }
    });

}

function get_bill() {

    bill.loadBillsInTrans()

}


// edit item
function edit_item(item,reference) {
    switch (item){
        case 'product_group':
            // set session
            let session_data = ['action=edit','group='+reference]
            set_session(session_data,1)
            break;
    }
}


function set_category()
{
    alert('hello')
}

// change category
function change_category(group_uni) {
    //alert(group_uni)
    // prepare for ajax call
    var form_data = {
        'function':'change_item_group',
        'group':group_uni
    }

    //console.log(form_data)

    // make ajax call
    $.ajax({
        url:'/backend/process/form_process.php',
        type: "POST",
        data: form_data,
        success: function (response){
            // echo(response)

            if(response.split('%%').length === 2)
            {
                var act = response.split('%%')[0];
                var msg = response.split('%%')[1];

                //echo(act)
                //console.log(act);

                if(act === 'done')
                {
                    //console.log('done done')
                    $('#items').html(msg);
                }

            }
        }
    });

}

// add item to bill
function add_item_to_bill(barcode) {

    let newValue;
    echo(barcode);
    var existingValue = $('#general_input').val();
    if(existingValue.includes("*"))
    {
        newValue = existingValue.toString() + barcode.toString();
    }
    else
    {
        newValue = barcode.toString();
    }


    $('#general_input').val(newValue)

    // submit form
    $('#add_to_bill_form').submit();
    $("#bill_loader").animate({ scrollTop: $('#bill_loader').prop("scrollHeight")}, 1000);
    $('#gen_modal').modal('hide');

}

// making payment
function make_payment(method) {

    bill.payment(method)

    // // validate there is cash input
    // var amount_paid = document.getElementById('general_input').value; // gen input field
    //
    //
    // if(amount_paid.length > 0)
    // {
    //     // get total balance
    //     var balance = document.getElementById('sub_total').innerText;
    //
    //     var actual_balance = parseFloat(balance), actual_paid = parseFloat(amount_paid)
    //
    //     var b_balance = parseFloat(actual_paid) - parseFloat(actual_balance)
    //
    //     // compare balance
    //     if(actual_paid >= actual_balance)
    //     {
    //
    //         // make form data
    //         form_data = {
    //             'function':'payment',
    //             'method':method,
    //             'amount_paid':amount_paid
    //         }
    //
    //         // send ajax request
    //         $.ajax({
    //             url: form_process,
    //             type:'POST',
    //             data:form_data,
    //             success: function (response) {
    //                 echo(response);
    //                 ct(response)
    //                 get_bill();
    //                 let r_split = response.split('%')
    //                 ct(r_split)
    //                 if(r_split[0] === 'done')
    //                 {
    //
    //                     let b_n = parseInt(r_split[2]) + 1
    //                     jqh.setText({'bill_num':b_n,'amount_balance':b_balance})
    //                     jqh.setVal({'bill_number':b_n})
    //
    //
    //
    //                 } else
    //                 {
    //                     al('not done')
    //                 }
    //
    //                 //location.reload()
    //
    //
    //             }
    //         });
    //
    //     }
    //     else
    //     {
    //         alert('Paid amount less','','warning')
    //         $('#general_input').addClass('bg-danger');
    //         setTimeout(function (){$('#general_input').removeClass('bg-danger')},2000)
    //
    //     }
    // }
    // else
    // {
    //     alert('Enter Payment Amount','','warning')
    //     $('#general_input').addClass('bg-danger');
    //     $('#general_input').prop('autofocus',true)
    //     setTimeout(function (){$('#general_input').removeClass('bg-danger')},2000)
    // }


}

// center pop up
// const popup_center = ({url, title, w, h}) => {
//     // Fixes dual-screen position                             Most browsers      Firefox
//     const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
//     const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
//
//     const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
//     const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
//
//     const systemZoom = width / window.screen.availWidth;
//     const left = (width - w) / 2 / systemZoom + dualScreenLeft
//     const top = (height - h) / 2 / systemZoom + dualScreenTop
//     const newWindow = window.open(url, title,
//       `
//       scrollbars=yes,
//       width=${w / systemZoom},
//       height=${h / systemZoom},
//       top=${top},
//       left=${left}
//       `
//     )
//
//     if (window.focus) newWindow.focus();
// }

// apply discount
function apply_discount(discount) {
    console.log(discount)

    var form_data = {
        'function':'discount','discount':discount
    }

        $.ajax(
            {
                url:'backend/process/form_process.php',
                type: 'POST',
                data: form_data,
                success: function (response) {
                    echo(response)
                    error_handler(response);
                    subTotal();
                }
            }
        );

    $('#discountModal').modal('hide');
}

// hold bill
function hold_bill(params) {
    form_data = {'function':'hold_current_bill'}

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
            $.ajax({
                url:form_process,
                type: "POST",
                data: form_data,
                success: function (response) {
                    // do nothing
                    console.log(response)
                    get_bill()
                    subTotal();
                    // location.reload();
                }
            });
        } else if (result.isDenied) {

        }
    })


}

// recall bill
function recall_bill(token) {
     // validate there is cash input
     var val = document.getElementById('general_input'); // gen input field

     if(val.value.length > 0) // if amout value is greater than zero
     {
         // prapre form for ajax
         data = {
             'function':'recall_bill',
             'bill_grp':val.value,
             'token':token
         };

         // make ajax function
         $.ajax({
             url: '/backend/process/form_process.php',
             type: 'POST',
             data: data,
             success: function(response) {
                 echo(response)
                 error_handler(response)
                 get_bill()
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

// print report
function print_doc(params) {
    console.log(params)
}

// end shift
function take_report(params) {
    if(params === 1) // take z report
    {
        // z-report
        if(confirm('Are you sure you want to proceed?'))
        {
            console.log('procedd to take z-report')
        } else {
            console.log('task terminated')
        }

    } else if(params === 2) { // end daily sales
        // eod
        if(confirm('Are you sure you want to proceed?'))
        {
            console.log('procedd to take eod')
        } else {
            console.log('task terminated')
        }
    }

    console.log(params)
}

// reports function
function report(params) {

    switch (params) { // switch with param

        case 'sales': // sales
            // get sales report, fill it in reports
            $('#gen_modal').removeClass('modal-lg');
            $('#report_res').removeClass('modal_card'); // remove backgroudd of modal body
            $('.modal-title').text('Sales Report');

            // get total sales

            let gross = JSON.parse(fetch_rows(`SELECT SUM(gross_amt) as 'gross_amt' FROM bill_header`))[0].gross_amt

            ct(gross)

            var response = `<div class='w-100 p-2'> \
                <div class='w-100 text-center'> \
                    <p class='font-weight-bolder text-center text-elipse'>${gross}</p>\
                    <hr class='mb-3 mt-3'>\
                </div>\
                <div class='modal_card p-4 mb-4'>\
                    <h4 class='font-weight-bolder mb-2'>MACHINE 1</h4>\
    \
                    <!--CASH-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>CASH</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--MOMO-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>MOMO</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--DISCOUNT-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>Discount</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TOTAL-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TOTAL</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TAX-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TAX</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                </div>\
    \
                <!--MACHINE 2-->\
                <div class='modal_card p-4'>\
                    <h4 class='font-weight-bolder mb-2'>MACHINE 2</h4>\
    \
                    <!--CASH-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>CASH</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--MOMO-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>MOMO</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--DISCOUNT-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>Discount</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TOTAL-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TOTAL</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TAX-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TAX</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                </div>\
    \
                <div class='w-100 p-4 text-center'>\
                    <img \
                        class='img-fluid pointer print_25'\
                        src='assets/icons/home/print_25.png'\
                        title='print'\
                        onclick='print_doc(1)'\
                    >\
                </div>\
    \
            </div>`;
            break;

        case 'z_report': // Z REPORT
            // get sales report, fill it in reports
            $('#modal_d').removeClass('modal-lg');
            $('#report_res').addClass('modal_card'); // add backgroudd of modal body
            $('.modal-title').text('Take Z-Report');
            var response = "\
                <div class='modal_card p-2'>\
                    <div class='w-100'>\
                        <p class='m-0 f-20px text-danger p-0 mb-2 text-center'>This clear sales  for this Machine and end shift</p>\
                    </div>\
                    <div class='w-75 mx-auto clearfix'>\
                        <button onclick='take_report(1)' class='btn m-btn modal_yes rounded-0 float-left'>YES</button>\
                        <button data-dismiss='modal' class='btn m-btn modal_no rounded-0 w-45 float-right'>NO</button>\
                    </div>\
                </div>";
            break;

        case 'eod': // END OF DAY SALES
            // get sales report, fill it in reports
            $('#modal_d').removeClass('modal-lg');
            $('#report_res').addClass('modal_card'); // add backgroudd of modal body
            $('.modal-title').text('Take EOD Report');
            var response = "\
                <div class='modal_card p-2'>\
                    <div class='w-100'>\
                        <p class='m-0 f-20px text-danger p-0 mb-2 text-center'>This whill end sales for all machines</p>\
                    </div>\
                    <div class='w-75 mx-auto clearfix'>\
                        <button  onclick='take_report(2)' class='btn m-btn modal_yes rounded-0 float-left'>YES</button>\
                        <button data-dismiss='modal' class='btn m-btn modal_no rounded-0 w-45 float-right'>NO</button>\
                    </div>\
                </div>";
            break;

        case 'credit_balance': // credit balance
            // show ajax response
            var response = "<div class='w-100 table-responsive'>\
            <table class='table'>\
                <thead class='thead-dark'>\
                    <tr><th>Code</th><th>Contact</th><th>Mobile</th><th>Limit</th><th>Amount</th></tr>\
                </thead>\
                <tbody>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Jane Dow</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Sarkodie</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                    <tr><td>JMT</td><td>Yaa Abena</td><td>+233 20 00 000 000</td><td>$100.00</td><td>$100.00</td></tr>\
                </tbody>\
            </table>\
        </div>";
            // increase modal size
            $('.modal-title').text('Credit');
            $('#modal_d').addClass('modal-lg');
            $('#report_res').removeClass('modal_card'); // remove backgroudd of modal body
            break;

        default: // default switch
            break;
    }

    $("#grn_modal_res").html(response); // send result into modal

    show_modal('gen_modal'); // show modal

}
// cearch product
function search_result(search_domain, match_case) {
    switch (search_domain) {
        case 1: // searching for product
            cl("Searching for " + match_case + " in product")
            break;



        default:
            break;
    }
}
// general modal function
function gen_modal(params,title='Not Set',content = 'none') {
    var response = '';
    $('.modal-title').text(title);
    cl(title)

    switch (params) {
        case 'search_box':
            $('.modal-title').text('Search For Peoduct'); // set modal title
            $('.modal-dialog').addClass('modal-lg'); // increace modal size
            response = '<div class="product_search_bar">\
                <input class="form-control rounded-0 pl-2" onkeyup="search_result(1,this.value)" type="text" placeholder="Barcode, Product Name">\
                </div>\
                <div class="search_container">\
                    <table class="table">\
                        <thead class="thead-dark">\
                        <tr>\
                            <th>Product</th>\
                            <th>Category</th>\
                            <th>Cost</th>\
                            <th>Retail</th>\
                        </tr>\
                        </thead>\
                        <tbody id="myTable">\
                        <tr onclick="set_session(\'product=product_id\')">\
                            <td>Product</td>\
                            <td>Category</td>\
                            <td>$100.00</td>\
                            <td>$150.00</td>\
                        </tr>\
                        </tbody>\
                    </table>\
                </div>';
            $('#grn_modal_res').html(response); // set modal content
            show_modal('gen_modal') // show modal
            break;

        case 'delete_product':
            $('.modal-title').text('Delete Product'); // set modal title
            $('.modal-dialog').removeClass('modal-lg'); // increace modal size
            response = '<div class="w-100 p-5 d-flex flex-wrap align-content-center justify-content-between">' +
                '<div class="w-100 text-center mb-5"><p>Are you sure you eant to delete item?</p></div>' +
                '<button onclick="delete_item(\'product\',\'id\')" class="btn m-btn btn-danger">YES</button>' +
                '<button class="btn m-btn btn-info" data-dismiss="modal">NO</button>' +
                '</div>';
            $('#grn_modal_res').html(response);
            show_modal('gen_modal') // show modal
            break;

        case "category_sub":
            response = '<div class="table-responsive">\n' +
                '                    <table class="table">\n' +
                '                        <thead class="thead-dark">\n' +
                '                            <tr>\n' +
                '                                <th>SN</th><th>Description</th><th>Action</th>\n' +
                '                            </tr>\n' +
                '                        </thead>\n' +
                '                        <tbody>\n' +
                '                            <tr id="test_view">\n' +
                '                                <td>1</td><td>Sub Category</td>\n' +
                '                                <td>\n' +
                '                                    <div class="w-100 d-flex flex-wrap">\n' +
                '                                        <button type="button" onclick="i_hide(\'test_view\');i_show(\'test_edit\')" class="button-25px mr-2 btn"><img class="img-fluid" src="../assets/icons/home/edit_property.png"></button>\n' +
                '                                    </div>\n' +
                '                                </td>\n' +
                '                            </tr>\n' +
                '                            <tr style="display: none" id="test_edit">\n' +
                '                                <form id="form_x">\n' +
                '                                    <input form="form_x" type="hidden" name="sub_id" value="id">\n' +
                '                                    <td colspan="2"><input type="text" class="w-100" autocomplete="off" value="Sub Category"></td>\n' +
                '                                    <td>\n' +
                '                                        <div class="w-100 d-flex flex-wrap">\n' +
                '                                            <button type="button" onclick="i_show(\'test_view\');i_hide(\'test_edit\')" class="button-25px mr-2 btn"><img class="img-fluid" src="../assets/icons/home/cancel.png"></button>\n' +
                '                                            <button form="form_x" class="button-25px mr-2 btn" type="submit"><img class="img-fluid" src="../assets/icons/home/save_close.png"></button>\n' +
                '                                        </div>\n' +
                '                                    </td>\n' +
                '                                </form>\n' +
                '                            </tr>\n' +
                '                        </tbody>\n' +
                '                    </table>\n' +
                '                </div>';

            $('#grn_modal_res').html(response);
            show_modal('gen_modal')
            break;

        case 'LKUP': // show lookup
            $('.modal-title').text('Item Lookup'); // set modal title
            $('.modal-dialog').addClass('modal-lg');
            $('#grn_modal_res').html(content);
            show_modal('gen_modal')
            show_modal('gen_modal')
            break;

        case 'new_item_sub_group':
            var form = "<div  id='sub_category_form'>" +
                "<input type='text' placeholder='Description' id='sub_desc' class='form-control rounded-0' autocomplete='off' name='desc'>" +
                "<input type='hidden' name='function' value='new_item_sub_group'>" +
                "<input type='hidden' name='parent' id='cat_parent' value='" + content + "'>" +
                "<button type='button' onclick='save_new_sub()' class='rounded-0 btn btn-success w-100 mt-2'>SAVE</button>"
                "</div>";
            $('#grn_modal_res').html(form);
            show_modal('gen_modal')
            break;

        case 'select_item_for_po':
            break;

        case 'ret_po_for_grn':
            $('.modal-title').text('Retrieve Purchase Order'); // set modal title
            $('.modal-dialog').addClass('modal-lg');

            //get all approved po
            var all_app_po = JSON.parse(
                get_row('po_hd',"`status` = 1")
            );
            let app_po;
            let app_po_number;
            let grn_exist;
            for (let i = 0; i < all_app_po.length; i++) {
                app_po = all_app_po[i]
                app_po_number = app_po.doc_no // po number

                // check if there is grn for this po
                grn_exist = row_count('grn_hd', "`po_number` = '"+app_po_number+"'")
                if(grn_exist === 0)
                {
                    // grn not made

                }

            }

            $('#grn_modal_res').html("Hllo World");
            show_modal('gen_modal')
            show_modal('gen_modal')
            break

        case 'grn_trans':
            loader('show')

            var entry_no = $('#entry_no').text()
            // get document transactions
            var grn_trans = JSON.parse(
                get_row('doc_trans',"`entry_no` = '"+entry_no+"' AND `doc_type` =  'GRN'")
            )
            var tr = '';
            var res = "<div class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>" +
                "<p class='text-info'>No Document Transactions</p>" +
                "</div>";
            if(row_count('doc_trans',"`entry_no` = '"+entry_no+"' AND `doc_type` =  'GRN'") > 0)
            {
                for (let r = 0; r < grn_trans.length; r++)
                {
                    let line = grn_trans[r]
                    let func = line.trans_func
                    let user = line.created_by
                    let username = getUser(user,'clerk_name');
                    let timeStamp = line.created_on
                    tr += "<tr>" +
                        "<td>"+func+"</td><td>"+username+"</td><td>"+timeStamp+"</td>" +
                        "</tr>"
                }
                var table = "<table class='table table-sm table-bordered'>" +
                    "<thead class='thead-dark'><tr><th>Function</th><th>User</th><th>Time</th></tr></thead>" +
                    "<tbody>"+tr+"</tbody>" +
                    "</table>";
                res = table;
            }


            $('#grn_modal_res').html(res)
            loader('hide')
            show_modal('gen_modal')
            // get grn transactions

        default:
            break;
    }
}
// logout
$(document).ready(function() {
    $("#logout").click(function(){
        //
        $.ajax({
            url: '/backend/process/user_mgmt.php',
            type:'POST',
            data: {'function':'logout'},
            success: function (){
                location.reload()
            }
        });
    });
});
// cancel bill
function cancel_bill() {

    bill.cancelBill()

}

$("#bill_loader").ready(function(){
    get_bill();
    //block will be loaded with element with id myid is ready in dom
    // setInterval(function(){
    //     //this code runs every second
    //
    // }, 1000);
})



$(function() {
    //hang on event of form with id=myform
    $("#general_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#general_form')[0]); // The form with the file inputs.
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
                echo(response);
                i_hide('numericKeyboard')
                $('#general_input').val('');
                error_handler(response);

            },

        });

        return false;

    });

});


function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

// pop up window center
function windowPopUp(url, title, w, h)
{
    let left = (screen.width/2)-(w/2);
    let top = (screen.height/2)-(h/2);
    let reAssignWindow = open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    parent.document.body.disabled = true;
    reAssignWindow.focus();
}

function set_value(id,val) {
    $("#"+id.toString()).val(val)
}

function set_text(id,val) {
    $("#"+id.toString()).text(val)
}

function tax_input(inv_amt,tax_cls)
{
    // prepare and send data to server through ajax
    var post_data = {
        'function':'get_input_tax',
        'invoice_value':inv_amt,
        'tax_class':tax_cls
    }

    var result = 0;

    $.ajax({
        url:'backend/process/ajax_tools.php',
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'html',
        data: post_data,
        success: function (response)
        {
            echo(response)
            result = response
        }
    });

    return result;

}


function download_products() // download products
{
    swal.fire("Downloading.....")
    // delete all and download al buttons
    exec("DELETE FROM item_buttons");
    // download item buttons
    var item_groups = JSON.parse(
        get_row('item_group', "`id` > 0")
    );
    echo(item_groups)
    for (let b = 0; b < item_groups.length; b++) {
        var button = item_groups[b];

        // get index (id) and short description
        var index = button.id;
        var description = button.shrt_name

        // check if group has item
        if(row_count('prod_master',"`group` = '"+index+"'") > 0 )
        {
            var data = {
                'cols': ['button_index', 'description'],
                'vars': [index, description]
            }

            insert('item_buttons', data)
        }



    }

    // delete all items
    exec("DELETE FROM prod_mast");
    // download item buttons
    var items = JSON.parse(
        get_row('prod_master', "`item_code` > 0")
    );

    for (let i = 0; i < items.length; i++) {
        var item = items[i];

        // get index (id) and short description
        let item_id, uni, group, sub_group, barcode, desc, cost, retail, tax_grp, packing, discount, discount_rate,
            stock_type;

        item_id = item.item_code
        uni = item.item_uni;
        group = item.group
        sub_group = item.sub_group;
        barcode = item.barcode;
        desc = item.item_desc
        cost = item.cost;
        retail = item.retail
        tax_grp = item.tax
        packing = item.packing;
        stock_type = item.stock_type


        var data = {
            'cols': ['id', 'item_grp', 'item_uni', 'barcode', 'desc', 'cost', 'retail', 'tax_grp', 'stock_type', 'sub_grp'],
            'vars': [item_id, group, uni, barcode, desc, cost, retail, tax_grp, stock_type, sub_group]
        }

        insert('prod_mast', data)

    }

    swal.fire("Downloading Complete")
}

function new_line(doc,item_code) // insert new line
{
    let body = '#po_items_list', tr = '';
    let item_detail;
    let item_in_list = 'no';



    if (doc === 'grn') {
        item_detail = JSON.parse(
            get_row('prod_master', "`item_code` = '" + item_code + "'")
        )[0]

        cl(JSON.stringify(item_detail))

        let sn = $('#po_items_list tr').length + 1
        let barcode = item_detail.barcode
        let description = item_detail.item_desc;
        // getting pack id
        var prod_packing = JSON.parse(get_row('prod_packing', "`item_code` = '" + item_code + "' AND `purpose` = 2"))[0]
        let pack_pk = prod_packing.pack_id;
        var pack_id = JSON.parse(get_row('packaging', "`id` = '" + pack_pk + "'"))[0].desc
        let packing = prod_packing.pack_desc;

        let qty = 0;
        let tax_amount = 0;
        let net_amount = 0;
        let price = 0;
        let total_amt = 0;
        let this_cost = item_detail.cost;
        let retail = item_detail.retail;

        // ids
        let tr_id = 'row_' + item_code.toString();
        let price_id = 'price_' + sn.toString();
        let qty_id = "qty_" + sn.toString();
        let total_id = 'total_' + sn.toString();
        let cost_id = 'cost_' + sn.toString()
        let retail_id = 'retail_' + sn.toString()
        let code_id = 'code_id_' + sn.toString()
        let net_id = 'net_' + sn.toString()
        let tax_id = 'tax_' + sn.toString()

        if ($("#" + tr_id).length > 0) {
            var line = $("#" + tr_id).find("td:first").text()
            swal_error("Item Exist in list on line " + line)
            item_in_list = 'yes'
        }
        let retail_bg = '';
        if (this_cost >= retail) {
            // danger
            retail_bg = 'bg-danger'
        }

        tr += "<tr id='" + tr_id + "'>\n" +
            "                            <td class='text_xs'><input type='hidden' name='item_code[]' id='" + code_id + "' value='" + item_code + "'>" + sn + "</td>\n" +
            "                            <td class='text_xs'>" + barcode + "</td>\n" +
            "                            <td class='text_xs'>" + description + "</td>\n" +
            "                            <td class='text_xs'>" + pack_id + "</td>\n" +
            "                            <td class='text_xs'>" + packing + "</td>\n" +
            "                            <td class='text_xs'><input type='number' onkeyup=\"grn_list_calc(" + sn + ")\" name='qty[]' id='" + qty_id + "' class='grn_nums' value='" + qty + "'></td>\n" +
            "                            <td class='text_xs'><input type='number' onkeyup=\"grn_list_calc(" + sn + ")\" name='price[]' id='" + price_id + "' class='grn_nums' value='" + price + "'></td>\n" +
            "                            <td class='text_xs'><input type='number' readonly name='total_amt[]' id='" + total_id + "' class='grn_nums bg-primary' value='" + total_amt + "'></td>\n" +
            "                            <td class='text_xs'> <input type='number' readonly id='"+tax_id+"' value='" + tax_amount.toFixed(2) + "' class='grn_nums bg-secondary' name='tax[]' /></td>\n" +
            "                            <td class='text_xs'> <input type='number' readonly class='grn_nums bg-success' name='net[]' id='" + net_id + "' value='" + net_amount.toFixed(2) + "' /></td>\n" +
            "                            <td class='text_xs'><input type='number' id='" + cost_id + "' class='grn_nums' onkeyup=\"grn_list_calc(" + sn + ")\" name='cost[]' value='" + this_cost+ "'></td>\n" +
            "                            <td class='text_xs'><input type='number' id='" + retail_id + "' class='grn_nums "+retail_bg+"' onkeyup=\"grn_list_calc(" + sn + ")\" name='retail[]' value='" + retail + "'></td>\n" +
            "                            <td class='text_xs'><i class='fa fa-minus pointer text-danger pointer' onclick='remove_grn_item(\"" + description + "\",\"#" + tr_id + "\")'></i></td>" +
            "                        </tr>";


        if (item_in_list === 'no') {
            $(body).append(tr)
        }
    }

}

function approve_doc(doc) // approve document
{

    Swal.fire({
        title: 'Are you sure you want to approve document?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'YES',
        denyButtonText: `NO`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {

            // approve grn
            if(doc === 'GRN')
            {
                //get grn number
                let entry_no = $('#entry_no').text();
                if(row_count('grn_hd',"`entry_no` = '"+entry_no+"'") === 1)
                {
                    let grn_hd = JSON.parse(get_row('grn_hd',"`entry_no` = '"+entry_no+"'"))[0];
                    let grn_loc = grn_hd.loc


                    /*
                    * update cost and retail prices
                    * 1. get all transactions with entry number same as header
                    * 2. loop through and in each loop
                    * 3. get item code, cost price, and retail price from current JSON object in loop
                    * 4. with the item code, get item current cost and retail price from prod_master
                    * 5. save current prices into price_history
                    * 6. finally update current price and cost for item
                    * 7. update stock
                    *  */

                    let doc_trans = JSON.parse(get_row('grn_trans',"`entry_no` = '"+entry_no+"'")); //1
                    for(let t = 0; t < doc_trans.length; t++) // 2
                    {
                        // 3
                        let tran = doc_trans[t];
                        let item_code = tran.item_code;
                        let cost = tran.prod_cost;
                        let retail = tran.ret_amt

                        // 4
                        let item_det = JSON.parse(get_row('prod_master',"`item_code` = '"+item_code+"' LIMIT 1"))[0];
                        let cur_cost,cur_ret;
                        cur_cost = item_det['cost'];
                        cur_ret = item_det['retail'];
                        //5
                        exec("INSERT INTO price_change (item_code, price_type, previous, current) VALUES ('"+item_code+"','c','"+cur_cost+"','"+cost+"')")
                        exec("INSERT INTO price_change (item_code, price_type, previous, current) VALUES ('"+item_code+"','r','"+cur_ret+"','"+retail+"')")
                        // 6
                        exec("UPDATE prod_master set prev_retail = retail, retail = '"+retail+"', cost = '"+cost+"' WHERE `item_code` = '"+item_code+"' ")

                        //7
                        let tran_qty = tran.qty;
                        let tran_um = tran.pack_um;

                        let actual_tran_qty = tran_qty * tran_um;
                        let stock_query = "";
                        if(row_count('stock',"`item_code` = '' AND 'loc_id' = '' ") > 0)
                        {
                            // update stock
                            stock_query = "UPDATE stock SET qty = qty + " + actual_tran_qty + " WHERE `item_code` = '"+item_code+"' AND `loc_id` = '"+grn_loc+"'";
                        } else
                        {
                            //insert new stock
                            stock_query = `INSERT INTO stock (item_code, loc_id, qty) VALUES ('${item_code}','${grn_loc}','${actual_tran_qty}')`;
                        }
                        cl("#################")
                        cl(stock_query)
                        cl('#################')
                        exec(stock_query)

                    }

                    // set document state to approved
                    exec("UPDATE `grn_hd` SET `status` = 1 WHERE `entry_no` = '"+entry_no+"'")
                    // load document again
                    viewGrn(entry_no)

                    // add entry
                    db.new_doc_trans("GRN",entry_no,"AP")

                } else {
                    swal_error("Cannot find document <i>"+entry_no+"</i>")
                }
            }



        } else if (result.isDenied) {

        }
    })


}

function delete_doc(doc,entry_no) // delete document
{
    if(a_sess.get_session('action') === 'view')
    {
        if(doc === 'GRN')
        {

            // check availability
            if(row_count('grn_hd',"`entry_no` = '"+entry_no+"'") === 1)
            {
                // get document details
                let doc = JSON.parse(get_row('grn_hd',"`entry_no` = '"+entry_no+"'"))[0];
                let status = doc.status;

                if(status === 1) // approved
                {
                    swal_error("Document has already been approved ")
                } else
                {
                    // swal_error('lets go')
                    // delete
                    exec("UPDATE `grn_hd` set status = -1 WHERE `entry_no` = '"+entry_no+"'")
                    db.new_doc_trans('GRN',entry_no,'DEL')
                    // load grn
                    viewGrn(entry_no)
                }
            } else
            {
                swal_error("Cannot find document");
            }

        } else
        {
            swal_error("Go into view mode")
        }
    }
}

function db_access(){

}
