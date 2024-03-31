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

    sys_variable(variable){
        var form_data = {
            'token':'none',
            'function':'sys_variable',
            'variable':variable
        }
        //cl("session var : " + sess_var)
        var result = '';
        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: form_data,
                success: function (response) {
                    result = response;
                }
            }
        );
        return result;
    }

    adminAuth(){
        let admin_auth_username,admin_auth_password,err_c = 0,err_m = ' ',result = false;
        admin_auth_username = $('#admin_auth_username').val()
        admin_auth_password = $('#admin_auth_password').val()

        if(admin_auth_username.length < 1)
        {
            err_c ++
            err_m += "Provide User ID | "
        }
        if(admin_auth_password.length < 1)
        {
            err_c ++
            err_m += "Provide Password | "
        }

        if (err_c > 0)
        {
            $('#adminAuthErr').text(err_m)
        } else {
            let dataToSend = {
                'function':'mj',
                'user_id':admin_auth_username,
                'password':admin_auth_password
            }

            $.ajax({
                url: '/backend/process/form_process.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: dataToSend,
                success: function(response) {
                    // echo(response)
                    let resp = JSON.parse(response)
                    if(resp['status'] === 200)
                    {
                        result = true

                    } else {
                        result = false
                    }

                }
            });
        }

        return result
    }

    adminAuthV2(){
        let admin_id_v2,admin_password_v2,result = false;
        Swal.fire({
            title: 'AUTHENTICATE',
            html: `<input type="text" autocomplete='off' id="login" class="swal2-input" placeholder="User ID">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">`,
            confirmButtonText: 'Sign in',
            focusConfirm: false,
            preConfirm: () => {
                const login = Swal.getPopup().querySelector('#login').value
                const password = Swal.getPopup().querySelector('#password').value
                if (!login || !password) {
                    Swal.showValidationMessage(`Please enter login and password`)
                } else {

                }
                return { login_v2: login, password_v2: password }
            }
        }).then((result) => {
            admin_id_v2 = result.value.login_v2;
            admin_password_v2 = result.value.password_v2;

            var dataToSend = {
                'function':'mj',
                'user_id':admin_id_v2,
                'password':admin_password_v2,
            }

            $.ajax({
                url: '/backend/process/form_process.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: dataToSend,
                success: function(response) {
                    // echo(response)
                    let resp = JSON.parse(response)
                    if(resp['status'] === 200)
                    {
                        result = true

                    } else {
                        result = false
                    }


                }
            });

            //ct(dataToSend)
            //ct(result)

        })
        return result
    }

    StartShift() {
        let data = {
            'function':'start_shift',
            'mech': $('#mech_no').val()
        }

        $.ajax({
            url:'/backend/process/form-processing/sys.php',
            type:'POST',
            data:data,
            success: function (response) {
                console.log(response)
                let res = JSON.parse(response);
                swal_reload(res['message'])
            }
        });

    }


    taxComponents(tax_code,value){

        var form_data = {
            'function':'taxInclusive',
            'tax_code':tax_code,
            'value':value
        }
        var result = {};
        $.ajax(
            {
                url:'/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data:form_data,
                success: function (response) {

                    result =  JSON.parse(response)
                    ct(result)
                }
            }
        );

        return result;

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
                //ct(res[i])
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

    getTax(attr,val=0){
        let tax; let tax_rate;
        tax = JSON.parse(get_row('tax_master',`attr = '${attr}'`))[0];
        tax_rate = tax.rate;
        let tax_desc = tax.description;
        $('#tax_descr').text(tax_desc);

        // ct(attr);
        var result = 0;

        if(tax_rate !== 'null')
        {
            // calculate oercentage
            let form_data = {
                'function':'get_tax_val',
                'tax_code':attr,
                'amount':val
            }

            $.ajax({
                url:'/backend/process/form-processing/sys.php',
                data:form_data,
                "async": false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                success: function (response) {
                    // cl(response)
                    let r = JSON.parse(response)
                    let details = r['details']
                    let code = r['code']
                    let withoutTax = details['withoutTax']

                    // ct(r)

                    result = r;

                    $('#retail_without_tax').val(withoutTax)

                }
            });


        }
        return result;
    }

    alTax()
    {
        alert('TAX CLASS LOADED')
    }

    taxCalculate(value){
        var form_data = {
            'function': 'tax_calculation',
            'value': value,
        }



        //echo("SELECT * FROM "+table+" WHERE "+condition)

        var result = {};

        $.ajax(
            {
                url: '/backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data: form_data,
                success: function (response) {
                    if(isJson(response)){
                        result = JSON.parse(response);
                    } else {
                        result['code'] = 505;
                        result['message'] = response;
                    }


                }
            }
        );

        console.table(result)
        return result;
    }

    taxInclusive(value) {
        try {
            const covidRate = 1;
            const nhisRate = 2.5;
            const getFundRate = 2.5;


             // retail price + quantity
            const taxableAmount = (value * 100) / 121.9;

            // get levies values
            const covid = (covidRate / 100) * taxableAmount;
            const nhis = (nhisRate / 100) * taxableAmount;
            const gFund = (getFundRate / 100) * taxableAmount;
            const vat = (15.9 / 100) * taxableAmount;

            const taxDetail = {
                type: "INCLUSIVE",
                vat: vat.toFixed(2),
                cv: covid.toFixed(2),
                gf: gFund.toFixed(2),
                nh: nhis.toFixed(2)
            };

            return {
                code: 200,
                message: taxDetail
            };
        } catch (error) {
            return {
                code: 505,
                message: `${error.message} ${error.lineNumber || error.line}`
            };
        }
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

    LoadGroupsScreen(target = 0){
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
            //ct(ug)
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
            //ct(data)
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
                //ct(row)
                let id = row['id']
                let descr = row['descr']
                options[id] = descr
            }

            //ct(options)

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
        let clerk_details;
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

    SaveNewClerk()
    {
        let clerk_full_name = $('#clerk_full_name').val();
        let grp = $('#user_grp').val()

        if(clerk_full_name.length >= 5)
        {

            // save
            let data = {
                'function':'new_user',
                'full_name':clerk_full_name,
                'grp':grp
            }

            $.ajax({
                url: '/backend/process/form-processing/sys.php',
                type: 'POST',
                data:data,
                success: function (response) {
                    let resp = JSON.parse(response)
                    if(resp['code'] === 202)
                    {
                        let ms = resp['message'];
                        let clerk_code = ms['clerk_code']
                        let clerk_key = ms['clerk_key']

                        set_session(['action=view'],0)

                        swal_reload(`clerk added successfully. Code : ${clerk_code} , Key : ${clerk_key}`)

                    } else
                    {
                        swal_error(resp['message'])
                    }
                    cl(resp)
                    //ct(resp)
                }
            })

            //ct(data)

        } else
        {
            Swal.fire({
                icon:'error',
                text:"Full name should be at least 5 characters"

            });
        }
    }

    adminAuthScreen(){
        let html = `
            <input type="password" id="admin_code" maxlength="4" class="form-control text-center rounded-0 mb-2" placeholder="PIN" />
            <button onclick="User.adminAuth()" class="w-100 btn btn-danger">AUTH</button>
        `;
        mpop.setTitle('ADMIN AUTH')
        mpop.setBody(html);
        mpop.show()
    }

    adminAuth(){
        let result = false;
        let ids = ['admin_code'];
        if(anton.validateInputs(ids)){
            let input = anton.Inputs(ids);
            let admin_code = md5(input['admin_code']);
            // console.log(admin_code)
            let condition = `pin = '${admin_code}' and user_grp = 1`;
            console.log(condition)
            let validate = row_count('clerk',condition);
            if(validate === 1){
                result = true
            }

            // validate


        } else {
            kasa.error("Provide Pin")
        }
        // let form_data = {
        //     'function':'adminAuth','code':code,'clerk_key':clerk_key
        // }



        // $.ajax(
        //     {
        //         url:'/backend/process/ajax_tools.php',
        //         'async': false,
        //         'type': "POST",
        //         'global': false,
        //         'dataType': 'html',
        //         data:form_data,
        //         success: function (response)
        //         {
        //             let res = JSON.parse(response)
        //             ct(res)
        //             if(res['code'] === 200)
        //             {
        //                 result = true
        //             }
        //             // result = JSON.parse(response);
        //
        //
        //         }
        //     }
        // );

        return result;

    }


}

class MechConfig {
    // get this meching details
    ThisMech(){

        var result = 0;

        let form_data = {'function': 'this_mech'}


        $.ajax({
            url:'/backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response) {
                if(isJson(response)){
                    result = JSON.parse(response)
                }

            }
        })

        return result


    }

    is_shift(mech_no = this.ThisMech()['machine_number'],day=''){

        // check if there is shift
        let r = row_count('shifts', `mech_no = '${mech_no}'  AND end_time is null`) === 1;
        // console.log(`SHIFT IS ${r}`);
        return r;

    }

    my_shift(){
        let response = {'valid':0,'shift':''}
        if(this.is_shift())
        {
            let my_sh = JSON.parse(get_row('shifts', `mech_no = '${this.ThisMech()['machine_number']}'  AND end_time is null`))[0]
            let my_sh_d = {
                'recId':my_sh['recId'],
                'clerk':my_sh['clerk'],
                'shift_date':my_sh['shift_date'],
                'start_time':my_sh['start_time']
            }

            response['valid'] = 1
            response['shift'] = my_sh_d
        }

        // console.log('mysift')
        // console.table(response)
        return response

    }

    open_shifts(){
        let  shifts_count = row_count('shifts','end_time is null');
        let response = {
            'count':shifts_count,'shifts':[]
        }
        // get all shifts
        if(shifts_count > 0)
        {
            let oss = []
            let all_open_shifts = JSON.parse(get_row('shifts','end_time is null'))

            for (let s = 0; s < all_open_shifts.length; s++) {
                let this_shift = all_open_shifts[s]
                let shift_date = this_shift['shift_date']
                let mech = this_shift['mech_no'];
                let recId = this_shift['recId']
                oss.push([mech,shift_date,recId])
            }

            response['shifts'] = oss

        }

        return response
    }

    register(){
        let desc = $('#description').val()
        let mac_addr = $('#mac_addr').val()
        let mech_no = $('#mech_no').val()

        if(anton.validateInputs(['description','mac_addr','mech_no'])){

            let query = `INSERT INTO mech_setup (mech_no, descr, mac_addr) values ('${mech_no}','${desc}','${mac_addr}')`;
            let data = {
                'cols':['mech_no','descr','mac_addr'],
                'vars':[mech_no,desc,mac_addr]
            }
            console.table(data)
            let savee = exec(query);
            console.assert(savee);

        } else {
            kasa.error("Fill FIelds")
        }
    }

}

class Keyboard {
    showQwerty(input_field='general_input'){
        anton.setCookie('input_field',input_field)
        $('#alphsKeyboard').fadeIn();

    }

    hideQwerty(){

        $('#alphsKeyboard').hide();
    }
}

const keyboard = new Keyboard();