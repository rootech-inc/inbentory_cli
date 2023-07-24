<?php

namespace billing;

use db_handeer\db_handler;

class ProductMaster extends db_handler
{

    /**
     * @var string
     */
    private $itemBarcode;

    public function __construct($itemBarcode)
    {
        $this->itemBarcode = $this->sanitizeInput($itemBarcode);
    }
    function isTaxable(): bool
    {
        $result = false;


        // Check if product exists in the database
        if ($this->row_count('prod_mast', "`barcode` = '$this->itemBarcode'") === 1) {
            // Get the tax group of the product
            $tax_grp = $this->get_rows('prod_mast', "`barcode` = '$this->itemBarcode'")['tax_grp'];

            // Check if tax group exists
            if ($this->row_count('tax_master', "`id` = '$tax_grp'") === 1) {
                // Get the tax code
                $tax_code = $this->get_rows('tax_master', "`id` = '$tax_grp'")['attr'];

                if ($tax_code === 'VM') {
                    $result = true;
                }
            }
        }

        return $result;
    }

    private function sanitizeInput($input): string
    {
        // Sanitize the input to prevent SQL injection
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    public function getPrice(): array
    {
        $resp = array('taxAmt'=>0,'taxableAmt'=>0,'amount'=>0);
        if ($this->row_count('prod_mast', "`barcode` = '$this->itemBarcode'") === 1){
            $amount = $this->get_rows('prod_mast', "`barcode` = '$this->itemBarcode'")['retail'];
            $resp['amount'] = $amount;
            $taxAmt = 0;
            $taxableAmt = 0;
            if($this->isTaxable()){

                $taxAmt = $amount  * 21.90;
                $taxAmt /= 121.9;
                $taxableAmt = $amount - $taxAmt;
            }

            $resp['taxAmt'] = $taxAmt;
            $resp['taxableAmt'] = $taxableAmt;

        }

        return $resp;
    }
}



