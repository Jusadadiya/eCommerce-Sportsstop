<?php
  session_start();

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
    try
    {
		$db = new PDO("mysql:host=$database_hostname;dbname=$database_name",$database_user,$database_password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
	}
    catch(PDOException $z)
    {
 
		die($z->getMessage());
	}

    $query = "SELECT * FROM cart";	
	$statement = $db->prepare($query);
	$statement->execute();
 
	$userData_List = array();
 
	while($row=$statement->fetch(PDO::FETCH_ASSOC))
    {
		
        $userData_List['Data'][] = $row;	
 	}
	echo json_encode($userData_List);


?>