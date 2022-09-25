<?php

    if($sub_module === 'system'){

        require 'backend/includes/parts/settings/home.php';
    } elseif ($sub_module === 'user_group'){
        require 'backend/includes/parts/settings/user-group.php';
    }
    else
    {
        print_r($_SESSION);
    }
