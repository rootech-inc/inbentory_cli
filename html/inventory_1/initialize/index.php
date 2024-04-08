<?php

    session_start();
    if(!isset($_SESSION['stage'])){
        $_SESSION['stage'] = 'DB_SETUP';
    }

//$_SESSION['stage'] = 'DB_SETUP';


    $stage = $_SESSION['stage'];
    $page_title = str_replace('_',' ',$stage);


    if(isset($_GET['error'])){
        $error = $_GET['error'];
        echo "<script>alert(`$error`); location.href='/initialize'</script>";
    }




?>

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
</head>
<body>

    <div class="container-fluid bg-dark d-flex flex-wrap justify-content-center align-content-center" style="height: 100vh !important">
        <form method="post" action="process.php" enctype="multipart/form-data" class="w-50 h-50 card">
            <div class="card-header">
                <div class="w-100 d-flex">
                    <div class="w-50">
                        <strong class="card-title"><?php echo $page_title ?></strong>
                    </div>
                </div>
            </div>

            <div  class="card-body p-5 overflow-auto">
                <?php if($stage === 'DB_SETUP'): ?>
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <label class="w-100" for="db_host">DB HOST</label>
                            <input type="text" required name="db_host" id="db_host" class="form-control rounded-0">
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="w-100" for="db_name">DB NAME</label>
                            <input type="text" required name="db_name" id="db_name" class="form-control rounded-0">
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="w-100" for="db_user">DB USER</label>
                            <input type="text" required name="db_user" autocomplete="off" id="db_user" class="form-control rounded-0">
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="w-100" for="db_password">DB PASSWORD</label>
                            <input type="password" required name="db_password" autocomplete="off" id="db_password" class="form-control rounded-0">
                        </div>
                        <div class="col-sm-6">
                            <input type="checkbox" name="db_ini" id="db_ini">
                            <label for="db_ini">Initialize DB</label>

                        </div>
                    </div>

                <?php elseif ($stage === 'SYSTEM_CONFIG'): ?>

                    <div class="row d-flex flex-wrap justify-content-between">
                        <div class="col-sm-8 row p-2">
                            <div class="col-sm-6 mb-2">
                                <label class="w-100" for="mech_no">MECH N0</label>
                                <input type="number" required name="mech_no" id="mech_no" class="form-control rounded-0">
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label class="w-100" for="mac_addr">MAC ADDR</label>
                                <input type="text" required name="mac_addr" id="mac_addr" class="form-control rounded-0">
                            </div>

                            <div class="col-sm-6 mb-2">
                                <label class="w-100" for="name">NAME</label>
                                <input type="text" required name="name" id="name" class="form-control rounded-0">
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label class="w-100" for="debug">DEBUGING</label>
                                <select name="debug" id="debug" class="form-control rounded-0">
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 py-2">
                            <div class="col-sm-12">
                                <label for="print_type">PRINT TYPE</label>
                                <select name="print_type" id="print_type" class="form-control mb-2 rounded-0">
                                    <option value="SERVER">Thermal</option>
                                    <option value="BROWSER">PDF</option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <label for="print_name">NAME</label><input type="text" value="EPSON" name="print_name" id="print_name" class="form-control mb-2 rounded-0">
                            </div>
                            <div class="col-sm-12">
                                <label for="print_status" class="w-100">STATUS</label>
                                <select name="print_status" id="print_status" class="form-control rounded-0">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                    </div>

                <?php elseif ($stage === 'API_CONFIGURATION'): ?>
                    <div class="row d-flex justify-content-between">
                        <div class="col-sm-5 card p-2">
                            <h5>LOYALTY</h5>
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="w-100" for="lty_status">ACTIVE</label>
                                    <select name="lty_status" id="lty_status" class="form-control rounded-0">
                                        <option value="0">False</option>
                                        <option value="1">True</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <label class="w-100" for="loy_token">API TOKEN</label>
                                    <input type="password" name="loy_token" id="loy_token" class="form-control rounded-0">
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <label class="w-100" for="loy_url">API ENDPOINT</label>
                                    <input type="url" name="loy_url" id="loy_url" class="form-control rounded-0">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5 card p-2">
                            <h5>EVAT</h5>
                            <div class="row">
                                <div class="col-sm-12 mb-2">
                                    <label class="w-100" for="evat_status">ACTIVE</label>
                                    <select name="evat_status" id="evat_status" class="form-control rounded-0">
                                        <option value="0">False</option>
                                        <option value="1">True</option>
                                    </select>
                                </div>

                                <div class="col-sm-12 mb-2">
                                    <label class="w-100" for="evat_end_point">API ENDPOINT</label>
                                    <input type="url" name="evat_end_point" id="evat_end_point" class="form-control rounded-0">
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($stage === 'COMPANY_SETUP'): ?>
                    <!--NAME -->
                    <div class="row">
                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="name">COMPANY NAME</label>
                            <input type="text" required name="name" class="form-control rounded-0" id="name">
                        </div>

                        <div class="col-sm-6">
                            <label for="logo" class="w-100">Logo</label>
                            <input type="file" required accept="image/png" class="form-control rounded-0" name="logo" id="logo">
                        </div>
                    </div>

                    <!-- COMP CODE, TAX CODE -->
                    <div class="row">
                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="code">COMP. CODE</label>
                            <input type="number" required value="1" readonly name="code" class="form-control rounded-0" id="code">
                        </div>

                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="tax_code">Tax Code</label>
                            <input type="text" required name="tax_code" class="form-control rounded-0" id="tax_code">
                        </div>
                    </div>
                    <hr>
                    <!-- COUNTRY , CITY -->
                    <div class="row">
                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="country">Country</label>
                            <input type="text" required name="country" class="form-control rounded-0" id="country">
                        </div>

                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="city">City</label>
                            <input type="text" required name="city" class="form-control rounded-0" id="city">
                        </div>
                    </div>

                    <!-- STREET, BOX -->
                    <div class="row">
                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="street">Street Address.</label>
                            <input type="text" required name="street" class="form-control rounded-0" id="street">
                        </div>

                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="box">Post Box</label>
                            <input type="text" required name="box" class="form-control rounded-0" id="box">
                        </div>
                    </div>

                    <!-- Phone, Email -->
                    <div class="row">
                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="phone">Phone.</label>
                            <input type="tel" required name="phone" class="form-control rounded-0" id="phone">
                        </div>

                        <div class="input-group col-sm-6 mb-2">
                            <label class="w-100" for="email">Email Address</label>
                            <input type="email" required name="email" class="form-control rounded-0" id="email">
                        </div>
                    </div>

                    <hr>

                    <!-- FOOTER -->
                    <div class="row">

                        <div class="input-group col-sm-12 mb-2">
                            <label class="w-100" for="footer">Footer</label>
                            <textarea id="footer" name="footer" class="form-control rounded-0" rows="3"></textarea>
                        </div>


                    </div>

                <?php elseif ($stage === 'SETUP_COMPLETE'): ?>
                    <div class="w-100 h-100 d-flex flex-wrap justify-content-center align-content-center">
                        <div class="alert alert-success w-50">
                            <strong>SUCCESS</strong>
                            <p>You have successfully configured venta, visit base url to start operations</p>
                        </div>
                    </div>

                <?php
                    unset($_SESSION['stage']);
                    header("Location:/login");
                endif; ?>
            </div>
            <?php if($stage !== 'SETUP_COMPLETE'): ?>
            <div class="card-footer">
                <button class="btn btn-info">NEXT</button>
            </div>
            <?php endif; ?>
        </form>
    </div>

</body>
</html>

