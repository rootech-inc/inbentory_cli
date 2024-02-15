class Grn {
    save(){
        // validate header
        let header_fields = ['rec_date','loc_id','supp_id','ref_doc','invoice_number','total_amount','remarks']
        if(anton.validateInputs(header_fields)){
            let header = anton.Inputs(header_fields);
            console.table(header)
            let total_amt = 0;

            // get items
            let rows = $('#grn_items_list tr');
            let row_cunt = rows.length
            console.log(rows)

            if(row_cunt > 0){
                let row_error_count = 0;
                let row_error_message = "";
                let transactions = []
                // loop through
                for (let i = 0; i < rows.length; i++) {
                    let line = i +1;
                    let item_code_id = `code_id_${line}`;
                    let row_element = $(`#${item_code_id}`).val()
                    let qty_id = `qty_${line}`;
                    let price_id = `price_${line}`;
                    let total_id = `total_${line}`;
                    let barcode_id = `barcode_${line}`;
                    let descr_id = `descr_${line}`

                    let tran_ids = [item_code_id,qty_id,price_id,total_id]
                    console.log(tran_ids)
                    if(anton.validateInputs(tran_ids)){
                        $(`#${row_element}`).removeClass('text-danger');
                        $(`#${row_element}`).addClass('text-success');
                        transactions.push({
                            item_code:$(`#${item_code_id}`).val(),
                            quantity:$(`#${qty_id}`).val(),
                            price: $(`#${price_id}`).val(),
                            total_amount: $(`#${total_id}`).val(),
                            barcode:$(`#${barcode_id}`).text(),
                            name:$(`#${descr_id}`).text(),
                        })

                        total_amt  += parseFloat($(`#${total_id}`).val());
                    } else {
                        row_error_count ++;
                        $(`#${row_element}`).removeClass('text-success');
                        $(`#${row_element}`).addClass('text-danger')

                    }

                }

                if(row_error_count === 0){
                    kasa.success("All is valid")
                    header['total_amount'] = total_amt;
                    let payload = {
                        module:'grn',
                        crud:'write',
                        data:{
                            header:header,
                            transactions:transactions
                        }
                    };

                    // console.log(JSON.stringify(payload))
                    let x = api.call('POST',payload,'/api/');
                    console.table(x)
                    kasa.response(x)
                } else {
                    kasa.error(`There are ${row_error_count} error(s) in item transactions`)
                }


            } else {
                kasa.error("No Items In Transaction")
            }
        } else {
            kasa.error("Invalid Header")
        }
    }
}

const grncl = new Grn();