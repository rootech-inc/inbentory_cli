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
    <form method="post" action="backend/process/form-processing/produc-master-form.php" id="general_form" class="w-100 h-100 product_container">
        <div class="d-flex flex-wrap align-content-center product_header">
            <input type="hidden" name="function" value="new_item">

            <!--HEADER LEFT-->
            <div class="w-50 d-flex flex-wrap align-content-center pl-2 h-100 overflow-hidden">


                <!--SAVE-->
                <button title="Save" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/save_close.png"
                            class="img-fluid"
                    >
                </button>

                <!--CANCEL-->
                <button onclick="set_session(['action=view'])"  type="button" title="Cancel" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/cancel.png"
                            class="img-fluid"
                    >
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
                        <select
                                name="category"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp"
                                onchange="newProductSubGroup(this.value)"
                        >
                            <option value="cat_id">Select Group</option>
                            <?php
                                while($gp = $groups->fetch(PDO::FETCH_ASSOC)){
                                    $id = $gp['id'];
                                    $name = $gp['group_name'];
                                    echo "<option value='$id'>$name</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!--SUB CAT-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Sub Group</p>
                        </div>
                        <select
                                name="sub_category" id="sub_category"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp"
                        >
                            <option value="sub_id">Sub Group</option>
                        </select>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <select
                                name="supplier" id="supplier"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp"
                        >
                            <option value="0">Supplier</option>
                            <?php
                                while($supp = $supp_mast->fetch(PDO::FETCH_ASSOC))
                                {
                                    $supp_id = $supp['supp_id'];
                                    $supp_name = $supp['supp_name'];
                                    echo "<option value='$supp_id'>$supp_name</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!--Barcode-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Barcode</p>
                        </div>
                        <input required
                                name="barcode"
                                autocomplete="off"
                                type="text"
                                class="form-control form-control-sm prod_inp"
                        >
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <input required
                                name="description"
                                autocomplete="off"
                                type="text"
                                class="form-control form-control-sm prod_inp"
                               onkeyup="catDesc(this.value)"
                        >
                    </div>

                    <!--Short Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc</p>
                        </div>
                        <input
                                required
                                name="short_description"
                                id="short_description"
                                maxlength="8"
                                autocomplete="off"
                                type="text"
                                class="form-control form-control-sm prod_inp"
                        >
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">
                    <!--Packaging-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Packaging</p>
                        </div>
                        <select
                                name="packaging"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp">

                            <?php
                            while($pack = $packaging->fetch(PDO::FETCH_ASSOC))
                            {
                                $pack_id = $pack['id'];
                                $pack_desc = $pack['desc'];
                                echo "<option value='$pack_id'>$pack_desc</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- PACKING QUANTITY -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Pack QTY</p>
                        </div>
                        <input
                                required
                                name="pack_qty"
                                id="pack_qty"
                                maxlength="8"
                                autocomplete="off"
                                type="number"
                                class="form-control form-control-sm prod_inp"
                        >
                    </div>

                    <!-- STOCK TYPE -->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Stock Type</p>
                        </div>
                        <select
                                name="stock_type"
                                autocomplete="off"
                                class="form-control form-control-sm prod_inp">

                            <?php
                                while($stock = $stock_type->fetch(PDO::FETCH_ASSOC))
                                {
                                    $stock_id = $stock['id']; $stock_desc = $stock['description'];
                                    echo "<option value='$stock_id'>$stock_desc</option>";
                                }
                            ?>

                        </select>
                    </div>

                    <!--Expiry-->
                    <div id="expiry" class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Expiry</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <select name="expiry" class="form-control form-control-sm w-25 prod_inp" id="expiry">
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                            <input
                                    name="expiry_date"
                                    autocomplete="off"
                                    type="date"
                                    class="form-control form-control-sm w-65 prod_inp"
                            >
                        </div>

                    </div>
                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-50 overflow-hidden prod_button d-flex flex-wrap">

                <header class="w-100 h-20 d-flex flex-wrap overflow-hidden">
                    <button type="button" onclick="i_hide('stock');i_show('price')" autofocus class="btn rounded-0 btn_p_more_nav w-15">
                        <p class="m-0 p-0 text-elipse">PRICE</p>
                    </button>
                </header>

                <article class="p-2">
                    <div id="price" class="w-50 h-100">

                        <!--TAX-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Tax Group</p>
                            </div>
                            <select
                                    name="tax"
                                    autocomplete="off"
                                    class="form-control form-control-sm prod_inp"
                                    id="tax" onchange="retailWithoutTax()"
                            >

                                <?php
                                    while ($tax = $tax_master->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $tax_id = $tax['attr']; $description = $tax['description']; $rate = $tax['rate'];
                                        echo "<option value='$tax_id'>$rate%  $description</option>";
                                    }
                                ?>
                            </select>
                        </div>


                        <!--COST PRICE-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Cost Price</p>
                            </div>
                            <input
                                    name="cost_price"
                                    autocomplete="off"
                                    type="text"
                                    class="form-control form-control-sm prod_inp"
                                    value="0.00"
                            >
                        </div>



                        <!-- RETAIL PRICE -->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail</p>
                            </div>
                            <input
                                    name="retail_with_tax"
                                    id="retail_with_tax"
                                    autocomplete="off"
                                    type="text"
                                    value="0.00"
                                    class="form-control form-control-sm prod_inp"
                                    onkeyup="retailWithoutTax()"
                            >
                        </div>

                        <!--RETAIL WITHOUT TAX-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail Without Tax</p>
                            </div>
                            <input readonly
                                    name="retail_without_tax"
                                    autocomplete="off"
                                    type="text"
                                    class="form-control form-control-sm prod_inp"
                                    id="retail_without_tax"

                            >
                        </div>


                    </div>
                </article>

            </div>

        </div>

    </form>

</div>