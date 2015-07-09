<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Savings;
use App\Models\Tag;
use App\Models\Transaction;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class TotalsController
 * @package App\Http\Controllers
 */
class TotalsController extends Controller
{

    /**
     *
     * @param Request $request
     * @return array
     */
    public function getAllocationTotals(Request $request)
    {
        return Transaction::getAllocationTotals($request->get('transaction_id'));
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
        $savings_balance = $balance - $savings_total;

        $totals = array(
            "total_income" => number_format($total_income, 2),
            "total_expense" => number_format($total_expense, 2),
            "balance" => number_format($balance, 2),
            "reconciled_sum" => number_format($this->getReconciledSum(), 2),
            "savings_total" => number_format($savings_total, 2),
            "savings_balance" => number_format($savings_balance, 2),
            "expense_without_budget_total" => number_format($this->getTotalExpenseWithoutBudget(), 2),
            "EFLB" => number_format($this->getTotalExpenseWithFLB(), 2)
        );

        return $totals;
    }

    /**
     *
     * @return array
     */
    public function getBudgetTotals()
    {
        $user_id = Auth::user()->id;
        $FB_info = $this->getBudgetInfo($user_id, 'fixed');
        $FLB_info = $this->getBudgetInfo($user_id, 'flex');
        $remaining_balance = $this->getRB();

        //adding the calculated budget for each tag.
        //I'm doing it here rather than in getBudgetInfo because
        //$remaining_balance is needed before each calculated_budget can be
        //calculated.
        //I'm creating a new array because it didn't work when I tried
        //to modify the original array.
        $FLB_tags_with_calculated_budgets = [];
        $total_calculated_budget = 0;
        $total_remaining = 0;

        foreach ($FLB_info['each_tag'] as $tag) {
            $budget = $tag['budget'];
            $spent = $tag['spent'];
            $received = $tag['received'];

            $calculated_budget = $remaining_balance / 100 * $budget;
            $total_calculated_budget += $calculated_budget;

            $remaining = $calculated_budget + $spent + $received;
            $total_remaining += $remaining;

            $tag['calculated_budget'] = number_format($calculated_budget, 2);
            $tag['remaining'] = number_format($remaining, 2);
            $tag['spent'] = number_format($spent, 2);
            $tag['received'] = number_format($received, 2);

            $FLB_tags_with_calculated_budgets[] = $tag;
        }

        $FLB_info['each_tag'] = $FLB_tags_with_calculated_budgets;

        $FLB_info['totals']['calculated_budget'] = number_format($total_calculated_budget, 2);
        $FLB_info['totals']['remaining'] = number_format($total_remaining, 2);

        //formatting
        $FB_info['totals'] = $this->numberFormat($FB_info['totals']);

        return [
            "FB" => $FB_info,
            "FLB" => $FLB_info,
            "RB" => number_format($remaining_balance, 2)
        ];
    }

    /**
     * Get the user's remaining balance (RB)
     * @return int
     */
    public function getRB()
    {
        $user_id = Auth::user()->id;
        $FB_info = $this->getBudgetInfo($user_id, 'fixed');
        $FLB_info = $this->getBudgetInfo($user_id, 'flex');

        //calculating remaining balance
        $total_income = $this->getTotalIncome();
        $total_CFB = $FB_info['totals']['cumulative_budget'];
        $EWB = $this->getTotalExpenseWithoutBudget();
        $EFLB = $this->getTotalExpenseWithFLB();
        $total_spent_before_CSD = $FB_info['totals']['spent_before_CSD'];
        $total_spent_after_CSD = $FB_info['totals']['spent'];
        $total_savings = Savings::getSavingsTotal();

        $RB = $total_income - $total_CFB + $EWB + $EFLB + $total_spent_before_CSD + $total_spent_after_CSD - $total_savings;

        return $RB;
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
     * Get total spent on a given tag before starting date
     * @param $tag_id
     * @param $CSD
     * @return mixed
     */
    public function getTotalSpentOnTagBeforeCSD($tag_id, $CSD)
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $tag_id);

        if ($CSD) {
            $total = $total->where('transactions.date', '<', $CSD);
        }

        $total = $total
            ->where('transactions.type', 'expense')
            ->sum('calculated_allocation');

        return $total;
    }

    /**
     * Get total spent on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getTotalSpentOnTag($tag_id, $starting_date)
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $tag_id);

        if ($starting_date) {
            $total = $total->where('transactions.date', '>=', $starting_date);
        }

        $total = $total
            ->where('transactions.type', 'expense')
            ->sum('calculated_allocation');

        return $total;
    }

    /**
     * Get total received on a given tag on or after starting date
     * @param $tag_id
     * @param $starting_date
     * @return mixed
     */
    public function getTotalReceivedOnTag($tag_id, $starting_date)
    {
        $total = DB::table('transactions_tags')
            ->join('tags', 'transactions_tags.tag_id', '=', 'tags.id')
            ->join('transactions', 'transactions_tags.transaction_id', '=', 'transactions.id')
            ->where('transactions_tags.tag_id', $tag_id);

        if ($starting_date) {
            $total = $total->where('transactions.date', '>=', $starting_date);
        }

        $total = $total
            ->where('transactions.type', 'income')
            ->sum('calculated_allocation');

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

    /**
     *
     * @param $user_id
     * @param $type
     * @return array
     */
    public function getBudgetInfo($user_id, $type)
    {
        if ($type === 'fixed') {
            $tags = Tag::getTagsWithFixedBudget($user_id);
            $total_cumulative_budget = 0;
        } elseif ($type === 'flex') {
            $tags = Tag::getTagsWithFlexBudget($user_id);
        }

        // We will be returning $budget_info.
        $budget_info = [
            "each_tag" => [],
            "totals" => []
        ];

        $total_budget = 0;
        $total_spent = 0;
        $total_received = 0;
        $total_remaining = 0;
        $total_spent_before_CSD = 0;

        foreach ($tags as $tag) {
            $tag_id = $tag->id;
            $CSD = $tag->starting_date;

            //Get cumulative month number ($CMN)
            if ($CSD) {
                $CMN = Tag::getCMN($CSD);
            } else {
                $CMN = 1;
            }

            //Get other stuff :)
            $spent = $this->getTotalSpentOnTag($tag_id, $CSD);
            $received = $this->getTotalReceivedOnTag($tag_id, $CSD);
            $spent_before_CSD = $this->getTotalSpentOnTagBeforeCSD($tag_id, $CSD);

            if ($type === 'fixed') {
                $budget = $tag->fixed_budget;
                $cumulative_budget = $budget * $CMN;
                $remaining = $cumulative_budget + $spent + $received;
                $total_remaining += $remaining;
                $total_cumulative_budget += $cumulative_budget;
            } elseif ($type === 'flex') {
                $budget = $tag->flex_budget;
            }

            $total_budget += $budget;
            $total_spent += $spent;
            $total_received += $received;
            $total_spent_before_CSD += $spent_before_CSD;

            if ($CSD) {
                $CSD = Transaction::convertDate($CSD, 'user');
            }

            $tag_info = [
                "id" => $tag_id,
                "name" => $tag->name,
                "budget" => number_format($budget, 2),
                "CSD" => $CSD,
                "CMN" => $CMN,
                "spent" => $spent,
                "received" => $received,
                "spent_before_CSD" => number_format($spent_before_CSD, 2)
            ];

            if ($type === 'fixed') {
                $tag_info['cumulative_budget'] = number_format($cumulative_budget, 2);
                $tag_info['remaining'] = number_format($remaining, 2);
            }

            $budget_info['each_tag'][] = $tag_info;
        }

        $budget_info['totals'] = array(
            "budget" => $total_budget,
            "spent" => $total_spent,
            "received" => $total_received,
            "spent_before_CSD" => $total_spent_before_CSD
        );

        if ($type === 'fixed') {
            $budget_info['totals']['cumulative_budget'] = $total_cumulative_budget;
            $budget_info['totals']['remaining'] = $total_remaining;
        }

        return $budget_info;
    }

    /**
     *
     * @param $array
     * @return array
     */
    public function numberFormat($array)
    {
        $formatted_array = array();
        foreach ($array as $key => $value) {
            $formatted_value = number_format($value, 2);
            $formatted_array[$key] = $formatted_value;
        }

        return $formatted_array;
    }
}
