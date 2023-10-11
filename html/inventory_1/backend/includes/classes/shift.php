<?php

namespace billing;

use db_handeer\db_handler;

class shift extends \db_handeer\db_handler
{
    
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

}

$shiftCL = new shift();