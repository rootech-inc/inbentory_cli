const inventory_backend = 'backend/process/form-processing/category-form-process.php';
$(function() {
    //hang on event of form with id=myform
    $("#new_sub_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#new_sub_form')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                echo(response);
                i_hide('numericKeyboard')
                $('#general_input').val('');
                error_handler(response);

            },

        });

        return false;

    });

});


function catDesc(val) {
    if($('#short_description').val(val).length <= 10)
    {
        $('#short_description').val(val.substr(0,15))
    }
}

function newSub(target,parent = '') { // create new sub
    if(target === 'item_group_sub') // create item group sub
    {
        // check if there are sub in current subs
        let current_group = $('#sort_left').val();
        let table = 'item_group_sub';
        let condition = "`parent` = '" + current_group.toString() + "'";

        // fire modal to add new
        gen_modal('new_item_sub_group','New Sub Group in ' + parent.toString(),current_group);
    }
}

function categorySub(id,action='view') {
    let table, condition,sub_res,all_subs;
    table = 'item_group_sub';
    condition = "`parent` = '" + id.toString() + "'";
    echo(action)
    if(row_count(table,condition) > 0)
    {
        all_subs = get_row(table,condition);

        var obj = JSON.parse(all_subs);

        let id,desc,table_start,table_end;
        // table_start = "<table> <thead><tr><th>ID</th><th>Description</th></tr></thead>";

        var row = "";
        for(let i = 0; i < obj.length; i++)
        {
            id = obj[i].id
            desc = obj[i].description
            if(action === 'edit')
            {
                row += "<tr><td>"+id.toString()+"</td><td class='h-100'><input type='text' class='form-control form-control-sm h-100' name='group_sub["+id+"]' value='"+desc.toString()+"'></td><td><button type='button' class='btn btn-danger h-100'>Del</button></td></tr>";
            } else
            {
                row += "<tr><td>"+id.toString()+"</td><td>"+desc.toString()+"</td></tr>";
            }

        }


        table_end = "<div class='w-100 h-100 overflow-auto table-responsive'>\n" +
            "                        <table class='table table-sm table-striped bg-x'>\n" +
            "                            <tbody>"+row+"</tbody>\n" +
            "                        </table>\n" +
            "                    </div>";

        $('#categorySub').html(table_end);

    }
    else
    {
        $('#categorySub').html("<p class='text-muted'>No Sub</p>");
    }


}

function loadCategory(id,action='view')
{
    let code = '',desc = '',
        short_desc = '',tax = '',
        owner = '', date_created = '',
        time_created = '',modified = '',
        date_mod = '',time_mod = '';

    form_data = {
        'function':'load_category',
        'target':id
    }



    $.ajax(
        {
            url:'backend/process/form-processing/category-form-process.php',
            type: 'POST',
            data:form_data,
            success: function (response) {
                if(responseType(response) === 'done')
                {
                    let returned = responseMessage(response);
                   if(returned.split('^').length === 8)
                   {
                       var r = returned.split('^');

                       code = r[0]; desc = r[1], short_desc = r[2];
                       tax = r[3]; owner = r[4]; date_created = r[5];
                       date_mod = r[7]; modified = r[6];

                       if(action === 'edit')
                       {
                           $('#code').val(code)
                           $('#desc').val(desc)
                           $('#short_description').val(short_desc)


                           // get all tax
                           let all_taxes = get_row(`tax_master`,'none')
                           var obj = JSON.parse(all_taxes);

                           var option = '';

                           for(let i = 0; i < obj.length; i++)
                           {
                               id = obj[i].id
                               desc = obj[i].description
                               if(desc === tax)
                               {
                                   option += "<option selected value='"+id+"'>"+desc+"</option>";
                               }
                               else
                               {
                                   option += "<option value='"+id+"'>"+desc+"</option>";
                               }

                               //echo(id)

                           }
                            var tax_sel = "<select name='tax' class='prod_inp'>"+option+"</select>";
                           echo(tax_sel)
                           $('#tax_res').append(tax_sel)

                       }
                       else
                       {
                           $('#code').text(code)
                           $('#desc').text(desc)
                           $('#short_desc').text(short_desc)
                           $('#tax').text(tax)
                       }
                       $('#owner').text(owner)
                       $('#date_created').text(date_created)
                       $('#modified').text(modified)
                       $('#date_mod').text(date_mod)



                       categorySub(code,action)

                       $('#sort_left').val(code)
                       $('#sort_right').val(code)



                       if(row_count('item_group',"`id` > '" + code.toString() + "'") > 0 )
                       {
                           // enabled next
                           arr_enable('sort_right')
                       } else {
                           // disable next
                           arr_disable('sort_right')
                       }

                       if(row_count('item_group',"`id` < '" + code.toString() + "'") > 0 )
                       {
                           // enabled prev
                           arr_enable('sort_left')
                       } else {
                           // disable prev
                           arr_disable('sort_left')
                       }

                       $('#catSearchBox').modal('hide');

                   }
                }
            }
        }
    );



}

// submit sub category form
$(function() {
    //hang on event of form with id=myform
    $("#sub_category_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#sub_category_form')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                echo(response);
                //i_hide('numericKeyboard')
                $('#general_input').val('');
                error_handler(response);

            },

        });

        return false;

    });

});

// category search
$('#categorySearch').on('keyup',function (e) {
    let query = $('#categorySearch').val();
    if(query.length > 0)
    {
        // post form
        form_data = {
            'function':'category_search',
            'query':query
        }
        $.ajax({
            url: inventory_backend,
            type: 'POST',
            data: form_data,
            success: function (response) {
                let res_type, res_msg;
                res_type = responseType(response);
                res_msg = responseMessage(response);

                if(res_type === 'done')
                {
                    // append message
                    $('#catRes').html(res_msg)
                }

            }
        })
    }
})

// update category
$(function() {
    //hang on event of form with id=myform
    $("#update_group").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#update_group')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                //echo(response);
                $('#general_input').val('');
                error_handler(response);
                set_session(['action=view'])

            },

        });

        return false;

    });

});

