<main class="p-0 mx-auto">
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">

            <!--Core Nav-->
            <?php include 'backend/includes/parts/core/nav/nav.php'?>

            <!-- COre Work Space-->
            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">

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

                            <button onclick="User.CreateGroup()" id="new_property" title="New" type="button" class="btn p-0">
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

                                </div>
                                <div class="w-50 pl-2">
                                    <button onclick="windowPopUp('/backend/includes/parts/add-ons/user-permissions.php','User Permissions',1024,678)" class="btn btn-danger rounded-0">PERMISSIONS</button>
                                </div>

                            </div>


                        </article>

                    </div>
                </div>

            </div>

        </div>

    </div>

</main>

<script>
    User.LoadGroupsScreen()
</script>