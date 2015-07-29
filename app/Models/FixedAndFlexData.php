<?php namespace App\Models;


use App\Repositories\Totals\TotalsRepository;
use App\Services\BudgetTableTotalsService;

/**
 * Class FixedAndFlexData
 * @package App\Models
 */
class FixedAndFlexData {

    /**
     * @var
     */
    public $FB_info;

    /**
     * @var
     */
    public $FLB_info;

    /**
     * @var
     */
    public $RB;

    /**
     * @var
     */
    public $RBWEFLB;

    /**
     * @var TotalsRepository
     */
    private $totalsRepository;

    /**
     * @param TotalsRepository $totalsRepository
     */
    public function __construct(TotalsRepository $totalsRepository)
    {
        $this->totalsRepository = $totalsRepository;
    }

    /**
     * Get all the data for the fixed and flex budget tables,
     * as well as RB and RBWEFLB.
     * This is the method that calls the other total stuff.
     * Todo: Could be fixedandflexdata model with 4 properties (the ones I am returning here)
     * In the future I could also create interfaces.
     * Like example in codementor. Don't use extends model and line 6 here because I don't need the model method things.
     * @return array
     */

    public function getFixedAndFlexData()
    {
        $this->FB_info = $this->totalsRepository->getTagsAndTotalsForSpecifiedBudget('fixed');
        $this->FLB_info = $this->totalsRepository->getTagsAndTotalsForSpecifiedBudget('flex');
        $budgetTableTotalsService = new BudgetTableTotalsService();

        //Get the unallocated values for flex budget
        $this->FLB_info['unallocated'] = $budgetTableTotalsService->getUnallocatedFLB();
        $this->FLB_info['totals']['budget'] = 100;

        return [
            "FB" => $this->FB_info,
            "FLB" => $this->FLB_info,
            "RB" => number_format($this->totalsRepository->getRBWithEFLB(), 2),
            "RBWEFLB" => number_format($this->totalsRepository->getRBWEFLB(), 2)
        ];
    }
}