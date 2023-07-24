ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



<?php

require 'backend/includes/core.php';
(new \mechconfig\MechConfig)->validate_device();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMHOS - CLI</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="/css/keyboard.css">
    <link rel="icon" type="image/png" href="/assets/logo/logo.ico">


    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/query.js"></script>
    <script src="/js/classes/session.js"></script>
    <script src="/js/classes/j_query_supplies.js"></script>
    <script src="/js/classes/db_trans.js"></script>
    <script src="/js/classes/system.js"></script>
    <script src="/js/classes/inventory.js"></script>
    <script src="/js/classes/screen.js"></script>

    <script src="/js/sweetalert2@11.js"></script>

    <link rel="stylesheet" href="/css/sweetalert.min.css">

    <script src="/js/error_handler.js"></script>
    <script src="/js/anton.js"></script>
    <script src="/js/keyboard.js"></script>

    <script src="/js/classes/buttons.js"></script>
    <script src="/js/classes/bill.js"></script>
    <script src="/js/trigger.js"></script>
    <script src="/js/classes/reports.js"></script>
    <script src="/js/classes/Evat.js"></script>
    <script src="/js/classes/tax.js"></script>
    <script src="/js/classes/loyalty.js"></script>

    <link rel="stylesheet" href="/css/anton.css">










</head>
<body onload="initialize()" onresize="validateSize('yes')" class="abs_1 p-0 d-flex flex-wrap align-content-center">
