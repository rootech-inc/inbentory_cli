<!--VIEW CATEGORY-->
<div class="ant-bg-dark w-95 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-75 ant-round">
    <div class="w-100 h-100 d-flex flex-wrap align-content-between">
        <header class="inside_card_header pl-3 p-1 pr-1 d-flex flex-wrap align-content-center">

            <!-- EXIT -->
            <button onclick="set_session(['sub_module=home'])" title="Exit" type="button" class="btn p-0">
                <img
                        src="assets/icons/home/exit.png"
                        class="img-fluid"
                >
            </button>

            <button onclick="set_session(['action=new'])" id="new_property" title="New" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/new_property.png"
                        class="img-fluid"
                >
            </button>

            <button id="delete_property" onclick="delete_item('product_category',12)" title="Delete" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/delete_document.png"
                        class="img-fluid"
                >
            </button>

            <button id="edit_property" onclick="edit_item('product_group',$('#code').text())" title="Edit" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/edit_property.png"
                        class="img-fluid"
                >
            </button>

            <button id="save_property" title="Save" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/save_close.png"
                        class="img-fluid"
                >
            </button>

            <button id="cancel_property_edit" title="Cancel" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/cancel.png"
                        class="img-fluid"
                >
            </button>
            <button id="sort_left" value="<?php echo $last ?>" onclick="item_sort('product_category','left',$('#code').text())" title="Sort Left" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/sort_left.png"
                        class="img-fluid"
                >
            </button>

            <button id="sort_right" value="<?php echo $last ?>" onclick="item_sort('product_category','right',this.value)" title="Sort Right" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/sort_right.png"
                        class="img-fluid"
                >
            </button>

            <!-- SUB -->
            <button onclick="newSub('item_group_sub',$('#desc').text())" title="New Sub" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/insert_row.png"
                        class="img-fluid"
                >
            </button>

            <!-- SEARCH -->
            <button data-toggle="modal" data-target="#catSearchBox" title="New Sub" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/finder.png"
                        class="img-fluid"
                >
            </button>
            <div class="modal fade" id="catSearchBox">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg_search rounded-0">
                        <div class="modal-header">
                            <input id="categorySearch" autocomplete="off" type="text" placeholder="search_query" class="form-control">
                        </div>
                        <div class="modal-body overflow-hidden" style="height: 50vh; padding: 1px !important">
                            <div class="w-100 h-100 m-0 table-responsive">
                                <table class="table table-sm">
                                    <tbody id="catRes">
                                        <tr class='pointer' onclick="loadCategory(1)">
                                            <td>SN</td>
                                            <td>Description</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </header>

        <article id="category_form" class="inside_card_body p-1 overflow-hidden">
            <div class="h-50 w-100 p-2 mt-2 d-flex flex-wrap">
                <div class="w-50 pr-2">
                    <!-- CODE -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Code</p>
                        </div>
                        <div id="code" class="prod_inp"></div>
                    </div>

                    <!-- DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <div id="desc" class="prod_inp"></div>
                    </div>

                    <!-- SHORT DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc.</p>
                        </div>
                        <div id="short_desc" class="prod_inp"></div>
                    </div>

                    <!-- TAX GRP -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Tax Grp</p>
                        </div>
                        <div id="tax" class="prod_inp">

                        </div>
                    </div>

                </div>

                <div class="w-50 pl-2">
                    <!-- CODE -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Owner</p>
                        </div>
                        <div id="owner" class="prod_inp"></div>
                    </div>

                    <!-- DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Date Created</p>
                        </div>
                        <div id="date_created" class="prod_inp"></div>
                    </div>

                    <!-- SHORT DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Mod By</p>
                        </div>
                        <div id="modified" class="prod_inp"></div>
                    </div>

                    <!-- TAX GRP -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Mod Date</p>
                        </div>
                        <div id="date_mod" class="prod_inp"></div>
                    </div>


                </div>
            </div>

            <div class="w-100 h-45 overflow-hidden">
                <div id="categorySub" class="w-100 p-2 d-flex bg_more_table border-info flex-wrap align-content-center justify-content-center h-100 overflow-hidden">
                    <div class="spinner-border spinner-grow-sm"></div>
                </div>
            </div>

        </article>

    </div>
</div>

<script>
    loadCategory(1)
</script>