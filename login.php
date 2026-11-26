<!DOCTYPE HTML>
<html>
<head>          
    <title>Login</title>
</head>

<body>

<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == "empty") {
        echo "<p>Please enter your username and password.</p>";
    } elseif ($_GET["error"] == "invalid") {
        echo "<p>Incorrect username or password.</p>";
    }
}
?>

    <form action="processlogin.php" method="POST">
        Username:<input type="text" name="Username"><br>
        
        Password:<input type="password" name="Password"><br>
        
        <input type="submit" value="Login">
    </form>
    

</body>
</html>