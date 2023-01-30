<?php

namespace billing;

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
                $start_sql = "INSERT INTO shifts (clerk, mech_no) values ('$clerk','$mech_no')";
                $start_stmt = $this->db_connect()->prepare($start_sql);
                $start_stmt->execute();
                $resp['code'] = 202; $resp['message'] = "Shift Started";

            } catch (\PDOException  $e)
            {
                $resp['code'] = 505; $resp['message'] = $e->getMessage();
            }
        }

        echo json_encode($resp);
    }
}

$shiftCL = new shift();