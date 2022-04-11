<?php
    require '../../includes/core.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = $anton->post('function');
            if($function === 'new_category') // adding category
            {

                $description = $anton->post('description');
                $short_description = $anton->post('short_description');
                $tax_group = $anton->post('tax_group');
                $owner = $myName;
                $date_created = $anton->post('date_created');
                $last_modified_by = $myName;
                $last_date_modified = $anton->post('last_date_modified');
                $uni = md5($description.$today);

                // add to database
                $sql = "insert into item_group (group_name,shrt_name,tax_grp, owner, grp_uni, modified_by)
                        values ('$description','$short_description','$tax_group','$owner','$uni','$owner');";

                if($db->row_count('item_group',"`group_name` = '$description'") > 0 )
                {
                    $anton->err("Category Exist");
                    exit();
                }
                else
                {
                    $db->db_connect()->exec($sql);
                    $anton->done("Group Added Successfully");
                }



            }

            elseif ($function === 'load_category') // load single category
            {
                $target = $anton->post('target');

                $result = $db->get_rows('item_group',"`id` = '$target'");
                $tax_id = $result['tax_grp'];

                $code = $result['id'];
                $description = $result['group_name'];
                $short_description = $result['shrt_name'];
                $tax_group = $db->get_rows('tax_master',"`id` = '$tax_id'")['description'];
                $owner = $result['owner'];
                $date_created = $result['date_created'] . ' ' . $result['time_added'];
                $modified_by = $result['modified_by'];
                $date_modified = $result['date_modified'] . ' ' . $result['time_modified'];


                // get subs
//                $sub = $db->get_rows('item_group_sub',"`parent` = $code",'json');



                $return = "$code^$description^$short_description^$tax_group^$owner^$date_created^$modified_by^$date_modified";
                $anton->done($return);

            }

            elseif ($function === 'new_item_sub_group') // add category
            {
                $parent = $anton->post('parent');
                $desc = $anton->post('desc');

                try {
                    $db->db_connect()->exec(
                        "INSERT INTO item_group_sub (parent, description, owner)
                                VALUES ('$parent', '$desc', '$myName')"
                    );
                    echo $_SERVER['HTTP_REFERER'];
                    header("Location:".$_SERVER['HTTP_REFERER']);
                    $anton->done('item_group_sub_added');
                } catch (PDOException $e)
                {
                    $anton->err($e->getMessage());
                }

            }

            elseif ($function === 'category_search')
            {
                // search category
                $string = $anton->post('query');
                $row_count = $db->row_count('item_group',"`group_name` LIKE '%$string%'");
                if($row_count > 0)
                {
                    // get array of items
                    $s_result = $db->db_connect()->query("SELECT * FROM `item_group` where `group_name` LIKE '%$string%'");
                    $sn = 0;
                    $row = "";
                    // loop though and populate rows for table
                    while ($group = $s_result->fetch(PDO::FETCH_ASSOC))
                    {
                        $desc = $group['group_name'];
                        $id = $group['id'];
                        $sn ++;
                        $row .= "<tr class='pointer' onclick='loadCategory($id)'><td class='w-20'>$sn</td><td class='w-80'>$desc</td></tr>";

                    }
                    $anton->done($row);

                } else
                {
                    // leave box empty
                }
            }

            elseif ($function === 'edit_category')
            {
                print_r($_POST);

                // update name
                $code = $anton->post('code');
                $desc = $anton->post('desc');
                $short_description = $anton->post('short_description');
                $tax = $anton->post('tax');

                print_r($_POST);

                $sql = "UPDATE `item_group` SET `group_name` = '$desc', `shrt_name` = '$short_description', `tax_grp` = '$tax', `modified_by` = '$myName', `date_modified` = current_date(), `time_modified` = current_time() where `id` = '$code'";

                if($db->db_connect()->exec($sql))
                {
                    // update subs
                    if(isset($_POST['group_sub']))
                    {
                        foreach ($_POST['group_sub'] as $kay => $value)
                        {
                            // update sub and set new name
                            $db->db_connect()->exec("update `item_group_sub` set `description` = '$value' where `id` = $kay");

                        }
                    }
                }



            }

        }
    }
