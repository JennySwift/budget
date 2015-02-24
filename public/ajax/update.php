<?php

    include("database-connect.php");
    
    $description = json_decode(file_get_contents('php://input'), true)["description"];

    try {
        
        /*================================cumulative starting date================================*/

        if ($description === 'CSD') {
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
            $CSD = json_decode(file_get_contents('php://input'), true)["CSD"];

            $sql = "UPDATE tags SET starting_date='$CSD' WHERE id='$tag_id'";
            $db->query($sql);
        }
        /*================================allocated percent================================*/

        elseif ($description === 'allocation') {
            $type = json_decode(file_get_contents('php://input'), true)["type"];
            $value = json_decode(file_get_contents('php://input'), true)["value"];
            $transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];

            if ($type === 'percent') {
                updateAllocatedPercent($db, $value, $transaction_id, $tag_id);
            }
            elseif ($type === 'fixed') {
                updateAllocatedFixed($db, $value, $transaction_id, $tag_id);
            }
            
            //get the updated tag info after the update
            $allocation_info = getAllocationInfo($db, $transaction_id, $tag_id);
            $allocation_totals = getAllocationTotals($db, $transaction_id);

            $array = array(
                "allocation_info" => $allocation_info,
                "allocation_totals" => $allocation_totals
            );
        }

        // elseif ($description === 'transaction tag budget') {
        //     $transaction_id = $_GET['transaction_id'];
        //     $tag_id = $_GET['tag_id'];
        //     $value = $_GET['value'];
        //     $type = $_GET['type'];
        //     if ($type == "%") {
        //         $sql = "UPDATE transactions_tags SET allocated_percent = '$value' WHERE transaction_id = '$transaction_id' AND tag_id = '$tag_id'";
        //     }
        //     elseif ($type == "$") {
        //         $sql = "UPDATE transactions_tags SET allocated_percent = null, allocated_fixed = '$value' WHERE transaction_id = '$transaction_id' AND tag_id = '$tag_id'";
        //     }
            

        //     $result = $db->query($sql);

        //     $sql2 = "UPDATE transactions_tags allocated_fixed JOIN transactions ON transactions.id = transaction_id SET allocated_fixed = transactions.total / 100 * allocated_percent WHERE transaction_id = '$transaction_id' AND tag_id = '$tag_id' WHERE allocated_percent IS NOT NULL";
        //     $result2 = $db->query($sql2);
        // }

        /*================================allocation status================================*/

        elseif ($description === 'allocation status') {
            $transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
            $status = json_decode(file_get_contents('php://input'), true)["status"];
            
            updateAllocationStatus($db, $transaction_id, $status);
        }

        // elseif ($description === 'mark as allocated') {
        //     $transaction_id = $_GET['transaction_id'];
        //     $action = $_GET['action'];

        //     if ($action == "mark as allocated") {
        //         $sql = "UPDATE transactions SET allocation='true' WHERE id='$transaction_id'";
        //     }
        //     elseif ($action == "mark as not allocated") {
        //         $sql = "UPDATE transactions SET allocation=null WHERE id='$transaction_id'";
        //     }

        //     $db->query($sql);
        // }

        /*================================reconciliation================================*/

        elseif ($description === 'reconciliation') {
            $id = json_decode(file_get_contents('php://input'), true)["id"];
            $reconciliation = json_decode(file_get_contents('php://input'), true)["reconciliation"];
            $sql = "UPDATE transactions SET reconciled='$reconciliation' WHERE id='$id'";
            $db->query($sql);
        }

        /*================================transaction================================*/

        elseif ($description === 'transaction') {
            $transaction = json_decode(file_get_contents('php://input'), true)["transaction"];

            $transaction_id = $transaction['id'];
            $account_id = $transaction['account']['id'];
            $date = $transaction['date']['sql'];
            $merchant = $transaction['merchant'];
            $total = $transaction['total'];
            $tags = $transaction['tags'];
            $description = $transaction['description'];
            $type = $transaction['type'];
            $reconciliation = $transaction['reconciled'];
            $reconciliation = formatReconciliation($reconciliation);
                    
            $sql = "UPDATE transactions SET
                account = '$account_id',
                type = '$type',
                date = '$date',
                merchant = ?,
                total = '$total',
                description = ?,
                reconciled = '$reconciliation'
                WHERE id = '$transaction_id'";

            $result = $db->prepare($sql);
            $result->bindParam(1, $merchant);
            $result->bindParam(2, $description);
            $result->execute();

            //delete all previous tags for the transaction and then add the current ones 
            deleteAllTagsForTransaction($db, $transaction_id);

            require_once("../tools/FirePHPCore/FirePHP.class.php");
            ob_start();
            $firephp = FirePHP::getInstance(true);        
            $firephp->log($tags, 'tags');
            $firephp->log($transaction_id, 'transaction_id');

            insertTags($db, $user_id, $transaction_id, $tags);
        }
        /*================================description================================*/

        // elseif ($description === 'edit description') {
        //     $transaction_id = $_GET['transaction_id'];
        //     $description = $_GET['description'];
            
        //     $sql = "UPDATE transactions SET description = '$description' WHERE id = $transaction_id";

        //     $db->query($sql);
        // }
        
        /*================================tag name================================*/

        elseif ($description === 'tag name') {
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
            $tag_name = json_decode(file_get_contents('php://input'), true)["tag_name"];

            $sql = "UPDATE tags SET name='$tag_name' WHERE id='$tag_id'";
            $db->query($sql);
        }

        /*================================account name================================*/

        elseif ($description === 'account name') {
            $account_id = json_decode(file_get_contents('php://input'), true)["account_id"];
            $account_name = json_decode(file_get_contents('php://input'), true)["account_name"];

            $sql = "UPDATE accounts SET name='$account_name' WHERE id='$account_id'";
            $db->query($sql);
        }
        
        /*================================colors================================*/

        elseif ($description === 'colors') {
            $colors = json_decode(file_get_contents('php://input'), true)["colors"];

            require_once("../tools/FirePHPCore/FirePHP.class.php");
            ob_start();
            $firephp = FirePHP::getInstance(true);        
            $firephp->log($colors, 'colors');
            
            foreach ($colors as $type => $color) {
                $sql = "UPDATE colors SET color='$color' WHERE item='$type' AND user_id = $user_id";
                $db->query($sql);

                require_once("../tools/FirePHPCore/FirePHP.class.php");
                ob_start();
                $firephp = FirePHP::getInstance(true);        
                $firephp->log($sql, 'sql');
            }  
        }
        /*================================budget for a tag================================*/

        elseif ($description === 'budget') {
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
            $budget = json_decode(file_get_contents('php://input'), true)["budget"];
            $column = json_decode(file_get_contents('php://input'), true)["column"];

            $sql = "UPDATE tags SET $column = $budget WHERE id = $tag_id;";

            $sql_result = $db->query($sql);
        }
        
        /*================================budget================================*/

        // elseif ($description === 'budget') {
        //     //deleting in the form of updating
        //     $tag_id = $_GET['tag_id'];
        //     $tag_name = $_GET['tag_name'];
        //     $sql = "UPDATE tags SET budget=NULL, percent=NULL WHERE id='$tag_id'";

        //     $db->query($sql);
        // }
    
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
    