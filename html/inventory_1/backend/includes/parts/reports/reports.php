<div class="container-fluid p-0 h-100">

    <div class="h-100 row no-gutters">
        <!--Core Nav-->
        <?php include "backend/includes/parts/core/nav/nav.php"; ?>
        
        <!-- COre Work Space-->
        <div class="col-sm-10 h-100 p-2 d-flex flex-wrap align-content-center justify-content-center">
            <?php 
                if ($sub_module === 'reports'){
                    require 'backend/includes/parts/reports/base.php';
                } elseif ($sub_module === 'sales_report') {
                    require 'backend/includes/parts/reports/sales_report.php'; 
                }
            ?>
        </div>
       

    </div>
</div>
