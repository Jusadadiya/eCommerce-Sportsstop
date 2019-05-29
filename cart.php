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

   $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	
 	
	$statement = $db->prepare($query);
    $statement->bindValue(':uid', $_SESSION['user']);
	$statement->execute();
     
	$userData_List = array();
         
    while($row=$statement->fetch(PDO::FETCH_ASSOC))
     {
        $success = true;
        $userData_List['Data'][] = $row;
               
     }
     echo json_encode($userData_List);
        
        if($success!="true")
        {
                $message = 'No product in cart';
        }
            $resp = new stdClass();
            $resp->success = $success;
            $resp->message = $message;

            echo json_encode($resp);
if($success=="false")
{
        $myObj = new stdClass();
        $myObj->Purchase_cart="1";
        $myObj->Clear_cart="2";
        $myObj->Delete_item_from_cart="3";
        $myjson=json_encode($myObj);

        echo $myjson;
        $userinput=$json['choice'];
    if($userinput=="1")
    {
        $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	

        $statement = $db->prepare($query);
        $statement->bindValue(':uid', $_SESSION['user']);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
         {
            $query = 'INSERT INTO purchasehistory (uid, product_Id,purchase_Qty,purchase_amount)' .
                    'VALUES (:uid, :pid,:pqty,:pamt)';	

        $stmt = $db->prepare($query);
        $stmt->bindValue(':uid', $_SESSION['user']);
        $stmt->bindValue(':pid', $row['product_Id']);
        $stmt->bindValue(':pqty', $row['product_qty']);
        $stmt->bindValue(':pamt', $row['total$']);
        $stmt->execute();

         }
        #$result="Success";
            echo $result;
         $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="2")
    {
        $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="3" && isset($json['productId']))
    {
        $query = 'DELETE from cart where uid=:uid and product_Id=:pid' ;	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->bindValue(':pid', $json['productId']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
}
?><?php
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

   $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	
 	
	$statement = $db->prepare($query);
    $statement->bindValue(':uid', $_SESSION['user']);
	$statement->execute();
     
	$userData_List = array();
         
    while($row=$statement->fetch(PDO::FETCH_ASSOC))
     {
        $success = true;
        $userData_List['Data'][] = $row;
               
     }
     echo json_encode($userData_List);
        
        if($success!="true")
        {
                $message = 'No product in cart';
        }
            $resp = new stdClass();
            $resp->success = $success;
            $resp->message = $message;

            echo json_encode($resp);
if($success=="false")
{
        $myObj = new stdClass();
        $myObj->Purchase_cart="1";
        $myObj->Clear_cart="2";
        $myObj->Delete_item_from_cart="3";
        $myjson=json_encode($myObj);

        echo $myjson;
        $userinput=$json['choice'];
    if($userinput=="1")
    {
        $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	

        $statement = $db->prepare($query);
        $statement->bindValue(':uid', $_SESSION['user']);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
         {
            $query = 'INSERT INTO purchasehistory (uid, product_Id,purchase_Qty,purchase_amount)' .
                    'VALUES (:uid, :pid,:pqty,:pamt)';	

        $stmt = $db->prepare($query);
        $stmt->bindValue(':uid', $_SESSION['user']);
        $stmt->bindValue(':pid', $row['product_Id']);
        $stmt->bindValue(':pqty', $row['product_qty']);
        $stmt->bindValue(':pamt', $row['total$']);
        $stmt->execute();

         }
        #$result="Success";
            echo $result;
         $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="2")
    {
        $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="3" && isset($json['productId']))
    {
        $query = 'DELETE from cart where uid=:uid and product_Id=:pid' ;	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->bindValue(':pid', $json['productId']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
}
?><?php
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

   $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	
 	
	$statement = $db->prepare($query);
    $statement->bindValue(':uid', $_SESSION['user']);
	$statement->execute();
     
	$userData_List = array();
         
    while($row=$statement->fetch(PDO::FETCH_ASSOC))
     {
        $success = true;
        $userData_List['Data'][] = $row;
               
     }
     echo json_encode($userData_List);
        
        if($success!="true")
        {
                $message = 'No product in cart';
        }
            $resp = new stdClass();
            $resp->success = $success;
            $resp->message = $message;

            echo json_encode($resp);
if($success=="false")
{
        $myObj = new stdClass();
        $myObj->Purchase_cart="1";
        $myObj->Clear_cart="2";
        $myObj->Delete_item_from_cart="3";
        $myjson=json_encode($myObj);

        echo $myjson;
        $userinput=$json['choice'];
    if($userinput=="1")
    {
        $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	

        $statement = $db->prepare($query);
        $statement->bindValue(':uid', $_SESSION['user']);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
         {
            $query = 'INSERT INTO purchasehistory (uid, product_Id,purchase_Qty,purchase_amount)' .
                    'VALUES (:uid, :pid,:pqty,:pamt)';	

        $stmt = $db->prepare($query);
        $stmt->bindValue(':uid', $_SESSION['user']);
        $stmt->bindValue(':pid', $row['product_Id']);
        $stmt->bindValue(':pqty', $row['product_qty']);
        $stmt->bindValue(':pamt', $row['total$']);
        $stmt->execute();

         }
        #$result="Success";
            echo $result;
         $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="2")
    {
        $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="3" && isset($json['productId']))
    {
        $query = 'DELETE from cart where uid=:uid and product_Id=:pid' ;	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->bindValue(':pid', $json['productId']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
}
?><?php
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

   $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	
 	
	$statement = $db->prepare($query);
    $statement->bindValue(':uid', $_SESSION['user']);
	$statement->execute();
     
	$userData_List = array();
         
    while($row=$statement->fetch(PDO::FETCH_ASSOC))
     {
        $success = true;
        $userData_List['Data'][] = $row;
               
     }
     echo json_encode($userData_List);
        
        if($success!="true")
        {
                $message = 'No product in cart';
        }
            $resp = new stdClass();
            $resp->success = $success;
            $resp->message = $message;

            echo json_encode($resp);
if($success=="false")
{
        $myObj = new stdClass();
        $myObj->Purchase_cart="1";
        $myObj->Clear_cart="2";
        $myObj->Delete_item_from_cart="3";
        $myjson=json_encode($myObj);

        echo $myjson;
        $userinput=$json['choice'];
    if($userinput=="1")
    {
        $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	

        $statement = $db->prepare($query);
        $statement->bindValue(':uid', $_SESSION['user']);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
         {
            $query = 'INSERT INTO purchasehistory (uid, product_Id,purchase_Qty,purchase_amount)' .
                    'VALUES (:uid, :pid,:pqty,:pamt)';	

        $stmt = $db->prepare($query);
        $stmt->bindValue(':uid', $_SESSION['user']);
        $stmt->bindValue(':pid', $row['product_Id']);
        $stmt->bindValue(':pqty', $row['product_qty']);
        $stmt->bindValue(':pamt', $row['total$']);
        $stmt->execute();

         }
        #$result="Success";
            echo $result;
         $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="2")
    {
        $query = 'DELETE from cart where uid=:uid';	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
    else if($userinput=="3" && isset($json['productId']))
    {
        $query = 'DELETE from cart where uid=:uid and product_Id=:pid' ;	

        $stmt1 = $db->prepare($query);
        $stmt1->bindValue(':uid', $_SESSION['user']);
        $stmt1->bindValue(':pid', $json['productId']);
        $stmt1->execute();
           $result="Success";
            echo $result;
    }
}
?>