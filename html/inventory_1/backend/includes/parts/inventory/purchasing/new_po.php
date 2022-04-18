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

                <!--ADD-->
                <button onclick="set_session(['action=new'])"  type="button" title="New PO" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/new_property.png"
                            class="img-fluid"
                    >
                </button>

                <!--DELETE-->
                <button onclick="gen_modal('delete_product')" type="button" title="Delete" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/delete_document.png"
                            class="img-fluid"
                    >
                </button>



                <!--EDIT-->
                <button onclick="set_session('action:edit')" type="button" title="Edit" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/edit_property.png"
                            class="img-fluid"
                    >
                </button>


            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <!--ADD-->
                <button type="button" title="Search" onclick="selectItemForPo()" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/home/insert_row.png"
                            class="img-fluid"
                    >
                </button>

            </div>

        </div>

        <!--PRODUCT BODY-->
        <div class="product_body">
            <!--TOP-->
            <div class="w-100 h-40 overflow-hidden d-flex flex-wrap">

                <!--PO Left-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--PO NUMBER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">PO Number</p>
                        </div>
                        <div class="prod_inp_view" id="po_number"></div>
                    </div>

                    <!-- LOCATION -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Loc</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <div class="prod_inp_view w-25">001</div>
                            <div class="prod_inp_view w-65" id="location">Description</div>
                        </div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <select name="supplier" class="prod_inp_view" id="supplier">
                            <?php
                                while ($supp = $suppliers->fetch(PDO::FETCH_ASSOC))
                                {
                                    $sup_desc = $supp['supp_name'];
                                    $sup_id = $supp['supp_id'];
                                    echo "<option value='".$sup_id."'>".$sup_desc."</option>";
                                }
                            ?>

                        </select>
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
                        <div class="prod_inp_view" id="remarks"></div>
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">

                    <!--Total Amount-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Total Amount</p>
                        </div>
                        <div class="prod_inp_view" id="total_amount"></div>
                    </div>

                    <!--Owner-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Owner</p>
                        </div>
                        <div class="prod_inp_view" id="owner"></div>
                    </div>

                    <!--Date Created-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Date Created</p>
                        </div>
                        <div class="prod_inp_view" id="created_at"></div>
                    </div>

                    <!-- EDITED BY -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Edited By</p>
                        </div>
                        <div class="prod_inp_view" id="edited_by"></div>
                    </div>

                    <!--Last Edited-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Edited</p>
                        </div>
                        <div class="prod_inp_view" id="edited_at"></div>
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
                                        <tr onclick="loadPoItem()">
                                            <td>John</td>
                                            <td>Barcode</td>
                                            <td>Doe</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-sm table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>AC</th><th>Item</th><th>Description</th><th>Pack ID</th><th>Qty</th><th>Cost</th><th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="po_items_list">
                        <tr>
                            <td>
                                <kbd class="">&plus;</kbd>
                            </td>
                            <td><input ondblclick="selectItemForPo(this.id)" onkeyup="loadPoItem(this.id,event)" type="text" name="item_code[]" id="itemCode_1" style="width: 100px" value="1000101"></td>
                            <td>
                                <input type="text" readonly name="item_desc[]" id="itemDesc_1" value="Test Item">
                            </td>
                            <td>
                                <select name="item_pack[]" id="itemPack_1" style="width: 50px">
                                    <option value="">CTN</option>
                                </select>
                            </td>
                            <td><input style="width: 50px" type="text" name="item_qty[]" id="itemQty_1"></td>
                            <td><input style="width: 50px" type="text" name="item_cost[]" id="itemCost_1"></td>
                            <td><input style="width: 50px" type="text" readonly name="item_amount[]" id="itemAmount_1"></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>