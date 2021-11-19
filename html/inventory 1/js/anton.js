
// usefull root functions
function cl(params) { // console log
    console.log(params + '\n')
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
function item_sort(module, direction) {
    cl(module);cl(direction);
}

// delete item
function delete_item(module,item) {
    cl("deleting " + item + " from "+ module);
}

// show hide
function i_show(params) {
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        cl(element)
        document.getElementById('price').style.display = 'none';
        document.getElementById(element).style.display = '';
        
    }
}
function i_hide(params) {
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        cl(element)
        document.getElementById(element).style.display = 'none';
        
    }
}

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

    if(screen_width === 1024 && screen_height === 768)
    {
        if(reload === 'yes')
        {
            location.reload();
        }

    } else {
        // var content = "<div class='bg-light w-100 vh-100 text-danger d-flex flex-wrap align-content-center justify-content-center'><p class='enc'>Unsupported Scrren Dimension</p></div>";
        // document.getElementsByTagName('body')[0].innerHTML = content;
    }
}

// initialize page
function initialize(params) {
    validateSize('no');
}

// set session
function set_session(data) {
    console.log(data);
}

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

// mark bill item
function mark_bill_item(item_id) {
    console.log(item_id);
}



// change category
function change_category(params) {
    console.log(params);
}

// add item to bill
function add_item_to_bill(params) {
    console.log(params)
}

// making payment
function make_payment(method,token) {

    // validate there is cash input
    var val = document.getElementById('gen_input'); // gen input field
    
    if(val.value.length > 0) // if amout value is greater than zero
    {
        // prapre form for ajax
        data = {
            'function':'make_payment',
            'payment_method':method,
            'amount':val.value,
            'token':token
        };
        
        // make ajax function
        $.ajax({
            url: '/config/process/bill_process.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(response);
            }
        });

    }
    else
    {
        console.log(val.value.length)
        val.style.border = '2px solid red';
        val.style.background = '#eb9783';
        val.placeholder = 'Amount Not Set';
    }

    if(method === 'cash')
    {
        // cash payment
    } else if (method === 'momo')
    {
        // momo payment
    }
}

// center pop up
const popup_center = ({url, title, w, h}) => {
    // Fixes dual-screen position                             Most browsers      Firefox
    const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = width / window.screen.availWidth;
    const left = (width - w) / 2 / systemZoom + dualScreenLeft
    const top = (height - h) / 2 / systemZoom + dualScreenTop
    const newWindow = window.open(url, title, 
      `
      scrollbars=yes,
      width=${w / systemZoom}, 
      height=${h / systemZoom}, 
      top=${top}, 
      left=${left}
      `
    )

    if (window.focus) newWindow.focus();
}

// apply discount
function apply_discount(params) {
    console.log(params)
    $('#discount').modal('hide');
}

// hold bill
function hold_bill(params) {
    console.log('holding bill')
}

// recall bill
function recall_bill(token) {
     // validate there is cash input
     var val = document.getElementById('gen_input'); // gen input field
    
     if(val.value.length > 0) // if amout value is greater than zero
     {
         // prapre form for ajax
         data = {
             'function':'hold_bill',
             'token':token
         };
         
         // make ajax function
         $.ajax({
             url: '/config/process/bill_process.php',
             type: 'POST',
             data: data,
             success: function(response) {
                 console.log(response);
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



// end daily sales for all
// $('#eod').click(function name(params) {
//     if(confirm('Has all machines ended sales?')){ // let user comfirm again if they want to end shift
//         console.log('ending') // end shift
//     }
//     else
//     {
//         console.log('opted'); // cancel operation
//     }
// })

// reports function
function report(params) {
    switch (params) { // switch with param

        case 'sales': // sales
            // get sales report, fill it in reports
            $('#modal_d').removeClass('modal-lg');
            $('#report_res').removeClass('modal_card'); // remove backgroudd of modal body
            $('.modal-title').text('Sales Report');
            var response = "<div class='w-100 p-2'> \
                <div class='w-100 text-center'> \
                    <p class='font-weight-bolder text-center text-elipse'>$ 2000.00</p>\
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
            </div>";
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
    
    $("#report_res").html(response); // send result into modal

    show_modal('report_modal'); // show modal
    
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
function gen_modal(params) {
    var response = '';
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
            response = '<div class="w-100 p-5 d-flex flex-wrap align-content-center justify-content-between"><button class="btn m-btn btn-warning">HELLO</button></div>';
            $('#grn_modal_res').html(response);
            // TODO create delete product modal
            show_modal('gen_modal') // show modal

        default:
            break;
    }
}