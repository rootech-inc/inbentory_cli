<div class="container-fluid p-0 h-100">

    <div class="h-100 row no-gutters">
        <!--Core Nav-->
        <?php include "backend/includes/parts/core/nav/nav.php"; ?>

        <!-- COre Work Space-->
        <div class="col-sm-10 h-100 d-flex flex-wrap align-content-center justify-content-center">
            <div class="ant-bg-dark w-75 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
                <article class="d-flex flex-wrap align-content-start justify-content-between overflow-auto">
                    <button onclick="report('sales')" class="master_button btn m-2 p-1 pointer">SALES</button>
                    <button class="master_button btn btn-warning m-2 p-1 pointer" disabled>Z-REPORT</button>
                    <button class="master_button btn btn-danger m-2 p-1 pointer" disabled>EOD</button>
                </article>
            </div>
        </div>

    </div>
</div>
