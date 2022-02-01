<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1024 X 768</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/anton.css">
    <!-- jQuery library -->
    <script src="js/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="../js/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/anton.js"></script>
    <script>
        setInterval(time, 1000);
    </script>
</head>
<body oncontextmenu="return false" onload="initialize()" onresize="validateSize('yes')" class="ant-bg-black">
    
    <main class="p-0 d-flex flex-wrap align-content-between justify-content-center">
        <header class="billing-header m-1 d-flex flex-wrap align-content-center justify-content-between">
            <div class="w-25 h-100 d-flex flex-wrap text_xx align-content-center">
                Bill #<?php echo $bill_number ?>
            </div>

            <div class="d-flex flex-wrap w-25 h-100 justify-content-start align-content-center overflow-hidden">
                <div style="display: none" class="text-success text-left w-100"><i id="date" class="date"><?php echo $today ?></i></div>
                <div class="w-fit text-left text-left">
                    <strong id="bill_time" class="time text-left">00:00</strong>
                </div>
            </div>

            <div class="w-45 d-flex flex-wrap h-100">
                <div class="w-50 h-50 d-flex flex-wrap justify-content-between align-content-center px-2 x_border">
                    <span>Taxable Amt</span> <span id="sub_total">0.00</span>
                </div>
                <div class="w-50 h-50 d-flex flex-wrap justify-content-between align-content-center px-2 x_border">
                    <span>Tax Amt</span> <span id="tax">0.00</span>
                </div>
                <div class="w-50 h-50 d-flex flex-wrap justify-content-between align-content-center px-2 x_border">
                    <span>Amount Paid</span> <span id="amount_paid">0.00</span>
                </div>
                <div class="w-50 h-50 d-flex flex-wrap justify-content-between align-content-center px-2 x_border">
                    <span>Amount Bal.</span> <span id="amount_balance">0.00</span>
                </div>
            </div>


        </header>

        <div class="bill-body border container-fluid">
            
            <div class="row no-gutters h-100">
                <!-- Items -->
                <div class="col-sm-7 pr-1 d-flex flex-wrap align-content-between h-100">
                    <div class="bill-item-header d-flex flex-wrap align-content-center justify-content-start pl-3">
                        <button onclick="set_session(['module=home'])" class="exit_button rounded-0 btn">
                            EXIT
                        </button>
                    </div>

                    <div class="bill-item-body border d-flex flex-wrap align-content-start justify-content-between">
                        <!-- category-->
                        <div class="w-25 h-100 border">
                            <!--Scroll Up-->
                            <div class="h-10 w-100 d-flex flex-wrap align-content-center justify-content-center border">
                                <button onclick="custom_scroll('category','up')" class="cat_button_scroll btn shadow text-center m-2">
                                    <img
                                    style="height: 45px; width: 45px;"
                                    class="img-fluid"
                                    src="../assets/icons/home/arrow_up.png"
                                >
                                </button>
                            </div>

                            <!--Categories-->
                            <div id="category" class="h-80 w-100 d-flex flex-wrap justify-content-center overflow-hidden align-content-start border">

                                <?php while ($group = $item_groups->fetch(PDO::FETCH_ASSOC)):
                                    $uni = $group['grp_uni'];
                                    ?>
                                    <button onclick="change_category('<?php echo $uni ?>')" class="<?php if($anton->set_session('current_group') == $uni){echo 'cat_button_active';} else {echo 'cat_button';} ?> btn text-center shadow m-2">
                                    <img
                                        style="height: 45px; width: 45px;"
                                        class="img-fluid"
                                        src="../assets/icons/home/category_image.png"
                                    >
    
                                    <p class="m-0 text-elipse p-0"><?php echo $group['group_name'] ?></p>
    
                                </button>
                                <?php endwhile; ?>

                            </div>

                            <!--SCROLL DOwn-->
                            <div class="h-10 w-100 d-flex flex-wrap align-content-center justify-content-center border">
                                <button onclick="custom_scroll('category','down')" class="cat_button_scroll btn shadow text-center m-2">
                                    <img
                                    style="height: 45px; width: 45px;"
                                    class="img-fluid"
                                    src="../assets/icons/home/arrrow_down.png"
                                >
                                </button>
                            </div>
                        </div>
                        
                        <!--Items-->
                        <div id="items_container"  class="w-75 d-flex flex-wrap align-content-start justify-content-between h-100 border">
                                <div id="items" class="h-90 w-100 overflow-hidden d-flex flex-wrap border align-content-start justify-content-start">


                                </div>
                                <!--ITEMS SCROW-->
                                <div class="w-100 h-10 p-2 clearfix">
                                    <button onclick="custom_scroll('items','up')" class="w-45 item_scroll h-100 float-left h-100">
                                        <img
                                        style="height: 45px; width: 45px;"
                                        class="img-fluid"
                                        src="../assets/icons/home/arrow_up.png"
                                    >
                                    </button>
                                    <button onclick="custom_scroll('items','down')" class="w-45 h-100 item_scroll float-right h-100">
                                        <img
                                        style="height: 45px; width: 45px;"
                                        class="img-fluid"
                                        src="../assets/icons/home/arrrow_down.png"
                                    >
                                    </button>
                                </div>

                        </div>
                    </div>
                </div>

                <!--Bill Details-->
                <div class="col-sm-5 row no-gutters pl-1 d-flex flex-wrap align-content-between h-100">


                    <div class="bill-item-header">
                        <div class="d-flex h-100 flex-wrap align-content-center justify-content-between">
                            <!--Sn-->
                            <div class="w-10 h-100 d-flex flex-wrap align-content-center pl-1 border">
                                <strong>SN</strong>
                            </div>

                            <!--Descriptipn-->
                            <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1 border">
                                <strong>Description</strong>
                            </div>

                            <!--Quantity-->
                            <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1 border">
                                <strong>QTY</strong>
                            </div>

                            <!--Cost-->
                            <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1 border">
                                <strong>Cost</strong>
                            </div>
                        </div>

                    </div>

                    <div class="bill-item-body border">
                        <!--CART-->
                        <div class="cart_display">

                            <div class="border border-light h-100">
                                <div id="bill_loader" class="h-90 w-100 overflow-auto">
                                    <div oncontextmenu="mark_bill_item('md5 of item')" ondblclick="mark_bill_item('12')" class="d-flex flex-wrap cart_item align-content-center justify-content-between border-dotted pb-1 pt-1">

                                        <div class="w-10 h-100 d-flex flex-wrap align-content-center pl-1">
                                            <p class="m-0 p-0">1</p>
                                        </div>

                                        <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-1">
                                            <p class="m-0 p-0">Description hello world we need food</p>
                                        </div>

                                        <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                            <p class="m-0 p-0">12</p>
                                        </div>

                                        <!--Cost-->
                                        <div class="w-20 h-100 d-flex flex-wrap align-content-center pl-1">
                                            <p class="m-0 p-0">100.00</p>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>

                        <!--Functions-->
                        <div class="card_functions p-2 d-flex flex-wrap align-content-between border">
                            <form id="general_form" action="backend/process/form_process.php" method="post" class="input-group">
                                <input type="hidden" name="function" value="get_bill_items" class="">
                                <input required id="general_input" name="barcode_for_bill" value="" type="text" autocomplete="off" class="bill_main_input form-control rounded-0">
                                <div class="input-group-append w-20">
                                    <span class="input-group-text w-100 rounded-0 text-dark">
                                        <button type="submit" class="btn h-100 rounded-0 w-100 btn-info">GO</button>
                                    </span>
                                </div>
                            </form>
                            <!-- TODO make barcode scanning with code -->


                            <div class="w-100 pt-1 d-flex flex-wrap justify-content-between">
                                <button onclick="make_payment('cash')" class="bill_func_main_btn btn rounded-0">
                                    CASH
                                </button>
                                <button onclick="make_payment('momo','token')" class="bill_func_main_btn btn rounded-0">
                                    MOMO
                                </button>
                                <button disabled class="bill_func_main_btn btn rounded-0">
                                    OTHERS
                                </button>
                            </div>

                            <div class="w-100 pt-1 d-flex flex-wrap justify-content-between">
                                <button id="cancel" disabled onclick="cancel_bill()" class="bill_func_sub_btn btn btn-sm my-1 btn-danger rounded-0">
                                    CANCEL
                                </button>
                                <button id="hold" disabled onclick="hold_bill()" class="bill_func_sub_btn btn btn-sm my-1 btn-warning rounded-0">
                                    HOLD
                                </button>
                                <button id="recall" onclick="recall_bill('token')" class="bill_func_sub_btn my-1 btn btn-info btn-sm rounded-0">
                                    RECAL
                                </button>
                                <button id="discount" disabled
                                        class="bill_func_sub_btn my-1 btn btn-primary btn-sm rounded-0"
                                        data-toggle="modal"
                                        data-target="#discount"
                                >
                                    DISC
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        
    </main>

     <!--DISCOUNT MODAL-->
     <div class="modal fade" id="discount">
        <div class="modal-dialog modal-dialog-centered mx-auto">
          <div class="modal-content mx-auto">
      
            <!-- Modal Header -->
            <div class="modal-header">
              <strong class="modal-title">Modal Heading</strong>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
      
            <!-- Modal body -->
            <div class="modal-body">
                <div class="p-0 d-flex flex-wrap align-content-center justify-content-between">
                    <button onclick="apply_discount('md5 of discount id')" class="btn disc_btn">
                        3%
                    </button>
                    <button class="btn disc_btn">
                        5%
                    </button>
                    <button class="btn disc_btn">
                        10%
                    </button>
                </div>
            </div>
      
          </div>
        </div>
      </div>
    
</body>
</html>