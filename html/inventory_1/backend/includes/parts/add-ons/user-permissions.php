<?php

    require $_SERVER['DOCUMENT_ROOT'].'/backend/includes/core.php';
    $user_group = $anton->get_session('user_group');

    $all_permissions = $db->db_connect()->query("SELECT * FROM user_access WHERE `group` = '$user_group';");

    // todo : Put user in group, and fetch permissions

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMHOS - CLI</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/anton.css">
    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="css/keyboard.css">
    <link rel="icon" type="image/png" href="/assets/logo/logo.ico">


    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/classes/session.js"></script>
    <script src="/js/classes/j_query_supplies.js"></script>
    <script src="/js/classes/db_trans.js"></script>
    <script src="/js/classes/system.js"></script>

    <script src="/js/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/sweetalert.min.css">

    <script src="/js/error_handler.js"></script>
    <script src="/js/anton.js"></script>
    <script src="/js/keyboard.js"></script>
    <script src="/js/query.js"></script>
    <script src="/js/classes/buttons.js"></script>







</head>

<body style="height: 100vh; overflow: hidden">

    <header class="inside_card_header rounded-0 pl-3 p-1 pr-1 d-flex flex-wrap align-content-center">
        <button onclick="window.close()"  title="Exit" type="button" class="btn p-0">
            <img
                src="/assets/icons/home/exit.png"
                class="img-fluid"
            >
        </button>
    </header>

    <article>
        <div class="container p-2">
            <header class="row no-gutters bg-info text-light">
                <div class="col-sm-6 border border-dark p-1">Screen</div>
                <div class="col-sm-2 p-1">Read</div>
                <div class="col-sm-2 p-1">Write</div>
                <div class="col-sm-2 p-1">Print</div>
            </header>
            <article>
                <div class="row no-gutters">
                    <div class="col-sm-6 border border-dark p-1">Screen</div>
                    <div class="col-sm-2 p-1">Read</div>
                    <div class="col-sm-2 p-1">Write</div>
                    <div class="col-sm-2 p-1">Print</div>
                </div>
            </article>
        </div>
    </article>
</body>

</html>

<script>

    taxMaster.LoadScreen()

</script>
