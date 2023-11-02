<div class="w-100 p-2 h-100 ant-bg-dark ">
    <div style='height:8% !important' class='bg-light p-1 d-flex flex-wrap align-content-center'>
        <button onclick='salesProcess(this.value)' title='process' id='post' disabled class="btn btn-primary fa fa-tools m-1"> process</button>
        <button disabled class="btn btn-dark fa fa-print m-1"> print</button>
        <button onclick='loadSales(this.value)' id='prev' class="btn btn-info fa fa-backward m-1"></button>
        <button onclick='loadSales(this.value)'  id='next' class="btn btn-info fa fa-forward m-1"></button>
    </div>

    <div style='height:92% !important'>
        <div class="h-30 w-100 bg-info">
            <div class="container p-2">
                <div class="row">
                    <div class="col-sm-4">
                        <!-- DATE -->
                        <div class="input-group input-group-sm mb-1">
                            <label class='' for="gross">DATE.</label>
                            <input type="date" readonly id='date' class="prod_inp_view_sm rounded-0 form-control-sm">
                        </div>
                        <!-- GROSS -->
                        <div class="input-group input-group-sm mb-1">
                            <label class='' for="gross">GRO AMT.</label>
                            <input type="text" readonly id='gross' class="prod_inp_view_sm rounded-0 form-control-sm">
                        </div>

                        <!-- TAX -->
                        <div class="input-group input-group-sm mb-1">
                            <label class='' for="tax">TAX AMT.</label>
                            <input type="text" disabled id='tax' class="prod_inp_view_sm rounded-0 form-control-sm">
                        </div>

                        <!-- NET -->
                        <div class="input-group input-group-sm mb-2">
                            <label class='' for="net">NET AMT.</label>
                            <input type="text" disabled id='net' class="prod_inp_view_sm rounded-0 form-control-sm">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="w-100 h-70 prod_button table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>MECH</th><th>SHIFT</th><th>BILL N<u>O</u></th><th>BARCODE</th><th>NAME</th>
                        <th>UN. COST</th><th>UN. RETAIL</th><th>SOLD QTY</th><th>COST</th><th>RETAIL</th><th>TAX AMT</th>
                    </tr>
                </thead>
                <tbody id="salesBody">
                    <tr>
                        <td>1</td><td>1</td><td>1</td><td>123456</td><td>Mango</td><td>100</td><td>100</td>
                        <td>1</td><td>100</td><td>100</td><td>21.90</td>
                    </tr>
                    <tr>
                        <td colspan='7'><strong>TOTAL</strong></td>
                        <td>1</td><td>100</td><td>100</td><td>21.90</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    

</div>

<script>

    function loadSales(sales_date) {
        //console.log(sales_date);
        // check if sales exist
        let sales_count = row_count('sales_hd',`sales_date = '${sales_date}'`);

        if(sales_count > 0){
            $('#date').val(sales_date)
            $('#post').val(sales_date)
            //console.log('get her');
            let sales_hd = FETCH(`SELECT * FROM sales_hd where sales_date = '${sales_date}'`)[0];
            
            // set header
            $('#gross').val(sales_hd['gross']);
            $('#net').val(sales_hd['net']);
            $('#tax').val(sales_hd['tax']);

            /*
            * toggle headers
            */ 

            // post button
            if(sales_hd['posted'] === 0){
                // not posted
                $('#post').prop('disabled',false);

            } else if (sales_hd['posted'] === 1){
                $('#post').prop('disabled',true);
            }

            // next
            let next_count = row_count('sales_hd',`sales_date > '${sales_date}'`);
            //console.log(next_count);
            if(next_count > 0){
                let next = FETCH(`SELECT sales_date from sales_hd where sales_date > '${sales_date} order by sales_date asc limit 1'`)[0];
                $('#next').val(next['sales_date']);
                $('#next').prop('disabled',false);
            } else {
                $('#next').prop('disabled',true);
            }

            // previous
            if(row_count('sales_hd',`sales_date < '${sales_date}'`) > 0){

                let prev = FETCH(`SELECT sales_date from sales_hd where sales_date < '${sales_date} order by sales_date desc limit 1'`)[0];
                $('#prev').val(prev['sales_date']);
                $('#prev').prop('disabled',false);

            } else {
                $('#prev').prop('disabled',true);
            }

            /*
            * toggle headers end
            */ 

            // ge transactions
            let sales_trans = FETCH(`SELECT * FROM sales_tran where bill_date = '${sales_date}'`);
            
            let rows = '';
            for(let st = 0; st < sales_trans.length; st++){
                let tran = sales_trans[st];
                rows += `

                <tr>
                        <td>${tran['mach']}</td><td>${tran['shift']}</td><td>${tran['bill_no']}</td><td>${tran['barcode']}</td><td>${tran['item_desc']}</td><td>${tran['un_cost']}</td><td>${tran['un_retail']}</td>
                        <td>${tran['sold_qty']}</td><td>${tran['total_cost']}</td><td>${tran['total_sold']}</td><td>${tran['total_tax']}</td>
                </tr>
                
                `;
                //console.table(tran);
            }

            $('#salesBody').html(rows);



        } else {
            kasa.error(`No sales for ${sales_date}`)
        }

    }

    function salesProcess(sales_date){
        if(confirm(`Are you sure you want to process sales entry for ${sales_date}?`)){

            // get and loop through all day transactions
            let sales = FETCH(`SELECT barcode, sold_qty from sales_tran where bill_date = '${sales_date}'`);
            for(let s = 0; s < sales.length; s++){
                al('PROCESSING');
                let barcode,sold_qty, sale = sales[s];
                barcode = sale['barcode'];
                sold_qty = sale['sold_qty'];
                let sold_two = parseFloat(sold_qty) * 2;
                let sold = parseFloat(sold_qty) - sold_two;
                

                // get product detail
            
                let product = FETCH(`SELECT * FROM prod_master where barcode = '${barcode}'`)[0];
                // console.table(product);
                let itemcode = product['item_code'];
                // console.log(itemcode);
                // console.log(sold)
                // console.table(sale)
                let stock_query = `INSERT INTO stk_tran (entry_no,doc,item_code,loc_to,pack_desc,pack_un,tran_qty,loc_fro) values ('SS${sales_date}','SS','${itemcode}','001','PCS',1,'${sold}','001')`;
                // console.log(stock_query);
                exec(stock_query)
            }

            // update sales posted
            exec(`UPDATE sales_hd set posted = 1 where sales_date = '${sales_date}' `);

            // load sales again
            loadSales(sales_date)

            kasa.success("SALES PROCESSED")
        } else {
            kasa.warning("Process Cancelled")
        }
    }
    // get last sales date
    if(row_count('sales_hd',`sales_date > '2000-01-01'`) > 0)
    {
        let last_sales = FETCH("SELECT sales_date from sales_hd order by sales_date desc limit 1")[0]['sales_date'];
        loadSales(last_sales);
    } else {
        kasa.info("NO SALES BUDDY")
    }
    

</script>