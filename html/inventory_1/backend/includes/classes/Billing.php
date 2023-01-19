<?php
namespace billing;
use mechconfig\MechConfig;
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
        $today = today;
        $clerk_code = clerk_code;

        //get item details
        $machine_number = mech_no;
        $clerk = $_SESSION['clerk_id'];


        // get item details
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $barcode = $item['barcode'];
        $item_code = $item['id'];
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
        $tax_code = $taxDetails['attr'];

        if($taxDetails['attr'] === 'V0')
        {
            $taxAmount = 0.00;
        }
        elseif ($taxDetails['attr'] === 'VM')
        {
            // calculate for tax
            $tax = (new \anton())->tax($tax_code,$bill_amt);
            $taxAmount = $tax['details']['taxableAmount'];


//            $taxAmount = (new \anton)->tax($rate,$bill_amt);
        } else
        {
            $taxAmount = 0.00;
        }





        // add to bill in trans
        $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`) values
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate')";

        $tax_tran = "insert into bill_tax_tran (bill_date, clerk_code, mech_no, bill_no, tran_code, tran_qty, tax_code,taxableAmt,tax_amt)
                    VALUES ('$today','$clerk_code','$machine_number','$bill_number','$item_code','$qty','$tax_code','$bill_amt','$taxAmount')";

//        print_r($sql);
        print_r($tax_tran);

        try {
            (new \db_handeer\db_handler())->db_connect()->prepare($sql);
            (new \db_handeer\db_handler())->db_connect()->exec($sql);
            (new \db_handeer\db_handler())->db_connect()->prepare($tax_tran);
            (new \db_handeer\db_handler())->db_connect()->exec($tax_tran);

            return true;

        } catch (PDOException  $e)
        {
//            echo "error%%".$e->getMessage();
            return false;
        }


    }

    public function billTotal($bill_number,$date): array
    {
        $machine_number = mech_no;
        $response = [
            'valid'=>'N','tran_qty'=>0.00,'taxable_amt'=>0.00,'tax_amt'=>0.00,'bill_amt'=>0.00,'amt_paid'=>0.00,'amt_bal'=>0.00,
            'disc_valid'=>'N','disc_rate'=>0.00,'disc_value'=>0.00
        ];
        $tran_qty = $this->db_handler()->row_count('bill_trans',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number");
        $disc_cond = "`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'D'";
//        die($disc_cond);
        $disc_qty = $this->db_handler()->row_count('bill_trans',$disc_cond);
        $taxable_amt = $this->db_handler()->sum('bill_trans','retail_price',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'i'");
        $tax_amt = $this->db_handler()->sum('bill_tax_tran','tax_amt',"`bill_no` = '$bill_number' and `bill_date` = '$date' and mech_no = $machine_number");
        $bill_amt = $this->db_handler()->sum('bill_trans','bill_amt',"`bill_number` = '$bill_number' and `date_added` = '$date' and mach = $machine_number and `trans_type` = 'i'");

        if($tran_qty > 0)
        {
            $response['valid'] = 'Y';
            $response['tran_qty'] = $tran_qty;
            // get sums
            $response['taxable_amt'] = $taxable_amt;
            $response['tax_amt'] = $tax_amt;
            $response['bill_amt'] = $bill_amt;

            if($disc_qty === 1){
                $discount = $this->db_handler()->fetch_rows("SELECT * FROM bill_trans where $disc_cond",'array');
                $response['disc_valid'] = 'Y';
                // $response['disc_rate'] = $discount['bill_amt'];
                // $response['disc_value'] = $this->anton()->percentage($response['disc_rate'],$response['taxable_amt']);
                // $response['taxable_amt'] -= $response['disc_value'];
                // $response['bill_amt'] = $response['taxable_amt'];
                // $tax_amt = $response['tax_amt'];
                // $response['tax_amt'] = $this->anton()->percentage($response['disc_rate'],$tax_amt);
            }

        }

        

        return $response;


    }

    public function makePyament($method,$amount_paid): array
    {
        $myName = $_SESSION['clerk_id'];
        $today = date('Y-m-d');
        $response = ['status'=>505,'message'=>'initialization'];
        // get current bill details
        $bill_number = bill_no;
        $machine_number = (new MechConfig())->mech_details()['mechine_number'];
        $bill_tran_cond = "`bill_date` = '$today' and `mech_no` = '$machine_number' and `bill_number` = '$bill_number'";
        $bill_hd_cond = "`bill_date` = '$today' and `mach_no` = '$machine_number' and `bill_no` = '$bill_number'";
        $bill_trans_count = (new db_handler())->row_count('bill_trans',"`date_added` = '$today' and `mach` = '$machine_number' and `bill_number` = '$bill_number'");

        if($bill_trans_count > 0)
        {

            // get transaction details
            $bill_totals = $this->billTotal($bill_number,$today);
            if($bill_totals['valid'] === 'Y')
            {
                $gross_amt = $bill_totals['taxable_amt'];
                $tax_amt = $bill_totals['tax_amt'];
                $bill_amt = $bill_totals['bill_amt'];
                $tran_qty = $bill_totals['tran_qty'];
                $amt_balance = $amount_paid - $gross_amt;
                $bill_totals['amt_paid'] = number_format($amount_paid,2);
                $bill_totals['amt_bal'] = number_format($amt_balance,2);

                #1 make bill tran payment.
                #2 make bill hd payment,
                #3 return bill details
                $bill_header_insert = "INSERT INTO bill_header (mach_no, clerk, bill_no, pmt_type, gross_amt, tax_amt, net_amt,tran_qty,amt_paid,amt_bal)VALUES 
                                                                        ($machine_number, '$myName', $bill_number, '$method', $gross_amt, $tax_amt, $bill_amt, $tran_qty,$amount_paid,$amt_balance);
";
                if($this->db_handler()->row_count('bill_header',$bill_hd_cond) == 0)
                {
                    // make bill
                    $this->db_handler()->db_connect()->exec($bill_header_insert);
                    if($method === 'refund')
                    {
                        $this->db_handler()->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values 
                                                                                                    ('$machine_number','$bill_number','$method','R','$myName','REFUND')");
                    } else
                    {
                        $this->db_handler()->db_connect()->exec("insert into `bill_trans` (`mach`,`bill_number`,`item_desc`,`trans_type`,`clerk`,`item_barcode`) values ('$machine_number','$bill_number','$method','P','$myName','PAYMENT')");
                    }

                }
            }

            $response['status'] = 200;
            $response['message'] = $bill_totals;

        } else
        {
            $response['status'] = 404;
            $response['message'] = 'Cannot make payment for an empty transaction';
        }

        return $response;


    }

    private function db_handler(): db_handler
    {
        return (new db_handler());
    }

    private function anton(): anton
    {
        return (new anton());
    }

}