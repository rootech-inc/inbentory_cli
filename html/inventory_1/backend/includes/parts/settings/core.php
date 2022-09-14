<?php

    if($sub_module === 'system'){
        require 'backend/includes/parts/settings/home.php';
    } else
    {
        print_r($_SESSION);
    }
