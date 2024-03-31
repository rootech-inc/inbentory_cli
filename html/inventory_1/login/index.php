<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VENTA</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="/css/keyboard.css">
    <link rel="icon" type="image/png" href="/assets/logo/venta.png">


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
<body onload="initialize()" onresize="validateSize('yes')" class="abs_1 p-0 d-flex flex-wrap align-content-center">
    <main class="ant-bg-black container-fluid">
        <div class="row h-100 overflow-hidden d-flex flex-wrap justify-content-center">
<!--            <div class="col-sm-6 d-flex flex-wrap justify-content-center align-content-center h-100">-->
<!--                <div class="w-50"><img src="assets/icons/inv_mgmt.png" alt="" class="img-fluid"></div>-->
<!--            </div>-->
            <div class="h-100 col-sm-5 overflow-hidden border border-light d-flex flex-wrap justify-content-center align-content-center">
<!--                <div class="w-100 text-center mb-2 text-primary"><h1>SHOP FLOW v0.1</h1></div>-->
                <div class="w-100 text-center">
                    <img src="/assets/logo/venta.png" style="width:50px" alt="" class="img-fluid mb-2">
                </div>
                <div class="form-group w-100">
                    <input type="password" autofocus id="user_token" maxlength="4" name="user_token" class="form-control rounded-0 w-100 p-5 text_xx text-center">
                </div>
                <div class="w-100 d-flex flex-wrap justify-content-between">
                    <div class="col-sm-4 p-0 border"><button value="7" class="btn numKey btn-outline-light rounded-0 w-100"><strong>7</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="8" class="btn numKey btn-outline-light rounded-0 w-100"><strong>8</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="9" class="btn numKey btn-outline-light rounded-0 w-100"><strong>9</strong></button></div>

                    <div class="col-sm-4 p-0 border"><button value="4" class="btn numKey btn-outline-light rounded-0 w-100"><strong>4</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="5" class="btn numKey btn-outline-light rounded-0 w-100"><strong>5</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="6" class="btn numKey btn-outline-light rounded-0 w-100"><strong>6</strong></button></div>

                    <div class="col-sm-4 p-0 border"><button value="1" class="btn numKey btn-outline-light rounded-0 w-100"><strong>1</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="2" class="btn numKey btn-outline-light rounded-0 w-100"><strong>2</strong></button></div>
                    <div class="col-sm-4 p-0 border"><button value="3" class="btn numKey btn-outline-light rounded-0 w-100"><strong>3</strong></button></div>

                    <div class="col-sm-4 p-0 border"><button value="1" onclick="$('#user_token').val('')"  class="btn numKeyMiddle btn-outline-warning rounded-0 w-100"><strong>CLEAR</strong></button></div>
                    <div class="col-sm-8 p-0 border"><button onclick="auth()" value="2" class="btn numKeyMiddle btn-outline-success rounded-0 w-100"><strong>LOGIN</strong></button></div>

                </div>
                <p class="text-center text-primary mt-4">v0.1</p>
            </div>
        </div>
    </main>
</body>
<script >
    function auth(){
        let input = $('#user_token').val();
        if(anton.validateInputs(['user_token'])){

            let formData = {
                pin:input
            };
            $.ajax({
                type: 'POST',
                url: '/backend/process/user_mgmt.php',
                data: formData,
                success: function (response){
                    console.log(response)
                    if(isJson(response)){
                        let resp = JSON.parse(response)

                        let code = resp['status_code'];
                        if(code === 200){
                            location.href = '/'
                        } else {
                            alert(resp['message'])
                        }
                    } else {
                        kasa.error("Invalid Response")
                    }
                }
            });
            $('#user_token').val('')
        } else {
            kasa.error("Provide Token")
        }
    }

    $(document).ready(function() {

        $('.numKey').click(function() {
            let existing_value = $('#user_token').val();
            let value = this.value;
            let new_value = `${existing_value}${value}`;
            $('#user_token').val(new_value);
            if(new_value.length >= 4){
                auth()
            }

            console.log(value)
        });

    });

</script>
</html>