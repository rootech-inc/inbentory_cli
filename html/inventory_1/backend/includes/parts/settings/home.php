<main class="p-0 mx-auto">
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">

            <!--Core Nav-->
            <?php include 'backend/includes/parts/core/nav/nav.php'?>

            <!-- COre Work Space-->
            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
                <div class="ant-bg-dark w-75 p-2 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">

                    <article class="d-flex flex-wrap align-content-start justify-content-between overflow-auto">

                        <button onclick="windowPopUp('backend/includes/parts/add-ons/taxMaster.php','Tax Master',700,700)" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">TAX</p></button>
                        <button onclick="windowPopUp('backend/includes/parts/add-ons/supMaster.php','Tax Master',700,700)" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">SUPPLIER</p></button>
                        <button onclick="windowPopUp('backend/includes/parts/add-ons/locMaster.php','Tax Master',700,700)" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">LOC</p></button>
                        <button onclick="set_session(['sub_module=user_group'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">GROUPS</p></button>
                        <button onclick="set_session(['sub_module=users','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">USER FILE</p></button>
                        <button onclick="set_session(['sub_module=loyalty','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">LOYALTY</p></button>
                        <button onclick="set_session(['sub_module=customers','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">CUSTOMERS</p></button>
                        <button onclick="set_session(['sub_module=company_setup','action=view'])" class="master_button btn m-2 p-1 pointer"><p class="m-0 p-0 text-elipse">COMPANY</p></button>

                        <button class="master_button btn m-2 p-1 pointer" disabled></button>


                    </article>

                </div>

            </div>

        </div>

    </div>

</main>