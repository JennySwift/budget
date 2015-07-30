<?php namespace App\Totals;

/**
 * Class FixedAndFlexData
 * @package App\Totals
 */
/**
 * Class FixedAndFlexData
 * @package App\Totals
 */
class FixedAndFlexData
{

    /**
     * @var
     */
    public $FB;

    /**
     * @var
     */
    public $FLB;

    /**
     * @var
     */
    public $RB;

    /**
     * @var
     */
    public $RBWEFLB;

    /**
     * Get all the data for the fixed and flex budget tables,
     * as well as RB and RBWEFLB.
     * Todo: In the future I could also create interfaces.
     * @return array
     */
    public function __construct()
    {
        $this->FB = new BudgetTable('fixed');
        $this->FLB = new BudgetTable('flex');

        $RB = new RB($this->FB, $this->FLB);
        $this->RB = $RB->withEFLB;
        $this->RBWEFLB = $RB->withoutEFLB;
    }
}