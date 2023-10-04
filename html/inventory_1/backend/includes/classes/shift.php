<?php

namespace billing;

use db_handeer\db_handler;

class shift extends \db_handeer\db_handler
{
    function is_shift($mech = mech_no): bool
    {
//        $day = today;
        if($this->row_count('shifts',"`mech_no` = '$mech'  AND `end_time` is null ") > 0)
        {
            // there is shift
            return true;
        } else {
            // no shift
            return false;
        }

    }

    function start_shift($mech_no = mech_no,$clerk = clerk_code){
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

                $start_sql = "INSERT INTO shifts (clerk, mech_no,enc) values ('$clerk','$mech_no','$enc')";
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
        if($db->row_count('shifts',"`recId` = '$recId'") === 1){
            // shift exist
            // close shift
            $start_sql = "UPDATE shifts SET endate = CURRENT_DATE,end_time = CURRENT_TIME WHERE recId = '$recId'";
            $start_stmt = $this->db_connect()->prepare($start_sql);
            $start_stmt->execute();
            $resp['code'] = 202; $resp['message'] = "Shift Ended";
        } else {
            $resp['code'] = 404; $resp['message'] = "NO SHIFT FOUND";
        }

        return $resp;

    }
}

$shiftCL = new shift();