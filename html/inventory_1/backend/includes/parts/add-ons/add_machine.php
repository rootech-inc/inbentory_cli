

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMHOS - CLI</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="/css/keyboard.css">
    <link rel="icon" type="image/png" href="/assets/logo/logo.ico">


    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/query.js"></script>

    <script src="/js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">


    <script src="/js/classes/buttons.js"></script>

    <link rel="stylesheet" href="/css/anton.css">







</head>

<body style="height: 100vh; overflow: hidden">
    <div class="container-fluid h-100 d-flex flex-wrap align-content-center ant-bg-light">
        <div class="row w-100 d-flex flex-wrap justify-content-center">
            <div class="col-sm-6">
                <div class="card w-100">
                    <div class="card-header">
                        <strong class="card-title">Initialize Machine</strong>
                    </div>
                    <div class="card-body p-2">
                        <input readonly value="<?php echo (new \db_handeer\db_handler())->row_count('mech_setup') + 1 ?>" id="mech_no" type="hidden" class="form-control form-control-sm rounded-0" required min="1">

                        <label class="w-100">Description
                            <input  type="text" id="description" autocomplete="off" class="form-control form-control-sm rounded-0" required min="1">
                        </label>

                        <label class="w-100">Mac Address
                            <input  type="text" id="mac_addr" autocomplete="off" class="form-control form-control-sm rounded-0" required min="1">
                        </label>

                        <button onclick="ini_machine()" class="btn btn-success w-100">INITIALIZE</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>

    function ini_machine() {

        let desc,mac_addr, err,err_msg

        err = 0
        err_msg = ''

        desc = $('#description').val()
        mac_addr = $('#mac_addr').val()
        let mech_no = $('#mech_no').val()

        if(mac_addr.length < 1)
        {
            // error
            err ++
            err_msg += ' Invalid Mac Address'
        }

        if(desc.length < 1)
        {
            //error
            err ++
            err_msg += ' Invalid Mac Description'
        }

        if(err > 0)
        {
            alert(err_msg)
        } else
        {


            let form_data = {
                'function':'mech_ini',
                'desc':desc,
                'mac_addr':mac_addr,
                'mech_no':mech_no
            }

            console.table(form_data)


            var result = 0;

            $.ajax(
                {
                    url:'/backend/process/ajax_tools.php',
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'html',
                    data:form_data,
                    success: function (response)
                    {
                        result = response;
                        // error_handler(response)
                        location.reload()

                    }
                }
            );

            return result;

           console.table(form_data)

        }


    }


</script>
