
<?php

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
//        print_r($_POST);

        $mech_no = htmlentities($_POST['mech_no']);
        $mac_addr = htmlentities($_POST['mac_addr']);
        $description = htmlentities($_POST['description']);


        $query = "INSERT INTO mech_setup (mech_no, descr, mac_addr) VALUES ('$mech_no','$description','$mac_addr')";
        $stmt = $mech_db->prepare($query);
        $stmt->execute();

        header("Location:".$_SERVER['HTTP_REFERER']);

    }



?>

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
    <script src="/js/classes/session.js"></script>
    <script src="/js/classes/j_query_supplies.js"></script>
    <script src="/js/classes/db_trans.js"></script>
    <script src="/js/classes/system.js"></script>
    <script src="/js/classes/inventory.js"></script>
    <script src="/js/classes/screen.js"></script>

    <script src="/js/sweetalert2@11.js"></script>

    <link rel="stylesheet" href="/css/sweetalert.min.css">

    <script src="/js/error_handler.js"></script>
    <script src="/js/anton.js"></script>
    <script src="/js/keyboard.js"></script>

    <script src="/js/classes/buttons.js"></script>
    <script src="/js/classes/bill.js"></script>
    <script src="/js/trigger.js"></script>
    <script src="/js/classes/reports.js"></script>
    <script src="/js/classes/Evat.js"></script>
    <script src="/js/classes/tax.js"></script>
    <script src="/js/classes/loyalty.js"></script>
    <script src="/js/classes/kasa.js"></script>
    <script src="/js/classes/api.js"></script>
    <script src="/js/classes/Modal.js"></script>
    <script src="/js/classes/productMaster.js"></script>
    <script src="/js/classes/cust.js"></script>
    <script src="/js/classes/jspdf.umd.min.js"></script>

    <link rel="stylesheet" href="/css/anton.css">







</head>

<body style="height: 100vh; overflow: hidden">
    <div class="container-fluid h-100 d-flex flex-wrap align-content-center ant-bg-light">
        <div class="row w-100 d-flex flex-wrap justify-content-center">
            <div class="col-sm-3">
                <form method="post" class="card w-100">
                    <div class="card-header text-center">
                        <strong class="card-title">Initialize Machine</strong>
                    </div>
                    <div class="card-body p-2">
                        <label class="w-100" for="mech_no">Machine No</label><input readonly value="<?php echo $number ?>" id="mech_no" name="mech_no" type="text" class="form-control form-control-sm rounded-0" required min="1">



                        <label class="w-100">Mac Address
                            <input readonly value="<?php echo $mac ?>" type="text" id="mac_addr" name="mac_addr" autocomplete="off" class="form-control form-control-sm rounded-0" required min="1">
                        </label>

                        <label class="w-100">NAME
                            <input  type="text" value="<?php echo $name ?>" id="description" name="description" autocomplete="off" class="form-control form-control-sm rounded-0" required min="1">
                        </label>

                        <button type="submit" class="btn btn-success w-100">INITIALIZE</button>



                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>

    function ini_machine() {

        let desc,mac_addr, err,err_msg,result = 0

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

            let query = `INSERT INTO mech_setup (mech_no, descr, mac_addr) values ('${mech_no}','${desc}','${mac_addr}')`;
            let data = {
                'cols':['mech_no','descr','mac_addr'],
                'vars':[mech_no,desc,mac_addr]
            }
            console.table(data)
            let savee = exec(query);
            console.assert(savee);
            // let form_data = {
            //     'function':'mech_ini',
            //     'desc':desc,
            //     'mac_addr':mac_addr,
            //     'mech_no':mech_no
            // }

            // console.table(form_data)
            //
            //
            // var result = 0;
            //
            // $.ajax(
            //     {
            //         url:'/backend/process/ajax_tools.php',
            //         'async': false,
            //         'type': "POST",
            //         'global': false,
            //         'dataType': 'html',
            //         data:form_data,
            //         success: function (response)
            //         {
            //             result = response;
            //             console.table(response)
            //             //location.reload()
            //
            //         }
            //     }
            // );

            return result;


        }


    }


</script>
