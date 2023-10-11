<div class="container-fluid p-0 h-100">

    <div class="h-100 row no-gutters">
        <!--Core Nav-->
        <?php include "backend/includes/parts/core/nav/nav.php"; ?>

        <!-- COre Work Space-->
        <div class="col-sm-10 h-100 d-flex flex-wrap align-content-center justify-content-center">
            <div class="ant-bg-dark w-75 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
                <div class="w-50 text_xx">
                    <p>
                        <span class="font-weight-bolder">CLERK </span>
                        <span class="ant-text-sec"><?php echo $myName ?></span>
                    </p>
                    <p>
                        <span class="font-weight-bolder">DATE </span>
                        <span class="ant-text-sec"><?php echo $today ?></span>
                    </p>
                    <p>
                        <span class="font-weight-bolder">SHIFT N<u>0</u> </span>
                        <span class="ant-text-sec"><?php echo shift_no ?></span>
                    </p>
                    <p>
                        <span class="font-weight-bolder">BILL N<u>0</u> </span>
                        <span class="ant-text-sec"><?php echo $bill_number ?></span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
