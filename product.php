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

    $query = "SELECT * FROM product";	
	$statement = $db->prepare($query);
	$statement->execute();
 
	$userData_List = array();
 
	while($row=$statement->fetch(PDO::FETCH_ASSOC))
    {
		
        $userData_List['Data'][] = $row;	
 	}
	echo json_encode($userData_List);

    if(isset($json['email']) &&isset($json['password']))
      {

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
             $resp = new stdClass();
            $resp->success = $success;
            $resp->message = $message;

            echo json_encode($resp);

    }


else if(isset($_SESSION['user']) && isset($json['productId']) && isset($json['productQty']))
{
    
    try 
    {
        $sql = $db->prepare('SELECT * FROM product WHERE product_Id = :pid');
        $sql->bindValue(':pid', $json['productId']);
        $sql->execute();
        $userid= $_SESSION['user'];
        
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if (isset($json['productId']) && isset($json['productQty']))
        {
            //if not, create a new user record and save it
            $cmd = 'INSERT INTO cart (product_Id,product_Qty,uid)' .
                'VALUES (:pid,:pqty,:userid)';
            $sql = $db->prepare($cmd);
            $sql->bindValue(':pid', $json['productId']);
            $sql->bindValue(':pqty', $json['productQty']);
            $sql->bindValue(':userid',$userid);
            $sql->execute();
            $success = true;
        } 
        else
        {
                $message = 'Product id does not match our records';
        }
     } 
    catch(Exception $e)
    {
        $message = $e->getMessage() . ' something else went wrong';
    }
  $myObj = new stdClass();
  $myObj->success = $success;
  $myObj->message = $message;
  $myJSON = json_encode($myObj);
  echo $myJSON;
}
    

?>