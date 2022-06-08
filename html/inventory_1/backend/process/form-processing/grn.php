<?php
    require '../../includes/core.php';

    if(isset($_POST['function'])) // if we are posting a form with function
    {
        $function = $anton->post('function'); // function values

        if($function === 'new_grn') // save a new grn
        {
            // get grn_hd_values
            $loc_id = $anton->post('loc_id');
            $supp_id = $anton->post('supp_id');
            $ref_doc = $anton->post('ref_doc');
            $tax_grp = $anton->post('tax_grp');
            $remarks = $anton->post('remarks');
            $total_amount = $anton->post('total_amount');

            print_r($_POST);

        }
        elseif ($function === 'search_grn_item')
        {
            $query = $anton->post('search_query');
            $supp_id = $anton->post('supp_id');
            $search_result = $db->grn_list_item($query,$supp_id);
            var_dump($search_result);
            echo("DONE");
        }

    }