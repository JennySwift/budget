<?php
  
    include("database-connect.php");
    
    $description = json_decode(file_get_contents('php://input'), true)["description"];
    
    try {

        /*================================account================================*/

        if ($description === 'account') {
            $name = json_decode(file_get_contents('php://input'), true)["name"];
            $sql = "INSERT INTO accounts (name, user_id)
                VALUES ('$name', '$user_id')";

            $db->query($sql);
        }

        /*================================new transaction================================*/

        elseif ($description === 'new transaction') {
            $new_transaction = json_decode(file_get_contents('php://input'), true)["new_transaction"];
            $type = $new_transaction['type'];

            if ($type !== "transfer") {
                insertTransaction($db, $user_id, $new_transaction);
            }
            else {
                //It's a transfer, so insert two transactions, the from and the to
                insertTransaction($db, $user_id, $new_transaction, "from");
                insertTransaction($db, $user_id, $new_transaction, "to");
            }

            //check if the transaction that was just entered has multiple budgets. Note for transfers this won't do both of them.
            $last_transaction_id = getLastTransactionId($db, $user_id);
            $transaction = getTransaction($db, $last_transaction_id);
            $multiple_budgets = hasMultipleBudgets($db, $last_transaction_id);

            $array = array(
                "transaction" => $transaction,
                "multiple_budgets" => $multiple_budgets
            ); 
        }
        
        //=========================response=========================
    
        $variables = get_defined_vars();
    
        $response = array(
            "variables" => $variables,
            "array" => $array
        );
    
        $json = json_encode($response);
        echo $json;
    }
    catch (Exception $e) {
        $variables = get_defined_vars(); 
        $json = json_encode($variables);
        echo $json;
        exit;
    }
 
?>