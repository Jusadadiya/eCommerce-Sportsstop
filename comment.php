<?php
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
$query = "SELECT product_Id,userEmail,productRating, product_comment FROM comments";	
$statement = $database_connection->prepare($query);
$statement->execute();

$userData_List = array();

while($row=$statement->fetch(PDO::FETCH_ASSOC)){
    
    $userData_List['Data'][] = $row;	


}
echo json_encode($userData_List);




?>