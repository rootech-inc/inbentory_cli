<?php

    require '../includes/core.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = $anton->post('function');
            if($function === 'set_session')
            {
                // get form data
                $session_data = $_POST['session_data'];
                print_r($session_data);
                $anton->set_session($session_data);
            }

            elseif ($function === 'row_count') // count rows
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];

                echo $db->row_count("$table","$condition");

            }

            elseif ($function === 'query')
            {
                $query = $_POST['query'];
                echo($query);
                $db->db_connect()->exec($query);
            }

            elseif ($function === 'get_row')
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];
                $res =  $db->get_rows($table,$condition,'json');
                print_r($res);
            }

            elseif ($function === 'insert') // insert into table
            {
                $query = $_POST['query'];
//                echo($query);
                try {
                    $db->db_connect()->exec($query);
                    echo '1';
                } catch (PDOException $e){
                    echo $e->getMessage();
                }
            }
        }
    }
