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
    <link rel="stylesheet" href="css/keyboard.css">


    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">

    <script src="js/error_handler.js"></script>
    <script src="js/anton.js"></script>
    <script src="js/keyboard.js"></script>







</head>
<body onload="initialize()" onresize="validateSize('yes')" class="ant-bg-black">

    <div id="numericKeyboard" class="ant-bg-black">
        <div class="w-100 p-1 d-flex flex-wrap align-content-start">
            <div class="numKey p-1">
                <button onclick="numInp(9)" class="btn btn-info w-100 h-100">9</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(8)" class="btn btn-info w-100 h-100">8</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(7)" class="btn btn-info w-100 h-100">7</button>
            </div>

            <div class="numKey p-1">
                <button onclick="numInp(4)" class="btn btn-info w-100 h-100">4</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(5)" class="btn btn-info w-100 h-100">5</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(4)" class="btn btn-info w-100 h-100">4</button>
            </div>

            <div class="numKey p-1">
                <button onclick="numInp(1)" class="btn btn-info w-100 h-100">1</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(2)" class="btn btn-info w-100 h-100">2</button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(3)" class="btn btn-info w-100 h-100">3</button>
            </div>


            <div class="numKey p-1">
                <button onclick="backSpace()"  class="btn btn-primary w-100 h-100">
                    <i class="fa fa-backspace"></i>
                </button>
            </div>
            <div class="numKey p-1">
                <button onclick="numInp(0)" class="btn btn-info w-100 h-100">
                    <span>0</span>
                </button>
            </div>
            <div onclick="numInp('*')" class="numKey p-1">
                <button class="btn btn-primary w-100 h-100">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="p-1 d-flex w-100 flex-wrap justify-content-between">
                <div class="w-50 p-1">
                    <button type="submit" form="general_form" class="btn btn-success rounded-0 numKeyLong">ENTER</button>
                </div>
                <div class="w-50 p-1">
                    <button class="btn btn-danger w-100 h-100">
                        <i class="fa fa-keyboard"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
    
    <div id="alphsKeyboard" class="ant-bg-black p-2">
        <div class="w-100 d-flex flex-wrap justify-content-center">
            <input type="text" class="form-control w-75 mb-2">
        </div>

        <div class="w-100 d-flex flex-wrap justify-content-center">
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">Q</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">w</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">E</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">R</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">T</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">Y</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">U</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">I</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">O</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">P</button>
        </div>
        <div class="w-100 d-flex flex-wrap justify-content-center">
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">A</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">S</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">D</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">F</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">G</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">H</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">J</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">K</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">L</button>
        </div>
        <div class="w-100 d-flex flex-wrap justify-content-center">
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">Z</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">X</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">C</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">V</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">B</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">N</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">M</button>
            <button class="alpha btn m-1 btn-light btn-sm shadow-sm">NUM</button>
        </div>
    </div>


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
    <?php }
    else { ?>
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




