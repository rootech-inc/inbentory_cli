

<script src="js/billing.js"></script>

<input type="hidden" name="clerk" id="clerk" value="<?php echo $myName ?>">
<input type="hidden" name="bill_number" id="bill_number" value="<?php echo $bill_number ?>">
<input type="hidden" id="bill_ref" value="<?php echo billRef ?>">
<input type="hidden"  id="refundOriginalInvoice">
<!-- REFIND MODAL -->
<div class="modal" id="refundModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title">REFUNDING</strong>
                <button class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="refundForm" method="post" action="/backend/process/form-processing/billing.php">

                    <input type="hidden" name="function" value="bill_refund">
                    <input type="hidden" required id="ref_type" name="ref_type" value="">
                    <input type="hidden" required id="billRef" name="billRef" value="">
                    <div class="w-100 d-flex flex-wrap justify-content-between">
                        <div class="w-50">
                            <strong>Date : </strong><span id="refDate">${b_hd['bill_date']}</span> <br>
                            <strong>Time : </strong><span id="refundTime"></span> <br>
                        </div>

                        <div class="w-50 text-right">
                            <strong>Mech # : </strong><span id="refMech">${b_hd['mach_no']}</span> <br>
                            <strong>Clerk : </strong><span id="refClerk">${b_hd['clerk']}</span> <br>
                        </div>
                    </div>

                    <hr>
                    <table class="table table-sm table-striped table-bordered">
                        <thead class="table-dark">
                        <tr>
                            <th><i class="fa fa-check-square"></i></th>
                            <th>Barcode</th>
                            <th>Description</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody id="refundBody">

                        </tbody>
                    </table>

                    <hr>
                    <button type="submit" class="btn btn-warning">REFUND</button>

                </form>
            </div>
        </div>
    </div>

</div>
    <main style="" class="p-0 h-100 d-flex flex-wrap align-content-start justify-content-center">
        <header class="billing-header border-light m-1 d-flex flex-wrap align-content-center justify-content-between">
            <div class="w-25 h-100 d-flex flex-wrap text_xx align-content-center">
                <kbd class='mr-2 bg-primary'>SHIFT <?php echo shift_no ?></kbd> Bill #<div id="bill_num"><?php echo $bill_number ?></div>
            </div>

            <div class="d-flex flex-wrap w-25 h-100 justify-content-start align-content-center overflow-hidden">
                <div style="display: none" class="text-success text-left w-100"><i id="date" class="date"><?php echo $today ?></i></div>
                <div class="w-fit text-left text-center">
                    <strong id="bill_time" class="time text-center">00:00</strong>
                </div>
            </div>

            <div class="w-50 d-flex flex-wrap h-100">
                <table class="table h-100 m-0 p-0 table-bordered">
                    <thead class="h-50">
                        <tr>
                            <th>Tot Amt.</th>
                            <th>Disc Amt.</th>
                            <th>Bill Amt.</th>
                            <th>Tax Amt.</th>
                            <th>Paid Amt.</th>
                            <th>Bal Amt.</th>
                        </tr>
                    </thead>
                    <tbody class="text-light h-50 p-0 m-0">
                        <tr>
                            <td id="sub_total" class="text-light">0.00</td>
                            <td id="disc_amt" class="text-light">0.00</td>
                            <td id="bill_amt" class="text-light">0.00</td>
                            <td id="tax" class="text-light">0.00</td>
                            <td id="amount_paid" class="text-light">0.00</td>
                            <td id="amount_balance" class="text-light">0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </header>

        <div class="bill-body border p-0">

            <div class="row no-gutters h-100">
                <!-- Items -->
                <div class="col-sm-7 pr-1 h-100">
                    <div class="bill-item-header d-flex flex-wrap align-content-center justify-content-between pl-3 pr-3">
                        <button onclick="set_session(['module=home'])" class="exit_button rounded-0 btn">
                            EXIT
                        </button>

                        <select onchange="change_category(this.value)" name="" id="" class="form-control rounded-0 w-50">
                            <option value="0">SELECT CATEGORY</option>
                            <?php while($selcGroup = $item_groups2->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $selcGroup['button_index'] ?>"><?php echo $selcGroup['description'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <span id="msglegend"></span>
                    </div>

                    <div class="bill-item-body p-1 border d-flex flex-wrap align-content-start justify-content-between">
                        <!-- category
                        <div class="w-25 p-1 h-100 border">
                            <div class="h-10 w-100 d-flex flex-wrap align-content-center justify-content-center border">
                                <button onclick="custom_scroll('category','up')" class="cat_button_scroll btn rounded-0 shadow text-center m-2">
                                    <img
                                    style="height: 45px; width: 45px;"
                                    class="img-fluid"
                                    src="../assets/icons/home/arrow_up.png"
                                >
                                </button>
                            </div>

                            <div id="category" class="h-80 w-100 d-flex flex-wrap justify-content-center overflow-hidden align-content-start border">

                                <?php while ($group = $item_groups->fetch(PDO::FETCH_ASSOC)):
                                    $grp_id = $group['button_index'];
                                    ?>
                                    <button onclick="change_category('<?php echo $grp_id ?>')" class="<?php if($anton->get_session('current_group') == $grp_id){echo 'cat_button_active';} else {echo 'cat_button';} ?> btn text-center shadow m-2">
                                    <img
                                        style="height: 45px; width: 45px;"
                                        class="img-fluid"
                                        src="../assets/icons/home/category_image.png"
                                    >

                                    <p class="m-0 text-elipse p-0"><?php echo $group['description'] ?></p>

                                </button>
                                <?php endwhile; ?>

                            </div>

                            <div class="h-10 w-100 d-flex flex-wrap align-content-center justify-content-between border">
                                <button onclick="custom_scroll('category','down')" class="cat_button_scroll btn rounded-0 shadow text-center m-2">
                                    <img
                                    style="height: 45px; width: 45px;"
                                    class="img-fluid"
                                    src="../assets/icons/home/arrrow_down.png"
                                >
                                </button>
                            </div>
                        </div>
                    -->

                        <!--Items-->
                        <div id="items_container"  class="w-100 d-flex flex-wrap align-content-start justify-content-between h-100 border">
                                <div id="items" class="h-100 w-100 overflow-auto d-flex flex-wrap border align-content-start justify-content-start">
                                    

                                </div>
                                <!--ITEMS
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
                                </div> SCROW-->

                        </div>
                    </div>
                </div>

                <!--Bill Details-->
                <div class="col-sm-5 row no-gutters pl-1 d-flex flex-wrap align-content-between h-100">


                    <div class="bill-item-header">
                        <div class="container-fluid h-100">
                            <div class="row w-100 h-100 no-gutters">
                                <div class="col-sm-10 h-100">
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
                            </div>
                        </div>

                    </div>

                    <div class="bill-item-body border">

                        <!--CART-->
                        <div class="cart_display">

                            <div class="border border-light h-100">
                                <div id="bill_loader" class="h-90 w-100 overflow-auto">
                                    <div oncontextmenu="mark_bill_item('md5 of item')" ondblclick="mark_bill_item('12')" class="d-flex flex-wrap cart_item align-content-center justify-content-between border-dotted pb-1 pt-1">


                                    </div>

                                </div>

                            </div>


                        </div>

                        <!--CART MESSAGE -->
                        <div class="cart_message w-100 px-2">
                            <div id="process_message" class="w-100 h-100 d-flex text-info flex-wrap align-content-center justify-content-center">
                                Hello World
                            </div>
                        </div>

                        <!--Functions-->
                        <div class="card_functions p-2 d-flex flex-wrap align-content-between border">
                            <form id="add_to_bill_form" action="backend/process/form_process.php" method="post" class="input-group bg-danger h-20 overflow-hidden">
                                <input type="hidden" name="function" value="new_item" class="">

                                <div class="input-group">
                                    <input  required id="general_input" name="barcode" value="" type="text" autocomplete="off"
                                            class="bill_main_input h-100 form-control rounded-0">
                                    <div class="input-group-prepend">
                                        <button type="button" onclick="keyboard.showQwerty()" class="btn btn-info"><i class="fa fa-keyboard"></i></button>
                                    </div>
                                </div>
                                <div style="display: none" class="input-group-append w-20 bill_main_input p-0">
                                    <span class="input-group-text w-100 rounded-0 text-dark p-0">
                                        <button type="submit" class="btn h-100 rounded-0 w-100 btn-info">GO</button>
                                    </span>
                                </div>

                            </form>
                            <!-- TODO make barcode scanning with code -->


                            <div class="w-100 pt-1 h-80 d-flex flex-wrap justify-content-between align-content-start overflow-hidden">
                                <!-- BUTTONS -->
                                <div id="functionButtons" class="w-85 h-100 d-flex overflow-hidden flex-wrap align-content-start">

                                    <button id="cash_payment" onclick="bill.payment('cash')" class="bill_func_sub_btn btn btn-sm btn-primary rounded-0">
                                        CASH
                                    </button>
                                    <button id="momo_payment" onclick="bill.payment('momo')" class="bill_func_sub_btn btn btn-sm btn-primary rounded-0">
                                        MOMO
                                    </button>
                                    <button id="credit_payment" onclick="bill.payment('credit_payment')" class="bill_func_sub_btn btn btn-sm btn-primary rounded-0">
                                        MOMO
                                    </button>
                                    <button id="cancel" disabled onclick="cancel_bill()" class="bill_func_sub_btn btn btn-sm btn-danger rounded-0">
                                        CANCEL
                                    </button>
                                    <button id="void_button" disabled onclick="bill.void()" class="bill_func_sub_btn btn btn-sm btn-warning rounded-0">
                                        VOID
                                    </button>
                                    <button id="subTotal" disabled onclick="bill.sub_total()" class="bill_func_sub_btn btn btn-sm btn-success rounded-0">
                                        SUB TOTAL
                                    </button>
                                    <button id="hold" disabled onclick="bill.holdBill()" class="bill_func_sub_btn btn btn-sm btn_traditional rounded-0">
                                        HOLD
                                    </button>
                                    <button onclick="itemLookup()" id="LKUP" class="bill_func_sub_btn btn btn_traditional btn-sm rounded-0">
                                        LKUP
                                    </button>
                                    <button disabled onclick="lty.loadCustomer()" id="LOYALTY_LOOKUP" class="bill_func_sub_btn btn btn-sm btn-secondary rounded-0">
                                        LOY LOAD
                                    </button>

                                    <button disabled onclick="lty.redeem()" id="LOYALTY_REDEEM" class="bill_func_sub_btn btn btn-sm btn-secondary rounded-0">
                                        LOYRED
                                    </button>

                                    <button onclick="kasa.info('NOT INPLEMENTED')" disabled id="load_cust" class="bill_func_sub_btn btn btn-sm btn-light rounded-0">
                                        LOAD CUST
                                    </button>

                                    <button id="bill_recall" disabled onclick="bill.recall()" class="bill_func_sub_btn btn btn-info btn-sm rounded-0">
                                        RECAL
                                    </button>
                                    <button id="discount" disabled
                                            class="bill_func_sub_btn btn_traditional btn-sm rounded-0"
                                            onclick="discount()"
                                    >
                                        DISC
                                    </button>
                                    <button disabled id="REFUND" class="bill_func_sub_btn btn_traditional btn-sm rounded-0">
                                        REFUND
                                    </button>

                                </div>
                                <!-- MORE FUNCTIONS -->
                                <div class="w-15 h-100 py-1 d-flex flex-wrap justify-content-between align-content-between">
<!--                                    <button type="button" class="bill_func_sub_btn btn-outline-dark h-30 w-100 btn-sm" onclick="sys.OnKeyboard()" ><i class="fa fa-keyboard"></i></button>-->
                                    <button type="button" class="bill_func_sub_btn item_scroll h-30 w-100 btn-sm" onclick="custom_scroll('functionButtons','up')"><i class="fa fa-arrow-up"></i></button>
                                    <button type="button" class="bill_func_sub_btn item_scroll h-30 w-100 btn-sm" onclick="custom_scroll('functionButtons','down')"><i class="fa fa-arrow-down"></i></button>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

        </div>

    </main>

     <!--DISCOUNT MODAL-->
     <div class="modal fade" id="discountModal">
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
                    <?php
                        $dsc = $db->db_connect()->query("SELECT * FROM `disc_mast`");
                        while($discount = $dsc->fetch(PDO::FETCH_ASSOC)):
                            $disc_uni = $discount['disc_uni'];
                    ?>
                    <button onclick="apply_discount('<?php echo $disc_uni ?>')" class="btn disc_btn">
                        <?php echo $discount['desc'] ?>
                    </button>
                    <?php endwhile; ?>

                </div>
            </div>

          </div>
        </div>
      </div>

<script>
    get_bill();
    checkVoud();

</script>
