class ProductCategory {

    // create
    async Create () {

        var taxes = taxMaster.Get('*')

        if (taxes['count'] > 0)
        {
            let res = JSON.parse(taxes['result'])
            let options = {

            }
            for (let i = 0; i < res.length; i++) {

                let row = res[i]
                ct(row)
                let id = row['id']
                let descr = row['description']
                options[id] = descr
            }

            ct(options)

            const { value: fruit } = await Swal.fire({
                title: 'Create New Group',
                input: 'select',
                inputOptions: options,
                inputPlaceholder: 'Select Tax Group',
                showCancelButton: true,
                inputValidator: async (value) => {
                    const {value: formValues} = await Swal.fire({
                        title: 'Description',
                        html:
                            '<input id="description" class="swal2-input">',
                        focusConfirm: false,
                        preConfirm: () => {
                            return [
                                document.getElementById('description').value,
                            ]
                        }
                    })

                    if (formValues) {
                        let description = formValues[0]
                        let uni = description.slice(0,3)
                        let shrt_name = description.slice(0,8)

                        let data = {
                            'cols':['group_name','owner','grp_uni','shrt_name','tax_grp'],
                            'vars':[description,user_id,uni,shrt_name,value]
                        }
                        insert('item_group',data)
                        swal_reload('Process Completed')
                    }
                }
            })

        }else {
            swal_error("error%%Create a tax component")
            taxMaster.Create()
        }




    }

    GroupCount()
    {
        return row_count('item_group','none')
    }
    GetAll(condition='id > 0'){
        if(row_count('item_group',condition) > 0)
        {

        }
    }

    GetLast() // get last group
    {
        let count = row_count('item_group','none')
        let result = JSON.parse(fetch_rows("SELECT * FROM item_group ORDER BY id DESC LIMIT 1"))

        return {
            'count':count,'result':result
        }

    }

    GetCategory(id)
    {
        let count = row_count('item_group',`id = '${id}'`)
        let result = JSON.parse(fetch_rows(`SELECT * FROM item_group where id = '${id}' `))

        return {
            'count':count,'result':result
        }
    }

    // get subs
    CategorySubs(parent)
    {
        return {
            'count':row_count('item_group_sub',`parent='${parent}'`),
            'result':get_row('item_group_sub',`parent='${parent}'`)
        }
    }

    // check nav
    CheckNav(id){
        // next
        if(row_count('item_group',`id > '${id}'`) > 0 )
        {
            // enable
            arr_enable("sort_right")
        } else
        {
            // disable
            arr_disable("sort_right")
        }

        // previous
        if(row_count('item_group',`id < '${id}'`) > 0 )
        {
            // enable
            arr_enable("sort_left")
        } else
        {
            // disable
            arr_disable("sort_left")
        }
    }

    //Nav
    Nav(sort)
    {
        let current_category = $('#code').text()

        if(current_category.length > 0)
        {
            let target ;
            switch (sort){
                case 'next':
                    target = JSON.parse(fetch_rows(`SELECT id FROM item_group where id > '${current_category}' LIMIT 1`))[0].id
                    break
                case 'prev':
                    target = JSON.parse(fetch_rows(`SELECT id FROM item_group where id < '${current_category}' order by id desc LIMIT 1`))[0].id
                    break
                default:
                    target = 0

            }
            if(target > 0)
            {
                // load screen
                this.LoadScreen(target)
            } else
            {
                cl("CANNOT NAVIGATE")
            }
        }

    }

    // loadScreen
    LoadScreen(target='*',nav=1){
        let id = 0;
        let row
        if(target === 'ini')
        {
            row = this.GetLast()
        } else {
            row = this.GetCategory(target)
        }

        if (row['count'] > 0)
        {
            //
            ct(row)
            let grp = row['result'][0]
            let descr;
            id = grp['id']
            descr = grp['group_name']
            jqh.setText({
                'desc':descr,
                'short_desc':grp['shrt_name'],
                'code':grp['id'],
                'owner':grp['owner'],
                'date_created':grp['date_created'],
                'date_mod':grp['date_modified']
            })



        } else
        {
            // create new group
            set_session(['action=new'])
        }


        // nav
        if(nav === 1 && id !== 0)
        {
            this.CheckNav(id)

            
        }

        // subs
        if(id !== 0)
        {
            let subs = this.CategorySubs(id)
            let tr = ''

            if(subs['count'] > 0)
            {

                // load subs
                let result = JSON.parse(subs['result'])
                ct(result)
                for (let i = 0; i < result.length; i++)
                {
                    let this_result = result[i]
                    let sn, descr
                    sn = i + 1;
                    descr = this_result['description']

                    tr += `<tr onclick="">
                                <td>${sn}</td>
                                <td>${descr}</td>
                                <td><span class="badge badge-success">Active</span></td>
                            </tr>`
                }


            } else
            {
                // show no subs

            }
            $('#catSubsBody').html(tr)
        }

    }

    // supplier

    async CreateSupplier () {
        let sucode = `SU${row_count('supp_mast','none') + 1}`;
        const {value: formValues} = await Swal.fire({
            title: 'Supplier Creation',
            html:
                `<input id="supp_code" value="${sucode}" maxlength="3"  placeholder="Code" class="swal2-input"> <input id="descr" placeholder="Description" class="swal2-input">`,
            focusConfirm: false,
            preConfirm: () => {
                let obj = {

                    'descr': $('#descr').val(),
                    'code': $('#supp_code').val()
                }
                return obj
                //return [
                //  document.getElementById('code').value,
                //document.getElementById('descr').value,
                //document.getElementById('rate').value
                //]
            }
        })

        if (formValues) {

            let descr = formValues['descr']
            let code = formValues['code']


            // insert
            let data = {
                'cols': ['supp_name',  'tax_grp','supp_id'],
                'vars': [descr, 1,code]
            }

            let q = `INSERT INTO supp_mast (supp_id, supp_name) values ('${code}','${descr}')`;

            // insert('supp_mast', data)


            swal_reload(exec(q)['message'])
        }


    }

    // supplier


}