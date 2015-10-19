<?php namespace App\Http\Transformers;

use App\Models\SavedFilter;
use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class SavedFilterTransformer
 */
class SavedFilterTransformer extends TransformerAbstract
{
    /**
     *
     * @param SavedFilter $savedFilter
     * @return array
     */
    public function transform(SavedFilter $savedFilter)
    {
        return [
            'id' => $savedFilter->id,
            'name' => $savedFilter->name,
            'filter' => $savedFilter->filter
        ];
    }

}