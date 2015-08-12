<?php

namespace App\Totals;


/**
 * Class Format
 * @package App\Totals
 */
class Format {

    /**
     * @param $number
     */
    public function __construct($number)
    {
        $this->raw = $number;
        $this->formatted = number_format($number, 2);
    }
}