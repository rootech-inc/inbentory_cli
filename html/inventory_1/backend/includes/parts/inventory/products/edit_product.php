<!-- COre Work Space-->
<div class="w-100 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">


    <!--INVENTORY PRODUCTS-->
    <form method="post" id="general_form" action="backend/process/form-processing/produc-master-form.php" class="w-100 h-100 product_container">
        <input type="hidden" name="function" value="update">

        <div class="d-flex flex-wrap align-content-center product_header">

            <!--HEADER LEFT-->
            <div class="w-50 d-flex flex-wrap align-content-center pl-2 h-100 overflow-hidden">

                <!-- EXIT -->
                <button onclick="set_session(['sub_module=inventory'])" title="Exit" type="button" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/inventory/inventory_home.png"
                            class="img-fluid"
                    >
                </button>

                <!--ADD-->
                <button onclick="set_session(['action=view'])"  type="button" title="Add" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/cancel.png"
                            class="img-fluid"
                    >
                </button>


                <!--SAVE-->
                <button id="edit_prod" type="submit" title="Save" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-save text-success"></i>
                </button>

            </div>

        </div>

        <!--PRODUCT BODY-->
        <div class="product_body">
            <!--TOP-->
            <div class="w-100 h-50 overflow-hidden d-flex flex-wrap">

                <!--Product Left-->
                <div class="w-50 h-100 p-2 overflow-hidden">
                    <!--CATEGORY-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Group</p>
                        </div>
                        <select onchange="newProductSubGroup(this.value)" name="group" class="prod_inp_view" id="group"></select>
                    </div>

                    <!--SUB CAT-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Sub Group</p>
                        </div>
                        <select name="sub_group" class="prod_inp_view" id="sub_category"></select>

                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <select name="supplier" class="prod_inp_view" id="supplier"></select>
                    </div>

                    <!--Barcode-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Barcode</p>
                        </div>
                        <input class="prod_inp_view" id="barcode" name="barcode" required>
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <input class="prod_inp_view" id="item_desc" required name="item_desc"/>
                    </div>

                    <!--Short Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc</p>
                        </div>
                        <input class="prod_inp_view" id="item_desc1" required name="item_desc1"/>
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">
                    <!--Packaging-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Packaging</p>
                        </div>
                        <select name="packing" class="prod_inp_view" id="packing"></select>

                    </div>

                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Stock Type</p>
                        </div>
                        <select
                                name="stock_type" id="stock_type"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp">



                        </select>
                    </div>

                    <!--Expiry-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Expiry</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <div class="prod_inp_view w-25">YES</div>
                            <input type="date" class="prod_inp_view w-65" id="expiry" min="<?php echo $today ?>" name="expiry"/>
                        </div>
                    </div>

                    <!--Owner
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Owner</p>
                        </div>
                        <div class="prod_inp_view" id="owner"></div>
                    </div>-->

                    <!--Date Created
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Date Created</p>
                        </div>
                        <div class="prod_inp_view" id="created_at"></div>
                    </div>-->

                    <!-- EDITED BY
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Edited By</p>
                        </div>
                        <div class="prod_inp_view" id="edited_by"></div>
                    </div>-->

                    <!--Last Edited
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Edited</p>
                        </div>
                        <div class="prod_inp_view" id="edited_at"></div>
                    </div>-->
                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-50 overflow-hidden prod_button d-flex flex-wrap">

                <header class="w-100 h-20 d-flex flex-wrap overflow-hidden p-1">
                    <button type="button" onclick="arr_hide('stock,packing_tab');arr_show('price')" autofocus class="btn rounded-0 btn_p_more_nav mr-1 w-15">
                        <p class="m-0 p-0 text-elipse">PRICE</p>
                    </button>
                    <button type="button" onclick="arr_hide('price,stock');arr_show('packing_tab')" class="btn rounded-0 btn_p_more_nav mr-1 w-15">
                        <p class="m-0 p-0 text-elipse">Packing</p>
                    </button>
                </header>

                <article class="p-2">
                    <div id="price" class="w-50 h-100">

                        <!-- TAX -->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Tax</p>
                            </div>
                            <select
                                    name="tax"
                                    autocomplete="off"
                                    id="prod_tax"
                            >


                            </select>
                            <div id="tax_descr" class="ml-2 text-dark"></div>
                        </div>

                        <!--COST PRICE-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Cost Price</p>
                            </div>
                            <input type="text" required name="cost_price" id="cost_price">
                        </div>

                        <!-- RETAILs -->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail</p>
                            </div>
                            <input
                                    name="retail_with_tax"
                                    id="retail_with_tax"
                                    autocomplete="off"
                                    type="text"

                            >
                        </div>

                        <!--RETAIL WITHOUT TAX-->
<!--                        <div class="w-100 d-flex flex-wrap prod_inp_container">-->
<!--                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">-->
<!--                                <p class="m-0 p-0 text-elipse">Retail WO/T</p>-->
<!--                            </div>-->
<!--                            <input readonly-->
<!--                                   name="retail_without_tax"-->
<!--                                   autocomplete="off"-->
<!--                                   type="text"-->
<!--                                   id="retail_without_tax"-->
<!---->
<!--                            >-->
<!---->
<!--                        </div>-->


                    </div>



                    <div id="packing_tab" style="display: none;" class="w-75 h-100">
                        <!--PACKING-->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr class="thead-light">
                                    <th class="p-1">Purpose</th>
                                    <th class="p-1">Pack ID</th>
                                    <th class="p-1">Pack Desc</th>
                                    <th class="p-1">Quantity</th>
                                </tr>
                                </thead>
                                <tbody id="packaging_row">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </article>

            </div>

        </div>

    </form>

</div>