<?php
use App\Exceptions\ModelAlreadyExistsException;
use App\Exceptions\NotLoggedInException;
use Carbon\Carbon;

/**
 * Throw an exception with a helpful error when the user is not logged in
 */
//function checkLoggedIn ()
//{
//    if (!Auth::check()) {
//        throw new NotLoggedInException;
//    }
//}

/**
 * Merge two array together, passing the second array through array filter to remove null values
 * @param array $base
 * @param array $newItems
 * @return array
 */
function array_compare(array $base, array $newItems)
{
//    dd($base);
    return array_merge($base, array_filter($newItems));
}

/**
 * Loop through an array, formatting each value
 * @param $array
 * @return array
 */
function numberFormat($array)
{
    $formatted_array = array();
    foreach ($array as $key => $value) {
        $formatted_array[$key] = number_format($value, 2);
    }

    return $formatted_array;
}

/**
 * Loop through an object's properties, formatting each value
 * @param $totals
 */
function numberFormatObject($totals)
{
    foreach ($totals as $key => $value) {
        $totals->$key = number_format($value, 2);
    }
    return $totals;
}

/**
 *
 * @param $variable
 * @return int
 */
//function convertFromBoolean($variable)
//{
//    if ($variable == 'true') {
//        $variable = 1;
//    }
//    elseif ($variable == 'false') {
//        $variable = 0;
//    }
//
//    return $variable;
//}

/**
 *
 * @param $variable
 * @return bool
 */
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

/**
 *
 * @param $date
 * @param $for
 * @return string
 */
function convertDate( Carbon $date, $for = NULL)
{
    switch($for) {
        case "sql":
            return $date->format('Y-m-d');
            break;
        default:
            return $date->format('d/m/y');
            break;
    }
//    if ($for === 'user') {
//        return $date->format('d/m/y');
//    }
//    elseif ($for === 'sql') {
//        return $date->format('Y-m-d');
//
//    }
}

/**
 * Check the user doesn't have the model already with the same name
 * @param $model
 */
function checkForDuplicates($model)
{
    $duplicate_count = $model::where('user_id', Auth::user()->id)
        ->where('name', $model->name)
        ->count();

    if ($duplicate_count) {
        throw new ModelAlreadyExistsException(class_basename(get_class($model)));
    }
}

