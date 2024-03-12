function lineCalculate(line) {
    // initiate variables
    let pack_qty, quantity, unit_cost,net_cost,tax,gross;
    pack_qty = $(`#pack_${line}`);
    quantity = $(`#qty_${line}`);
    unit_cost = $(`#unit_cost_${line}`);
    net_cost = $(`#net_cost_${line}`);
    tax = $(`#tax_${line}`);
    gross = $(`#gross_${line}`);


    // set net
    let net = unit_cost.val() * quantity.val();
    net_cost.val(net)

    let tax_amt = 0;
    if($('#taxable').val() == '1'){
        // calculate tax
        let barcode = $(`#barcode_${line}`).val()
        console.log(`BARCODE IS id  #barcode_${line}`)
        // get tax_code
        let taxable = JSON.parse(get_row('prod_master',`barcode='${barcode}'`))[0]['tax'];
        let taxClass = new TaxMaster();
        tax_amt = 0;
        if(taxable === 'YES'){
            console.log(net)
            let tx = taxClass.taxInclusive(net);
            tax_amt = parseFloat(tx['message']['vat'])
            console.table(tx)
        }
    }

    let newnew = net - tax_amt;
    net_cost.val(newnew.toFixed(2))
    tax.val(tax_amt);
    gross.val(newnew + tax_amt)

    // calculate header
    calculateHeader()

}

function calculateHeader(){
    let t_element = $('#table_body tr');
    let row_count = t_element.length
    let leg_gross = 0;
    let leg_net = 0;
    let leg_tax = 0;
    for (let i = 1; i <= row_count; i++) {
        // lineCalculate(i)
        let gross = $(`#gross_${i}`).val();
        let tax = $(`#tax_${i}`).val();
        let net = $(`#net_cost_${i}`).val();
        leg_gross += parseFloat(gross);
        leg_net += parseFloat(net);
        leg_tax += parseFloat(tax);

    }
    let other_cost = 0;
    if($('#other_cost').val().length > 0)
    {
        other_cost = $('#other_cost').val()
    }

    $('#net_amt').val(leg_net.toFixed(2));
    $('#tax_amt').val(leg_tax.toFixed(2))
    // $('#other_cost').val(other_cost.toFixed(2))
    $('#gross_amt').val(leg_gross + parseFloat(other_cost))
}

function retrieveProforma(entry_no) {
    $("body").css("cursor",'wait');
    mpop.hide()

    if(row_count('prof_hd',`entry_no = '${entry_no}'`) === 1){

        // get hd
        let hd = get_row("prof_hd",`entry_no = '${entry_no}'`);
        let trans = get_row('prof_tran',`entry_no = '${entry_no}'`);

        // fill header values
        if(isJson(hd)){
            let header = JSON.parse(hd)[0];
            let pk = header['id'];
            $('#ref_no').val(header['entry_no']);

            let loc_pk = header['loc_id'];
            let location = JSON.parse(get_row('loc',`id='${loc_pk}'`))[0];
            let loc_opt = `<option value="${location['loc_id']}">${location['loc_id']}</option>`;
            $('#loc_id').html(loc_opt)
            $('#loc_desc').val(location['loc_desc'])
            $('#customer').val(header['customer']);
            $('#remarks').val(header['remarks']);
            $('#taxable').val(header['taxable']);
            $('#issue_date').val(header['iss_date']);
            $('#due_date').val(header['due_date']);
            $('#days_in_due').val(
                anton.daysBetween(header['iss_date'],header['due_date'])
            );
            $('#net_amt').val(header['net_amt']);
            $('#tax_amt').val(header['tax_amt']);
            $('#other_cost').val(header['other_cost']);
            $('#gross_amt').val(header['gross_amt'])





            // go for transactions
            if(isJson(trans)){
                let transactions = JSON.parse(trans);
                let tr = "";
                if(header['valid'] === 1) {
                    for (let t = 0; t < transactions.length; t++) {
                        let row = transactions[t];
                        let sn = t + 1;
                        let barcode = row['barcode'];
                        let prod = JSON.parse(get_row('prod_master',`barcode='${barcode}'`))[0];
                        let item_code = prod['item_code'];
                        let pack_q = `select qty,pack_desc from prod_packing where item_code = '${item_code}'`;
                        let packs = fetch_rows(pack_q);

                        let pack_options = `<option value="${row['pack_qty']}">${row['packing']}</option>`;
                        let p_packs = JSON.parse(packs);
                        for (let p = 0; p < p_packs.length ; p++) {
                            let pack = p_packs[p];
                            if(pack['qty'] !== row['pack_qty']){

                                pack_options += `<option value="${pack['qty']}">${pack['pack_desc']}</option>`;

                            }
                        }

                        let barcode_id = `barcode_${sn}`;
                        let name_id = `name_${sn}`;
                        let pack_id = `pack_${sn}`;
                        let qty_id = `qty_${sn}`;
                        let unit_cost_id = `unit_cost_${sn}`;
                        let net_cost_id = `net_cost_${sn}`;
                        let tax_id = `tax_${sn}`;
                        let gross_id = `gross_${sn}`;

                        tr += `
                            <tr id="row_${sn}">
                            <td>${sn}</td>
                            <td><input type="text" id="${barcode_id}" class="form-control form-control-sm text_sm rounded-0" value="${row['barcode']}"></td>
                            <td><input type="text" id="${name_id}" value="${row['item_desc']}" readonly class="form-control text_sm form-control-sm rounded-0"></td>
                            <td class="text_sm"><select id="${pack_id}" onchange="lineCalculate('${sn}')" class="form-control form-control-sm rounded-0">${pack_options}</select></td>
                            <td><input type="text" id="${qty_id}" onchange="lineCalculate('${sn}')" value="${row['tran_qty']}" class="form-control rounded-0 text_sm form-control-sm"></td>
                            <td><input type="text" id="${unit_cost_id}" onchange="lineCalculate('${sn}')" value="${row['unit_cost']}" class="form-control form-control-sm text_sm rounded-0"></td>
                            <td><input type="text" id="${net_cost_id}" value="${row['net_cost']}" disabled class="form-control form-control-sm text_sm rounded-0"></td>
                            <td><input type="text" id="${tax_id}" value="${row['tax_amt']}" disabled class="form-control form-control-sm text_sm rounded-0"></td>
                            <td><input type="text" id="${gross_id}" value="${row['gross_amt']}" disabled class="form-control form-control-sm text_sm rounded-0"></td>
                        </tr>
                        `;
                    }
                }

                $('#table_body').html(tr);
            }
        }

    } else {
        kasa.error(`Entry Does Not Exist`)
    }
    $("body").css("cursor",'default')
}

$(document).ready(function(){
    //new entry
    $('#type').change(function(){
        // check ref type
        let ref_type = $('#type').val()

        // 0=direct,1=pro
        if(ref_type === 'proforma'){
            // then get po
            let q = "SELECT * FROM prof_hd right join customers cs on prof_hd.customer = cs.customer_id where valid = 1 and approved = 1";
            let result = fetch_rows(q);

            if(isJson(result)){
                let trans = JSON.parse(result);
                let htm = "No Records"
                let rows = trans.length;
                if(rows > 0){
                    let tr = "";
                    for(let x = 0; x < rows; x++){
                        let row = trans[x];
                        console.table(row)
                        let td = yyyy + '-' + mm + '-' + dd;
                        let dx = anton.daysBetween(td,row['due_date'])
                        tr += `
                        <tr ondblclick="retrieveProforma('${row['entry_no']}')"><td>${row['first_name']} ${row['last_name']}</td><td>${row['entry_no']}</td><td>${row['date_created']}</td><td>${row['gross_amt']}</td><td>${dx}</td></tr>
                    `;
                    }

                    htm = `

                    <table class="table table-sm"><thead class="bg-dark"><tr><th>CUSTOMER</th><th>ENTRY</th><th>DATE</th><th>GROSS</th><th>DUE IN DAYS</th></tr></thead><tbody>${tr}</tbody></table>

                `;
                }
                mpop.setBody(htm);
                mpop.setTitle("Pending Proforma");
                mpop.setSize('lg')
                mpop.show()
            } else {
                kasa.error("Invalid Response")
            }
        } else {
            $('#ref_no').val('none')
        }
    });

    // save
    $('#save').click(function(){
        let header_id = [
            'loc_id','ref_no','customer','remarks','taxable',
            'net_amt','tax_amt', 'other_cost','gross_amt','type',
        ];

        if(anton.validateInputs(header_id)){
            let header = anton.Inputs(header_id);
            let trans = []
            // validate transactions
            let tran_rows =$('#table_body tr');
            let row_count = tran_rows.length

            if(row_count > 0){
                let errors = 0;
                let error_message = '';

                for(let t = 1; t <= row_count; t++){
                    // fine places
                    let line = t;
                    let barcode_id = `barcode_${line}`;
                    let name_id =  `name_${line}`;
                    let pack_id = `pack_${line}`;
                    let qty_id = `qty_${line}`;
                    let unit_cost_id = `unit_cost_${line}`;
                    let net_cost_id = `net_cost_${line}`;
                    let tax_id = `tax_${line}`
                    let gross_id = `gross_${line}`

                    // validate
                    let ids = [
                        barcode_id,pack_id,qty_id,unit_cost_id,net_cost_id,
                        tax_id, gross_id,name_id
                    ]

                    if(anton.validateInputs([ids])){
                        // create object and push to array
                        let this_inputs = anton.Inputs(ids);
                        let this_obj = {
                            line:line,
                            'barcode':this_inputs[barcode_id],
                            name:this_inputs[name_id],
                            pack_qty:this_inputs[pack_id],
                            pack_desc :$(`#${pack_id} option:selected`).text(),
                            qty:this_inputs[qty_id],
                            unit_cost:this_inputs[unit_cost_id],
                            net_cost:this_inputs[net_cost_id],
                            tax:this_inputs[tax_id],
                            gross:this_inputs[gross_id]
                        }

                        trans.push(this_obj)



                    } else {
                        errors ++;
                        error_message += `<p>There is an error on line ${line}</p>`
                    }
                }

                // check errors or not
                if(errors === 0){
                    console.table(header);
                    console.table(trans)
                    // handle header
                    let hd_rows = JSON.parse(fetch_rows("SELECT COUNT(*) as 'ct' from invoice_hd"))[0]['ct'];
                    let ent_no = 10000 + hd_rows;
                    let entry_no = `IN${ent_no}`;

                    // insert into header
                    let hd_query = `INSERT INTO invoice_hd (entry_no, loc_id, customer, remarks, created_by,net_amt,tax_amt,other_cost,gross_amt,taxable,ref_no,ref_type)
                                                    VALUES ('${entry_no}','${header['loc_id']}','${header['customer']}',
                                                            '${header['remarks']}','${user_id}','${header['net_amt']}','${header['tax_amt']}','${header['other_cost']}','${header['gross_amt']}','${header['taxable']}','${header['ref_no']}','${header['type']}')`;

                    let hd_save = exec(hd_query);
                    if(hd_save['code'] !== 500){

                        // save headers
                        // make transactions queries
                        for(let r = 0; r < trans.length; r++){
                            let tr = trans[r];
                            let row_qry = `
                                    INSERT INTO invoice_tran(entry_no, line_no, barcode, item_desc, packing, pack_qty, tran_qty, unit_cost, net_cost, tax_amt, gross_amt) values
                                                        ('${entry_no}','${tr['line']}','${tr['barcode']}','${tr['name']}',
                                                         '${tr['pack_desc']}','${tr['pack_qty']}','${tr['qty']}','${tr['unit_cost']}','${tr['net_cost']}','${tr['tax']}','${tr['gross']}')
                                `;

                            exec(row_qry)
                            console.log(row_qry)

                        }

                        kasa.success("Entry Saved");
                        if(header['type'] === 'proforma'){
                            // update proforma
                            exec(`UPDATE prof_hd set posted = 1 where entry_no = '${header['ref_no']}'`)
                        }
                        set_session(['action=view']);
                        location.reload();

                    } else {
                        kasa.error(hd_save['message'])
                    }



                } else {
                    kasa.html(error_message)
                }

            } else {
                kasa.error("Cannot Empty Transaction")
            }
        } else {
            kasa.error("Invalid Headers")
        }
    });

});