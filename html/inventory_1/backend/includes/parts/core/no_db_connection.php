<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/anton.css">
    <title>NO CONNECTION!!</title>
</head>
<body>
    <div class="w-100 h-100 ant-bg-light d-flex flex-wrap align-content-center justify-content-center">
        <div class="w-50 alert p-0 alert-danger card">
            <div class="card-header text-center">
                <strong class="card-title m-0 font-weight-bolder">ERROR!!</strong>
            </div>
            <div class="card-body p-2 text-center">

                Cannot connect to database, please contact System Administrator!!
                <table class="table text-left table-bordered border-dark table-sm">
                    <tr>
                        <td><strong>DB Server</strong></td>
                        <td><?php echo db_host ?></td>
                    </tr>
                    <tr>
                        <td><strong>DB Name</strong></td>
                        <td><?php echo db_name ?></td>
                    </tr>
                    <tr>
                        <td><strong>DB User</strong></td>
                        <td><?php echo db_user ?></td>
                    </tr>
                    <tr>
                        <td><strong>Debug Info</strong></td>
                        <td><?php echo $_SESSION['db_err_msg']  ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>