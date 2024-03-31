class ProductMaster {
    searchProduct(){
        mpop.setTitle(`<input id="searchProduct" onkeydown="pmast.loadSearchScreen()" class="form-control form-control-sm rounded-0 " placeholder="Barcode, Name">`);

        mpop.setSize('L');
        mpop.setBody(`
            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">SEARCH FOR PRODTC</div>
        `);
        $('#searchProduct').prop('focus',true)
        mpop.show()
    }
    loadSearchScreen() {
        let qString = $('#searchProduct').val();
        if(qString.length > 0){
            let query = `SELECT * FROM prod_master where item_code like 
                                '%${qString}%' OR barcode like '%${qString}%' OR item_desc like '%${qString}%' LIMIT 20 `;

            let result = fetch_rows(query);
            if(isJson(result)){
                let restj = JSON.parse(result);
                let row = '';
                for (let p = 0; p < restj.length; p++) {
                    let tprod = restj[p];
                    row += `<tr onclick="loadProduct('${tprod['item_code']}','view')"><td>${tprod['item_code']}</td><td>${tprod['barcode']}</td><td>${tprod['item_desc']}</td></tr>`

                }

                let tab = "<table class='table table-sm'><thead class='thead-dark'><tr><th>ITEM CODE</th><th>BARCODE</th><th>NAME</th></tr></thead>" +
                    "<tbody>" +
                    row +
                    "</tbody></table>";

                mpop.setBody(tab);

            } else {
                al("INVALID RESPONSE");
            }

        }
    }

    // suppliers
    getSupplier(id='*'){
        let payload = {
            module:'supplier',
            crud:'read',
            data:{
                key:id
            }
        }

        return api.call('GET',payload)
    }

    getCustomer(cust_no='*'){
        let payload = {
            module:'customer',
            crud:'read',
            data:{
                cust_no:cust_no
            }
        }


        return api.call('GET',payload)
    }
}

const pmast = new ProductMaster();