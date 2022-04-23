
<main class="p-0 mx-auto">
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">
            <!--Core Nav-->
            <?php include 'backend/includes/parts/core/nav/nav.php'?>

            <!-- COre Work Space-->
            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">

                <?php if($sub_module === 'home'): ?>
                    <!--INVENTORY HOME-->
                    <div class="ant-bg-dark w-75 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">

                        <article class="d-flex flex-wrap align-content-start overflow-auto">

                            <button onclick="set_session(['sub_module=category','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">Categories</p></button>
                            <button onclick="set_session(['sub_module=products','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">Products</p></button>
                            <button onclick="set_session(['sub_module=purchasing','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">Purchasing</p></button>
                            <button onclick="set_session(['sub_module=receiving','action=view'])" class="master_button btn  m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">Receive</p></button>
                        </article>

                    </div>
                <?php endif; ?>

                <?php if($sub_module === 'category'): ?>

                    <?php if($action === 'view'): ?>

                        <?php
                            // get last category
                            $active_category_sql = $db->db_connect()->query("SELECT * FROM `item_group` order by id desc LIMIT 1");

                            $ac_result = $active_category_sql->fetch(PDO::FETCH_ASSOC);
                            $last = $ac_result['id'];

                            include 'backend/includes/parts/inventory/category/category.php';
                        ?>
                        <script>
                            // disable buttons

                            let dis_buttons = "save_property,cancel_property_edit";
                            arr_disable(dis_buttons)
                        </script>
                    <?php  endif; ?>

                    <?php if($action === 'new'):
                        // get tax
                        $taxes = $db->db_connect()->query("SELECT * FROM `tax_master` WHERE `active` = 1");
                    ?>
                        <!-- NEW CATEGORY -->
                        <?php include 'backend/includes/parts/inventory/category/new_category.php'; ?>
                        <script>
                            // disable buttons
                            let dis_buttons = "new_property,delete_property,edit_property,sort_left,sort_right,sub_categories";
                            arr_disable(dis_buttons)
                        </script>

                    <?php  endif; ?>

                    <?php if($action === 'edit'): ?>

                        <?php
                        // get last category
                        $active_category_sql = $db->db_connect()->query("SELECT * FROM `item_group` order by id desc LIMIT 1");

                        $ac_result = $active_category_sql->fetch(PDO::FETCH_ASSOC);
                        $last = $anton->get_session('group');

                        include 'backend/includes/parts/inventory/category/edit_category.php';
                        ?>
                        <script>
                            // disable buttons

                            let dis_buttons = "save_property,cancel_property_edit";
                            arr_enable(dis_buttons)
                        </script>
                    <?php  endif; ?>

                    <script src="js/category.js"></script>
                    <script>
                        <?php
                        if($action === 'view')
                        {
                            echo "loadCategory($last)";
                        }
                        elseif ($action === 'edit')
                        {
                            echo "loadCategory($last,'edit')";
                        }
                        ?>
                    </script>

                <?php endif; ?>

                <?php if($sub_module === 'products'): ?>
                    <script src="js/product_master.js"></script>
                    <?php
                        if($action === 'view')
                        {
                            //check if product exist
                            $items_count = $db->row_count('prod_master','none');
                            if($items_count > 0 )
                            {
                                $last_q = $db->db_connect()->query("SELECT * FROM `prod_master` order by item_code desc limit 1");
                                $last_r = $last_q->fetch(PDO::FETCH_ASSOC);

                                $last = $last_r['item_code'];

                            }
                            else
                            {
                                // create new item
                                $anton->set_session(['action=new']);
                                $anton->reload();
                            }
                            include "backend/includes/parts/inventory/products/view_product.php";
                        }
                        elseif ($action === 'new')
                        {
                            $groups = $db->db_connect()->query("SELECT * FROM `item_group` order by group_name asc");
                            $supp_mast = $db->db_connect()->query("SELECT * FROM `supp_mast` order by `supp_name`");
                            $packaging = $db->db_connect()->query("SELECT * FROM `packaging` order by `id`");
                            $tax_master = $db->db_connect()->query("SELECT * FROM `tax_master`");
                            $stock_type = $db->db_connect()->query("SELECT * FROM `stock_type`");


                            include "backend/includes/parts/inventory/products/new_product.php";
                        } elseif ($action === 'edit')
                        {
                            $item_code = $anton->get_session('prod');

                            include "backend/includes/parts/inventory/products/edit_product.php";
                        }
                    ?>


                    <script>
                        <?php
                        if($action === 'view')
                        {
                            echo "loadProduct($last)";
                        }
                        elseif ($action === 'edit')
                        {
                            echo "loadProduct('$item_code','edit')";
                        }
                        ?>
                    </script>

                <?php endif; ?>

                <?php
                    if($sub_module === 'purchasing'):
                    {
                        if($action === 'view')
                        {
                            // view purchasing order
                            require 'backend/includes/parts/inventory/purchasing/view_po.php';
                        } elseif ($action === 'new'){ // new purchasing order
                            $suppliers = $db->db_connect()->query("SELECT * FROm supp_mast order by supp_name asc");
                            $locations = $db->db_connect()->query("SELECT * FROM loc");
                            require 'backend/includes/parts/inventory/purchasing/new_po.php';
                        }
                    }
                ?>
                        <script src="js/po.js"></script>

                <?php
                    if($action === 'new')
                    {
                        echo "<script>loadPoTrans()</script>";
                    }
                endif; ?>

            </div>

            </row>

        </div>
</main>
