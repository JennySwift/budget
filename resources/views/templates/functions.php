<?php

use App\Transaction_Tag;
use App\Color;

// DB::enableQueryLog();

function convertFromBoolean($variable)
{
    if ($variable == 'true') {
    	$variable = 1;
    }
    elseif ($variable == 'false') {
    	$variable = 0;
    }
    return $variable;
}








?>
