<?php

    include("database-connect.php");
    
    $description = json_decode(file_get_contents('php://input'), true)["description"];
    
    try {

        /*================================count transactions with tag================================*/
        
        if ($description === 'count transactions with tag') {
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
            $sql = "SELECT COUNT(*) FROM transactions_tags WHERE tag_id = $tag_id";
            $sql_result = $db->query($sql);
            $count = $sql_result->fetchColumn(); 

            $array = $count;
        }

        /*================================autocomplete transaction================================*/

        elseif ($description === 'autocomplete transaction') {
            $typing = json_decode(file_get_contents('php://input'), true)["typing"];
            $typing = '%' . $typing . '%';
            $column = json_decode(file_get_contents('php://input'), true)["column"];
            
            $sql = "SELECT transactions.id,DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date, total,account AS account_id, accounts.name AS account_name, type,description,IFNULL(merchant,'') AS merchant_clone FROM transactions JOIN accounts ON transactions.account = accounts.id WHERE $column LIKE ? AND transactions.user_id = $user_id ORDER BY date DESC, id DESC LIMIT 50";
            $result = $db->prepare($sql);
            $result->bindParam(1, $typing);
            $result->execute();

            $array = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $transaction_id = $row['id'];
                $date = $row['formatted_date'];
                $description = $row['description'];
                $merchant = $row['merchant_clone'];
                $total = $row['total'];
                $type = $row['type'];
                $account_id = $row['account_id'];
                $account_name = $row['account_name'];

                $account = array(
                    "id" => $account_id,
                    "name" => $account_name
                );

                $this_transactions_tags = getTags($db, $transaction_id);

                $array[] = array(
                    "id" => $transaction_id,
                    "date" => $date,
                    "description" => $description,
                    "merchant" => $merchant,
                    "total" => $total,
                    "type" => $type,
                    "account" => $account,
                    "tags" => $this_transactions_tags
                );  
            }
        }

        /*================================budget tags================================*/

        elseif ($description === 'budget tags') {
            // $id_array = json_decode(file_get_contents('php://input'), true)["id_array"];
            $id_array = json_decode(stripslashes($_POST['id_array']));
            $budget_tags = array();

            foreach ($id_array as $id) {
                $tags_for_one_transaction = array();
                
                $sql = "SELECT transactions_tags.transaction_id, transactions_tags.tag_id, tags.name, tags.fixed_budget, tags.flex_budget FROM transactions_tags JOIN tags ON transactions_tags.tag_id = tags.id WHERE transaction_id = '$id';";

                $result = $db->query($sql);

                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $tag_name = $row['name'];
                    $tags_for_one_transaction[$tag_name] = array(
                        "transaction_id" => $row['transaction_id'],
                        "id" => $row['tag_id'],
                        "name" => $row['name'],
                        "budget" => $row['fixed_budget'],
                        "flex_budget" => $row['flex_budget']
                    );
                }

                $budget_tags[$id] = $tags_for_one_transaction;
            }
        }

        /*==================================================================*/
        /*==================================================================*/
        /*==================================================================*/
        /*=============================totals===============================*/
        /*==================================================================*/
        /*==================================================================*/
        /*==================================================================*/

        /*================================allocation popup================================*/

        elseif ($description === 'allocation info') {
            $transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
            $array = getAllocationTotals($db, $transaction_id);
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