<?php
  session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $resp = new stdClass();
    $post = trim(file_get_contents("php://input"));
	//and decode it into an associative array
	$json = json_decode($post, true);
	//get the raw POST content 
	$success = false;
	$message = '';
    $database_hostname = "localhost";
	$database_user = "root";
	$database_password = "";
	$database_name = "sportdb";
    try{
		$db = new PDO("mysql:host=$database_hostname;dbname=$database_name",$database_user,$database_password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
	}catch(PDOException $z){
 
		die($z->getMessage());
	}
	
	$sql = $db->prepare("SELECT * FROM user WHERE userEmail = :email");
	$sql->bindValue(':email', $json['email']);
	$sql->execute();
	
	if($user = $sql->fetch(PDO::FETCH_ASSOC))
    {
		if (password_verify($json['password'], $user['userPassword']))
        {
			$success = true;
			$_SESSION['user'] = $user['uid'];
		} 
        else
        {
			$message = 'Password does not match';
	    }
    } 
    else
    {
		$message = 'Email does not match our records';
    }
	
	$resp->success = $success;
	$resp->message = $message;

	echo json_encode($resp);
     
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
    $query = "SELECT * FROM product";	
	$statement = $database_connection->prepare($query);
	$statement->execute();
 
	$userData_List = array();
 
	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		
        $userData_List['Data'][] = $row;	
    

	}
	echo json_encode($userData_List);
?>