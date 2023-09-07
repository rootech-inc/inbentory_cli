<?php

    if($sub_module === 'system'){

        require 'backend/includes/parts/settings/home.php';
    } elseif ($sub_module === 'user_group'){
        require 'backend/includes/parts/settings/user-group.php';
    } elseif ($sub_module === 'users')
    {
        require 'backend/includes/parts/settings/users.php';
    } elseif ($sub_module === 'loyalty'){
        require 'backend/includes/parts/settings/loyalty.php';
    }
    elseif ($sub_module === 'customers'){
        require 'backend/includes/parts/settings/customers.php';
    } elseif ($sub_module === 'company_setup'){
        require 'backend/includes/parts/settings/company_setup.php';
    }
    else
    {
        print_r($_SESSION);
    }
