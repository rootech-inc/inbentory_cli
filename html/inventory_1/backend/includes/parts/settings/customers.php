<main class="p-0 mx-auto">
    <?php
        $customers_q = "select CONCAT(first_name,' ',last_name) as 'name',email,phone_number,CONCAT(country,' ',city,' ',address) as 'address',cust_no from customers;";
        $stmt = $db->db_connect()->prepare($customers_q);
        $stmt->execute();
    ?>
    <div class="container-fluid p-0 h-100">

        <div class="h-100 row p-0 no-gutters">

            <!--Core Nav-->
            <?php include 'backend/includes/parts/core/nav/nav.php'?>
            <div class="modal" id="newCustomer">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong class="modal-title">CUSTOMER REGISTRATION</strong>
                            <button class="btn close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="first_name">First Name</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="first_name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="last_name">Last Name</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="last_name">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="email">Email ID</label><input autocomplete="off" type="email" class="form-control rounded-0 mb-3" id="email">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="phone">Phone</label><input autocomplete="off" type="tel" class="form-control rounded-0 mb-3" id="phone">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="country">Country</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="country">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="city">City</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="city">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="address">Address</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="address">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="postal_code">Postal Address</label><input autocomplete="off" type="text" class="form-control rounded-0 mb-3" id="postal_code">
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-warning btn-sm rounded-0">CANCEL</button>
                            <button id="saveCustomer" class="btn btn-success btn-sm rounded-0">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- COre Work Space-->
            <div class="col-sm-10 h-100 p-3 d-flex flex-wrap align-content-center justify-content-center">
                <div class="ant-bg-dark w-75 p-3 d-flex flex-wrap align-content-center justify-content-center tool-box h-50 ant-round">
                    <div class="w-100 h-100 d-flex flex-wrap align-content-between">
                        <header class="inside_card_header pl-3 p-1 pr-1 d-flex flex-wrap align-content-center">
                            <button title="new" data-toggle="modal" data-target="#newCustomer" class="btn mr-2 p-0">
                                <i class="fa fa-plus-square text-primary"></i>
                            </button>
                            <button id="prev" class="btn mr-2 p-0">
                                <i class="fa fa-backward text-info"></i>
                            </button>
                            <button id="next" class="btn mr-2 p-0">
                                <i class="fa fa-forward text-info"></i>
                            </button>
                        </header>
                        <article class="inside_card_body p-1">
                            <div class="container h-100">
                                <div class="w-100 table-responsive h-100">
                                    <table class="table table-sm table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>NAME</th><th>EMAIL</th><th>PHONE</th><th>ADDRESS</th><th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($customer = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td class="text-light"><?php echo $customer['name'] ?></td>
                                                    <td class="text-light"><?php echo $customer['email'] ?></td>
                                                    <td class="text-light"><?php echo $customer['phone_number'] ?></td>
                                                    <td class="text-light"><?php echo $customer['address'] ?></td><td class="text-light">
                                                        <div class="dropdown dropleft">
                                                            <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>

                                                            <!-- Dropdown menu -->
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" onclick="customerMaster.make_payment('<?php echo $customer['cust_no'] ?>')" href="javascript:void(0)">Make Payment</a>
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="customerMaster.printStatement('<?php echo $customer['cust_no'] ?>')">Statement</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
<!--                                <div class="row h-100">-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <label for="prev_first_name">First Name </label><br><input autocomplete="off" type="text" class="form-control form-control-sm mb-2" id="prev_first_name">-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <label for="prev_last_name">Last Name </label><br><input autocomplete="off" type="text" class="form-control form-control-sm mb-2" id="prev_last_name">-->
<!--                                    </div>-->
<!---->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_phone">Phone </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_phone">-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_email">Email </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_email">-->
<!--                                    </div>-->
<!---->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_country">Country </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_country">-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_city">City </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_city">-->
<!--                                    </div>-->
<!---->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_address">Address </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_address">-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <label for="prev_postal">Postal </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  mb-2" id="prev_postal">-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <label for="prev_cust_number">NUMBER </label><br><input autocomplete="off" type="text" class="form-control form-control-sm  w-100 mb-2" id="prev_cust_number">-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </article>
                    </div>


                </div>

            </div>

        </div>

    </div>

</main>

<script>

    // disable preview field
    function loadCustomer(cust_no) {
        $('#loader').modal('show')
        let prev_field = ['prev_first_name','prev_last_name','prev_phone','prev_email','prev_country','prev_city','prev_address','prev_postal','prev_cust_number']
        disableFields(prev_field)

        // gut last customer
        if(row_count('customers',`\`cust_no\`  = '${cust_no}'`) === 1){


            // get customer
            let customer = m_cust.getCustomer(`${cust_no}`)
            let status,message
            status = customer['status_code']
            message = customer['message']

            if(status === 200){
                let alone = message[0]

                // fill fields
                let ids = prev_field;
                let data = [
                    alone['first_name'],alone['last_name'],alone['phone_number'],alone['email'],alone['country'],alone['city'],alone['address'],alone['postal_code'],alone['cust_no']
                ]

                autoFill(ids,data)

                // check next previous
                if(row_count('customers',`customer_id > ${alone['customer_id']}`) > 0 ){
                    // there is next
                    let next = JSON.parse(fetch_rows(`SELECT * FROM customers where customer_id > '${alone['customer_id']}' limit 1`))[0]['cust_no']
                    enableFields(['next'])
                    $('#next').val(next)

                } else {
                    // no next
                    $('#next').val('')
                    disableFields(['next'])
                }

                if(row_count('customers',`customer_id < ${alone['customer_id']}`) > 0 ){
                    // there is prev
                    let prev = JSON.parse(fetch_rows(`SELECT * FROM customers where customer_id < '${alone['customer_id']}' ORDER BY customer_id desc limit 1`))[0]['cust_no']
                    enableFields(['prev'])
                    $('#prev').val(prev)
                } else {
                    // no next
                    $('#prev').val('')
                    disableFields(['prev'])
                }


            } else {
                kasa.error(message)
            }

        } else {
            $('.inside_card_body').html(`
            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center"><div class="alert alert-info">NO CUSTOMER</div></div>
        `)
        }

        $('#loader').modal('hide')
    }

    let last = JSON.parse(fetch_rows(`SELECT cust_no FROM customers order by customer_id desc limit 1`))[0]['cust_no'];
    loadCustomer(`${last}`)

    $('#next').click(function () {
        loadCustomer(`${this.value}`)
    });

    $('#prev').click(function () {
        loadCustomer(`${this.value}`)
    });

</script>