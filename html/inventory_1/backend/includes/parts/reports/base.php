<div class="ant-bg-dark w-80 d-flex flex-wrap align-content-center justify-content-center tool-box h-80 ant-round">
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
                        <button id="item_availability" class="master_button btn btn-danger m-2 p-1 pointer">AVAILABILITY</button>
                        <button onclick="set_session(['module=reports','sub_module=sales_report'])" id="sales_report" class="master_button btn btn-danger m-2 p-1 pointer">DAILY SALES</button>
                        <button  id="expiry_report" class="master_button btn btn-danger m-2 p-1 pointer">EXPIRY REPORT</button>

                    </article>
                </div>