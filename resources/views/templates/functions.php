<?php

use App\Transaction_Tag;
use App\Color;

// DB::enableQueryLog();

include('filter-function.php');
include('total-functions.php');

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



function convertToBoolean($variable)
{
	if ($variable === 1) {
		$variable = true;
	}
	else {
		$variable = false;
	}
	return $variable;
}

function convertDate($date, $for)
{
	$date = new DateTime($date);

	if ($for === 'user') {
		$date = $date->format('d/m/y');
	}
	elseif ($for === 'sql') {
		$date = $date->format('Y-m-d');
	}
	return $date;
}

function numberFormat($array)
{
	$formatted_array = array();
	foreach ($array as $key => $value) {
		$formatted_value = number_format($value, 2);
		$formatted_array[$key] = $formatted_value;
	}

	return $formatted_array;
}

?>
