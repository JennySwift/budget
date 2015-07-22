<?php

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
 *
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
