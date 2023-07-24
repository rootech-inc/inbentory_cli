class Loyalty {

    // save customer
    base_url = '/backend/process/form-processing/loyalty.php';
    result = {'code':505,'message':"INIT"};
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

    getCustomer(str){
        ajaxform['type'] = 'POST';
        ajaxform['data'] = {'str':str,'task':'get_customer'}
        ajaxform['url'] = this.base_url
        ajaxform['success'] = function (response) {
            if(isJson(JSON.stringify(response))){
                lty.result = JSON.parse(response)
                // ct(lty.result)
            } else {
                al('INVALID RESPONSE')
            }
        }
        $.ajax(ajaxform)

        return lty.result
    }

    loadCustomer(){
        let cust_code = $('#general_input').val()
        if(cust_code.length > 0){
            // validate customer exist
            let cust_rows = row_count('loy_customer',`cust_code = '${cust_code}'`)

            if(cust_rows === 1){
                // there is customer
                let bill_ref = $('#bill_ref').val()
                exec(`DELETE FROM loyalty_tran WHERE cust_code = '${cust_code}' AND billRef = '${bill_ref}'`)
                exec(`INSERT INTO loyalty_tran (cust_code, billRef) VALUES ('${cust_code}','${bill_ref}')`)
                bill.loadBillsInTrans()
                al('Customer Added')
                $('#general_input').removeClass('border-danger')


            } else {
                // no customer
                $('#legmsg').html(`<span class="text-dark">Customer Does Not Exist</span>`)
                al('CUSTOMER DOES NOT EXIST')
            }


        } else {
            $('#general_input').addClass('border-danger')
            al("Provide Customer Code")
        }
        $('#general_input').val('')

    }

    redeem(){
        let billRef = $('#bill_ref').val()
        if(row_count('loyalty_tran',`billRef = '${billRef}'`) === 1){
            // there is customer
            if(row_count('bill_trans',`billRef = '${billRef}' and trans_type = 'D'`) === 0){
                // there is no discount
                let customer_get = get_row('loyalty_tran',`billRef = '${billRef}'`);
                let customer = JSON.parse(customer_get)[0]
                let cust_code = customer['cust_code'];
                let pointsSum = JSON.parse(return_rows(`select sum(value) as value from loyalty_point_stmt where cust_code = '${cust_code}'`))[0]['value'];

                // check if value is up to 50

                let value = pointsSum * (5/100);
                if(value > 50 ){

                    // reddem fire
                    Swal.fire({
                        title: 'Enter Points and Redeem Amount',
                        html:
                            `<div style="width: 100% !important"><input id="totalPoints" readonly value="${value}" class="swal2-input form-control form-control-sm w-75" placeholder="Total Points">
                            <input id="redeemAmount" class="swal2-input form-control form-control-sm w-75" placeholder="Redeem Amount"></div>`,
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
                                        // add to loyalty trans

                                        let points_query = `INSERT INTO loyalty_point_stmt (cust_code, value, billRef) VALUES ('${cust_code}','${minusPoint}','${billRef}')`;

                                        ajaxform['data'] = {
                                            'function':'loy_redem',
                                            'amount':redeemAmount
                                        }
                                        ajaxform['type'] = 'POST'
                                        ajaxform['success'] = function (response) {
                                            al(response)
                                        }
                                        ajaxform['url'] = '/backend/process/form_process.php'

                                        $.ajax(ajaxform)


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


}

const lty = new Loyalty()