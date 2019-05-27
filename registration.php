<?php
$username = "root";
$password = "";
$connected = false;

try {
    #connect to the database
    $db = new PDO('mysql:dbname=sportdb; host=localhost',$username, $password); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connected = true;
} catch(PDOException $e) {
    die($e->getMessage());
    #log the exception to a file on the server side
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
 $resp = new stdClass();
 $resp->id = 0;
   
        try{
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);

            if (isset($json['userEmail']) &&  strlen($json['userEmail'])> 0 && 
                isset($json['userPassword']) &&
                strlen($json['userPassword'])> 0 &&
                isset($json['user_fname']) &&
                strlen($json['user_fname'])> 0 &&
                isset($json['user_lname']) &&
                strlen($json['user_lname'])> 0 &&
                isset($json['userName']) && 
                strlen($json['userName'])> 0 &&
                isset($json['userShippingAdd']) &&
                strlen($json['userShippingAdd'])> 0){
                echo var_dump($json);
                $cmd = 'INSERT INTO user (userEmail,userPassword,user_fname,user_lname,userName,userShippingAdd)' .
                'VALUES (:email,:password,:fname,:lname,:username,:ship)';
                $sql = $db->prepare($cmd);
                $sql->bindValue(':email', $json['userEmail']);
                $sql->bindValue(':password', $json['userPassword']);
                $sql->bindValue(':fname', $json['user_fname']);
                $sql->bindValue(':lname', $json['user_lname']);
                $sql->bindValue(':username', $json['userName']);
                $sql->bindValue(':ship', $json['userShippingAdd']);
           
                $sql->execute();
                $resp->id = $db->lastInsertId();
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    
    echo json_encode($resp);
}
    
    
  /*//get the json the client sent
  $post = trim(file_get_contents("php://input"));
  $json = json_decode($post, true);

  $success = false;
  $message = '';
  $email = $json['Email'];
  $password = $json['Password'];
  $fname = $json['fname'];
  $lname = $json['lname'];
  $username = $json['username'];
  $useraddress= $json['ShippingAdd'];


  if (isset($email) && isset($password)  && isset($fname)  && isset($lname)  && isset($username)  && isset($useraddress)){
    //open access to our database
    try {
        $db = new PDO('mysql:host=localhost; dbname=sportdb;', 'root', ''); 
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //see if the email already exists in the database
        $sql = $db->prepare('SELECT * FROM user WHERE userEmail = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();
        
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if (!isset($user['userEmail'])){
            //if not, create a new user record and save it
            $cmd = 'INSERT INTO user (userEmail, userPassword,user_fname,user_lname,userName,userShippingAdd ' .
                'VALUES (:email, :password,:fname,:lname,:username,:useradrress)';
            $sql = $db->prepare($cmd);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':password', $password);
             $sql->bindValue(':fname', $fname);
            $sql->bindValue(':lname', $lname);
             $sql->bindValue(':username', $username);
            $sql->bindValue(':useraddress', $useraddress);
            $sql->execute();
            $success = true;
        } else
            $message = 'user ' . $user['userEmail'] . ' found';
    } catch(PDOException $e) {
        $message = $e->getMessage() . ' cannot connect to database';
    } catch(Exception $e){
        $message = $e->getMessage() . ' something else went wrong';
    }
  } else
    $message = 'email/password not set';

  //report back to client
  $myObj = new stdClass();
  $myObj->success = $success;
  $myObj->message = $message;
  $myJSON = json_encode($myObj);
  echo $myJSON;*/
?>