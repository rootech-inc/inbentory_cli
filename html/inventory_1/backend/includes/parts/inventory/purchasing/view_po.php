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
                    <i class="fa fa-home"></i>
                </button>

                <!--ADD-->
                <button onclick="set_session(['action=new'])"  type="button" title="New PO" class="header_icon btn-light mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-1">
                    <img
                            src="../../assets/icons/home/new_property.png"
                            class="img-fluid"
                    >
                </button>

                <!--DELETE-->
                <button id="delete_button" onclick="delete_po()" type="button" title="Delete" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/delete_document.png"
                            class="img-fluid"
                    >
                </button>



                <!--EDIT-->
                <button id="edit_button" onclick="set_session(['action=edit','po_number='+$('#po_number').text()])" type="button" title="Edit" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/edit_property.png"
                            class="img-fluid"
                    >
                </button>

                <!--SORT LEFT-->
                <button type="button" onclick="previewPoTrans(this.value)" value="" id="sort_left" title="Sort Left" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_left.png"
                            class="img-fluid"
                    >
                </button>

                <!--SORT RIGHT-->
                <button onclick="previewPoTrans(this.value)" value="xx" type="button" id="sort_right" title="Sort Right" class="header_icon d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/sort_right.png"
                            class="img-fluid"
                    >
                </button>

            </div>

            <!--HEADER RIGHT-->
            <div class="w-50 d-flex flex-wrap align-content-center justify-content-end pr-2 h-100 overflow-hidden">
                <input style="width: 150px; height: 20px; font-size: small; display: none!important" autocomplete="off" class="form-control form-control-sm mr-2" id="po_search">
                <!--SEARCH-->
                <button type="button" onclick="searchTrigger()" title="Search" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/search_property.png"
                            class="img-fluid"
                    >
                </button>

                <!-- PRINT -->
                <button type="button" onclick="print_po()" title="Print" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/print.png"
                            class="img-fluid"
                    >
                </button>

                <!-- APPORVE -->
                <button type="button" id="approve_button" onclick="approve_po()" title="Approve" class="header_icon mr-1 d-flex flex-wrap align-content-center justify-content-center btn p-0">
                    <img
                            src="../../assets/icons/home/approve.png"
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
                            <div class="prod_inp_view w-25" id="loc_id"></div>
                            <div class="prod_inp_view w-65" id="location">Description</div>
                        </div>
                    </div>

                    <!--SUPPLIER-->
                    <div class="w-100 d-flex flex-wrap prod_inp_container">
                        <div class="prod_inp_descriptio d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Supplier</p>
                        </div>
                        <div class="prod_inp_view" id="supplier"></div>
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
                        <div class="prod_inp_view text-elipse" id="remarks"></div>
                    </div>

                    <i id="document_stat" class="">Pending</i>



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
                        <div class="prod_inp_view" id="edited_on"></div>
                    </div>

                    <!-- APPROVE INFO -->
                    <div id="approved_container" class="text-info text-right"><i id="approved_msg">Not Approved</i></div>

                </div>

            </div>

            <!--Bottom-->
            <div class="w-100 h-60 overflow-hidden prod_button">

                <table class="table table-sm table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>LN</th>
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>Pack ID</th>
                            <th>Pack Desc</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody id="po_items_list">

                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>
    <script>

    </script>