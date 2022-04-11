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
    <form class="w-100 h-100 product_container p-3">
        <div class="d-flex flex-wrap align-content-center product_header">

            <!--HEADER LEFT-->
            <div class="w-50 d-flex flex-wrap align-content-center pl-2 h-100 overflow-hidden">

                <!-- EXIT -->
                <button onclick="set_session('sub_module=inventory')" title="Exit" type="button" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/exit.png"
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
                <button disabled type="button" title="Save" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/save_close.png"
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

                <!--SORT LEFT-->
                <button type="button" onclick="item_sort('product','left')" title="Sort Left" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_left.png"
                            class="img-fluid"
                    >
                </button>

                <!--SORT RIGHT-->
                <button onclick="item_sort('product','right')" type="button" title="Sort Right" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_right.png"
                            class="img-fluid"
                    >
                </button>

            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <!--ADD-->
                <button type="button" onclick="gen_modal('search_box')" title="Search" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/search_property.png"
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
                            <p class="m-0 p-0 text-elipse">Category</p>
                        </div>
                        <div class="prod_inp_view">Category</div>
                    </div>

                    <!--SUB CAT-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Sub Category</p>
                        </div>
                        <div class="prod_inp_view">Sub Category</div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <div class="prod_inp_view">Supplier</div>
                    </div>

                    <!--Barcode-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Barcode</p>
                        </div>
                        <div class="prod_inp_view">02156423412</div>
                    </div>

                    <!--Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <div class="prod_inp_view">Description</div>
                    </div>

                    <!--Short Description-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc</p>
                        </div>
                        <div class="prod_inp_view">Short Description</div>
                    </div>

                </div>
                <!--Prod Right-->
                <div class="w-50 h-100 p-2 overflow-hidden">
                    <!--Packaging-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Packaging</p>
                        </div>
                        <div class="prod_inp_view">Packaging</div>
                    </div>

                    <!--Expiry-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Expiry</p>
                        </div>
                        <div class="w-60 d-flex flex-wrap justify-content-between">
                            <div class="prod_inp_view w-25">YES</div>
                            <div class="prod_inp_view w-65">00/00/0000</div>
                        </div>
                    </div>

                    <!--Owner-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Owner</p>
                        </div>
                        <div class="prod_inp_view">Owner</div>
                    </div>

                    <!--Date Created-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Date Created</p>
                        </div>
                        <div class="prod_inp_view">00/00/0000</div>
                    </div>

                    <!--Last Edited-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Edited</p>
                        </div>
                        <div class="prod_inp_view">00/00/0000</div>
                    </div>
                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-50 overflow-hidden prod_button d-flex flex-wrap">

                <header class="w-100 h-20 d-flex flex-wrap overflow-hidden">
                    <button type="button" onclick="i_hide('stock');i_show('price')" autofocus class="btn rounded-0 btn_p_more_nav w-15">
                        <p class="m-0 p-0 text-elipse">PRICE</p>
                    </button>
                    <button type="button" onclick="i_hide('price');i_show('stock')" class="btn rounded-0 btn_p_more_nav w-15">
                        <p class="m-0 p-0 text-elipse">STOCK</p>
                </header>

                <article class="p-2">
                    <div id="price" class="w-50 h-100">
                        <!--COST PRICE-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Cost Price</p>
                            </div>
                            <div class="prod_inp_view">100.00</div>
                        </div>

                        <!--RETAIL WITHOUT TAX-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail WO/T</p>
                            </div>
                            <div class="prod_inp_view">150.00</div>
                        </div>

                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Retail</p>
                            </div>
                            <div class="prod_inp_view">150.00</div>
                        </div>

                        <!--TAX-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Tax Group</p>
                            </div>
                            <div class="prod_inp_view">V3</div>
                        </div>

                    </div>

                    <div id="stock" style="display: none;" class="w-50 h-100">
                        <!--COST PRICE-->
                        <div class="w-100 d-flex flex-wrap prod_inp_container">
                            <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                                <p class="m-0 p-0 text-elipse">Branch</p>
                            </div>
                            <div class="prod_inp_view">100</div>
                        </div>

                    </div>
                </article>

            </div>

        </div>

    </form>

</div>