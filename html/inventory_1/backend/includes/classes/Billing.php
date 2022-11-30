<?php
namespace billing;
use PDO;
use PDOException;
use anton;
use db_handeer\db_handler;
class Billing
{

    function billNumber (): int
    {
        $machine_number = mech_no;
        $today = today;
        return (new \db_handeer\db_handler)->row_count('bill_header',"`mach_no` = '$machine_number' and `bill_date`
         = '$today'") + 1;
    }

    public function AddToBill($bill_number,$item,$qty,$myName)
    {
        //get item details
        $machine_number = mech_no;
        $clerk = $_SESSION['clerk_id'];


        // get item details
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $barcode = $item['barcode'];
        $disc = $item['discount'];
//        echo $disc;


        if($item['discount'] == '1')
        {
            // calculate discount rate off

            //$retail_p = $item_retail;
            $discount_rate = $item['discount_rate'];
            $retail_p = $item_retail - (new \anton())->percentage($discount_rate,$item_retail);
        }
        else
        {
            $retail_p = $item_retail;
        }

        $bill_amt = $retail_p * $qty;
        $tax_group = $item['tax_grp'];


        // get tax rate
        $taxDetails = (new \db_handeer\db_handler())->get_rows('tax_master',"`id` = '$tax_group'");
        $rate = $taxDetails['rate'];
        $tax_description =$taxDetails['description'];
        if($taxDetails['rate'] < 1)
        {
            $taxAmount = 0.00;
        }
        else
        {
            // calculate for tax
            $taxAmount = (new \anton)->tax($rate,$bill_amt);
        }



        // add to bill in trans
        $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`) values
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate')";


        try {
            (new \db_handeer\db_handler())->db_connect()->prepare($sql);
            (new \db_handeer\db_handler())->db_connect()->exec($sql);

            return true;

        } catch (PDOException  $e)
        {
//            echo "error%%".$e->getMessage();
            return false;
        }


    }

    public function billTotal(){
        $response = [
            'valid'=>'N','tran_qty'=>0.00,'taxable_amt'=>0.00,'tax_amt'=>0.00,'bill_amt'=>0.00
        ];
    }

    public function makePyament()
    {
        $response = ['status'=>505,'message'=>'initialization'];
        // get current bill details
        $bill_number = bill_no;
        $bill_trans_count = (new db_handler())->row_count('bill_trans',"`bill_date` = '$today' and `mech_no` = '$machine_number' and `bill_number` = '$bill_number'");

        if($bill_trans_count > 0)
        {
            // get transaction details

        } else
        {
            $response['status'] = 404;
            $response['message'] = 'Cannot make payment for an empty transaction';
        }


    }

}