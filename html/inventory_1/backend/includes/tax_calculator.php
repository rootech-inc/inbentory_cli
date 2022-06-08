<?php

class tax_calculator extends db_handler
{
    public function tax_input($value,$class)
    {
        if($class === 'gra_latest')
        {
            // get levies
            $nhis = $this->percentage(2.5,$value);
            $get_fund = $this->percentage(2.5,$value);
            $covid = $this->percentage(1,$value);
            $levis = $nhis + $get_fund + $covid;

            // set new value
            $new_value = $levis + $value;
        }
    }

}