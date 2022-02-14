<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require 'backend/includes/core.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMHOS - CLI</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/anton.css">
    <link rel="stylesheet" href="css/all.css">


    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">

    <script src="js/error_handler.js"></script>
    <script src="js/anton.js"></script>







</head>
<body onload="initialize()" onresize="validateSize('yes')" class="ant-bg-black">


    <?php if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true'){ ?>
        <main class="p-0 mx-auto">
            <?php

                if($module === 'home')
                {
                    include 'backend/includes/parts/home/home_index.php';
                }
                elseif ($module === 'billing'){
                    // get categories
                    $item_groups = $db->db_connect()->query("SELECT * from `item_group`");


                    // include billing
                    include 'backend/includes/parts/billing/billing.php';
                }

            ?>
        </main>
    <?php } else { ?>
        <main class="w-100 h-100 grade_danger d-flex flex-wrap align-content-center justify-content-between overflow-hidden">


            <div class="w-50 d-flex flex-wrap justify-content-center">
                <div class="w-50"><img src="assets/icons/inv_mgmt.png" alt="" class="img-fluid"></div>
            </div>

            <div class="w-50 d-flex flex-wrap justify-content-center align-content-center">
                <form id="login" method="post" action="backend/process/user_mgmt.php" class="w-50 h-100`">


<!--                    <div class="w-25 mx-auto m-2">-->
<!--                        <img src="assets/logo/logo.png" class="img-fluid">-->
<!--                    </div>-->



                    <div class="w-100 container h-100 p-0">
                        <div class="row h-100 no-gutters">
                            <div class="col-sm-9 p-2 h-100">
                                <div class="w-100 text-warning" id="error_box">

                                </div>
                                <div class="input-group mb-2">
                                    <input id="clerk_code" value="<?php if (isset($_SESSION['clerk_code'])){echo $_SESSION['clerk_code'];} ?>" class="form-control rounded-0 font-weight-bold" type="text" autocomplete="off" placeholder="Code" name="clerk_code" required>
                                </div>

                                <div class="input-group mb-2">
                                    <input id="clerk_password" class="form-control rounded-0 font-weight-bold" type="password" autocomplete="off"  placeholder="Key" name="clerk_key" required>
                                </div>

                                <div class="input-group">
                                    <select name="db_state" id="state" readonly disabled required class="form-control rounded-0 font-weight-bold">
                                        <option selected value="Network">Network</option>
                                        <option value="Local">Local</option>
                                    </select>
                                    <input type="hidden" value="Network" name="db_state" id="setInp">
                                </div>
                            </div>

                            <!--KEYS-->
                            <div class="col-sm-3 d-flex flex-wrap align-content-center py-2 h-100 border">
                                <button style="height: 100% !important" type="submit" name="login" class="w-100 font-weight-bolder fas fa-key btn-danger"></button>
                            </div>
                        </div>
                    </div>






                </form>



            </div>



        </main>
        <script type="text/javascript">
            var frm = $('#login');

            frm.submit(function (e) {

                e.preventDefault();

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    success: function (response) {
                        console.log(response)
                        var response_split = response.split('%%');
                        if(response_split.length === 2)
                        {
                            var response_action = response_split[0];
                            echo(response_action);
                            if(response_action === 'error')
                            {
                                var err_message = response_split[1];
                                $('.form-control').addClass('border-danger');
                            }
                            else
                            {
                                location.reload();
                            }
                        }
                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                    },
                });
            });
        </script>
    <?php } ?>
    
</body>
</html>




