<?php namespace App\Totals;

use App\Models\Savings;
use Auth;
use DB;

/**
 * Class TotalsService
 * For dealing with the basic totals
 * @package App\Totals
 */
class TotalsService
{

    /**
     * @var FixedAndFlexData
     */
    private $fixedAndFlexData;

    /**
     * @param Total $total
     * @param FixedAndFlexData $fixedAndFlexData
     */
    public function __construct(FixedAndFlexData $fixedAndFlexData)
    {
        $this->fixedAndFlexData = $fixedAndFlexData;
    }

    /**
     *
     * @return array
     */
    public function getBasicAndBudgetTotals()
    {
        return [
            'basic' => $this->getBasicTotals(),
            'budget' => $this->getFixedAndFlexData()
        ];
    }

    /**
     *
     * @return array
     */
    public function getBasicTotals()
    {
        $basicTotals = new BasicTotals();
        $credit = $basicTotals->getCredit();
        $debit = $basicTotals->getDebit();

        $totals = array(
            "credit" => number_format($credit, 2),
            "debit" => number_format($debit, 2),
            "balance" => number_format($credit + $debit, 2),
            "reconciled_sum" => number_format($basicTotals->getReconciledSum(), 2),
            "savings" => number_format(Savings::getSavingsTotal(), 2),
            "EWB" => number_format($basicTotals->getEWB(), 2)
        );

        return $totals;
    }

    /**
     *
     * @return array
     */
    public function getFixedAndFlexData()
    {
        return new FixedAndFlexData();
    }
}