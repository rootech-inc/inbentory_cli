class Evat {
    invoice(){
        ajaxform['url'] = '/backend/process/evat.php';
        ajaxform['data'] = {
            'evfunc':'invoice','mech_no':1,'bill_no':1,'date':toDay
        }
        ajaxform['success'] = function (response) {
            let resp = JSON.parse(response)
            cl(resp)

        }

        $.ajax(ajaxform)


    }
}

let evat = new Evat();