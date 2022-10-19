<?php


class tax_calculator extends anton
{
    public function tax_input($value,$class)
    {
        if($class === 'gra_latest')
        {
            // get levies
            $nhis = (new \anton)->percentage(2.5,$value);
            $get_fund = (new \anton)->percentage(2.5,$value);
            $covid = (new \anton)->percentage(1,$value);
            $levis = $nhis + $get_fund + $covid;

            // set new value
            $new_value = $levis + $value;
        }
    }

}