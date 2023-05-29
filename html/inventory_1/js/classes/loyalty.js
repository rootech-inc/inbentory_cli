class Loyalty {

    // save customer
    base_url = '/backend/process/form-processing/loyalty.php';
    result = {'code':505,'message':"INIT"};
    customerReg(full_name,email){
        ajaxform['type'] = 'POST';
        ajaxform['data'] = {'email':email,'name':full_name,'task':'register'}
        ajaxform['url'] = this.base_url
        ajaxform['success'] = function (response) {
            if(isJson(JSON.stringify(response))){
                lty.result = JSON.parse(response)
            } else {
                al('INVALID RESPONSE')
            }
        }

        $.ajax(ajaxform)
        if(lty.result['code'] === 202){
            // get customer
            al('Customer Added')
            return lty.getCustomer(email)['message']
        } else {
            return lty.result['message']
        }
    }

    getCustomer(str){
        ajaxform['type'] = 'POST';
        ajaxform['data'] = {'str':str,'task':'get_customer'}
        ajaxform['url'] = this.base_url
        ajaxform['success'] = function (response) {
            if(isJson(JSON.stringify(response))){
                lty.result = JSON.parse(response)
                ct(lty.result)
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

}

const lty = new Loyalty()