<div class="container-fluid p-0 h-100">

    <div class="h-100 row no-gutters">
        <!--Core Nav-->
        <?php include "backend/includes/parts/core/nav/nav.php"; ?>

        <!-- COre Work Space-->
        <div class="col-sm-10 h-100 d-flex flex-wrap align-content-center justify-content-center">
            <div class="ant-bg-dark w-75 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
                <article class="d-flex flex-wrap align-content-start justify-content-between overflow-auto">
                    <button id="sales_report" class="master_button btn m-2 p-1 pointer">SALES</button>
                    <button id="z_modal" class="master_button btn btn-warning m-2 p-1 pointer">Z-REPORT</button>
                    <div class="modal fade" id="zModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <strong class="modal-title">TAKE Z-REPORT</strong>
                                </div>
                                <div class="modal-body" id="zBody">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group w-100">
                                                    <label for="shifts">SELECT SHIFT:</label>
                                                    <select class="form-control" id="shifts" name="shifts">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button onclick="reports.zReport()" class="btn btn-sm btn-success">TAKE</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button id="eod" class="master_button btn btn-danger m-2 p-1 pointer">EOD</button>
                </article>
            </div>
        </div>

    </div>
</div>
