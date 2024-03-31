class Tables {
    newLine(){
        let t_element = $('#table_body tr');
        let sn = t_element.length + 1

        // define ids
        let row = `
            <tr id="row_${sn}">
                        <td id="line_${sn}">${sn}</td>
                        <td>
                            <input
                                    id="barcode_${sn}"
                                    style="width: 150px !important"
                                    type="text"
                                    onkeyup="teb.loadItem(this.value,'${sn}',event)"
                                    onchange="teb.loadItem(this.value,'${sn}',event)"
                                    class="form-control text_xs form-control-sm rounded-0"
                            >
                        </td>
                        <td>
                            <input
                                    id="name_${sn}"
                                    style="width: 150px !important"
                                    type="text"
                                    readonly
                                    class="form-control text_xs form-control-sm rounded-0"
                            >
                        </td>
                        <td>
                            <select
                                    id="pack_${sn}"
                                    class="form-control text_xs form-control-sm rounded-0"
                                    onchange="teb.lineCalculate('${sn}')"
                            >
                                <option value="10">CTN (10 x 1)</option>
                            </select>
                        </td>
                        <td>
                            <input
                                    id="qty_${sn}"
                                    style="width: 60px !important"
                                    type="number"
                                    class="form-control text_xs form-control-sm rounded-0"
                                    onchange="teb.lineCalculate('${sn}')"
                                    value="0.00"
                            >
                        </td>
                        <td>
                            <input
                                    id="unit_cost_${sn}"
                                    style="width: 60px !important"
                                    type="number"
                                    class="form-control text_xs form-control-sm rounded-0"
                                    onchange="teb.lineCalculate('${sn}')"
                                    value="0.00"
                            >
                        </td>
                        <td>
                            <input
                                    id="net_cost_${sn}"
                                    style="width: 60px !important"
                                    type="number"
                                    readonly
                                    class="form-control text_xs form-control-sm rounded-0"
                                    value="0.00"
                            >
                        </td>
                        <td>
                            <input
                                    id="tax_${sn}"
                                    style="width: 60px !important"
                                    type="number"
                                    readonly
                                    class="form-control text_xs form-control-sm rounded-0"
                                    value="0.00"
                            >
                        </td>
                        <td>
                            <input
                                    id="gross_${sn}"
                                    style="width: 60px !important"
                                    type="number"
                                    readonly
                                    class="form-control text_xs form-control-sm rounded-0"
                                    value="0.00"
                            >
                        </td>
                    </tr>
        `;

        $('#table_body').append(row);
    }

    loadItem(v,sn,ev){
        console.table(ev)


        if(ev.key === 'Enter'){
            let barcode = v;

            // get product,
            let prod_count = row_count('prod_master',`barcode = '${barcode}'`);
            if(prod_count === 1){
                // get product
                let p_req = get_row('prod_master',`barcode = '${barcode}'`);
                if(isJson(p_req)){
                    let product = JSON.parse(p_req)[0];
                    let itemcode, item_desc, retail;
                    itemcode = product['item_code'];
                    item_desc = product['item_desc'];
                    retail = product['retail'];

                    $(`#name_${sn}`).val(item_desc);

                    // get product packing
                    let pack_q = `select qty,pack_desc from prod_packing where item_code = '${itemcode}'`;
                    let packs = fetch_rows(pack_q);
                    let pack_options = '';
                    if(isJson(packs)){
                        let p_packs = JSON.parse(packs);
                        for (let p = 0; p < p_packs.length ; p++) {
                            let pack = p_packs[p];
                            pack_options += `<option value="${pack['qty']}">${pack['pack_desc']}</option>`;
                        }

                        $(`#pack_${sn}`).html(pack_options);



                    } else {
                        kasa.error("Cannot Load Packing");
                    }
                }

            } else {
                kasa.error("Product Does Not Exist")
            }

            //
        } else {
            console.log("keep tyuping")
        }

    }

    lineCalculate(sn) {
        console.log(sn)
        // initiate variables
        let line = sn;
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
        teb.calculateHeader()
    }

    calculateHeader() {
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
}

const teb = new Tables();