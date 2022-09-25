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


    // check nav
    Nav(id){
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

    // loadScreen
    LoadScreen(target='*',nav=1){
        let id = 0;
        if (target === 'ini')
        {
            let row = this.GetLast()
            if (row['count'] > 0){
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
                this.Create()
            }
        }


        // nav
        if(nav === 1 && id !== 0)
        {
            this.Nav(id)
            // get subs
            
        }

    }

}