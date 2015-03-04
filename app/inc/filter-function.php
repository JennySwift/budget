<?php

use App\Transaction;

// DB::enableQueryLog();

function filter ($filter) {
    $user_id = Auth::user()->id;
    $offset = $filter['offset'];
    $num_to_fetch = $filter['num_to_fetch'];
    $transactions = Transaction::where('transactions.user_id', $user_id);

    foreach ($filter as $type => $value) {
        if ($value) {
            //================accounts================
            if ($type === "accounts") {
                $accounts = $value;

                $transactions = $transactions->whereIn('account_id', $accounts); 
            }
            //==========type is type, ie, income, expense, transfer==========
            elseif ($type === "types") {
                $types = $value;
                
                $transactions = $transactions->whereIn('type', $types);
            }
            // =============dates=============
            elseif ($type === "single_date_sql") {  
                $transactions = $transactions->where('date', $value);
                // $where = $where . " AND date = '$value'";
            }
            elseif ($type === "from_date_sql") {
                $transactions = $transactions->where('date', '>=', $value);
                // $where = $where . " AND date >= '$value'";
            }
            elseif ($type === "to_date_sql") {
                $transactions = $transactions->where('date', '<=', $value);
                // $where = $where . " AND date <= '$value'";
            }
            //==========total==========
            elseif ($type === "total") {
                $transactions = $transactions->where('total', $value);
                // $where = $where . " AND total = $value";
            }
            //==========reconciled==========
            elseif ($type === "reconciled") {
                if ($value !== "any") {
                    $reconciled = $value;
                    $reconciled = convertFromBoolean($reconciled);
                    $transactions = $transactions->where('reconciled', $reconciled);
                    // $where = $where . " AND reconciled = '$value'";
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
                // $transactions = $transactions->has('tags', '=', $tag_ids);

                foreach ($tag_ids as $tag_id) {
                    $transactions = $transactions->whereHas('tags', function ($q) use ($tag_id) {
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
                $sql = "select id from transactions where transactions.user_id = 1 and (select count(*) from tags inner join transactions_tags on tags.id = transactions_tags.tag_id
                where transactions_tags.transaction_id = transactions.id
                and tags.budget_id is not null)" . $num;

                $transactions_with_x_budgets = DB::select($sql);

                //format transactions_with_one_budget into a nice array
                $ids = array();
                foreach ($transactions_with_x_budgets as $transaction) {
                    $id = $transaction->id;
                    $ids[] = $id;
                }

                // Log::info('transactions_with_one_budget', $ids);
                Log::info('ids', $ids);
                $transactions = $transactions->whereIn('transactions.id', $ids);
                    
            }
            //==========description, merchant==========
            elseif ($type === "description" || $type === "merchant") {
                $value = '%' . $value . '%';
                $transactions = $transactions->where($type, 'LIKE', $value);
                // $where = $where . " AND transactions." . $type . " LIKE '%$value%'";
            }
        }
        
    }

    $transactions = $transactions
        ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->select('allocated', 'transactions.id', 'date', 'type', 'transactions.account_id AS account_id', 'accounts.name AS account_name', 'merchant' , 'description' , 'reconciled' , 'total', 'date')
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