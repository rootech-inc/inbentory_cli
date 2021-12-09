<?php
    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    $anton = new anton();

    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */