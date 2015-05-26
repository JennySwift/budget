<?php

use App\Transaction;

// DB::enableQueryLog();

function filter($filter)
{
    $user_id = Auth::user()->id;
    $offset = $filter['offset'];
    $num_to_fetch = $filter['num_to_fetch'];
    if ($filter['types']) {
        $transaction_type = $filter['types'];
    }
    
    $transactions = Transaction::where('transactions.user_id', $user_id);
    $totals = Transaction::where('transactions.user_id', $user_id);

    foreach ($filter as $type => $value) {
        if ($value) {
            //================accounts================
            if ($type === "accounts") {
                $accounts = $value;

                $transactions = $transactions->whereIn('account_id', $accounts);
                $totals = $totals->whereIn('account_id', $accounts);
            }
            //==========type, ie, income, expense, transfer==========
            elseif ($type === "types") {
                $types = $value;
                
                $transactions = $transactions->whereIn('type', $types);
                $totals = $totals->whereIn('type', $types);
            }
            // =============dates=============
            elseif ($type === "single_date_sql") {  
                $transactions = $transactions->where('date', $value);
                $totals = $totals->where('date', $value);
            }
            elseif ($type === "from_date_sql") {
                $transactions = $transactions->where('date', '>=', $value);
                $totals = $totals->where('date', '>=', $value);
            }
            elseif ($type === "to_date_sql") {
                $transactions = $transactions->where('date', '<=', $value);
                $totals = $totals->where('date', '<=', $value);
            }
            //==========total==========
            elseif ($type === "total") {
                $transactions = $transactions->where('total', $value);
                $totals = $totals->where('total', $value);
            }
            //==========reconciled==========
            elseif ($type === "reconciled") {
                if ($value !== "any") {
                    $reconciled = $value;
                    $reconciled = convertFromBoolean($reconciled);
                    $transactions = $transactions->where('reconciled', $reconciled);
                    $totals = $totals->where('reconciled', $reconciled);
                }
            }
            //==========tags==========
            elseif ($type === "tags") {
                $tags = $value;

                $tag_ids = array();
                foreach ($tags as $tag) {
                    $tag_id = $tag['id'];
                    $tag_ids[] = $tag_id;
                }

                foreach ($tag_ids as $tag_id) {
                    $transactions = $transactions->whereHas('tags', function ($q) use ($tag_id) {
                        $q->where('tags.id', $tag_id); 
                    });
                    $totals = $totals->whereHas('tags', function ($q) use ($tag_id) {
                        $q->where('tags.id', $tag_id); 
                    });
                }    
            }
            //==========budget==========
            elseif($type === "budget" && $value !== "all") {
                if ($value === "none") {
                    $num = ' = 0';
                }
                elseif ($value === "single") {
                    $num = ' = 1';
                }
                elseif ($value === "multiple") {
                    $num = ' > 1';
                }

                //first, find get all the transactions that have x number of budgets
                $sql = "select id from transactions where transactions.user_id = " . Auth::user()->id . " and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
                where transactions_tags.transaction_id = transactions.id
                and tags.budget_id is not null)" . $num;

                $transactions_with_x_budgets = DB::select($sql);

                //format transactions_with_one_budget into a nice array
                $ids = array();
                foreach ($transactions_with_x_budgets as $transaction) {
                    $id = $transaction->id;
                    $ids[] = $id;
                }

                $transactions = $transactions->whereIn('transactions.id', $ids);
                $totals = $totals->whereIn('transactions.id', $ids);
                    
            }
            //==========description, merchant==========
            elseif ($type === "description" || $type === "merchant") {
                $value = '%' . $value . '%';
                $transactions = $transactions->where($type, 'LIKE', $value);
                $totals = $totals->where($type, 'LIKE', $value);
            }
        }
        
    }

    $num_transactions = $totals->count();

    $totals = $totals
        ->select('type', 'reconciled' , 'total')
        ->get();

    $transactions = $transactions
        ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id', 'accounts.name AS account_name', 'merchant' , 'description' , 'reconciled' , 'total')
        ->skip($offset)
        ->take($num_to_fetch)
        ->get();

    // $queries = DB::getQueryLog();
    // Log::info('queries', $queries);

    //========================get the totals======================== 

    $income = 0;
    $expenses = 0;
    $total_reconciled = 0;

    foreach ($totals as $transaction) {
        $total = $transaction->total;
        $type = $transaction->type;
        $reconciled = $transaction->reconciled;
        if ($type === 'income') {
            $income+= $total;
        }
        elseif ($type === 'expense') {
            $expenses+= $total;
        }
        elseif ($type === 'transfer') {
            if ($total > 0) {
                $income+= $total;
            }
            elseif ($total < 0) {
                $expenses+= $total;
            }
        }
        if ($reconciled == 1) {
            $total_reconciled+= $total;
        }
    }

    $balance = $income + $expenses;

    $income = number_format($income, 2);
    $expenses = number_format($expenses, 2);
    $balance = number_format($balance, 2);
    $total_reconciled = number_format($total_reconciled, 2);

    //========================get the transactions========================

    $transactions_array = array();

    foreach ($transactions as $transaction) {
        $transaction_id = $transaction->id;
        $date = $transaction->date;
        $user_date = convertDate($date, 'user');
        $description = $transaction->description;
        $merchant = $transaction->merchant;
        $total = $transaction->total;
        $account_id = $transaction->account_id;
        $account_name = $transaction->account_name;
        $reconciled = $transaction->reconciled;
        $reconciled = convertToBoolean($reconciled);
        $allocated = $transaction->allocated;
        $allocated = convertToBoolean($allocated);
        $type = $transaction->type;
        $tags = getTags($transaction_id);
        $multiple_budgets = hasMultipleBudgets($transaction_id);

        $account = array(
            'id' => $account_id,
            'name' => $account_name
        );

        $date = array(
         "user" => $user_date
        );

        $transactions_array[] = array(
            'id' => $transaction_id,
            'date' => $date,
            'description' => $description,
            'merchant' => $merchant,
            'total' => $total,
            'account' => $account,   
            'reconciled' => $reconciled,
            'allocated' => $allocated,       
            'type' => $type,
            'tags' => $tags,
            'multiple_budgets' => $multiple_budgets
        );

    }

    $filter_totals = array(
        "num_transactions" => $num_transactions,
        "income" => $income,
        "expenses" => $expenses,
        "balance" => $balance,
        "reconciled" => $total_reconciled
    );

    $result = array(
        "transactions" => $transactions_array,
        "filter_totals" => $filter_totals
    );

    return $result;
}

?>