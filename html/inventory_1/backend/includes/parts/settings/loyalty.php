<main class="p-0 mx-auto">
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">

            <!--Core Nav-->
            <?php include 'backend/includes/parts/core/nav/nav.php'?>

            <!-- COre Work Space-->
            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
                <div class="ant-bg-dark w-75 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">

                    <article class="d-flex flex-wrap align-content-start justify-content-between overflow-auto">

                        <button data-toggle="modal" data-target="#newLtyCustomer" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">NEW CUSTOMER</p></button>
                        <div class="modal" id="newLtyCustomer">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <strong class="modal-title">LOYALTY CUSTOMER REGISTRATION</strong>
                                        <button class="btn close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">

                                        <label for="full_name">Full name</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="full_name">
                                        <label for="mobile">Mob.</label><input type="tel" autocomplete="off" class="form-control rounded-0 mb-3" id="mobile">
                                        <label for="email">Email Address</label><input autocomplete="off" type="email" class="form-control rounded-0 mb-3" id="email">

                                    </div>
                                    <div class="modal-footer">
                                        <button data-dismiss="modal" class="btn btn-warning btn-sm rounded-0">CANCEL</button>
                                        <button id="saveNewLoyalty" class="btn btn-success btn-sm rounded-0">SAVE</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="master_button btn m-2 p-1 pointer" disabled></button>


                    </article>

                </div>

            </div>

        </div>

    </div>

</main>