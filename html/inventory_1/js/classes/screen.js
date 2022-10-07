class Screen {



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
        if(get_row('clerk',`id = '${clerk_id}'`) > 0)
        {
            // todo get user details

            // todo get user group

            // todo get user screen id

            // todo check if user group has read access in screen access
            

        }
    }

}