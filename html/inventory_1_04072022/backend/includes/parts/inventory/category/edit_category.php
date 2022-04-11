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


            <button id="save_property" title="Save" type="submit" form="update_group" class="btn p-0">
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

        </header>

        <form id="update_group" method="post" action="backend/process/form-processing/category-form-process.php" class="inside_card_body p-1 w-100 overflow-hidden">
            <input type="hidden" name="function" value="edit_category">
            <div class="h-30 w-100 p-2 mt-2 d-flex flex-wrap">
                <div class="w-50 pr-2">
                    <!-- CODE -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Code</p>
                        </div>
                        <input type="text" readonly required autocomplete="off" id="code" name="code" class="prod_inp">
                    </div>

                    <!-- DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <input type="text" onkeyup="catDesc(this.value)" required autocomplete="off" id="desc" name="desc" class="prod_inp">
                    </div>

                    <!-- SHORT DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc.</p>
                        </div>
                        <input type="text" required autocomplete="off" id="short_description" name="short_description" class="prod_inp">
                    </div>

                    <!-- TAX GRP -->
                    <div id="tax_res" class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Tax Grp</p>
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

            <div class="w-100 h-70 overflow-hidden">
                <div id="categorySub" class="w-100 p-2 d-flex bg_more_table border-info flex-wrap align-content-center justify-content-center h-100 overflow-hidden">
                    <div class="spinner-border spinner-grow-sm"></div>
                </div>
            </div>

        </form>

    </div>
</div>