<?php
  session_start();
  $post = trim(file_get_contents("php://input"));
	//and decode it into an associative array
	$json = json_decode($post, true);
   
  try {
	//get the raw POST content 
  
	 
      if(isset($json['email']) &&isset($json['password']))
      {
	$success = false;
	$message = '';
    $dbuser="root";
	 $email = $json['email'];
	$password = $json['password'];
	  
    // Create connection to mySQL server
    $db = new PDO("mysql:dbname=sportdb;host=localhost,$dbuser"); 
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = $db->prepare("SELECT * FROM user WHERE userEmail = :email");
	$sql->bindValue(':email', $email);
	$sql->execute();
	
	if($user = $sql->fetch(PDO::FETCH_ASSOC)){
		if (password_verify($password, $user['userPassword'])){
			$success = true;
			$_SESSION['user'] = $user['uid'];
		} else
			$message = 'Password does not match';
	} else
		$message = 'Email does not match our records';
	
	$resp = new stdClass();
	$resp->success = $success;
	$resp->message = $message;

	echo json_encode($resp);
      }
  } catch(PDOException $e) {
	  print $e->getMessage();
  }

    $database_hostname = "localhost";
	$database_user = "root";
	$database_password = "";
	$database_name = "sportdb";
 
	try{
		$database_connection = new PDO("mysql:host=$database_hostname;dbname=$database_name",$database_user,$database_password);
		$database_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
	}catch(PDOException $z){
 
		die($z->getMessage());
	}
    $query = "SELECT product_Id,productName,productDesc, productPrice,productShipCost FROM product";	
	$statement = $database_connection->prepare($query);
	$statement->execute();
 
	$userData_List = array();
 
	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		
        $userData_List['Data'][] = $row;	
    

	}
	echo json_encode($userData_List);
?>