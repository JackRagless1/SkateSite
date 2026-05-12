<?php
include_once("connection.php");
if($_POST["Type"]==0){
    $type=0;
}else{
    $type=1;
}
$username=$_POST["Username"];
$hashedpassword=password_hash($_POST["Password"],PASSWORD_DEFAULT);
echo($hashedpassword);
try{
    $stmt=$conn->prepare("INSERT INTO tblusers 
    (UserID,Username,Password,Type,Address,Postcode)
    VALUES
    (NULL,:username,:password,:type,:address,:postcode)
    ");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedpassword);
    $stmt->bindParam(":type", $type);
    $stmt->bindParam(":address", $_POST["Address"]);
    $stmt->bindParam(":postcode", $_POST["Postcode"]);
    $stmt->execute();
}
catch(PDOException $e)
{
    echo("error: " . $e->getMessage());
}
?>