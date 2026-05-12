<!DOCTYPE HTML>
<html>
<head>          
    <title>PHP Info</title>
</head>

<body>
    <form action="adduser.php" method="POST">
        username:<input type="text" name="Username"><br>
        password:<input type="password" name="Password"><br>
        address:<input type="text" name="Address"><br>
        postcode:<input type="text" name="Postcode"><br>
        type:<br>
        <input type="radio" name="Type" value="0" checked> Buyer<br>
        <input type="radio" name="Type" value="1" > Seller<br>
        <input type="submit" value="Add User">
    </form>

</body>
</html>