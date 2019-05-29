<?php
 //the session starts from here
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
        //establishes database connection
		$db = new PDO("mysql:host=$database_hostname;dbname=$database_name",$database_user,$database_password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
	}
    catch(PDOException $z)
    {
 
		die($z->getMessage());
	}
    //checks for login credentials entered by user through JSON
    if(isset($json['email']) &&isset($json['password']))
          {
                //Searches for user through the email entered by user
                $sql = $db->prepare("SELECT * FROM user WHERE userEmail = :email");
                $sql->bindValue(':email', $json['email']);
                $sql->execute();

                if($user = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        // in case of password entered by user matches
                        if (password_verify($json['password'], $user['userPassword']))
                        {
                            $success = true;
                            $_SESSION['user'] = $user['uid'];
                             $message = 'Password match';
                        } 
                        //in case of password entered by user is incorrect
                        else
                        {
                            $message = 'Password does not match';
                        }
                    } 
                else
                    {
                         //if the email entered by user is not found in records
                        $message = 'Email does not match our records';
                    }
                 $resp = new stdClass();
                $resp->success = $success;
                $resp->message = $message;

                echo json_encode($resp);

        }
   try{
   //Retrieve cart for the registered user
   $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	
 	
	$statement = $db->prepare($query);
    $statement->bindValue(':uid', $_SESSION['user']);
	$statement->execute();
     $total=0;
	$userData_List = array();
         
    while($row=$statement->fetch(PDO::FETCH_ASSOC))
     {
        $success = true;
        $userData_List['Data'][] = $row;
        //Calculates the total price of the total items in cart
        $total=$row['total$']+$total;
        
        $message = 'Cart retrieve successfull';
     }
        $userData_List['Total'][]=$total;
     echo json_encode($userData_List);
        
        //displays message if the cart is empty
        if($success!="true")
        {
                
                $message = 'No product in cart';
        }
            $resp = new stdClass();
            
            $resp->success = $success;
            $resp->message = $message;
              
            echo json_encode($resp);
       }
        catch(PDOException $e)
        {
            $success="false";
        }
if($success=="true")
{
        $myObj = new stdClass();
        $myObj->Purchase_cart="1";
        $myObj->Clear_cart="2";
        $myObj->Delete_item_from_cart="3";
        $myjson=json_encode($myObj);

        echo $myjson;
    if(isset($json['choice']))
    {
        //takes choice from user to perform actions on cart such as purchase, clear or delete item from cart
        $userinput=$json['choice'];
        //user enters 1 to purchase
        if($userinput=="1")
        {
            
            $query = "SELECT a.product_Id,a.productName,b.product_qty,a.productPrice,a.productShipCost,(a.productPrice+a.productShipCost)*b.product_Qty as total$ FROM product a,cart b where uid = :uid and a.product_Id=b.product_Id";	

            $statement = $db->prepare($query);
            $statement->bindValue(':uid', $_SESSION['user']);
            $statement->execute();

            $userData_List = array();

            while($row=$statement->fetch(PDO::FETCH_ASSOC))
             {
                 //the user enters the product to be purchased and in how much quantity
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
        //user enters 2 to delete the cart
        else if($userinput=="2")
        {
            //the whole cart is deleted for a particular user
            $query = 'DELETE from cart where uid=:uid';	

            $stmt1 = $db->prepare($query);
            $stmt1->bindValue(':uid', $_SESSION['user']);
            $stmt1->execute();
               $result="Success";
                echo $result;
        }
        //user enters 3 to delete particular item from the cart
        else if($userinput=="3" && isset($json['productId']))
        {
            //deletes the specific item which user wants to be deleted
            $query = 'DELETE from cart where uid=:uid and product_Id=:pid' ;	

            $stmt1 = $db->prepare($query);
            $stmt1->bindValue(':uid', $_SESSION['user']);
            $stmt1->bindValue(':pid', $json['productId']);
            $stmt1->execute();
               $result="Success";
                echo $result;
        }
    }
}
?>