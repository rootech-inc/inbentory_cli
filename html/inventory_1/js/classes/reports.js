class Reports {
    SalesReport(){
        // get sales report, fill it in reports
        $('#gen_modal').removeClass('modal-lg');
        $('#report_res').removeClass('modal_card'); // remove backgroudd of modal body
        $('.modal-title').text('Sales Report');

        // get total sales

        let gross = JSON.parse(fetch_rows(`SELECT SUM(gross_amt) as 'gross_amt' FROM bill_header`))[0].gross_amt

        // get all machines
        let machines = JSON.parse(fetch_rows("SELECT mach_no from bill_header group by mach_no"));
        let all_sales = "";
        for (let im_n = 0; im_n < machines.length; im_n++) {

            let machine_number = machines[im_n]['mach_no']
            let this_sales = ""
            let m_sales = JSON.parse(fetch_rows(`select pmt_type, sum(gross_amt) as 'gross',sum(tax_amt) as 'tax',sum(net_amt) as 'net' from bill_header group by pmt_type`));
            let this_total = 0;
            for (let ms = 0; ms < m_sales.length; ms++)
            {
                let gross,net,pmt_type,tax
                let this_m_sales = m_sales[ms]
                gross = this_m_sales['gross']
                net = this_m_sales['net']
                pmt_type = this_m_sales['pmt_type']
                tax = this_m_sales['tax']
                this_total += parseFloat(net)

                this_sales += `<div class='w-100 clearfix border-dark p-1 border-bottom'>\
                            <div class='w-45 float-left'><p class='m-0 p-0'>${pmt_type}</p></div>\
                            <div class='w-45 float-right text-right'><p class='m-0 p-0'>$ ${net}</p></div>\
                        </div>`




            }
            this_sales += `<div class='w-100 font-weight-bold clearfix border-dark p-1 border-bottom'>\
                            <div class='w-45 float-left'><p class='m-0 p-0'>Total</p></div>\
                            <div class='w-45 float-right text-right'><p class='m-0 p-0'>$ ${this_total.toFixed(2)}</p></div>\
                        </div>`

            all_sales += `<div class='w-100 p-2'> \
                    <div class='modal_card p-4 mb-4'>\
                        <h4 class='font-weight-bolder mb-2'>MACHINE ${machine_number}</h4>\
                        ${this_sales}
                    </div>\
                </div>`;

        }

        var response = `<div class='w-100 p-2'> \
                <div class='w-100 text-center'> \
                    <p class='font-weight-bolder text-center text-elipse'>${gross}</p>\
                    <hr class='mb-3 mt-3'>\
                </div>\
                <div class='modal_card p-4 mb-4'>\
                    <h4 class='font-weight-bolder mb-2'>MACHINE 1</h4>\
    \
                    <!--CASH-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>CASH</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--MOMO-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>MOMO</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--DISCOUNT-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>Discount</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TOTAL-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TOTAL</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TAX-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TAX</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                </div>\
    \
                <!--MACHINE 2-->\
                <div class='modal_card p-4'>\
                    <h4 class='font-weight-bolder mb-2'>MACHINE 2</h4>\
    \
                    <!--CASH-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>CASH</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--MOMO-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>MOMO</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--DISCOUNT-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>Discount</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TOTAL-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TOTAL</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                    <!--TAX-->\
                    <div class='w-100 clearfix border-dark p-1 border-bottom'>\
                        <div class='w-45 float-left'><p class='m-0 p-0'>TAX</p></div>\
                        <div class='w-45 float-right text-right'><p class='m-0 p-0'>$100.00</p></div>\
                    </div>\
    \
                </div>\
    \
                <div class='w-100 p-4 text-center'>\
                    <img \
                        class='img-fluid pointer print_25'\
                        src='assets/icons/home/print_25.png'\
                        title='print'\
                        onclick='print_doc(1)'\
                    >\
                </div>\
    \
            </div>`;

        $("#grn_modal_res").html(all_sales); // send result into modal

        show_modal('gen_modal'); // show modal
    }
}

const reports = new Reports()