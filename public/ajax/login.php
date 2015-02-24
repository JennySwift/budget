<?php
	
	require_once('../php/inc/config.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);

		if ($username == "" OR $password == "") {
			echo "Please fill in all fields.";
			exit;
		}

		//addressing the serious spam attack
		foreach( $_POST as $value ){
		  if( stripos($value,'Content-Type:') !== FALSE ){
		    echo "There was a problem with the information you entered.";
		    exit;
		  }
		}

		//addressing the annoying spam attack
		if ($_POST["address"] != "") {
			echo "Your form submission has an error.";
			exit;
		}

		//=========================the database code=========================

		include("database-connect.php");

	    try {

	        $sql = "SELECT * FROM users WHERE name = '$username' AND password = '$password';";

	        $result = $db->query($sql);
	    }
	    catch (Exception $e) {
	        echo "Data could not be retrieved from the database.";
	        exit;
	    }

	    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	    	$database_user_id = $row['id'];
	    	$database_username = $row['name'];
	    	$database_password = $row['password'];
	    }

    	if ($username !== $database_username OR $password !== $database_password) {
    		echo "Incorrect details";
    		exit;
    	}

		// ===============================Correct details===============================
		else {
			session_start();
			$_SESSION['database_user_id'] = $database_user_id;
			$_SESSION['database_username'] = $database_username;
			$_SESSION['database_password'] = $database_password;
			header("Location: $home");
			exit;
		} 
		
	}
?>
