class Evat {
    invoice(){
        ajaxform['url'] = '/backend/process/evat.php';
        ajaxform['data'] = {
            'evfunc':'invoice','mech_no':1,'bill_no':1,'date':toDay
        }

        ajaxform['success'] = function (response) {
            cl(response)
            if(isJson(response))
            {
                let resp = JSON.parse(response)
                let message,status
                status = resp['status']
                message = resp['message']
                let evat_code = message['code']
                let evat_msg = message['message']
                ct(evat_msg)
                if(status === 200)
                {
                    al('VALID')
                } else {
                    let code = message['code']
                    let msg = message['message']

                    Swal.fire({
                        icon:'error',
                        title:`EVAT ERROR ${code}`,
                        text: msg
                    })
                }

                // ct(resp)

            } else {
                al("Invalid Response")
            }


        }

        $.ajax(ajaxform)


    }
}

let evat = new Evat();