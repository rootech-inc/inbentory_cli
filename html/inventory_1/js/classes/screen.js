class screenMaster {



    getScreen(scr_uni)
    {
        let r = 'invalid';
        if(scr_uni.length > 1)
        {
            if(row_count('screen',`scr_uni = '${scr_uni}'`) > 0)
            {
                //
                r = JSON.parse(get_row('screen',`scr_uni = '${scr_uni}'`))[0]
            } else
            {
                swal_error('error%%Screen Not Found')


            }

        } else
        {
            swal_error(`error%%Invalid Screen Settings`)
        }

        return r

    }

    screenAccess(clerk_id,screen)
    {
        let user_exist = row_count('clerk',`id = '${clerk_id}'`);
        if(user_exist > 0)
        {
            // get user details
            let clerk = JSON.parse(User.GetClerk(clerk_id)['result'])

            // todo get user group
            let clerk_grp = clerk['usr_grp']


            if(this.getScreen(screen) !== 'invalid')
            {
                ct(clerk)
                // todo get screen id
                // todo check if user group has read access in screen access
            }

        } else {
            swal_error(`error%%User Not Found ${user_exist}`)
        }
    }

}