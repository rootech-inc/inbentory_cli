//START OF PRODUCT  MASTER BUTTONS

function prodScreen(screen) {
    if(row_count('system_buttons',`elem_id = '${screen}'`) === 1)
    {
        // get screen details
        var screen_details = JSON.parse(get_row('system_buttons',`elem_id = '${screen}'`))[0]
        let module,sub_module,sub_sub_module,target_id
        module = screen_details.module
        sub_module = screen_details.sub_module
        sub_sub_module = screen_details.sub_sub_module
        target_id = screen_details.target_id

        // get all same group buttons
        let all_buttons = JSON.parse(get_row('system_buttons',`module = '${module}' AND sub_module = '${sub_module}' AND sub_sub_module = '${sub_sub_module}' AND target_id != '${target_id}' `));
        for (let i = 0; i < all_buttons.length; i++) {
            let this_button = all_buttons[i]
            let this_target = this_button.target_id
            cl(this_target)
            $(`#${this_target}`).hide()
        }
        $(`#${target_id}`).show()


        //swal_error(`Module : '${module}' | Sub Module : '${sub_module}' | Sub Sub Module : '${sub_sub_module}'`)



    } else {
        swal_error(`${screen} : Screen Not Found`)
    }

}

// END OF PRODUCT MASTER UTTONS