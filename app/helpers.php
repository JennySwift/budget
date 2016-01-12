<?php
use App\Exceptions\ModelAlreadyExistsException;
use App\Exceptions\NotLoggedInException;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

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
    return array_merge($base, array_filter($newItems));
}

/**
 *
 * @param $variable
 * @return int
 */
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

/**
 * For array_filter(), when I don't want values that are 0 to be removed
 * @param $value
 * @return bool
 */
function removeFalseKeepZero($value)
{
    return $value || is_numeric($value);
}

/**
 * For array_filter(), when I don't want values that are 0 to be removed,
 * or empty strings, for example, if a transaction has a description and they
 * remove it, changing it to an empty string.
 * @param $value
 * @return bool
 */
function removeFalseKeepZeroAndEmptyStrings($value)
{
    return $value || is_numeric($value) || $value === '';
}

/**
 *
 * @param $resource
 */
function transform($resource)
{
    $manager = new Manager();
    $manager->setSerializer(new DataArraySerializer);

    $manager->parseIncludes(request()->get('includes', []));

//    return $manager->createData($resource);
    return $manager->createData($resource)->toArray();
}

/**
 *
 * @param $model
 * @param TransformerAbstract $transformer
 * @param null $key
 * @return Collection
 */
function createCollection($model, TransformerAbstract $transformer, $key = null)
{
    return new Collection($model, $transformer, $key);
}

/**
 * @param Model               $model
 * @param TransformerAbstract $transformer
 * @param null                $key
 * @return Item
 */
function createItem($model, TransformerAbstract $transformer, $key = null)
{
    return new Item($model, $transformer, $key);
}

