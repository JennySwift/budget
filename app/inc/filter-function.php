<?php

function filter ($filter) {
	// return 'hello from filter';
    $select = "SELECT allocated, transactions.id, date,type, transactions.account AS account_id, accounts.name AS account_name,IFNULL(merchant, '') AS merchant_clone,description,reconciled,total, DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date";

    $where = " WHERE transactions.user_id = " . Auth::user()->id;

    foreach ($filter as $type => $value) {
        if ($value && $type === "accounts") {
            //==========type is accounts==========
            $accounts = $value;
            $where = $where . " AND (";
            $counter = 0;
            foreach ($accounts as $account_id) {
                $counter++;
                if ($counter < 2) {
                    //it's the first account in the array. no need for 'or'.
                    $where = $where . "account = $account_id";
                }
                else {
                    //need to use 'or'.
                    $where = $where . " OR account = $account_id";
                }
                
            }
            $where = $where . ")";
        }
        elseif ($value && $type === "types") {
            //==========type is type, ie, income, expense, transfer==========
            $types = $value;
            $where = $where . " AND (";
            $counter = 0;
            foreach ($types as $type) {
                $counter++;
                if ($counter < 2) {
                    //it's the first in the array. no need for 'or'.
                    $where = $where . "type = '$type'";
                }
                else {
                    //need to use 'or'.
                    $where = $where . " OR type = '$type'";
                }
                
            }
            $where = $where . ")";
        }
        elseif ($value && $type === "single_date_sql") {
            //==========dates==========
            $where = $where . " AND date = '$value'";
        }
        elseif ($value && $type === "from_date_sql") {
            $where = $where . " AND date >= '$value'";
        }
        elseif ($value && $type === "to_date_sql") {
            $where = $where . " AND date <= '$value'";
        }
        elseif ($value && $type === "total") {
            //==========total==========
            $where = $where . " AND total = $value";
        }
        elseif ($value && $type === "reconciled") {
            //==========reconciled==========
            if ($value !== "any") {
                $where = $where . " AND reconciled = '$value'";
            }
        }
        elseif ($value) {
            if ($type === "description" || $type === "merchant") {
                $where = $where . " AND transactions." . $type . " LIKE '%$value%'";
            }
        }
    }

    $join = "JOIN accounts ON transactions.account = accounts.id";

    $sql = "$select FROM transactions $join $where ORDER BY date DESC, id DESC;";
    $sql_result = DB::select($sql);

    $array = array();
    $transactions_with_tags = array();

    foreach ($sql_result as $row) {
    	$transaction_id = $row->id;
    	$type = $row->type;
    	$account_id = $row->account_id;
    	$account_name = $row->account_name;
    	$total = $row->total;
    	$description = $row->description;
    	$merchant = $row->merchant_clone;
    	$reconciled = $row->reconciled;
    	$allocated = $row->allocated;
    	$user_date = $row->formatted_date;

    	$reconciled = convertToBoolean($reconciled);
    	$allocated = convertToBoolean($allocated);

    	$date = array(
    	    "user" => $user_date
    	);

    	$account = array(
    	    "id" => $account_id,
    	    "name" => $account_name
    	);

    	$total = number_format($total, 2, '.', '');
    	$formatted_total = number_format($total, 2);

    	$this_transactions_tags = getTags($transaction_id);
    	$multiple_budgets = hasMultipleBudgets($transaction_id);

    	$array[] = array(
    	    "id" => $transaction_id,
    	    "type" => $type,
    	    "account" => $account,
    	    "total" => $total,
    	    "formatted_total" => $formatted_total,
    	    "date" => $date,
    	    "description" => $description,
    	    "merchant" => $merchant,
    	    "tags" => $this_transactions_tags,
    	    "reconciled" => $reconciled,
    	    "allocated" => $allocated,
    	    "multiple_budgets" => $multiple_budgets
    	);      
    }
    return $array;
}

?>