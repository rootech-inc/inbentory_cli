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

                <!--SAVE-->
                <button type="button" id="commit_edited_po" title="Save Edited PO" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa text-success fa-save"></i>
                </button>




            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">

                <button type="button" title="Search" onclick="selectItemForPo()" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/home/insert_row.png"
                            class="img-fluid"
                    >
                </button>

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

            </div>

        </div>

        <!--PRODUCT BODY-->
        <form method="post" id="general_form" action="backend/process/form-processing/po.php" class="product_body">
            <!--TOP-->
            <div  class="w-100 h-40 overflow-hidden d-flex flex-wrap">
                <input type="hidden" name="function" value="update_po">
                <!--PO Left-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--PO NUMBER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">PO Number</p>
                        </div>
                        <input type="text" name="" class="prod_inp_view" id="po_number" required readonly>
                    </div>

                    <!-- LOCATION -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Loc</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <select name="loc_id" class="prod_inp_view w-100" id="loc_id">

                            </select>
                        </div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <input type="text" disabled name="" class="prod_inp_view" id="supplier">
                    </div>

                    <!--PO TYPE-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">PO Type</p>
                        </div>
                        <div class="prod_inp_view" id="po_type"></div>
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Remarks</p>
                        </div>
                        <input type="text" autocomplete="off" required class="prod_inp_view" name="remarks" id="remarks">
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--Total Amount-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Total Amount</p>
                        </div>
                        <input type="number" disabled autocomplete="off" name="" class="prod_inp_view" id="total_amount">
                    </div>

                </div>

            </div>


            <!--Bottom-->
            <div class="w-100 h-60 overflow-hidden prod_button">

                <table class="table table-sm table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th class='text_xs'>LN</th>
                            <th class='text_xs'>Item Code</th>
                            <th class='text_xs'>Barcode</th>
                            <th class='text_xs'>Description</th>
                            <th class='text_xs'>Pack ID</th>
                            <th class='text_xs'>Pack Desc</th>
                            <th class='text_xs'>Quantity</th>
                            <th class='text_xs'>Cost</th>
                            <th class='text_xs'>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody id="po_items_list">

                    </tbody>
                </table>

            </div>

        </form>

    </div>

</div>
    <script>

    </script>