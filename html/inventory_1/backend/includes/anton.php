<?php

    include_once 'pdf/fpdf.php';



class anton extends FPDF
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

    public function unset_session($arr_of_data)
    {
        foreach ($arr_of_data as $iValue) // loop through each session data
        {
            $sess_data = $iValue;
            if(count(explode('=',$sess_data)) === 2) //if it is exploded and count is exactly 2
            {
                $sess_data_explode = explode('=',$sess_data);
                $session_variable = $sess_data_explode[0]; // session variable
                $session_value = $sess_data_explode[1]; // session value

                if(isset($_SESSION["$session_variable"]))
                {
                    unset($session_variable);
                }





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

    function return_error($message = ''): string
    {
        return "error%%$message";
    }

    public function not_session($variable,$value)
    {
        if(!isset($_SESSION["$variable"]))
        {
            $this->set_session(["$variable=$value"]);
        }
    }

    public function generateEAN($number): string
    {
        $code = '200' . str_pad($number, 9, '0');
        $weightflag = true;
        $sum = 0;
        // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
        // loop backwards to make the loop length-agnostic. The same basic functionality
        // will work for codes of different lengths.
        for ($i = strlen($code) - 1; $i >= 0; $i--)
        {
            $sum += (int)$code[$i] * ($weightflag?3:1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;
    }

    // print bill
    public function print_bill($bill_number,$type)
    {
        include_once 'barcode/barcode.php';
        // logo
        $ean8Code = $this->generateEAN('123');
        $barcode = new Barcode($ean8Code,'2','');
        $barcode->save();
        $temp = tmpfile();

        // get list of bill items
        // todo print bill
        $pdf = new FPDF('P','mm','pos');
        $pdf->AddPage();
        $pdf->SetFont('Arial','',2);
        $pdf->Cell(50,10,'Hello','B','L');
        $pdf->Image('barcode.png',10,10,'50','10','');


        $pdf->Output('F','/home/stuffs/Development/PHP/inbentory_cli/html/inventory_1/assets/test.pdf');


    }

    public function percentage($rate,$vale)
    {
        return ($rate/100) * $vale;
    }

    // calculate for tax
    public function tax($rate, $amount)
    {
        return $rate / 100 * $amount;

        // calculate levy

        // ad levy to retail

        //cal vat

        //return new result
    }


   public function generateRandomString($length = 10): string
   {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function reload()
    {
        echo "<script>location.reload()</script>";
    }

    public function br($str = '')
    {
        echo "\n $str \n";
    }

    public function input_tax($value,$class) // return an input tax
    {
        $tax_amount = 0;
        if($class === 'V3')
        {
            // calculate levies
            $levies_rate = 5;
            $levies_value = $this->tax($levies_rate,$value);
            $new_vale = $value + $levies_value;
            $tax_amount = $this->tax(12.5,$new_vale);
        }

        return $tax_amount;
    }


}