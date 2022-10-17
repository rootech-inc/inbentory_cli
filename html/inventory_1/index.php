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

    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/keyboard.css">
    <link rel="icon" type="image/png" href="/assets/logo/logo.ico">


    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/query.js"></script>
    <script src="js/classes/session.js"></script>
    <script src="js/classes/j_query_supplies.js"></script>
    <script src="js/classes/db_trans.js"></script>
    <script src="js/classes/system.js"></script>
    <script src="js/classes/inventory.js"></script>
    <script src="/js/classes/screen.js"></script>

    <script src="js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">

    <script src="js/error_handler.js"></script>
    <script src="js/anton.js"></script>
    <script src="js/keyboard.js"></script>

    <script src="js/classes/buttons.js"></script>

    <link rel="stylesheet" href="css/anton.css">








</head>
<body onload="initialize()" onresize="validateSize('yes')" class="abs_1 p-0 d-flex flex-wrap align-content-center">

    <div class="modal" id="loader">
        <div class="w-100 h-100 bg_trans_50 d-flex flex-wrap align-content-center justify-content-center">
            <div style="width: 100px; height: 100px; background: none !important">
                <img src="assets/icons/home/loader.gif" alt="" class="img-fluid">
            </div>
            <div class="m-2 w-100 text-center">
                <button class="btn btn-light rounded-0" onclick="loader('hide')">CLOSE</button>
            </div>
        </div>
    </div>

    <button onclick="keypad('none')" id="keyboardTrigger" class="my-4 btn btn-sm btn-outline-secondary">
        <i class="fa fa-keyboard"></i>
    </button>

    <div style="display: none" id="numericKeyboard" class="ant-bg-black">
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


    <div style="display: none" id="alphsKeyboard" class="ant-bg-black p-1">
        <div class="w-100 h-100" id="keypadContainer">

            <!-- INPUT FIELD -->
            <div class="w-100 d-flex flex-wrap mt-4">
                <div class="alpha w-100">
                    <input type="text" id="keyPadInput" class="form-control rounded-0">
                </div>

            </div>

            <!-- NUMERIC -->
            <div style="display: none" class="w-100 d-flex flex-wrap">
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">1</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">2</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">3</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">4</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">5</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">6</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">7</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">8</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">9</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-primary btn-sm shadow-sm">0</button>
                </div>
                <div class="alpha_double">
                    <button onclick="backSpace()" class="btn btn-danger m-0 btn-sm w-100 h-100 shadow-sm"><i class="fa fa-backspace"></i></button>
                </div>

            </div>

            <!-- QWERTY ROW 1 -->
            <div class="w-100 d-flex flex-wrap">
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">Q</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">W</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">E</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">R</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">T</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">Y</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">U</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">I</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">O</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">P</button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
            </div>

            <!-- QWERTY ROW 2 -->
            <div class="w-100 d-flex flex-wrap">
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">A</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">S</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">D</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">F</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">G</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">H</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">J</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">K</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">L</button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
            </div>

            <!-- QWERTY ROW 3 -->
            <div class="w-100 d-flex flex-wrap">
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">Z</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">X</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">C</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">V</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">B</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">N</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-light btn-sm shadow-sm">M</button>
                </div>
                <div class="alpha">
                    <button type="button" onclick="keyboardInput($(this).text())" class="btn m-0 w-100 h-100 btn-warning btn-sm shadow-sm">*</button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
            </div>

            <!-- QWERTY LAST ROW -->
            <div class="w-100 d-flex flex-wrap">
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha_space">
                    <button type="button" onclick="keyboardInput($(this).text())" class="alpha_space btn w-100 h-100 btn-light btn-sm shadow-sm"> </button>
                </div>
                <div class="alpha">
                    <button disabled class="btn btn-light m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
                <div class="alpha">
                    <button onclick="hideKboard()" class="btn btn-danger m-0 btn-sm w-100 h-100 shadow-sm"></button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div  class="modal fade" id="gen_modal">
        <div id="modal_d" class="modal-dialog modal-dialog-centered mx-auto">
            <div class="modal-content mx-auto">

                <!-- Modal Header -->
                <div class="modal-header">
                    <strong class="modal-title">Sales</strong>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div id="grn_modal_res" class="modal-body rounded-0">

                </div>

            </div>
        </div>
    </div>


    <?php if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true'){  ?>
        <main onclick="hideKboard()" class="p-0 mx-auto card ant-bg-black">
            <input type="hidden" id="my_user_name" value="<?php echo $myName ?>">
            <?php


                if($module === 'home')
                {


                    include 'backend/includes/parts/home/home_index.php';
                }
                elseif ($module === 'billing'){


                    // get categories
                    $item_groups = $db->db_connect()->query("SELECT * from `item_buttons`");


                    // include billing
                    include 'backend/includes/parts/billing/billing.php';
                }
                elseif ($module === 'inventory'){
                    // inventory
                    include 'backend/includes/parts/inventory/index.php';
                }
                elseif ($module === 'system') // system settings
                {
                    // settings
                    $sub_module = $anton->get_session('sub_module');
                    include 'backend/includes/parts/settings/core.php';

                }
                elseif ($module === 'reports'){
                    include 'backend/includes/parts/reports/reports.php';
                }

            ?>
        </main>
    <?php }
    else { ?>
        <main class="mx-auto card grade_danger overflow-hidden">


            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-between">
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

<script>
    // make keypad draggable
    dragElement(document.getElementById("alphsKeyboard"))
    dragElement(document.getElementById("numericKeyboard"))
</script>




