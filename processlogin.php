<?php
session_start();
print_r($_POST);
array_map("htmlspecialchars", $_POST);
include_once("connection.php");

try{
    $stmt=$conn->prepare("SELECT * from tblusers WHERE username=:username;");
    $stmt->bindParam(":username", $_POST["Username"]);
    $stmt->execute(); 

    //check username
    if ($stmt->rowCount() == 0) {
        echo("Invalid Username."); 
        header("location: login.php");
    }else{
        //check password
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            print_r($row);
            $hashed=$row["password"];
            $attempt=$_POST["Password"];

            if (password_verify($attempt,$hashed)){
                echo("success");
                $_SESSION["loggedinuser"]=$row["UserID"];
                $_SESSION["Type"]=$row["type"];
                header("location: index.php");
            }else{
                echo("Incorrect Password");
                header("location: login.php");
            }
        }
    }
}
catch(PDOException $e)
{
    echo("error: " . $e->getMessage());
}

?>