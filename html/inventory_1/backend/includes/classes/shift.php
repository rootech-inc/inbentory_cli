<?php

namespace billing;

use db_handeer\db_handler;
use billing\Billing;

class shift extends \db_handeer\db_handler
{
    public $response = array('code'=>0,'message'=>'ini');
    function is_shift($mech = mech_no){

        if($this->row_count('shifts',"`mech_no` = '$mech'  AND `end_time` is null ") === 1)
        {
            // there is shift
            return true;

        } else {
            // no shift
            return false;
        }

    }

    function my_shift($recId){
        $response = array(
            'rec'=>null,'date'=>null,'shift_no'=>null,'counter'=>null,
            'enc'=>null,'shift_date'=>null,'start_time'=>null
        );

        if($this->row_count('shifts',"`recId` = '$recId'") === 1){
            $shift = $this->get_rows('shifts',"`recId` = '$recId'");
            $response['rec'] = $shift['recId'];
            $response['date'] = $shift['shift_date'];
            $response['shift_no'] = $shift['shift_no'];
            $response['counter'] = $shift['mech_no'];
            $response['enc'] = $shift['enc'];

            $response['shift_date'] = $shift['shift_date'];
            $response['start_time'] = $shift['start_time'];

            $response['end_date'] = $shift['endate'];
            $response['end_time'] = $shift['end_time'];
        }

        return $response;
    }

    function start_shift($mech_no = mech_no,$clerk = clerk_code){
        $db = (new db_handler());
        $resp = ['code'=>000,'message'=>000];
        $day = today;

        // check if there is already session
        if($this->is_shift($mech_no))
        {
            $resp['code'] = 202; $resp['message'] = "Shift Already Started";
        } else
        {
            // start new shift
            try {
                // shift details
                $mech = $this->get_rows('mech_setup',"`mech_no` = '$mech_no'");
                $mac_addr = $mech['mac_addr'];

                $currentDateTime = date("Y-m-d H:i:s");
                $enc = md5($currentDateTime.$mac_addr.$clerk);
                $shift_no = $db->row_count('shifts',"`mech_no` = '$mech_no' and `shift_date` = '$day'") + 1;
                $start_sql = "INSERT INTO shifts (clerk, mech_no,enc,shift_no) values ('$clerk','$mech_no','$enc','$shift_no')";
                $start_stmt = $this->db_connect()->prepare($start_sql);
                $start_stmt->execute();
                $resp['code'] = 202; $resp['message'] = "Shift Started";

            } catch (\PDOException  $e)
            {
                $resp['code'] = 505; $resp['message'] = $e->getMessage();
            }
        }
        header("Content-Type:Application/Json");
        echo json_encode($resp);
    }

    function end_shit($recId): array
    {
        $db = (new db_handler());
        if($db->row_count('shifts',"`recId` = '$recId'") === 1){
            // shift exist
            // close shift
            $start_sql = "UPDATE shifts SET endate = CURRENT_DATE,end_time = CURRENT_TIME WHERE recId = '$recId'";
            $start_stmt = $this->db_connect()->prepare($start_sql);
            $start_stmt->execute();
            $resp['code'] = 202; $resp['message'] = "Shift Ended";
            $this->eodSerial($recId);
        } else {
            $resp['code'] = 404; $resp['message'] = "NO SHIFT FOUND";
        }

        return $resp;

    }


    function eodSerial($recId){
        // check shift with record exist
        
        if($this->row_count('shifts',"recId = '$recId'") === 1){
            $shift = $this->get_rows('shifts',"recId = '$recId'");
            $sales_date = $shift['shift_date'];

            // check if date exist in eod_serial
            if($this->row_count('eod_serial',"sales_date = '$sales_date'") === 0){
                // insert date
                $this->exe("INSERT INTO eod_serial (sales_date) value ('$sales_date')");
            }
        }
    }

    function closeEod($sales_date): array
    {
        // copy data to history
        $download = (new Billing())->downloadSales($sales_date);
        if($download['code'] === 200){

            $this->exe("INSERT INTO bill_history_header SELECT * FROM bill_header where bill_date = '$sales_date'");
            $this->exe("INSERT INTO `bill_history_trans` SELECT * FROM `bill_trans` where date_added = '$sales_date'");
            $this->exe("delete from bill_header where bill_date ='$sales_date'");
            $this->exe("delete from bill_trans where date_added = '$sales_date'");


            $this->exe("UPDATE eod_serial SET status = 1 where sales_date = '$sales_date'");
        }
        print_r($download);
        return $download;
        

    }
    function shifts($mech_no,$sales_date = today){

        $db = (new db_handler());
        if ($mech_no === '*'){
            $shift_cond = "`shift_date` = '$sales_date'";
            $sum_cond = "`bill_date` = '$sales_date'";
        } else {
            $shift_cond = "`mech_no` = '$mech_no' AND `shift_date` = '$sales_date'";
            $sum_cond = "`mech_no` = '$mech_no' AND `bill_date` = '$sales_date'";
        }
        // check if shift exists
        if($db->row_count('shifts',$shift_cond) > 0){

            $shifts_query = "SELECT * FROM shifts where $shift_cond";
            $stmt = $db->db_connect()->prepare($shifts_query);
            $stmt->execute();
            $gross = $db->sum('bill_header','gross_amt',$sum_cond);
            $tax = $db->sum('bill_header','tax_amt',$sum_cond);
            $net = $gross - $tax;
            $shiftSummary = array(
                "non_taxable_amt"=>$db->sum('bill_header','non_taxable_amt',$sum_cond),
                'taxable_amt'=>$db->sum('bill_header','taxable_amt',$sum_cond),
                'tax_amt'=>$tax,
                'gross'=>$gross,
                'net'=>$net
            );

            $shi_arr = array();
            while($shift = $stmt->fetch(\PDO::FETCH_ASSOC)){
                // get shift details
                $mech_no = $shift['mech_no'];
                $clerk = $shift['clerk'];
                $recId = $shift['recId'];

                $shi_arr[] = array(
                    'mech' => $mech_no, 'clerk' => $clerk, 'recId' => $recId
                );

            }

            $this->response['code'] = 200;
            $this->response['message'] = array('summary'=>$shiftSummary,'trans'=>$shi_arr);

        } else {
            $this->response['code'] = 404;
            $this->response['message'] = "NO SHIFTS $shift_cond";
        }

        return $this->response;
    }

}

$shiftCL = new shift();