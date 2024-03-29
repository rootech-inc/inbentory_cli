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

    <script src="/js/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/sweetalert.min.css">

    <script src="/js/error_handler.js"></script>
    <script src="/js/anton.js"></script>
    <script src="/js/keyboard.js"></script>

    <script src="/js/classes/buttons.js"></script>

    <link rel="stylesheet" href="/css/anton.css">



</head>

<body style="height: 100vh; overflow: hidden">
    <header class="p-2">
        <button data-toggle="modal" onclick="taxMaster.Create()" class="btn btn-info rounded-0 btn-sm">New Tax Group</button>
        <div class="modal fade" id="newTaxGroup">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <label class="w-100">Description <span class="text-danger">*</span>
                            <input type="text" autocomplete="off" required name="description" class="form-control rounded-0 form-control-sm mb-2">
                        </label>

                        <label class="w-100">Rate <span class="text-danger">*</span>
                            <input type="number" autocomplete="off" required name="rate" class="form-control rounded-0 form-control-sm mb-2">
                        </label>

                    </div>

                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-sm btn-warning rounded-0" type="button">CANCEL</button>
                        <button class="btn btn-success btn-sm rounded-">SAVE</button>
                    </div>

                </div>
            </div>
        </div>
    </header>
    <article>
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="taxScreen">

            </tbody>
        </table>
    </article>
</body>

</html>

<script>

    taxMaster.LoadScreen()

</script>
