<?php

namespace App\Services;

use App\Models\Savings;
use App\Models\Tag;
use App\Models\Transaction;
use App\Repositories\Tags\TagsRepository;
use Illuminate\Support\Facades\DB;
use Auth;

/**
 * Class BudgetService
 * @package App\Services
 */
class BudgetService {

    /**
     * @var TagsRepository
     */
    protected $tagsRepository;

    /**
     * @param TagsRepository $tagsRepository
     */
    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    public function getTagsWithSpecifiedBudget($type)
    {
        if ($type === 'fixed') {
            $tags = $this->tagsRepository->getTagsWithFixedBudget();
        }
        elseif ($type === 'flex') {
            $tags = $this->tagsRepository->getTagsWithFlexBudget();
        }
        return $tags;
    }

    public function getTotalBudget($tags, $type)
    {
        $total = 0;
        $string = $type . '_budget';

        foreach ($tags as $tag) {
            $total += $tag->$string;
        }

        return $total;
    }

    /**
     * Get total expenses, either fixed or flex, after starting date
     * @return int
     */
    public function getTotalEACSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getTotalSpentAfterCSD();
        }

        return $total;
    }

    public function getEBCSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getTotalSpentBeforeCSD();
        }

        return $total;
    }

    /**
     * Get total received on after CSD, either fixed or flex
     * @return int
     */
    public function getIACSD($tags)
    {
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getTotalReceivedAfterCSD();
        }

        return $total;
    }

    public function getTotalCumulativeBudget()
    {
        $tags = $this->getTagsWithSpecifiedBudget('fixed');
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->getCumulativeBudget();
        }

        return $total;
    }

    public function getTotalRemainingBudget()
    {
        $tags = $this->getTagsWithSpecifiedBudget('fixed');
        $total = 0;

        foreach ($tags as $tag) {
            $total += $tag->remaining;
        }

        return $total;
    }


    /**
     *
     * @param $user_id
     * @param $type
     * @return array
     */
    public function getBudgetInfo($type)
    {
        $tags = $this->getTagsWithSpecifiedBudget($type);

        $totals = [
            "budget" => $this->getTotalBudget($tags, $type),
            "spent" => $this->getTotalEACSD($tags),
            "received" => $this->getIACSD($tags),
            "spent_before_CSD" => $this->getEBCSD($tags)
        ];

        if ($type === 'fixed') {
            $totals['cumulative_budget'] = $this->getTotalCumulativeBudget();
            $totals['remaining'] = $this->getTotalRemainingBudget();
        }

        return [
            'tags' => $tags,
            'totals' => $totals
        ];
    }

    /**
     *
     * @return array
     */
    public function getBudgetTotals()
    {
        $FB_info = $this->getBudgetInfo('fixed');
        $FLB_info = $this->getBudgetInfo('flex');
        $RBWEFLB = $this->getRBWEFLB();

        //adding the calculated budget for each tag.
        //I'm doing it here rather than in getBudgetInfo because
        //$remaining_balance is needed before each calculated_budget can be
        //calculated.

        $total_calculated_budget = 0;
        $total_remaining = 0;

        foreach ($FLB_info['tags'] as $tag) {
            $calculated_budget = $RBWEFLB / 100 * $tag->flex_budget;
            $total_calculated_budget += $calculated_budget;

            $remaining = $calculated_budget + $tag->spent + $tag->received;

            return $remaining;
            $total_remaining += $remaining;

            $tag->calculated_budget = number_format($calculated_budget, 2);
            $tag->remaining = number_format($remaining, 2);
            $tag->spent = number_format($tag->spent, 2);
            $tag->received = number_format($tag->received, 2);
        }

        //Get the unallocated values for flex budget
        $FLB_info['unallocated'] = $this->getUnallocatedFLB($FLB_info, $RBWEFLB);

        //Add the unallocated budget to the total budget so it equals 100%
        $FLB_info['totals']['budget']+= $FLB_info['unallocated']['budget'];

        //Add the unallocated calculated budget to the total budget
        $total_calculated_budget+= $FLB_info['unallocated']['calculated_budget'];

        //Add the unallocated remaining budget to the total budget
        $total_remaining+= $FLB_info['unallocated']['remaining'];

        //formatting
        $FLB_info['totals']['calculated_budget'] = number_format($total_calculated_budget, 2);
        $FLB_info['totals']['budget'] = number_format($FLB_info['totals']['budget'], 2);
        $FLB_info['totals']['remaining'] = number_format($total_remaining, 2);
        $FB_info['totals'] = numberFormat($FB_info['totals']);
        $FLB_info['unallocated']['budget'] = number_format($FLB_info['unallocated']['budget'], 2);
        $FLB_info['unallocated']['remaining'] = number_format($FLB_info['unallocated']['remaining'], 2);


        return [
            "FB" => $FB_info,
            "FLB" => $FLB_info,
            "RB" => number_format($this->getRBWithEFLB(), 2),
            "RBWEFLB" => number_format($this->getRBWEFLB(), 2)
        ];
    }

    /**
     *
     * @return array
     */
    public function getBasicTotals()
    {
        $total_income = $this->getTotalIncome();
        $total_expense = $this->getTotalExpense();
        $balance = $total_income + $total_expense;
        $savings_total = Savings::getSavingsTotal();

        $totals = array(
            "credit" => number_format($total_income, 2),
            "debit" => number_format($total_expense, 2),
            "balance" => number_format($balance, 2),
            "reconciled_sum" => number_format($this->getReconciledSum(), 2),
            "savings_total" => number_format($savings_total, 2),
            "savings_balance" => number_format($balance - $savings_total, 2),
            "expense_without_budget_total" => number_format($this->getTotalExpenseWithoutBudget(), 2),
            "EFLB" => number_format($this->getTotalExpenseWithFLB(), 2)
        );

        return $totals;
    }

    /**
     *
     * @return array
     */
    public function getBasicAndBudgetTotals() {
        return [
            'basic' => $this->getBasicTotals(),
            'budget' => $this->getBudgetTotals()
        ];
    }

    private function getUnallocatedFLB($FLB_info, $RBWEFLB)
    {
        $total = $FLB_info['totals']['budget'];
        $budget = 100 - $total;
        return [
            'budget' => $budget,
            'calculated_budget' => $RBWEFLB / 100 * $budget,
            'remaining' => $RBWEFLB / 100 * $budget
        ];
    }

    /**
     * Get the user's remaining balance (RB), with EFLB in the formula.
     * @return int
     */
    public function getRBWithEFLB()
    {
        $FB_info = $this->getBudgetInfo('fixed');
        $FLB_info = $this->getBudgetInfo('flex');

        $total_income = $this->getTotalIncome();
//        $total_CFB = $FB_info['totals']['cumulative_budget'];
        $total_RFB = $FB_info['totals']['remaining'];
        $EWB = $this->getTotalExpenseWithoutBudget();
        $EFLB = $this->getTotalExpenseWithFLB();
        $EFBBCSD = $FB_info['totals']['spent_before_CSD'];
        $EFLBBCSD = $FLB_info['totals']['spent_before_CSD'];
//        $total_spent_after_CSD = $FB_info['totals']['spent'];
        $total_savings = Savings::getSavingsTotal();

        $RB = $total_income - $total_RFB + $EWB + $EFLB + $EFBBCSD + $EFLBBCSD - $total_savings;

        return $RB;
    }

    /**
     * Get remaining balance without the expenses with flex budget
     * @return int
     */
    private function getRBWEFLB()
    {
        return $this->getRBWithEFLB() - $this->getTotalExpenseWithFLB();
    }

    /**
     * This is for calculating the remaining balance.
     * Finds all transactions that have a flex budget
     * and returns the total of those transactions.
     * @return mixed
     */
    public function getTotalExpenseWithFLB()
    {
        //first, get all the transactions that have a budget.
        $sql = "select id from transactions where transactions.type = 'expense' AND transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
		where transactions_tags.transaction_id = transactions.id
		and tags.budget_id = 2) > 0";

        $transactions_with_FLB = DB::select($sql);

        //format transactions_with_one_budget into a nice array
        $ids = [];
        foreach ($transactions_with_FLB as $transaction) {
            $ids[] = $transaction->id;
        }

        $total = DB::table('transactions_tags')
            ->whereIn('transaction_id', $ids)
            ->where('budget_id', 2)
            ->join('tags', 'tag_id', '=', 'tags.id')
            ->sum('calculated_allocation');

        return $total;
    }

    /**
     * This is for calculating the remaining balance.
     * Finds all transactions that have no budget
     * and returns the total of those transactions.
     * @return mixed
     */
    public function getTotalExpenseWithoutBudget()
    {
        //first, get all the transactions that have no budget.
        $sql = "select id from transactions where transactions.type = 'expense' AND transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
		where transactions_tags.transaction_id = transactions.id
		and tags.budget_id is not null) = 0";

        $transactions_with_no_budgets = DB::select($sql);

        //format transactions_with_one_budget into a nice array
        $ids = [];
        foreach ($transactions_with_no_budgets as $transaction) {
            $ids[] = $transaction->id;
        }

        $total = Transaction::whereIn('transactions.id', $ids)
            ->sum('total');

        return $total;
    }

    /**
     * Get the sum of all the user's transactions that are reconciled
     * @return mixed
     */
    public function getReconciledSum()
    {
        $reconciled_sum = Transaction::where('user_id', Auth::user()->id)
            ->where('reconciled', 1)
            ->sum('total');

        return $reconciled_sum;
    }

    /**
     * Get the sum of all the user's income transactions
     * @return int
     */
    public function getTotalIncome()
    {
        $totals = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'income')
            ->lists('total');

        $total_income = 0;
        foreach ($totals as $total) {
            $total_income += $total;
        }

        return $total_income;
    }

    /**
     * Get the sum of all the user's expense transactions
     * @return int
     */
    public function getTotalExpense()
    {
        $totals = Transaction::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->lists('total');

        $total_expense = 0;
        foreach ($totals as $total) {
            $total_expense += $total;
        }

        return $total_expense;
    }
}