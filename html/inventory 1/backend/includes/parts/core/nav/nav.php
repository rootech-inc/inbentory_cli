<!--Core Nav-->
<div class="col-sm-3 h-100 p-2 ant-bg-dark">

    <!-- MACHIEN NUMBER AND LOGOUT-->
    <div class="mach_num_and_exit mb-4 d-flex flex-wrap align-content-center justify-content-between">
        <div class="mach_num d-flex flex-wrap align-content-center justify-content-center">
            <?php echo $db->machine_number() ?>
        </div>

        <button id="logout" class="logout btn rounded-0 text-light d-flex flex-wrap align-content-center justify-content-center fa fa-power-off">

        </button>
    </div>

    <div onclick="set_session(['module=billing'])" class="min_button mb-4 d-flex flex-wrap align-content-center justify-content-center">
        <p class="m-0 text-elipse text-center p-0">Billing</p>
    </div>

    <div onclick="set_session('module=reports,sub_module=reports')" class="min_button mb-4 d-flex flex-wrap align-content-center justify-content-center">
        <p class="m-0 text-elipse text-center p-0">Reports</p>
    </div>

    <div onclick="set_session('module=inventory,sub_module=invetory')" class="min_button mb-4 d-flex flex-wrap align-content-center justify-content-center">
        <p class="m-0 text-elipse text-center p-0">inventory</p>
    </div>

    <div onclick="set_session('module=system,sub_module=system')" class="min_button mb-4 d-flex flex-wrap align-content-center justify-content-center">
        <p class="m-0 text-elipse text-center p-0">System</p>
    </div>

</div>