class Loyalty {

    // save customer
    base_url = '/backend/process/form-processing/loyalty.php';
    result = {'code':505,'message':"INIT"};
    api_url = 'http://127.0.0.1:8000/api/'

    customerReg(full_name,email,mobile){
        ajaxform['type'] = 'POST';
        ajaxform['data'] = {'email':email,'name':full_name,'mobile':mobile,'task':'register'}
        ajaxform['url'] = this.base_url
        ajaxform['success'] = function (response) {
            if(isJson(JSON.stringify(response))){
                let result = JSON.parse(response)
                lty.result['code'] = result['code']
                if(result['code'] === 202){
                    // get customer
                    lty.result['message'] = `CUSTOMER ADDED WITH CODE ${lty.getCustomer(email)['message']['code']}`
                } else {

                    lty.result['message'] = result['message']
                }
            } else {
                lty.result['message'] = 'INVALID RESPONSE'

            }
        }

        $.ajax(ajaxform)
        return lty.result

    }

    getCustomer(cust_code){
        // let cust_code = $('#general_input').val()
        // get customer from database
        let payload = {
            "module":"card",
            pass_from:'VIEW',
            "data":{
                "card_no":cust_code
            }
        }

        return  api.call('POST',payload,this.api_url);

    }

    loadCustomer(){
        let cust_code = $('#general_input').val()
        // get customer from database
        let payload = {
            "module":"card",
            pass_from:'VIEW',
            "data":{
                "card_no":cust_code
            }
        }

        let response = api.call('POST',payload,this.api_url);
        if(response['code'] === 200){
            let system = new System();
            let bill_ref = system.sys_variable('billRef');
            let customer,name,phone,cardno,points,message = response['message'];
            customer = message['customer'];
            name = customer['name'];
            phone = customer['phone'];
            cardno = message['number'];
            points = message['points'];
            // console.table(message)
            // console.table(customer)

            // validate card in bill
            exec(`DELETE FROM loyalty_tran where billRef = '${bill_ref}'`);
            let ins_data = {
                'cols':['cust_code','billRef','cust_name','points_before'],
                'vars':[`${cardno}`,`${bill_ref}`,`${name}`,`${points}`]
            }
            insert('loyalty_tran',ins_data);

            // console.table(ins_data)
            $('#general_input').val('')

            kasa.info(`${name} Loaded`);
            bill.loadBillsInTrans();



        } else {
            kasa.error(response['message'])
        }



    }

    redeem(){
        let billRef = $('#bill_ref').val()
        if(row_count('loyalty_tran',`billRef = '${billRef}'`) === 1){
            // there is customer
            let condition = `billRef = '${billRef}' and trans_type in ('D','L')`;

            if(row_count('bill_trans',condition) === 0){
                // there is no discount
                let customer_get = get_row('loyalty_tran',`billRef = '${billRef}'`);
                let customer_row = JSON.parse(customer_get)[0]
                let cust_code = customer_row['cust_code'];

                let customer = lty.getCustomer(cust_code)['message'];

                let pointsSum = customer['points'];

                // check if value is up to 50

                let value = pointsSum * (5/100);
                if(value > 50 ){

                    // reddem fire
                    Swal.fire({
                        title: 'Enter Points and Redeem Amount',
                        html:
                            `<div style="width: 100% !important"><input id="totalPoints" readonly value="${value}" class="swal2-input form-control form-control-sm w-75" placeholder="Total Points">
                            <input id="redeemAmount" value="${$('#sub_total').text()}" class="swal2-input form-control form-control-sm w-75" placeholder="Redeem Amount"></div>`,
                        showCancelButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const totalPoints = Swal.getPopup().querySelector('#totalPoints').value;
                            const redeemAmount = Swal.getPopup().querySelector('#redeemAmount').value;
                            return { totalPoints, redeemAmount };
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const totalPoints = parseFloat(result.value.totalPoints);
                            const redeemAmount = parseFloat(result.value.redeemAmount);

                            if (redeemAmount > totalPoints) {
                                Swal.fire({
                                    title: 'Invalid',
                                    text: 'Redeem amount cannot be greater than total points!',
                                    icon: 'error',
                                });
                            } else {

                                // apply loyalty value
                                let RedeemPoints = redeemAmount / (5/100)
                                let minusPoint = -(redeemAmount / (5/100))
                                let redAmt = -(redeemAmount)

                                Swal.fire({
                                    title: 'Valid',
                                    text: `REDEEMED AMOUNT: ${redAmt}, REDEEMED POINTS: ${minusPoint}`,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Confirm',
                                    cancelButtonText: 'Cancel',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // add to bill trans
                                        let bill_tran = `INSERT INTO bill_trans (mach, clerk, bill_number, item_barcode, trans_type, item_qty, loyalty_points, item_desc, tran_type, billRef, shift,discount) VALUES ('${mech_no}','${user_id}','${billNo}','${cust_code}','D',1,${minusPoint},'LOYALTY','L','${billREF}','${shiftNO}','${redAmt}')`;
                                        let loy_trans = `UPDATE loyalty_tran SET points_earned = ${redAmt}, current_points = points_before + ${redAmt} where billRef = '${billREF}'`
                                        console.log(bill_tran)
                                        console.log(loy_trans)
                                        exec(bill_tran);
                                        exec(loy_trans);
                                        // add to loyalty trans

                                        //let points_query = `INSERT INTO loyalty_point_stmt (cust_code, value, billRef) VALUES ('${cust_code}','${minusPoint}','${billRef}')`;

                                        ajaxform['data'] = {
                                            'function':'loy_redem',
                                            'amount':redeemAmount
                                        }
                                        ajaxform['type'] = 'POST'
                                        ajaxform['success'] = function (response) {
                                            al(response)
                                        }
                                        ajaxform['url'] = '/backend/process/form_process.php'

                                        kasa.info("POINTS REDEMED")
                                        bill.loadBillsInTrans();

                                        // $.ajax(ajaxform)


                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Code to execute when cancelled
                                        console.log('Cancelled');
                                    }
                                });

                            }
                        }
                    });


                } else {
                    info(`POINTS: ${pointsSum} VALUE : ${value}  Points is low to redeem`)
                }



            } else {
                warning("Discount Present")
            }

        } else {
            info("LOAD CUSTOMER")
        }
    }

    givePoints(billRef){
        // check if bill exit
        let trans = row_count('loyalty_tran',`billRef = '${billRef}'`);
        let loyalty_i = row_count('bill_trans',"trans_type = 'L'");
        if(trans === 1 && loyalty_i === 0){
            // give points
            let payload = {
                'module':'points',
                'data':{
                    'card_no':'',
                    'points':0
                }
            }
        }
    }


}

class Customer{
    getCustomer(cust_no){
        // validate if customer exist with number
        let payload = {
            module:'customer',
            crud:"read",
            data:{
                cust_no:cust_no
            }
        }
        return api.call('POST',payload,'/api/')
    }

    isInTransaction(){
        let payload = {
            'module':'customer_in_transit',
            'crud':'read',
            'data':{}
        }
    }

}

const m_cust = new Customer()
const lty = new Loyalty()