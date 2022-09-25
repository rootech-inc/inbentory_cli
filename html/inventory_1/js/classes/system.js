class System {


}

class TaxMaster{

    async Create () {

        const {value: formValues} = await Swal.fire({
            title: 'Add a Tax component',
            html:
                '<input id="code" autocomplete="off" placeholder="Code" maxlength="3" class="swal2-input">' +
                '<input id="descr" placeholder="Description" class="swal2-input">' +
                '<input id="rate" type="number" placeholder="Rate" class="swal2-input">',
            focusConfirm: false,
            preConfirm: () => {
                let obj = {
                    'code': $('#code').val(),
                    'descr': $('#descr').val(),
                    'rate': $('#rate').val()
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
            let code = formValues['code']
            let descr = formValues['descr']
            let rate = formValues['rate']

            // insert
            let data = {
                'cols': ['description', 'rate', 'owner', 'active', 'attr', 'date_added', 'time_added'],
                'vars': [descr, rate, user_id, 1, code, toDay, time_x]
            }

            insert('tax_master', data)


            Swal.fire(`Creating new tax (${descr}) with rate of ${rate}`)
        }

        this.LoadScreen()
    }

    // GET TAX
    Get(limit='*',condition='id > 0'){

        if (limit !== '*' ){
            limit = ` LIMIT ${limit}`
        } else
        {
            limit = ''
        }

        let count = row_count('tax_master',condition)
        let result = get_row('tax_master',condition)

        return {
            'count':count,
            'result':result
        }
    }

    // LOAD TAX SCREEN
    LoadScreen()
    {
        let taxes = this.Get()
        let tax_count = taxes['count']
        let tr =""

        if(tax_count > 0){
            let res = JSON.parse(taxes['result'])
            for (let i = 0; i < res.length; i++) {
                let row = res[i]
                tr += `<tr>
                    <td>${row['attr']}</td>
                    <td>${row['description']}</td>
                    <td>${row['rate']} %</td>
                    <td><div class="w-100 d-flex flex-wrap"><span onclick="taxMaster.DeleteTax('${row['id']}')" class="badge badge-danger">Delete</span></div></td>
                </tr>`
                ct(res[i])
            }
            $('#taxScreen').html(tr)
        }else
        {
            this.Create()
        }
    }

    // delete tax
    DeleteTax(id)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                exec(`DELETE FROM tax_master where id = '${id}'`)
                this.LoadScreen()
            }
        })

    }
}

class UserConfig {


    // create user group
    async CreateGroup () {

        const {value: formValues} = await Swal.fire({
            title: 'Creating User Group',
            html:
                '<input id="new_name" autocomplete="off" placeholder="Group Name" class="swal2-input">' +
                '<input id="new_descr" placeholder="Description" class="swal2-input">' ,
            focusConfirm: false,
            preConfirm: () => {
                let obj = {
                    'new_name': $('#new_name').val(),
                    'new_descr': $('#new_descr').val(),
                }
                return obj
            }
        })

        if (formValues) {
            let new_name = formValues['new_name']
            let new_descr = formValues['new_descr']

            // insert
            let data = {
                'cols': ['descr', 'remarks'],
                'vars': [new_name,new_descr]
            }

            insert('user_group', data)

            // insert user_access
            // fetch last group
            let querySet = JSON.parse(fetch_rows("SELECT * FROM user_group ORDER BY id DESC LIMIT 1"))[0]
            let gid = querySet['id']

            let screens = JSON.parse(get_row('screens','none'));
            for (let i = 0; i < screens.length; i++) {
                let ss = screens[i]
                let screen_id,user_grp,i_date
                screen_id = ss['id']
                user_grp = gid

                i_date = {
                    'cols':['group','screen'],
                    'vars':[user_grp,screen_id]
                }

                insert('user_access',i_date)

            }


            Swal.fire("Group Added")
        }

        this.LoadGroupsScreen()
    }

    GetGroup(id = 'all'){
        let condition
        if(id === 'all')
        {
            condition = 'none'
        } else
        {
            condition = ` id = '${id}'`
        }

        return {
            'count':row_count('user_group',condition),
            'result':JSON.parse(get_row('user_group',condition))
        }



    }

    LoadGroupsScreen(){
        if(row_count('user_group','none') > 0)
        {
            // fetch last group
            let querySet = JSON.parse(fetch_rows("SELECT * FROM user_group ORDER BY id DESC LIMIT 1"))[0]
            let id = querySet['id']

            let group = this.GetGroup(id)['result'][0]
            jqh.setText({
                'desc':group['descr'],'code':group['id']
            })

            set_session([`user_grp=${id}`],0)

            // check nav
            // next
            if(row_count('user_group',`id > '${id}'`) > 0 )
            {
                // enable
                arr_enable("sort_right")
            } else
            {
                // disable
                arr_disable("sort_right")
            }

            // previous
            if(row_count('user_group',`id < '${id}'`) > 0 )
            {
                // enable
                arr_enable("sort_left")
            } else
            {
                // disable
                arr_disable("sort_left")
            }

        }
        else
        {
            this.CreateGroup()
        }
    }
}