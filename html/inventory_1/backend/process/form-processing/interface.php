<?php
    require '../../includes/core.php';
    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        if(isset($_GET['api'])){

            $module = $anton->get('module');
            $action = $anton->get('action');
            $data = json_encode($anton->get('data'));

            $response = ['code'=>'unset','message'=>'unset'];

        }
    }