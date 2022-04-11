<!--NEW CATEGORY-->
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

            <button id="edit_property" onclick="set_session('stage=edit')" title="Edit" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/edit_property.png"
                        class="img-fluid"
                >
            </button>

            <button id="save_property" title="Save" type="submit" form="general_form" class="btn p-0">
                <img
                        src="../../assets/icons/home/save_close.png"
                        class="img-fluid"
                >
            </button>

            <button  onclick="set_session(['action=view'])" id="cancel_property_edit" title="Cancel" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/cancel.png"
                        class="img-fluid"
                >
            </button>
            <button id="sort_left" onclick="item_sort('product_category','left')" title="Sort Left" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/sort_left.png"
                        class="img-fluid"
                >
            </button>

            <button id="sort_right" onclick="item_sort('product_category','right')" title="Sort Right" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/sort_right.png"
                        class="img-fluid"
                >
            </button>

            <!-- SUB -->
            <button id="sub_categories" onclick="gen_modal('category_sub','Category Sub')" title="Sub Categories" type="button" class="btn p-0">
                <img
                        src="../../assets/icons/home/sub.png"
                        class="img-fluid"
                >
            </button>
        </header>

        <article id="category_form" class="inside_card_body p-1 overflow-hidden">
            <form method="post" action="backend/process/form-processing/category-form-process.php" id="general_form" class="h-50 w-100 p-2 mt-2 d-flex flex-wrap">
                <!-- DETAILS -->
                <input type="hidden" name="function" value="new_category">
                <div class="w-50 pr-2">
                    <!-- CODE -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Code</p>
                        </div>
                        <input type="text" class="prod_inp" value="Auto Generated" disabled>
                    </div>

                    <!-- DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Description</p>
                        </div>
                        <input onkeyup="catDesc(this.value)" type="text" autofocus autocomplete="off" name="description" class="prod_inp" required>
                    </div>

                    <!-- SHORT DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Short Desc.</p>
                        </div>
                        <input type="text" autocomplete="off" maxlength="10" name="short_description" id="short_description" class="prod_inp" required>
                    </div>

                    <!-- TAX GRP -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Tax Grp</p>
                        </div>
                        <select name="tax_group" id="" class="prod_inp">
                            <?php while($tax = $taxes->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $tax['id'] ?>"><?php echo $tax['description'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>

                <!-- META -->
                <div class="w-50 pl-2">
                    <!-- CODE -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Owner</p>
                        </div>
                        <input type="text" name="owner" class="prod_inp" value="<?php echo $myName ?>" readonly>
                    </div>

                    <!-- DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Date Created</p>
                        </div>
                        <input type="date" name="date_created" class="prod_inp" value="<?php echo $today ?>" readonly>
                    </div>

                    <!-- SHORT DESC -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Mod By</p>
                        </div>
                        <input type="text" name="last_modified_by" class="prod_inp" value="<?php echo $myName ?>" readonly>
                    </div>

                    <!-- TAX GRP -->
                    <div class="prod_inp_container d-flex flex-wrap">
                        <div class="inp_text d-flex flex-wrap align-content-center">
                            <p class="m-0 p-0 text-elipse">Last Mod Date</p>
                        </div>
                        <input type="date" name="last_date_modified" class="prod_inp" value="<?php echo $today ?>" readonly>
                    </div>


                </div>
            </form>
        </article>

    </div>
</div>