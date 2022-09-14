<?php

    if($sub_module === 'settings'){
        require 'backend/includes/parts/settings/home.php';
    } else
    {
        print_r($_SESSION);
    }
