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
                <button onclick="set_session('sub_module=inventory')" title="Exit" type="button" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="assets/icons/inventory/inventory_home.png"
                            class="img-fluid"
                    >
                </button>

                <!--ADD-->
                <button onclick="set_session(['action=new'])"  type="button" title="Add" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
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

                <!--SAVE-->

                <!--EDIT-->
                <button onclick="set_session(['action=edit','prod='+this.value.toString()])" id="edit_prod" type="button" title="Edit" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/edit_property.png"
                            class="img-fluid"
                    >
                </button>

                <!--SORT LEFT-->
                <button type="button" onclick="loadProduct(this.value)" value="" id="sort_left" title="Sort Left" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_left.png"
                            class="img-fluid"
                    >
                </button>

                <!--SORT RIGHT-->
                <button onclick="loadProduct(this.value)" value="" type="button" id="sort_right" title="Sort Right" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_right.png"
                            class="img-fluid"
                    >
                </button>

            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <input style="width: 150px; height: 20px; font-size: small; display: none!important" autocomplete="off" class="form-control form-control-sm mr-2" id="bcodeSearch">
                <!--ADD-->
<!--                <button type="button" onclick="searchTrigger()" title="Search" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">-->
<!--                    <img-->
<!--                            src="../../assets/icons/home/search_property.png"-->
<!--                            class="img-fluid"-->
<!--                    >-->
<!--                </button>-->

                <button type="button" onclick="pmast.searchProduct()" title="Search" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <i class="fa fa-search"></i>
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
                        <div id="group" class="prod_inp_view"></div>
                    </div>

                    <!--SUB CAT-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Sub Group</p>
                        </div>
                        <div class="prod_inp_view" id="sub_group"></div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <div class="prod_inp_view" id="supplier"></div>
                    </div>

                    <!--Barcode-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Barcode</p>
                        </div>
                        <div class="prod_inp_view" id="barcode"></div>
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <div class="prod_inp_view" id="item_desc"></div>
                    </div>

                    <!--Short Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc</p>
                        </div>
                        <div class="prod_inp_view" id="item_desc1"></div>
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">
                    <!--Packaging-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Packaging</p>
                        </div>
                        <div class="prod_inp_view" id="packing">Packaging</div>
                    </div>

                    <!--Expiry-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Expiry</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <div class="prod_inp_view w-25">YES</div>
                            <div class="prod_inp_view w-65" id="expiry"></div>
                        </div>
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
            <div class="w-100 h-50 overflow-hidden prod_button d-flex flex-wrap">

                <header class="w-100 h-20 d-flex flex-wrap align-content-center overflow-hidden p-1">
<!--                    <button type="button" onclick="arr_hide('stock,packing_tab');arr_show('price')" autofocus class="rounded-0 btn_p_more_nav mr-1">-->
<!--                        <p class="m-0 p-0 text-elipse">PRICE</p>-->
<!--                    </button>-->
<!--                    <button type="button" onclick="arr_hide('price,packing_tab');arr_show('stock')" class="rounded-0 btn_p_more_nav mr-1">-->
<!--                        <p class="m-0 p-0 text-elipse">STOCK</p>-->
<!--                    </button>-->
<!--                    <button type="button" onclick="arr_hide('price,stock');arr_show('packing_tab')" class="rounded-0 btn_p_more_nav mr-1">-->
<!--                        <p class="m-0 p-0 text-elipse">Packing</p>-->
<!--                    </button>-->
                    <?php
                        $buttons = $db->db_connect()->query("SELECT * FROM system_buttons where module = 'inventory' AND sub_module = 'products' AND sub_sub_module = 'product_details' AND status = 1");
                        while($button = $buttons->fetch(PDO::FETCH_ASSOC)):
                    ?>
                            <button
                                    type="button"
                                    class="rounded-0 btn_p_more_nav mr-1"
                                    id="<?php echo $button['elem_id'] ?>"
                                    name="<?php echo $button['elem_name'] ?>"
                                    onclick="prodScreen(this.id)"
                                    screen_type = 'gello'
                            >
                                <p class="m-0 p-0 text-elipse"><?php echo $button['descr'] ?></p>
                            </button>
                    <?php endwhile; ?>
                </header>

                <article class="p-2" style="font-size: xx-small">
                    <div id="price" class="w-50 h-100">

                        <!-- TAX -->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Tax</p>
                            </div>
                            <div class="w-60 d-flex flex-wrap justify-content-between">
                                <div class="prod_inp_view w-25" id="tax_rate"></div>
                                <div class="prod_inp_view w-65" id="tax_desc"></div>
                            </div>
                        </div>

                        <!--COST PRICE-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Cost Price</p>
                            </div>
                            <div class="prod_inp_view" id="cost_price"></div>
                        </div>

                        <!-- RETAILs -->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail</p>
                            </div>
                            <div class="prod_inp_view" id="retail_price"></div>
                        </div>

                        <!--RETAIL WITHOUT TAX-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail WO/T</p>
                            </div>
                            <div class="prod_inp_view" id="retail_price_without_tax"></div>
                        </div>


                    </div>

                    <div id="stock" style="display: none;" class="w-50 h-100">
                        <!--STOCK-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Branch</p>
                            </div>
                            <div class="prod_inp_view">100</div>
                        </div>

                    </div>

                    <div id="packing_tab" style="display: none;" class="w-50 h-100">
                        <!--PACKING-->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr class="thead-light">
                                        <th class="p-1">Pack ID</th>
                                        <th class="p-1">Packing</th>
                                        <th class="p-1">Quantity</th>
                                        <th class="p-1">Purpose</th>
                                    </tr>
                                </thead>
                                <tbody id="packaginf_row">

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div id="more_barcode" style="display: none;" class="w-50 h-100">
                        <!--PACKING-->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr class="thead-light">
                                    <th class="p-1">Barcode</th>
                                    <th class="p-1">Description</th>
                                </tr>
                                </thead>
                                <tbody id="more_barcode_row">

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- SUPPLIERS -->
                    <div id="more_supplier" style="display: none;" class="w-50 h-100">
                        <!--PACKING-->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr class="thead-light">
                                    <th class="p-1">Code</th>
                                    <th class="p-1">Description</th>
                                    <th class="p-1">Level</th>
                                </tr>
                                </thead>
                                <tbody id="more_supplier_row">

                                </tbody>
                            </table>
                        </div>

                    </div>

                </article>

            </div>

        </div>

    </div>

</div>