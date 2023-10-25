class Evat {
    send_invoice(){
        ajaxform['url'] = '/backend/process/evat.php';
        ajaxform['data'] = {
            'evfunc':'invoice','mech_no':1,'bill_no':1,'date':toDay
        }

        ajaxform['success'] = function (response) {

            if(isJson(response)){

                console.table(JSON.parse(response))
            } else {
                console.log("INVALID REPONSE")
            }
        }

        $.ajax(ajaxform)

    }

    sign_invoice(num){
        ajaxform['url'] = '/backend/process/evat.php';
        ajaxform['data'] = {
            'evfunc':'sign_invoice',num:num
        }

        ajaxform['success'] = function (response) {
            console.log(response)
            if(isJson(response)){
                let resp = JSON.parse(response);
                if(resp['STATUS'] === 'SUCCESS'){
                    kasa.success("SIGNATURE DONE")
                } else {
                    kasa.error(resp['MESSAGE'])
                }
                console.table(JSON.parse(response))
            } else {
                console.log("INVALID RESPONSE")
            }
        }

        $.ajax(ajaxform)
    }
}

let evat = new Evat();