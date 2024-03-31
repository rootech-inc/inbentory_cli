class Cust {
    make_payment(customer_id){
        // script.js

        if(row_count('customers',`cust_no = '${customer_id}'`) === 1){
            let customer = JSON.parse(get_row('customers',`cust_no = '${customer_id}'`))[0];
            let cust_name,id,balance;
            cust_name = `${customer['first_name']} ${customer['last_name']}`;
            id = customer['customer_id'];
            balance = JSON.parse(fetch_rows(`select SUM(total_amount) as 'balance' from customers_trans where customer_id = '${id}'`))[0]['balance'];
            // console.log(balance)

            if(balance < 0 && balance !== null){
                Swal.fire({
                    title: `Enter Payment Details for ${cust_name}`,
                    html: `
                      <div class="w-100 text-left p-2">
                        <label for="swal-input1" class="w-100">Balance</label>
                        <input id="swal-input1" value="${balance}" class="form-control rounded-0" placeholder="">
                    
                        <label for="swal-input2" class="w-100">Amount Paid</label>
                        <input id="swal-input2" class="form-control rounded-0" placeholder="">
                    
                        <label for="swal-select" class="w-100">Payment Method</label>
                        <select id="swal-select" class="form-control rounded-0">
                          <option value="cash">Cash</option>
                          <option value="momo">Momo</option>
                          <option value="cheque">cheque</option>
                          <option value="others">Others</option>    
                        </select>
                    
                        <label for="swal-input-text" class="w-100">Reference</label>
                        <input id="swal-input-text" class="form-control form-control-sm rounded-0" placeholder="Enter Comment">
                      </div>
                    `,


                    showCancelButton: true,
                    confirmButtonText: 'Pay',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const num1 = Swal.getPopup().querySelector('#swal-input1').value;
                        const num2 = Swal.getPopup().querySelector('#swal-input2').value;
                        const paymentMethod = Swal.getPopup().querySelector('#swal-select').value;
                        const additionalComment = Swal.getPopup().querySelector('#swal-input-text').value;

                        if (!num1 || !num2 || isNaN(num1) || isNaN(num2)) {
                            Swal.showValidationMessage('Please enter valid numbers');
                            return false;
                        }

                        if (!paymentMethod) {
                            Swal.showValidationMessage('Please select a payment method');
                            return false;
                        }

                        if (!additionalComment.trim()) {
                            Swal.showValidationMessage('Please enter a comment');
                            return false;
                        }

                        return [num1, num2, paymentMethod, additionalComment];
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const [balance, amount_paid, paymentMethod, additionalComment] = result.value;
                        Swal.fire('Payment Confirmed', `Balance : ${balance}, Amount Paid: ${amount_paid}, Method: ${paymentMethod}, Comment: ${additionalComment}`, 'success');
                        let payment_query = `INSERT INTO customers_trans (entry_no,customer_id, total_amount, payment_method, items_purchased, transaction_notes,user) VALUES 
                                                                            ('${paymentMethod}','${id}','${amount_paid}','${paymentMethod}','payment','${additionalComment}','${user_id}')`;
                        let pay = exec(payment_query);
                        kasa.info(pay['message'])
                        loadCustomer(customer_id);
                        // swal_reload(pay['message'])
                    } else if (result.isDenied) {
                        Swal.fire('Payment Canceled', '', 'info');
                    }
                });
            } else {
                kasa.error(`Cannot make payment on ${balance} amount`)
            }



        } else {
            kasa.error(`NO CUSTOMER WITH CODE ${customer_id}`);
        }


    }

    printStatement(cust_no){
        let payload = {
            module:'customer',
            crud:'statement',
            data:{
                'cust_no':cust_no
            }
        }

        let response = api.call('POST',payload)
        if(response['status_code'] === 200){
            let pdf = response['message'];
            let frame = `<embed src="/api/test.pdf" type="application/pdf" width="100%" height="600px">`;
            mpop.setBody(frame);
            mpop.setTitle("CUSTOMER STATEMTN");
            mpop.setSize('lg');
            mpop.show();
        } else {
            kasa.error("INVALID RESPONSE");
        }
    }

    makePurchase(){
        // get customers
        if(row_count('customers',`customer_id > 0`) > 0){
            let customers = JSON.parse(get_row('customers',`customer_id > 0`));
            let tr = '';
            for (let c = 0; c < customers.length ; c++) {
                let customer = customers[c];
                tr += `<tr><td>${customer['first_name']} ${customer['last_name']}</td><td>${customer['phone_number']}</td><td><button onclick="bill.make_payment('credit','${customer['cust_no']}')">SELECT</button></td></tr>`;
            }
            let table = `
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>NAME</th><th>PHONE</th><th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>${tr}</tbody>
                </table>
            `;
            mpop.setBody(table);
            mpop.setTitle("SELECT CUSTOMER");
            mpop.show()
        } else {
            kasa.error("NO CUSTOMER")
        }
    }

    loadCustomer(number) {
        let payload = {
            module:'customer',
            crud:'load',
            data:{
                'code':number
            }
        };
        let response = api.call('POST',payload);
        if(response['status_code'] === 200){
            bill.loadBillsInTrans();
        } else {
            kasa.error(response['message']);
        }
    }
}

const customerMaster = new Cust();