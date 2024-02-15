    <!-- COre Work Space-->
<div class="w-100 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
    <!--INVENTORY HOME-->
    <!-- <div class="ant-bg-dark w-75 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
        <article class="d-flex flex-wrap align-content-start overflow-auto">
            <button onclick="set_session('inventory=category')" class="master_button m-2 p-1"><p class="m-0 p-0 text-elipse">CATEGORY</p></button>
            <button onclick="set_session('inventory=products')" class="master_button m-2 p-1"><p class="m-0 p-0 text-elipse">PRODUCTS</p></button>
        </article>
    </div> -->

    <!--INVENTORY PRODUCTS-->
    <div  id="save_new_grn" class="w-100 h-100 product_container">

        <div class="d-flex flex-wrap align-content-center product_header">

            <!--HEADER LEFT-->
            <div class="w-50 d-flex flex-wrap align-content-center pl-2 h-100 overflow-hidden">

                <!-- EXIT -->
                <button onclick="set_session('sub_module=inventory')" title="Inventory Master" type="button" class="btn btn-primary btn-sm mr-1">
                    <i class="fa fa-home"></i>
                </button>

                <!--ADD-->
                <button id="save_grn_x2" title="Save Document" class="btn btn-success btn-sm btn-sm mr-1">
                    <i class="fa fa-save"></i>
                </button>

                <!--DELETE-->
                <button onclick="set_session(['inventory=home'])" type="button" title="Cancel" class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i>
                </button>



            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <!--ADD-->
                <input style="width: 150px; height: 20px; font-size: small; display: none!important" autocomplete="off" placeholder="PO Number" class="form-control form-control-sm mr-2" id="po_search">
                <button type="button" title="Search" onclick="searchTrigger()" class="btn btn-dark mr-2 btn-sm">
                    <i class="fa fa-search"></i>
                </button>
                <button onclick="retrievePo()" class="btn btn-info btn-sm mr-1 d-flex">
                    Retrieve PO
                </button>
                <!-- INSERT NEW LINE -->
                <button type="button" style="display: none !important" data-toggle="modal" data-target="#new_grn_item_modal" disabled id="new_item" title="New Line" class="header_icon ml-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-plus text-info"></i>
                </button>
                <!-- NEW GRN MODAL -->
                <div class="modal fade" id="new_grn_item_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <input type="search" name="" autocomplete="off" placeholder="Item Detail" id="new_grn_item" class="form-control rounded-0">
                            </div>
                            <div class="modal-body overflow-hidden" style="height: 50vh">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Barcode</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grn_item_search_table">
                                            <tr ondblclick="new_line('grn',12345)">
                                                <td>65556</td>
                                                <td>Hello World</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>

        <!--PRODUCT BODY-->
        <form id="general_form" action="backend/process/form-processing/grn.php" method="post" class="product_body">
            <input type="hidden" value="new_grn" name="function">
            <!--TOP-->
            <div class="w-100 h-40 overflow-hidden d-flex flex-wrap">

                <!--PO Left-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!-- RECEIVED DATE -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Rec. Date</p>
                        </div>
                        <input class="prod_inp_view" id="rec_date" name="rec_date" type="date" value="<?php echo $today ?>">
                    </div>

                    <!-- LOCATION -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Loc</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <input type="text" readonly required class="prod_inp_view w-25" name="loc_id" id="loc_id">
                            <div class="prod_inp_view w-65" id="loc_desc"></div>
                        </div>
                    </div>

                    <!-- SUPPLIER -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <input type="text" readonly required class="prod_inp_view w-25" name="supp_id" id="supp_id">
                            <div class="prod_inp_view w-65" id="supplier"></div>
                        </div>
                    </div>

                    <!--PO TYPE-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Ref Doc</p>
                        </div>
                        <input class="prod_inp_view" id="ref_doc" readonly name="ref_doc">
                    </div>

<!--                    <div class="w-100 d-flex flex-wrap prod_inp_container">-->
<!--                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">-->
<!--                            <p class="m-0 p-0 text-elipse">Taxable</p>-->
<!--                        </div>-->
<!--                        <select onchange="new_grn_tax_calc(this.value)" name="tax_grp" class="prod_inp_view" id="taxable">-->
<!--                            <option value="1">YES</option>-->
<!--                            <option value="0">NO</option>-->
<!---->
<!--                        </select>-->
<!--                    </div>-->



                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!-- INVOICE NUMBER -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice Number</p>
                        </div>
                        <input class="prod_inp_view" id="invoice_number" name="invoice_number" type="text" required>
                    </div>

                    <!--Total Amount-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice Amount</p>
                        </div>
                        <input class="prod_inp_view" id="total_amount" name="total_amount" type="number" readonly value="0.00">
                    </div>

                    <!--Tax Amount-->
<!--                    <div class="w-100 d-flex flex-wrap prod_inp_container">-->
<!--                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">-->
<!--                            <p class="m-0 p-0 text-elipse">Tax Amount</p>-->
<!--                        </div>-->
<!--                        <input class="prod_inp_view" id="tax_amt" name="tax_amt" type="number" readonly value="0.00">-->
<!--                    </div>-->

                    <!-- NET AMT -->
<!--                    <div class="w-100 d-flex flex-wrap prod_inp_container">-->
<!--                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">-->
<!--                            <p class="m-0 p-0 text-elipse">Net Amount</p>-->
<!--                        </div>-->
<!--                        <input class="prod_inp_view" id="net_amt" name="net_amt" type="number" readonly value="0.00">-->
<!--                    </div>-->


                    <!-- REMARKS -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container" style="overflow: visible !important;">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Remarks</p>
                        </div>
                        <textarea class="w-60 form-control form-control-sm bg-info" id="remarks" name="remarks" rows="2"></textarea>
                    </div>

                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-60 overflow-hidden ant-bg-light">

                <div class="modal" id="newPoItem">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <input type="search" id="poInput" class="form-control">
                            </div>
                            <div class="modal-body overflow-hidden p-0" style="height: 50vh">
                                <table class="table table-sm table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Barcode</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody id="poTable">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th class="">SN</th>
                            <th class="">Barcode</th>
                            <th class="">Description</th>
                            <th class="">Qty</th>
                            <th class="">Unit Price</th>
                            <th class="">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody id="grn_items_list">
<!--                        <tr>-->
<!--                            <td ondblclick="remove_grn_item(123)" class=''>1</td>-->
<!--                            <td class=''>111111</td>-->
<!--                            <td class=''>111111</td>-->
<!--                            <td class=''>120</td>-->
<!--                            <td class=''>1</td>-->
<!--                            <td class=''>120</td>-->
<!--                        </tr>-->
                    </tbody>
                </table>

            </div>

        </form>

    </div>

</div>