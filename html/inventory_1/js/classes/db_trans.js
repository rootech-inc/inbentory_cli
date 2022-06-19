class Db_trans {
    // add transaction
    new_doc_trans(doc,entry_no,func)
    {
        let form_data = {
            'function':'doc_trans',
            'doc':doc,
            'func': func,
            'entry_no':entry_no
        }

        // ajax call
        $.ajax({
           url:'backend/process/ajax_tools.php',
           type:'POST',
           data: form_data,
           success: function (response) {
               cl("Transaction Added for " + doc)
           } 
        });
    }
}