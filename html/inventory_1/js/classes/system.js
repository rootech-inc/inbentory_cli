class System {

    // new location
    async NewLocation () {

        const {value: formValues} = await Swal.fire({
            title: 'Add Location',
            html:
                '<input id="loc_id" autocomplete="off" placeholder="loc id" maxlength="3" class="swal2-input">' +
                '<input id="descr" placeholder="Description" class="swal2-input">' ,
            focusConfirm: false,
            preConfirm: () => {
                let obj = {
                    'loc_id': $('#loc_id').val(),
                    'descr': $('#descr').val()
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
            let loc_id = formValues['loc_id']
            let descr = formValues['descr']

            // insert
            let data = {
                'cols': ['loc_id', 'loc_desc'],
                'vars': [loc_id, descr]
            }

            insert('loc', data)


            swal_reload('Location Added')
        }


    }

    OnKeyboard(){
        cl("Toggling Screen Keyboard")
        $('#alphsKeyboard').fadeIn();
    }
    OffKeyboard(){
        $('#alphsKeyboard').hide();
    }

    adminAuth(){
        let auth = false;
        let admin_id_l,admin_password_l
        Swal.fire({
            title: 'AUTHENTICATE',
            html: `<input type="text" autocomplete='off' id="admin_login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="admin_password" class="swal2-input" placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
                const admin_login = Swal.getPopup().querySelector('#admin_login').value
                const admin_password = Swal.getPopup().querySelector('#admin_password').value
                if (!admin_login || !admin_password) {
                    Swal.showValidationMessage(`Please enter login and password`)
                }
                return { admin_login: admin_login, admin_password: admin_password }
            }
        }).then((result) => {
            admin_id_l = result.value.admin_login;
            admin_password_l = result.value.admin_password;

            let form_data = {
                'function':'admin_auth',
                'user_id':admin_id_l,
                'password':admin_password_l
            }
            form_settings['url'] = '/backend/process/form_process.php'
            form_settings['type'] = 'POST';
            form_settings['data'] = form_data
            form_data['success'] = function(response) {
                echo(response)
                if(response.split('%%').length > 1)
                {
                    var type = response.split('%%')[0];
                    var mesg = response.split('%%')[1];

                    if(type === 'error')
                    {
                        auth = false
                    }
                    else if(type === 'done')
                    {
                        auth = true
                    }
                }

                //Swal.fire(response)
            }
        })

        return auth
    }

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
    session = get_session('clerk_id');

    constructor() {
        this.clerk = get_session('clerk_id')
        this.exist = row_count('clerk',`id = '${this.session}'`)

    }

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

    LoadClerksScreen(target='ini'){
        let querySet,id;
        let error = false;
        if(target === 'ini') // if fetching user for screen ini
        {
            if(row_count('clerk','none') > 0) // if there are rows in clerks
            {
                // fetch last group
                querySet = JSON.parse(fetch_rows("SELECT * FROM clerk ORDER BY id DESC LIMIT 1"))[0]
                id = querySet['id']


            }
            else // create new clerk
            {
                error = true;
                this.CreateUser()
            }
        }

        else // means an id has been passed
        {
            if(row_count('clerk',`id = '${target}'`) > 0)
            {
                // fetch last group
                querySet = JSON.parse(fetch_rows(`SELECT * FROM clerk WHERE id = '${target}' `))[0]
                id = querySet['id']
            }
            else
            {
                error = true
            }
        }


        if (error === false) // if no error from above
        {
            let group;
            let ug = this.GetGroup(querySet['user_grp'])
            ct(ug)
            if(ug['count'] === 1)
            {
                group = ug['result'][0]['descr']
            } else
            {
                group = 'Unknown';
            }

            var data = {
                'desc':querySet['clerk_name'],'code':querySet['clerk_code'],'group':group
            }
            ct(data)
            jqh.setText(data)

            set_session([`user_act=${id}`],0)
            $('#edit_property').val(id)

            // check nav
            // next
            if(row_count('clerk',`id > '${id}'`) > 0 )
            {
                // enable
                arr_enable("sort_right")
            } else
            {
                // disable
                arr_disable("sort_right")
            }

            // previous
            if(row_count('clerk',`id < '${id}'`) > 0 )
            {
                // enable
                arr_enable("sort_left")
            } else
            {
                // disable
                arr_disable("sort_left")
            }
        }
        else if (error === true && target !== 'ini')
        {
            swal_error(`Cannot get user property Target : ${target}`)
        }




    }

    async CreateUser() {

        var groups = this.GetGroup('all')
        if(groups['count'] > 0)
        {
            let res = JSON.parse(groups['result'])
            let options = {

            }
            for (let i = 0; i < res.length; i++) {

                let row = res[i]
                ct(row)
                let id = row['id']
                let descr = row['descr']
                options[id] = descr
            }

            ct(options)

            const { value: fruit } = await Swal.fire({
                title: 'Create New User',
                input: 'select',
                inputOptions: options,
                inputPlaceholder: 'Select Group',
                showCancelButton: true,
                inputValidator: async (value) => {
                    const {value: formValues} = await Swal.fire({
                        title: 'Description',
                        html:
                            '<input id="code" class="swal2-input">' +
                            '<input id="name" class="swal2-input">' +
                            '<input id="password" class="swal2-input">',
                        focusConfirm: false,
                        preConfirm: () => {
                            return [
                                document.getElementById('description').value,
                                $('#password').val()
                            ]
                        }
                    })

                    if (formValues) {
                        let code = formValues[0]
                        let name = formValues[1]
                        let password = md5(formValues[2])
                        let grp = value

                        let data = {
                            'cols':['clerk_code','clerk_key','clerk_name','user_grp'],
                            'vars':[code,password,name,grp]
                        }
                        insert('clerk',data)
                        swal_reload('Process Completed')
                    }
                }
            })

            this.LoadClerksScreen()
        } else
        {
            await this.CreateGroup()
        }


    }

    GetClerk(id_param='*')
    {
        let condition = 'none'
        if(id_param !== '*')
        {
            condition = ` id = ${id_param}`
        }

        return {
            'count':row_count('clerk',condition),
            'result':get_row('clerk',condition)
        }

    }

    SaveClerk()
    {
        let code,name,group,data,password;

        code = $('#code').val()
        password = $('#password').val()
        name = $('#name').val()
        group = $('#group').val()



        data = {
            'cols':['clerk_code','clerk_key','clerk_name','user_grp'],
            'vars':[code,md5(password),name,group]
        }

        if(row_count('clerk',`clerk_code = '${code}'`) === 0 )
        {
            insert('clerk',data)

            set_session(['action=view'],1)
        } else {
            swal_error(`Clerk Code (${code}) is taken`)
        }





    }

    ClerkNav(direction)
    {
        let clerk_details
        let clk_act = get_session('user_act');
        if(direction === '>')
        {
            if(row_count('clerk',`id > ${clk_act}`) > 0)
            {
                clerk_details = JSON.parse(fetch_rows(`SELECT * FROM clerk WHERE id > ${clk_act} LIMIT 1`))[0]
                this.LoadClerksScreen(clerk_details['id'])
            }
        }
        else if(direction === '<')
        {
            if(row_count('clerk',`id < ${clk_act}`) > 0)
            {
                clerk_details = JSON.parse(fetch_rows(`SELECT * FROM clerk WHERE id < ${clk_act} ORDER BY id desc LIMIT 1`))[0]
                this.LoadClerksScreen(clerk_details['id'])
            }
        }
    }

    ThisClerk(){
        return this.GetClerk(this.clerk)
    }




}

