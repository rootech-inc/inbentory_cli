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

}

const lty = new Loyalty()