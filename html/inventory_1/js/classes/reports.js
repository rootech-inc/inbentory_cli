class Reports {

    base_url = '/backend/process/reports.php';
    result = { 'code': 505, 'message': "INIT" };

    SalesReport() {

        // check if there is sales
        if (row_count('bill_trans', `date_added = '${toDay}'`) > 0) {
            let admin_id_v2, admin_password_v2, result = false;
            Swal.fire({
                title: 'AUTHENTICATE',
                html: `<input type="text" autocomplete='off' id="login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">`,
                confirmButtonText: 'Sign in',
                focusConfirm: false,
                backdrop: `
                rgba(245, 39, 145, 0.36)
                left top
                no-repeat
              `,

                preConfirm: () => {
                    const login = Swal.getPopup().querySelector('#login').value
                    const password = Swal.getPopup().querySelector('#password').value
                    if (!login || !password) {
                        Swal.showValidationMessage(`Please enter login and password`)
                    }
                    return { login_v2: login, password_v2: password }
                }
            }).then((result) => {
                admin_id_v2 = result.value.login_v2;
                admin_password_v2 = result.value.password_v2;
                $("#grn_modal_res").html("LOADING.....");
                //show_modal('gen_modal'); // show modal
                var dataToSend = {
                    'function': 'mj',
                    'user_id': admin_id_v2,
                    'password': admin_password_v2,
                }

                $.ajax({
                    url: '/backend/process/form_process.php',
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'html',
                    data: dataToSend,
                    success: function (response) {
                        // echo(response)
                        let resp = JSON.parse(response)
                        if (resp['status'] === 200) {
                            // get sales report, fill it in reports
                            $('#gen_modal').removeClass('modal-lg');
                            $('#report_res').removeClass('modal_card'); // remove backgroudd of modal body
                            $('.modal-title').text('Sales Report');

                            // get total sales

                            let gross = JSON.parse(fetch_rows(`SELECT SUM(gross_amt) as 'gross_amt' FROM bill_header where bill_date = '${toDay}'`))[0].gross_amt

                            // get all machines
                            let machines = JSON.parse(fetch_rows("SELECT mach_no from bill_header group by mach_no"));
                            let all_sales = "";
                            for (let im_n = 0; im_n < machines.length; im_n++) {

                                let machine_number = machines[im_n]['mach_no']
                                let this_sales = ""
                                let qquery = `select mech_setup.mech_no,mech_setup.descr, bill_header.pmt_type, sum(bill_header.gross_amt) as 'gross', sum(bill_header.tax_amt) as 'tax', sum(bill_header.net_amt) as 'net' from mech_setup join bill_header on bill_header.mach_no = mech_setup.mech_no where mech_no = ${machine_number} group by bill_header.pmt_type, mech_setup.mech_no, mech_setup.descr;`;
                                let m_sales = JSON.parse(fetch_rows(qquery));
                                let this_total = 0;
                                for (let ms = 0; ms < m_sales.length; ms++) {
                                    let gross, net, pmt_type, tax
                                    let this_m_sales = m_sales[ms]
                                    gross = this_m_sales['gross']
                                    net = this_m_sales['net']
                                    pmt_type = this_m_sales['pmt_type']
                                    tax = this_m_sales['tax']
                                    this_total += parseFloat(net)

                                    this_sales += `<div class='w-100 clearfix border-dark p-1 border-bottom'>\
                                                    <div class='w-45 float-left text-left'><p class='m-0 p-0'>${pmt_type}</p></div>\
                                                    <div class='w-45 float-right text-right'><p class='m-0 p-0'>${net}</p></div>\
                                                </div>`




                                }
                                this_sales += `<div class='w-100 font-weight-bold clearfix border-dark p-1 border-bottom'>\
                                <div class='w-45 float-left'><p class='m-0 text-left p-0'>Total</p></div>\
                                <div class='w-45 float-right text-right'><p class='m-0 p-0'> ${this_total.toFixed(2)}</p></div>\
                            </div>`

                                all_sales += `<div class='w-100 p-2'> \
                        <div class='modal_card p-4 mb-4'>\
                            <h4 class='font-weight-bolder mb-2'>MACHINE ${machine_number}</h4>\
                            ${this_sales}
                        </div>\
                    </div>`;

                            }



                            $("#grn_modal_res").html(all_sales); // send result into modal

                            Swal.fire({
                                title: 'Sales Report',
                                html: all_sales,
                                footer: "<button onclick='reports.printSales()' class='btn btn-info fa fa-print'></button>",

                            })

                        } else {
                            Swal.fire({
                                title: 'Admin Authentication',
                                text: "Authentication Failed",
                            })
                        }




                    }
                });



            })
        } else {
            al("NO SALES")
        }




    }

    EndOfDay() {
        if (row_count('bill_trans', `date_added = '${toDay}'`) > 0) {
            // if there is sales
            let admin_id_v2, admin_password_v2, result = false;
            Swal.fire({
                title: 'AUTHENTICATE',
                html: `<input type="text" autocomplete='off' id="login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">`,
                confirmButtonText: 'AUTH <i class="fa fa-key"></i>',
                focusConfirm: false,
                backdrop: `
                rgba(245, 39, 39, 0.8)
                left top
                no-repeat
              `,

                preConfirm: () => {
                    const login = Swal.getPopup().querySelector('#login').value
                    const password = Swal.getPopup().querySelector('#password').value
                    if (!login || !password) {
                        Swal.showValidationMessage(`Please enter login and password`)
                    }
                    return { login_v2: login, password_v2: password }
                }
            }).then((result) => {
                admin_id_v2 = result.value.login_v2;
                admin_password_v2 = result.value.password_v2;

                var dataToSend = {

                    'function': 'eod',
                    'clerk_code': admin_id_v2,
                    'clerk_key': admin_password_v2,
                }

                $.ajax({
                    url: 'backend/process/reports.php',
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'html',
                    data: dataToSend,
                    success: function (response) {
                        echo(response)
                        let resp = JSON.parse(response)

                        Swal.fire({
                            title: 'EOD REPORT',
                            html: resp['message'],

                        })



                    }
                });
            })
        } else {
            al("NO SALE")
        }

    }

    zReport() {
        let recId = $('#shifts').val()
        cl(recId)
        let mech = ''
        let day = ''
        let record = row_count('shifts', `recId = '${recId}'`)
        if (record === 1) {
            let row = JSON.parse(get_row('shifts', `recId = '${recId}'`))[0]
            console.table(row);
            mech = row['mech_no']
            day = row['shift_date']

        }

        console.log(record);
        console.log(mech);

        let shift_count = 0
        shift_count = Mech.is_shift(mech, day)
        if (shift_count) {
            // if there is sale
            let admin_id_v2, admin_password_v2, result = false;
            Swal.fire({
                title: 'AUTHENTICATE',
                html: `<input type="text" autocomplete='off' id="login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">`,
                confirmButtonText: 'AUTH <i class="fa fa-key"></i>',
                focusConfirm: false,
                backdrop: `
                rgba(245, 39, 39, 0.8)
                left top
                no-repeat
              `,

                preConfirm: () => {
                    const login = Swal.getPopup().querySelector('#login').value
                    const password = Swal.getPopup().querySelector('#password').value
                    if (!login || !password) {
                        Swal.showValidationMessage(`Please enter login and password`)
                    }
                    return { login_v2: login, password_v2: password }
                }
            }).then((result) => {
                admin_id_v2 = result.value.login_v2;
                admin_password_v2 = result.value.password_v2;

                var dataToSend = {

                    'function': 'z_report',
                    'clerk_code': admin_id_v2,
                    'clerk_key': admin_password_v2,
                    'recId': recId
                }


                $.ajax({
                    url: 'backend/process/reports.php',
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'html',
                    data: dataToSend,
                    success: function (response) {
                        echo(response)
                        if (isJson(response)) {
                            let resp = JSON.parse(response)

                            Swal.fire({
                                title: 'REPORT',
                                html: resp['message'],

                            })
                        } else {
                            al("INVALID RESPONSE: \n Contact System Admin")
                        }




                    }
                });
            })
        } else {
            al("CANNOT FIND SHIFT")
        }

    }

    printSales() {
        let form_data = { 'function': 'print_sales' };

        ajaxform['url'] = '/backend/process/ajax_tools.php'
        ajaxform['data'] = form_data
        ajaxform['success'] = function (response) {
            ct(response)
        }

        $.ajax(ajaxform)
    }

    itemAvailability(res = 'modal') {
        // get locations
        let locations = FETCH("SELECT loc_id,loc_desc from loc");
        let lopt = '';
        for (let l = 0; l < locations.length; l++) {
            let loc = locations[l];
            lopt += `<option value='${loc['loc_id']}'>${loc['loc_desc']}</option>`;
        }
        Swal.fire({
            title: "ITEM AVAILABILITY AS OF",
            html: `<div class="w-100 d-flex flex-wrap justify-content-between">
                        <div class="w-30 text-left">
                            <label for="loc_id">LOCATION</label>
                            <select name="loc_id" id="loc_id" class="form-control w-100 rounded-0">
                            ${lopt}
                            </select>
                        </div>
                        <div class="w-30 text-left">
                            <label for="stock_level">LEVEL</label>
                            <select name="stock_level" id="stock_level" class="form-control w-100 rounded-0">
                                <option value="1">Stock Only</option>
                                <option value="0">All</option>
                            </select>
                        </div>
                        
                        <div class="w-30 text-left">
                            <label for="as_of">AS OF DATE</label>
                            <input type="date" name="as_of" id="as_of" class="form-control w-30">
                        </div>
                    
                    
                  </div>`,
            showDenyButton: false,
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                // get values of form
                let loc_id = $('#loc_id').val();
                let as_of = $('#as_of').val();
                let stock_level = $('#stock_level').val()
                let tr = '';

                if (res === 'modal') {

                    let availability = FETCH(`CALL item_availability('${loc_id}','${as_of}','${stock_level}')`)
                    for (let r = 0; r < availability.length; r++) {
                        let barcode, name, stock;

                        let row = availability[r];
                        barcode = row['barcode'];
                        name = row['item_desc'];
                        stock = row['stock'];
                        // console.table(row);
                        tr += `<tr>
                            <td>${barcode}</td><td>${name}</td><td>${stock}</td>
                        </tr>`;
                    }

                    let table = `<table class='table table-bordered table-row table-sm table-stripped'>
                    <thead class='thead-dark'>
                    <tr>
                        <th>BAROCDE</th><th>DESCRIPTION</th><th>QTY</th>
                    </tr>
                    </thead><tbody>${tr}</tbody>
                    </table>`;

                    // $('#grn_modal_res').html(table);
                    // $('#gen_modal').modal('show');
                    mpop.setTitle(`ITEM AVAILABLITY REPORT AS OF ${as_of}`)
                    mpop.setBody(table)
                    mpop.setSize('lg')
                    mpop.setFooter(`<button onclick="reports.itemAvailability('print')" class='btn btn-info'>PRINT</button>`)
                    mpop.show()


                } else if (res === 'print') {

                    let form_data = { 'function': 'print_availability', loc_id: loc_id, as_of: as_of };

                    ajaxform['url'] = '/backend/process/reports.php'
                    ajaxform['data'] = form_data
                    ajaxform['success'] = function (response) {

                        if (isJson(response)) {
                            let ress = JSON.parse(response);
                            mpop.setBody(`<embed type='application/pdf' src="/assets/docs/${ress['file']}" style='width:100% !important' height="400" 
                            type="application/pdf">`)
                            //windowPopUp(`http://localhost/assets/docs/${ress['file']}`,'',1024,600)
                            mpop.setSize('lg')
                            mpop.show()
                        } else {
                            kasa.error("RESPONSE IS NOT A VALID JSON")
                        }
                    }

                    $.ajax(ajaxform)

                }

            }
        });
    }

    TakeEod() {
        // get all eod
        let sales_date = $('#eod_serial').val();

        if (sales_date.length > 0) {
            // continue
            let payload = {
                'function': 'take_eod',
                'sale_date': sales_date
            }
            ajaxform['type'] = 'POST';
            ajaxform['url'] = this.base_url;
            ajaxform['data'] = payload;
            ajaxform['success'] = function (response) {
                console.log(response)
                if (isJson(response)) {
                    let jponse = JSON.parse(response);
                    console.table(jponse);
                    if (response['status_code'] !== 200) {
                        kasa.error(jponse['message']);
                    } else {
                        kasa.info(jponse['message']);
                    }

                } else {
                    kasa.warning("INVALID EOD RESPONSE")
                }
            };
            $.ajax(ajaxform);

        } else {
            kasa.error("SELECT DATE");
        }
    }

    expiryScreen(){
        // mpop.hide()
        let html = `
            <label for="exp_loc_id">Location</label>
            <select name="exp_loc_id" id="exp_loc_id" class="form-control rounded">
                <option value="*">All</option>
            </select>
            <label for="as_of">Date As Of</label>
            <input class='form-control rounded-0' type='date' id='as_of' >
        `;
        mpop.setBody(html);
        mpop.setSize('lg');
        mpop.setTitle("EXPIRY AS OF")
        mpop.setFooter(`<button onclick='reports.retirveExpiry()' class='btn btn-info w-100 rounded-0'>RETRIEVE</button>`)
        mpop.show()
    }
    retirveExpiry(){

        let ids = ['as_of','exp_loc_id'];
        if(anton.validateInputs(ids)){
            let date,loc_id;
            date  = $('#as_of').val();
            loc_id = $('#exp_loc_id').val()

            // query
            let exps = fetch_rows(`CALL CheckStockExpiry('${loc_id}', '${date}');`)
            if(isJson(exps)){
                let expiries = JSON.parse(exps)
                let tr = "";
                for (let i = 0; i < expiries.length; i++) {
                    let row = expiries[i];
                    tr += `
                        <tr>
                            <td>${row['expiry_date']}</td>
                            <td>${row['barcode']}</td>
                            <td>${row['item_desc']}</td>
                            <td>${row['cost']}</td>
                            <td>${row['retail']}</td>
                            <td>${row['stock']}</td>
                            
                        </tr>
                    `
                    console.table(row)
                }
                let th = `
                    <table class="table table-bordered"><thead class="thead-dark bg-dark"></thead><tr><th>DATE</th><th>BARCODE</th><th>NAME</th><th>COST</th><th>RETAIL</th><th>STOCK</th></tr></thead><tbody>${tr}</tbody></table>
                `;

                mpop.setSize('lg');
                mpop.setTitle(`Expiry Report as of ${date}`);
                mpop.setBody(th)
                mpop.setFooter(`<button class="btn btn-info" onclick="reports.expiryScreen()">Filter</button>`);
                mpop.show()
            } else {
                kasa.error("invalid response")
            }
        } else {
            kasa.info("Invalid Filtering Forms")
        }
        
    }

}

const reports = new Reports()