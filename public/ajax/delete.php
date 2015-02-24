<?php

    include("database-connect.php");
    
    $description = json_decode(file_get_contents('php://input'), true)["description"];
    
    try {

        /*================================tag================================*/

        if ($description === 'tag') {
            $tag_id = json_decode(file_get_contents('php://input'), true)["tag_id"];
            $sql = "DELETE FROM tags WHERE id='$tag_id'";

            $db->query($sql);
        }

        /*================================account================================*/

        elseif ($description === 'account') {
            $account_id = json_decode(file_get_contents('php://input'), true)["account_id"];
            $sql = "DELETE FROM accounts WHERE id='$account_id'";
            $db->query($sql);
        }

        /*================================transaction================================*/

        elseif ($description === 'transaction') {
            $transaction_id = json_decode(file_get_contents('php://input'), true)["transaction_id"];
            
            $sql = "DELETE FROM transactions_tags WHERE transaction_id = '$transaction_id';
            DELETE FROM transactions WHERE id='$transaction_id'";

            require_once("../tools/FirePHPCore/FirePHP.class.php");
            ob_start();
            $firephp = FirePHP::getInstance(true);        
            $firephp->log($sql, 'sql');

            $db->query($sql);
        }

        //=========================response=========================
    
        $variables = get_defined_vars();
    
        $response = array(
            "variables" => $variables,
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
    