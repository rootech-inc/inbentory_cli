<?php

namespace taxer;
use anton;

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

    public function taxInclusive($tax_code,$value): array
    {
        $result = array('code'=>0,'message'=>'initializing');
        $db_conn = (new \db_handeer\db_handler());

        // check if tax exsit
        if($db_conn->row_count('tax_master',"`attr` = '$tax_code'") === 1){
            $tax_details = $db_conn->get_rows('tax_master',"`attr` = '$tax_code'");

            if($tax_code == 'VM'){

                $covidRate = 1;
                $nhisRate = 2.5;
                $getFundRate = 2.5;

                $totalCost = $value; // retail price + quantity

                $taxableAmount = $totalCost * 100 / 121.9;

                // get levies values
                $covid = ($covidRate / 100) * $taxableAmount;
                $nhis = ($nhisRate / 100) * $taxableAmount;
                $gFund = ($getFundRate / 100) * $taxableAmount;
                $vat = number_format((15.9 / 100) * $taxableAmount,2);

                $tax_detail = array(
                    'code'=>$tax_code,
                    'vat'=>number_format($vat,2),
                    'cv'=>number_format($covid,2),
                    'gf'=>number_format($gFund,2),
                    'nh'=>number_format($nhis,2)
                );
                $result['code'] = 200;
                $result['message'] = $tax_detail;

            }

        } else {
            $result['code'] = 505;
            $result['message'] = "No tax component with code $tax_code";
        }

        return $result;
    }



}