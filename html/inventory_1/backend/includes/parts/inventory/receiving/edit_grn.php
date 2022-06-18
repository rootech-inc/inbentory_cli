    <!-- COre Work Space-->
<div class="w-100 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
    <!--INVENTORY HOME-->
    <!-- <div class="ant-bg-dark w-75 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
        <article class="d-flex flex-wrap align-content-start overflow-auto">
            <button onclick="set_session('inventory=category')" class="master_button m-2 p-1"><p class="m-0 p-0 text-elipse">CATEGORY</p></button>
            <button onclick="set_session('inventory=products')" class="master_button m-2 p-1"><p class="m-0 p-0 text-elipse">PRODUCTS</p></button>
        </article>
    </div> -->

    <div class="modal" id="pdf_modal">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title"></strong>
                </div>
                <div id="pdf_body" class="modal-body" style="padding: 1px !important; height: 75vh">

                </div>
            </div>
        </div>
    </div>

    <!--INVENTORY PRODUCTS-->
    <div class="w-100 h-100 product_container">
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


                <button type="submit" form="general_form" title="Save" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <!--                    <img-->
                    <!--                            src="../../assets/icons/home/new_property.png"-->
                    <!--                            class="img-fluid"-->
                    <!--                    >-->
                    <i class="fa fa-save text-primary"></i>
                </button>


                <!--SORT RIGHT-->
                <button onclick="set_session(['action=view'])" value="xx" type="button" id="sort_right" title="Cancel" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                   <i class="fa fa-window-close text-danger"></i>
                </button>

            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <input style="width: 150px; height: 20px; font-size: small; display: none!important" autocomplete="off" class="form-control form-control-sm mr-2" id="po_search">
                <!--SEARCH-->
                <button disabled type="button" onclick="searchTrigger()" title="Search" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-search text-info"></i>
                </button>

                <!-- PRINT -->
                <button disabled type="button" onclick="print_grn()" title="Print" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-print text-danger"></i>
                </button>

                <!-- APPORVE -->
                <button disabled type="button" id="approve_button" onclick="approve_po()" title="Approve" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/approve.png"
                            class="img-fluid"
                    >
                </button>

                <!-- TRANSACTIONS -->
                <button disabled type="button" id="grn_transactions" onclick="gen_modal('grn_trans','GRN Transactions','grn_trans')" title="Transactions" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-list text-dark"></i>
                </button>

            </div>

        </div>

        <!--PRODUCT BODY-->
        <form id="general_form" action="backend/process/form-processing/grn.php" method="post" class="product_body">
            <input type="hidden" value="update_grn" name="function">
            <!--TOP-->
            <div class="w-100 h-40 overflow-hidden d-flex flex-wrap">

                <!--PO Left-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--PO NUMBER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Entry Number</p>
                        </div>
                        <input type="text" readonly name="entry_no" class="prod_inp_view" id="entry_no" />
                    </div>

                    <!-- LOCATION -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Location</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <input type="text" readonly name="loc_id" class="prod_inp_view w-25" id="loc_id" />
                            <div class="prod_inp_view w-65" id="loc_desc">Description</div>
                        </div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <input type="text" readonly name="supplier" class="prod_inp_view w-25" id="supplier" />
                            <div class="prod_inp_view w-65" id="supp_desc">Description</div>
                        </div>
                    </div>

                    <!--PO TYPE-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Ref PO</p>
                        </div>
                        <input type="text" readonly name="po_entry" class="prod_inp_view" id="po_entry" />
                    </div>

                    <!-- TAX -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Taxable</p>
                        </div>
                        <select onchange="new_grn_tax_calc(this.value)" name="tax_grp" class="prod_inp_view" id="tax_grp">
                            <option value="0">No</option>
                            <option value="V3">VAT 3%</option>
                        </select>
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Remarks</p>
                        </div>
                        <input type="text" readonly name="remarks" class="prod_inp_view" id="remarks" />
                    </div>

                    <i id="document_stat" class="">Pending</i>



                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!-- INVOICE NUMBER -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice</p>
                        </div>
                        <input type="text" readonly name="inv_number" class="prod_inp_view" id="inv_number" />
                    </div>

                    <!--Inv Amount-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Invoice Amount</p>
                        </div>
                        <input type="text" readonly name="inv_amt" class="prod_inp_view" id="inv_amt" />
                    </div>

                    <!-- TAX Amount -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Tax Amount</p>
                        </div>
                        <input type="text" readonly name="tax_amount" class="prod_inp_view" id="tax_amount" />
                    </div>

                    <!-- NET AMOUNT -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Net Amount</p>
                        </div>
                        <input type="text" readonly name="net_amount" class="prod_inp_view" id="net_amount" />
                    </div>


                    <!-- APPROVE INFO -->
                    <div id="approved_container" class="text-right"></div>

                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-60 overflow-hidden prod_button">

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
                            <th class="text_xs">Inv Amount</th>
                            <th class="text_xs">Tax Amt</th>
                            <th class="text_xs">Net Amt</th>
                            <th class="text_xs">Cost</th>
                            <th class="text_xs">Retail</th>
                            <th class="text_xs"><i class="fa fa-tools"></i></th>
                        </tr>
                    </thead>
                    <tbody id="grn_items_list">
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

                        </tr>
                    </tbody>
                </table>

            </div>

        </form>

    </div>

</div>
    <script>

    </script>