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
                <button onclick="set_session('sub_module=inventory')" title="Inventory Master" type="button" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/inventory/inventory_home.png"
                            class="img-fluid"
                    >
                </button>

                <!--ADD-->
                <button id="save_grn" title="Save Document" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/save_close.png"
                            class="img-fluid"
                    >
                </button>

                <!--DELETE-->
                <button onclick="set_session(['inventory=home'])" type="button" title="Cancel" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/cancel.png"
                            class="img-fluid"
                    >
                </button>



            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <!--ADD-->
                <input style="width: 150px; height: 20px; font-size: small; display: none!important" autocomplete="off" placeholder="PO Number" class="form-control form-control-sm mr-2" id="po_search">
                <button type="button" title="Search" onclick="searchTrigger()" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/home/retrieve.png"
                            class="img-fluid"
                    >
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

                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Taxable</p>
                        </div>
                        <select disabled onchange="new_grn_tax_calc(this.value)" name="tax_grp" class="prod_inp_view" id="tax_grp">
                            <option value="0">No</option>
                            <option value="1">YES</option>

                        </select>
                    </div>



                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--Total Amount-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice Amount</p>
                        </div>
                        <input class="prod_inp_view" id="total_amount" name="total_amount" type="number" readonly value="0.00">
                    </div>

                    <!-- INVOICE NUMBER -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice Number</p>
                        </div>
                        <input class="prod_inp_view" id="invoice_number" name="invoice_number" type="text" required>
                    </div>

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

                <table class="table table-sm table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class="text_xs">SN</th>
                            <th class="text_xs">Barcode</th>
                            <th class="text_xs">Description</th>
                            <th class="text_xs">Pack ID</th>
                            <th class="text_xs">Packing</th>
                            <th class="text_xs">Qty</th>
                            <th class="text_xs">Price</th>
                            <th class="text_xs">Total Amount</th>
                            <th class="text_xs">Tax Amt</th>
                            <th class="text_xs">Net Amt</th>
                            <th class="text_xs">Cost</th>
                            <th class="text_xs">Retail</th>
                            <th class="text_xs">Del</th>
                        </tr>
                    </thead>
                    <tbody id="po_items_list">
                        <tr>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'>Text</td>
                            <td class='text_xs'><i class='fa fa-minus pointer text-danger pointer' onclick='remove_grn_item(123)'></i></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </form>

    </div>

</div>