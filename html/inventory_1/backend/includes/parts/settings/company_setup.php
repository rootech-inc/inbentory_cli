<?php
    // get system config
    $comp_setup = $db->get_rows('comp_setup',"`id` = 1");
    // get sys settings
    $sys_settings = $db->db_connect()->query("SELECT * FROM sys_settings");
?>

<div class="modal" id="sysSettings">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title">SYSTEM SETTINGS</strong>
            </div>
            <div class="modal-body">
                <!-- NEW SETTING FORM -->
                <form action="">
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-sm-2">
                                <input required class="form-control form-control-sm" type="text" placeholder="set_key">
                            </div>
                            <div class="col-sm-3">
                                <input value="NONE" class="form-control form-control-sm" type="text" placeholder="set_val">
                            </div>
                            <div class="col-sm-3">
                                <input required class="form-control form-control-sm" type="text" placeholder="set_desc">
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control form-control-sm" name="" id="">
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                            <div class="col-sm-2 overflow-hidden">
                                <button class="btn w-100 btn-success btn-sm"><i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>

                <form action="">
                    <table class="table table-sm table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>KEY</th><th>VALUE</th><th>DESCRIPTION</th><th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($setting = $sys_settings->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $setting['set_key'] ?></td>
                                    <td><?php echo $setting['set_value'] ?></td>
                                    <td><?php echo $setting['set_desc'] ?></td>
                                    <td><input type="checkbox" checked="checked"></td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
    </div>
</div>

<main class="p-0 mx-auto">
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">

            <?php include 'backend/includes/parts/core/nav/nav.php'?>

            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
                <div class="ant-bg-dark w-100 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-100 ant-round">
                    <div class="w-100 h-100 d-flex flex-wrap align-content-between">
                        <header class="inside_card_header pl-3 p-1 pr-1 d-flex flex-wrap align-content-center">
                            <button title="new" data-toggle="modal" data-target="#sysSettings" class="btn mr-2 p-0">
                                <i class="fa fa-toolbox text-primary"></i>
                            </button>
                            <button id="prev" class="btn mr-2 p-0">
                                <i class="fa fa-backward text-info"></i>
                            </button>
                            <button id="next" class="btn mr-2 p-0">
                                <i class="fa fa-forward text-info"></i>
                            </button>
                        </header>
                        <article class="inside_card_body">
                            <div class="container p-2 h-100">
                                <div class="row h-100">
                                    <!-- COMPANY  ADDRESS -->
                                    <div class="col-sm-4">
                                        <!-- COMPANY NAME -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="c_name">Company Name </label>
                                            <input value="<?php echo $comp_setup['c_name'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="c_name"
                                                   name = 'c_name'
                                            >
                                        </div>
                                        <!-- POSTAL ADDRESS -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="box">POST BOX </label>
                                            <input value="<?php echo $comp_setup['box'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="box"
                                                   name = 'box'
                                            >
                                        </div>
                                        <!-- STREET -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="street">STREET </label>
                                            <input value="<?php echo $comp_setup['street'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="street"
                                                   name = 'street'
                                            >
                                        </div>
                                        <!-- COUNTRY -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="country">COUNTRY </label>
                                            <input value="<?php echo $comp_setup['country'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="country"
                                                   name = 'country'
                                            >
                                        </div>
                                        <!-- CITY -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="city">CITY </label>
                                            <input value="<?php echo $comp_setup['city'] ?>" disabled  autocomplete="off" type="text"
                                                   class="form-control  rounded-0 form-control-sm"
                                                   id="city"
                                                   name = 'city'
                                            >
                                        </div>
                                    </div>

                                    <!-- CONTACT -->
                                    <div class="col-sm-4">
                                        <!-- PHONE -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="phone">PHONE </label>
                                            <input value="<?php echo $comp_setup['phone'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="phone"
                                                   name = 'phone'
                                            >
                                        </div>
                                        <!-- EMAIL -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="email">EMAIL </label>
                                            <input value="<?php echo $comp_setup['email'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="email"
                                                   name = 'email'
                                            >
                                        </div>
                                        <!-- TAX CODE -->
                                        <div class="input-group mb-2">
                                            <label class="w-100" for="tax_code">TAX CODE </label>
                                            <input value="<?php echo $comp_setup['tax_code'] ?>" disabled autocomplete="off" type="text"
                                                   class="form-control rounded-0 form-control-sm"
                                                   id="tax_code"
                                                   name = 'tax_code'
                                            >
                                        </div>
                                    </div>

                                    <!-- LOGO -->
                                    <div class="col-sm-4">
                                        <div class="ant-bg-black w-100">
                                            <img src="/assets/logo/logo.png" alt="LOGO" class="img-fluid">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </article>
                    </div>


                </div>

            </div>


        </div>

    </div>
</main>