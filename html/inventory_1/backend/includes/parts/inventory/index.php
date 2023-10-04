<div class="modal" id="uploadOb">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title">UPLOAD FILE</strong>
            </div>
            <div class="modal-body">
                <input type="file" id='csvFileInput' accept=".csv" require  class="form-control">
            </div>
            <div class="modal-footer">
                <button id='processOb' class="btn btn-success">PROCESS</button>
            </div>
        </div>
    </div>
</div>
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
                            <button onclick="download_products()" class="master_button btn  m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">Download</p></button>
                            <button data-toggle='modal' data-target='#uploadOb' class="master_button btn  m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">OPENING BALANCE</p></button>
                        </article>

                    </div>
                <?php endif; ?>

                <?php if($sub_module === 'category'): ?>

                    <?php if($action === 'view'): ?>

                        <?php



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
                        { ?>

                        pCategory.LoadScreen('ini',1)

                        <?php }
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

                <?php if($sub_module === 'purchasing'):
                    {
                        if($action === 'view')
                        {
                            # check if there is po
                            $po_count = $db->row_count('po_hd',"none");
                            if($po_count > 0)
                            {
                                // get first po
                                $po_sql = $db->db_connect()->query("SELECT * FROM `po_hd` order by id desc LIMIT 1");
                                $po_res = $po_sql->fetch(PDO::FETCH_ASSOC);

                                $po_id = $po_res['id'];
                                $po_number = $po_res['doc_no'];
                            }
                            else{
                                $anton->set_session(['action=new']);
                                echo "<script>location.reload()</script>";
                            }
                            // view purchasing order
                            require 'backend/includes/parts/inventory/purchasing/view_po.php';
                        }

                        elseif ($action === 'new'){ // new purchasing order
                            $suppliers = $db->db_connect()->query("SELECT * FROm supp_mast order by supp_name asc");
                            $locations = $db->db_connect()->query("SELECT * FROM loc");
                            require 'backend/includes/parts/inventory/purchasing/new_po.php';
                        }

                        elseif ($action === 'edit'){ // new purchasing order

                            require 'backend/includes/parts/inventory/purchasing/edit_po.php';
                        }
                    }
                ?>
                        <script src="js/po.js"></script>

                <?php
                    if($action === 'new')
                    {
                        echo "<script>loadPoTrans()</script>";
                    } elseif ($action === 'view')
                    {

                        echo "<script>previewPoTrans('$po_number')</script>";
                    } elseif ($action === 'edit')
                    {
                        $po_number = $anton->get_session('po_number');
                        echo "<script>editPoTrans('$po_number')</script>";
                    }
                endif; ?>

                <?php if($sub_module === 'receiving'):
                    {
                        if($action === 'view')
                        {
                            # check if there is po
                            $grn_count = $db->row_count('grn_hd',"none");
                            if($grn_count > 0)
                            {
                                // get first po
                                $grn_sql = $db->db_connect()->query("SELECT * FROM `grn_hd` order by id desc LIMIT 1");
                                $grn_res = $grn_sql->fetch(PDO::FETCH_ASSOC);

                                $grn_id = $grn_res['id'];
                                $grn_number = $grn_res['entry_no'];
                            }
                            else{
                                $anton->set_session(['action=new']);
                                echo "<script>location.reload()</script>";
                            }
                            // view purchasing order
                            require 'backend/includes/parts/inventory/receiving/view_grn.php';
                        }

                        elseif ($action === 'new'){ // new grn

                            require 'backend/includes/parts/inventory/receiving/new.php';
                        }

                        elseif ($action === 'edit'){ // edit grn

//                            print_r($_SESSION);
//                            die();
                            require 'backend/includes/parts/inventory/receiving/edit_grn.php';

                        }
                    }
                    ?>
                    <script src="js/grn.js"></script>

                    <?php
                    if($action === 'new' && $sub_module === 'purchasing')
                    {
                        echo "<script>loadPoTrans()</script>";
                    }
                    if($action === 'new' && $sub_module === 'receiving')
                    {
//                        echo "<script>loadPoTrans()</script>";
                    }
                    elseif ($action === 'view')
                    {

                        echo "<script>viewGrn('$grn_number')</script>";
                    } elseif ($action === 'edit')
                    {
                        $entry_no = $anton->get_session('entry_no');
                        echo "<script>editGrn()</script>";
                    } 
                endif; ?>

            </div>

        </div>
</main>
<script>

$(document).ready(function() {
    $("#processOb").click(function() {
        console.log('FILE PROCESSING')
        const input = $("#csvFileInput")[0];
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                let ob_contents = e.target.result;
                let ob_lines = ob_contents.split("\n");

                // Loop through each row in the CSV file

                for (let i = 1; i < ob_lines.length; i++) {
                    let ob_product = ob_lines[i].split(",");
                    
                    let ob_barcode,ob_name,ob_tax_code,ob_cost,ob_retail,ob_supplier,ob_group,
                    ob_sub_group,ob_md5;
                    
                    ob_barcode = ob_product[0];
                    ob_name = ob_product[1];
                    ob_tax = ob_product[2];
                    ob_cost = ob_product[3];
                    ob_retail = ob_product[4];
                    ob_supplier = ob_product[5];
                    ob_group = ob_product[6];
                    ob_sub_group = ob_product[7];
                    ob_md5 = ob_barcode;

                    if(ob_barcode.length > 0){
                      
                        // validate if products exist
                        let p_count = row_count('prod_master',`barcode = '${ob_barcode}'`);
                        if(p_count === 0){
                            // insert
                            let q = `insert into prod_master (barcode,item_uni,`+"`group`"+`,sub_group,supplier,item_desc,item_desc1,cost,retail,tax,owner)
                            values ('${ob_barcode}','${ob_barcode}','${ob_group}','${ob_sub_group}','${ob_supplier}','${ob_name}','${ob_name}','${ob_cost}','${ob_retail}','${ob_tax}','opening stock')`;
                            console.log(q);
                            exec(q);
                        }
                    }


                }
            };

            reader.readAsText(file);
        } else {
            alert("Please select a CSV file.");
        }
    });
});

</script>

