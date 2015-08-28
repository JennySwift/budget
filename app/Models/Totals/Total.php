<?php

namespace App\Models\Totals;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Total
 * @package App\Models\Totals
 */
class Total implements Arrayable
{

    public $basic;
    public $budget;

    /**
     * @param BasicTotal $basicTotal
     * @param FixedAndFlexData $fixedAndFlexData
     */
    public function __construct(BasicTotal $basicTotal, FixedAndFlexData $fixedAndFlexData)
    {
        $this->basic = $basicTotal;
        $this->budget = $fixedAndFlexData;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'basic' => $this->basic->toArray(),
            'budget' => $this->budget->toArray()
        ];
    }

}