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
if(isset($_SESSION['user']))
{
        //displays the purchase history of existing users
        $query = "SELECT * FROM purchasehistory";	
        $statement = $db->prepare($query);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
        {

            $userData_List['User Purchase History'][] = $row;	


        }
        echo json_encode($userData_List);
    
    
    if(isset($json['productId']) &&isset($json['rating']) && isset($json['description']))
    {
        //asks the user to rate and comment the product purchased
        $query = 'INSERT INTO comments ( product_Id,uid,productRating,product_comment)' .
                        'VALUES (:pid,:uid,:prating,:pcmt)';	

            $stmt = $db->prepare($query);
            $stmt->bindValue(':pid',$json['productId']);
            $stmt->bindValue(':uid',$_SESSION['user']);
            $stmt->bindValue(':prating', $json['rating']);
            $stmt->bindValue(':pcmt', $json['description']);
            $stmt->execute();
            $message="comment successful";
           
        #header("location:comment.php");
    }
}
        echo $message;
        //displays all ratings and comments
        $query = "SELECT * FROM comments";	
        $statement = $db->prepare($query);
        $statement->execute();

        $userData_List = array();

        while($row=$statement->fetch(PDO::FETCH_ASSOC))
        {

            $userData_List['Data'][] = $row;	


        }
        echo json_encode($userData_List);
?>