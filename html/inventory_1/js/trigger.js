$(document).ready(function() {
    $("#REFUND").click(function(){
       let bill_amt,amount_paid;
       bill_amt = $('#sub_total').text();
       if(bill_amt.length > 0 && bill_amt > 0){
           // there is bill


           if(confirm("Are you sure you want to refund?"))
           {
               $('#general_input').val(bill_amt);
               bill.payment('refund')
           }

       } else {
           // no bill
       }
    });
});