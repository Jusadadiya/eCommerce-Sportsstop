<?php
    
  //get the json the client sent
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
  $hash = password_hash($password, PASSWORD_DEFAULT);

  if (isset($email) && isset($password)  && isset($fname)  && isset($lname)  && isset($username)  && isset($useraddress))
  {
    //open access to our database
    try {
        $db = new PDO('mysql:host=localhost; dbname=sportdb;', 'root', ''); 
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //see if the email already exists in the database
        $sql = $db->prepare('SELECT * FROM user WHERE userEmail = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();
        
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if (isset($user['userEmail']))
        {
            //
            $cmd1 = 'Update user SET userEmail=:email,userPassword=:password,user_fname=:fname,user_lname=:lname,userName=:username,userShippingAdd=:useraddress where uid=:uid';
            $sqls = $db->prepare($cmd1);
            $sqls->bindValue(':uid', $user['uid']);
            $sqls->bindValue(':email', $email);
            $sqls->bindValue(':password', $hash);
             $sqls->bindValue(':fname', $fname);
            $sqls->bindValue(':lname', $lname);
             $sqls->bindValue(':username', $username);
            $sqls->bindValue(':useraddress', $useraddress);
            $sqls->execute();
            $success = true;
            
        } 
        else 
        {
            //if not, create a new user record and save it
            $cmd = 'INSERT INTO user (userEmail, userPassword,user_fname,user_lname,userName,userShippingAdd)' .
                'VALUES (:email, :password,:fname,:lname,:username,:useraddress)';
            $sql = $db->prepare($cmd);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':password', $hash);
             $sql->bindValue(':fname', $fname);
            $sql->bindValue(':lname', $lname);
             $sql->bindValue(':username', $username);
            $sql->bindValue(':useraddress', $useraddress);
            $sql->execute();
            $success = true;
           
        }
        
    } 
      catch(PDOException $e) 
    {
        $message = $e->getMessage() . ' cannot connect to database';
    } 
     
  } 
else
  {
      $message = 'email/password not set';
  }
    

  //report back to client
  $myObj = new stdClass();
  $myObj->success = $success;
  $myObj->message = $message;
  $myJSON = json_encode($myObj);
  echo $myJSON;
?>