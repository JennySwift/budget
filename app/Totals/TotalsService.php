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

//    /**
//     * @var FixedAndFlexData
//     */
//    private $fixedAndFlexData;
//
//    /**
//     * @param Total $total
//     * @param FixedAndFlexData $fixedAndFlexData
//     */
//    public function __construct(FixedAndFlexData $fixedAndFlexData)
//    {
//        $this->fixedAndFlexData = $fixedAndFlexData;
//    }

    /**
     *
     * @return array
     */
    public function getBasicAndBudgetTotals()
    {
        // If in the future you feel like you need to update this response, I'd suggest to create a
        // BasicAndBudgetTotal object, easier to maintain on the long run.
        return [
            'basic' => BasicTotals::createFromDatabase()->toArray(),
            'budget' => FixedAndFlexData::createFromDatabase()->toArray()
        ];
    }

    /**
     *
     * @return array
     */
//    public function getBasicTotals()
//    {
        //Could change 'get' to 'calculate' in the method names
//        $basicTotals = new BasicTotals();
//        $credit = $basicTotals->getCredit();
//        $debit = $basicTotals->getDebit();

//        return BasicTotals::createFromDatabase()->toArray();

        //$totals could be an object
        //Learn about presenters? Lesson on Laracasts.


        // This could be an object and/or a transformer item :)
//        $totals = array(
//            "credit" => number_format($basicTotals->credit, 2),
//            "debit" => number_format($basicTotals->debit, 2),
//            "balance" => number_format($basicTotals->credit + $basicTotals->debit, 2),
//            "reconciled_sum" => number_format($basicTotals->reconciledSum, 2),
//            "savings" => number_format($basicTotals->savings, 2),
//            "EWB" => number_format($basicTotals->EWB, 2)
//        );

//        return $basicTotals->toArray();
//    }
//
//    /**
//     *
//     * @return array
//     */
//    public function getFixedAndFlexData()
//    {
//        return new FixedAndFlexData();
//    }
}