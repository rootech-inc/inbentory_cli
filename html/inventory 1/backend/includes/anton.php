<?php


class anton
{
    /** @noinspection MultiAssignmentUsageInspection */
    public function set_session($arr_of_data)
    {
        foreach ($arr_of_data as $iValue) // loop through each session data
        {
            $sess_data = $iValue;
            if(count(explode('=',$sess_data)) === 2) //if it is exploded and count is exactly 2
            {
                $sess_data_explode = explode('=',$sess_data);
                $session_variable = $sess_data_explode[0]; // session variable
                $session_value = $sess_data_explode[1]; // session value

                $_SESSION[(string)$session_variable] = $session_value; // set session



            }

        }
    }

    public function get_session($variable)
    {
        if(isset($_SESSION[$variable]))
        {
            return $_SESSION[$variable];
        }

        return false;
    }

    public function post($field_name) // post form
    {
        if(isset($_POST[$field_name]))
        {
            return htmlentities($_POST[$field_name]);
        }

        return 'empty';
    }

    function get($field_name) // get form
    {
        if(isset($_GET[$field_name]))
        {
            return htmlentities($_GET[$field_name]);
        }

        return 'empty';
    }


    public function compare($str1, $str2) // compare 2 strings
    {
        return $str1 === $str2;
    }

    public function done($message = 'Process Complete')
    {
        echo "done%%$message";
        exit();
    }
    public function err($message = "System Error")
    {
        echo "error%%$message";
        exit();
    }

    // print bill
    public function print_bill($bill_number,$db_connection)
    {
        // get list of bill items
        // todo print bill


    }

    public function percentage($rate,$vale)
    {
        return ($rate/100) * $vale;
    }


}