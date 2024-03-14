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

                <div class="card h-100 w-100">
                    <div class="card-header">
                        <div class="w-100 d-flex">
                            <div class="w-50 d-flex flex-wrap">
                                <button data-toggle="modal" data-target="#newCustomer" class="btn btn-sm rounded-0 btn-info mr-1"><i class="fa fa-plus-square"></i></button>
                                <button title="make payment" id="make_payment" class="btn btn-success rounded-0 btn-sm mr-1"><i class="fa fa-money-bill"></i></button>
                                <button id="previous" class="btn btn-info mr-2 rounded-0 btn-sm"><i class="fa fa-backward"></i></button>
                                <button class="btn btn-info btn-info btn-sm rounded-0" id="next"><i class="fa fa-forward"></i></button>

                            </div>
                            <div class="w-50 d-flex flex-wrap justify-content-end">
                                <button id="statement" class="btn btn-sm btn-info rounded-0 ml-2"><i class="fa fa-print"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="h-30 ant-bg-light overflow-auto">
                            <div class="container py-2">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row mb-2">
                                            <label for="cust_no" class="col-sm-5"><strong>Cust N0.</strong></label>
                                            <input type="text" id="cust_no" disabled class="col-sm-7 form-control rounded-0">
                                        </div>
                                        <div class="row mb-2">
                                            <label for="name" class="col-sm-5"><strong>Name</strong></label>
                                            <input type="text" id="name" disabled class="col-sm-7 form-control rounded-0">
                                        </div>
                                        <div class="row mb-2">
                                            <label for="balance" class="col-sm-5"><strong>Balance</strong></label>
                                            <input type="text" id="balance" disabled class="col-sm-7 form-control rounded-0">
                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="row mb-2">
                                            <label for="c_addr" class="col-sm-5"><strong>Address</strong></label>
                                            <textarea id="c_addr" rows="3" disabled readonly class="col-sm-7 form-control rounded-0"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="h-70 table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Entry</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00000</td>
                                        <td>00000</td>
                                        <td>00000</td>
                                        <td>00000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</main>

<script>

    let cid = null,next=null,previous=null;
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
               // console.table(alone)
                $('#cust_no').val(alone['customer_id']);
                $('#name').val(`${alone['first_name']} ${alone['last_name']}`)
                let address = `${alone['country']}, ${alone['city']}, ${alone['address']}`
                $('#c_addr').val(address)
                cid = alone['cust_no']

                // get balacke
                let balance = JSON.parse(fetch_rows(`SELECT SUM(total_amount) 'x' from customers_trans where customer_id = '${alone['customer_id']}'`))[0]['x']

                $('#balance').val(balance)

                // check next previous
                if(row_count('customers',`customer_id > ${alone['customer_id']}`) > 0 ){
                    // there is next
                    // echo("NEXT")
                    next = JSON.parse(fetch_rows(`SELECT * FROM customers where customer_id > '${alone['customer_id']}' order by customer_id asc limit 1`))[0]['cust_no']
                    enableFields(['next'])
                    // echo("NEXT")


                } else {
                    // no next
                    $('#next').val('')
                    disableFields(['next'])
                    next = null;
                }

                if(row_count('customers',`customer_id < ${alone['customer_id']}`) > 0 ){
                    // there is prev
                    previous = JSON.parse(fetch_rows(`SELECT * FROM customers where customer_id < '${alone['customer_id']}' ORDER BY customer_id desc limit 1`))[0]['cust_no']
                    enableFields(['previous'])

                } else {
                    // no next
                    $('#previous').val('')
                    disableFields(['previous'])
                    previous = null
                }

                // get transactions
                let transactions = `SELECT entry_no, transaction_date as 'date',total_amount,c.clerk_name as 'owner' from customers_trans right join posdb.clerk c on customers_trans.user = c.id where customer_id = '${alone['customer_id']}'`;
                let trans_q = JSON.parse(fetch_rows(transactions));
                let tr = '';
                for(let t = 0; t < trans_q.length; t++){
                    let tran = trans_q[t];

                    tr += `
                        <tr>
                                        <td>${tran['entry_no']}</td>
                                        <td>${tran['date']}</td>
                                        <td>${tran['total_amount']}</td>
                                        <td>${tran['owner']}</td>
                                    </tr>
                    `;
                }
                $('tbody').html(tr)

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

    $(document).ready(function(){
        $('#make_payment').click(function(){
            customerMaster.make_payment(cid)
        })

        $('#previous').click(function(){
            loadCustomer(previous)
        });

        $('#next').click(function(){
            loadCustomer(next)
        });

        $('#statement').click(function(){
            customerMaster.printStatement(cid)
        });
    });

</script>