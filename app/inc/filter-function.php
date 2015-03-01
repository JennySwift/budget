<?php

use App\Transaction;

// DB::enableQueryLog();

function filter ($filter) {
    $user_id = Auth::user()->id;
    $offset = $filter['offset'];
    $num_to_fetch = $filter['num_to_fetch'];
    $transactions = Transaction::where('transactions.user_id', $user_id);

    foreach ($filter as $type => $value) {
        //================accounts================
        if ($value && $type === "accounts") {
            $accounts = $value;

            $transactions = $transactions->whereIn('account', $accounts); 
        }
        //==========type is type, ie, income, expense, transfer==========
        elseif ($value && $type === "types") {
            $types = $value;
            
            $transactions = $transactions->whereIn('type', $types);
        }
        // =============dates=============
        elseif ($value && $type === "single_date_sql") {  
            $transactions = $transactions->where('date', $value);
            // $where = $where . " AND date = '$value'";
        }
        elseif ($value && $type === "from_date_sql") {
            $transactions = $transactions->where('date', '>=', $value);
            // $where = $where . " AND date >= '$value'";
        }
        elseif ($value && $type === "to_date_sql") {
            $transactions = $transactions->where('date', '<=', $value);
            // $where = $where . " AND date <= '$value'";
        }
        //==========total==========
        elseif ($value && $type === "total") {
            $transactions = $transactions->where('total', $value);
            // $where = $where . " AND total = $value";
        }
        //==========reconciled==========
        elseif ($value && $type === "reconciled") {
            if ($value !== "any") {
                $transactions = $transactions->where('reconciled', $value);
                // $where = $where . " AND reconciled = '$value'";
            }
        }
        //==========tags==========
        elseif ($value && $type === "tags") {
            $tags = $value;

            $tag_ids = array();
            foreach ($tags as $tag) {
                $tag_id = $tag['id'];
                $tag_ids[] = $tag_id;
            }
            // $transactions = $transactions->has('tags', '=', $tag_ids);

            foreach ($tag_ids as $tag_id) {
                $transactions = $transactions->whereHas('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id); 
                });
            }    
        }
        //==========description, merchant==========
        elseif ($value) {
            if ($type === "description" || $type === "merchant") {
                $value = '%' . $value . '%';
                $transactions = $transactions->where($type, 'LIKE', $value);
                // $where = $where . " AND transactions." . $type . " LIKE '%$value%'";
            }
        }
    }

    $transactions = $transactions
        ->join('accounts', 'transactions.account', '=', 'accounts.id')
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account AS account_id', 'accounts.name AS account_name', 'merchant' , 'description' , 'reconciled' , 'total', 'date')
        ->skip($offset)
        ->take($num_to_fetch)
        ->get();

    $transactions_array = array();

    // $queries = DB::getQueryLog();
    // Log::info('queries', $queries);

    foreach ($transactions as $transaction) {
        // Log::info('transactions', $transactions);
        // $transaction_id = $transaction['id'];
        // $account_id = $transaction['account_id'];
        // $account_name = $transaction['account_name'];
        // $total = $transaction['total'];
        // $reconciled = $transaction['reconciled'];
        // $allocated = $transaction['allocated'];
        // $user_date = $transaction['date'];

        $transaction_id = $transaction->id;
        $user_date = $transaction->date;
        $description = $transaction->description;
        $merchant = $transaction->merchant;
        $total = $transaction->total;
        $account_id = $transaction->account_id;
        $account_name = $transaction->account_name;
        $reconciled = $transaction->reconciled;
        $allocated = $transaction->allocated;
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

        // $transactions['tags'] = getTags($transaction_id);
        // $transactions['multiple_budgets'] = hasMultipleBudgets($transaction_id);

        // $transaction['formatted_total'] = number_format($total, 2);
        // $transaction['account'] = $account;
        // $transaction['reconciled'] = convertToBoolean($reconciled);
        // $transaction['allocated'] = convertToBoolean($allocated);

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

    return $transactions_array;
}

?>