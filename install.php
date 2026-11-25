
<?php
// Database connection
// Reference: https://www.w3schools.com/php/php_mysql_connect.asp

$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create and select database
    $conn->exec("CREATE DATABASE IF NOT EXISTS SkateSite");
    $conn->exec("USE SkateSite");

    echo "Database created successfully<br>";

    $conn->exec("DROP TABLE IF EXISTS tblitemhasphoto");
    $conn->exec("DROP TABLE IF EXISTS tblphotos");
    $conn->exec("DROP TABLE IF EXISTS tblorderitems");
    $conn->exec("DROP TABLE IF EXISTS tblorders");
    $conn->exec("DROP TABLE IF EXISTS tblitems");
    $conn->exec("DROP TABLE IF EXISTS tblcategories");
    $conn->exec("DROP TABLE IF EXISTS tblusers");

    // Create users table
    $conn->exec("CREATE TABLE tblusers (
        UserID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        Username VARCHAR(20) NOT NULL,
        Password VARCHAR(200) NOT NULL,
        Type BOOL NOT NULL,
        Address VARCHAR(100) NOT NULL,
        Postcode VARCHAR(20) NOT NULL
    )");
    echo "tblusers created<br>";

    // Create orders table
    $conn->exec("CREATE TABLE tblorders (
        userID INT(6) NOT NULL,
        orderNumber INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        orderDate DATE NOT NULL
    )");
    echo "tblorders created<br>";

    // Create order items table
    $conn->exec("CREATE TABLE tblorderitems (
        orderNumber INT(6) NOT NULL,
        itemID INT(6) NOT NULL,
        quantity INT(4) NOT NULL
    )");
    echo "tblorderitems created<br>";

    // Create items table
    $conn->exec("CREATE TABLE tblitems (
        itemID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        itemName VARCHAR(50) NOT NULL,
        descrip VARCHAR(200) NOT NULL,
        userID INT(6) NOT NULL,
        price DECIMAL(6,2) NOT NULL,
        catID INT(1) NOT NULL,
        stock INT(6) NOT NULL
    )");
    echo "tblitems created<br>";

    // Create categories table
    $conn->exec("CREATE TABLE tblcategories (
        catID INT(1) NOT NULL,
        category VARCHAR(20) NOT NULL
    )");
    echo "tblcategories created<br>";

    // Create item-photo linking table
    $conn->exec("CREATE TABLE tblitemhasphoto (
        itemID INT(6) NOT NULL,
        photoID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY
    )");
    echo "tblitemhasphoto created<br>";

    // Create photos table
    $conn->exec("CREATE TABLE tblphotos (
        photoID INT(6) NOT NULL,
        photoLink VARCHAR(20)
    )");
    echo "tblphotos created<br>";

    // Add test users

    $hashedpassword = password_hash("password", PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tblusers
        (Username, Password, Type, Address, Postcode)
        VALUES (:username, :password, :type, :address, :postcode)");

    $stmt->execute([
        ':username' => 'test.u',
        ':password' => $hashedpassword,
        ':type' => 0,
        ':address' => '123 Test Street',
        ':postcode' => 'AB1 2CD'
    ]);

    $buyerID = $conn->lastInsertId();

    $stmt->execute([
        ':username' => 'test.seller',
        ':password' => $hashedpassword,
        ':type' => 1,
        ':address' => '456 Example Road',
        ':postcode' => 'EF3 4GH'
    ]);

    $sellerID = $conn->lastInsertId();

    echo "Test users added<br>";

    // Add categories
    $stmt = $conn->prepare("INSERT INTO tblcategories
        (catID, category)
        VALUES
        (0, 'Decks'),
        (1, 'Trucks'),
        (2, 'Wheels'),
        (3, 'Bearings'),
        (4, 'Grip Tape'),
        (5, 'Accessories'),
        (6, 'Boards')");

    $stmt->execute();
    echo "Categories added<br>";

    // Add test product

    $stmt = $conn->prepare("INSERT INTO tblitems
        (itemName, descrip, userID, price, catID, stock)
        VALUES (:name, :description, :userID, :price, :catID, :stock)");

    $stmt->execute([
        ':name' => 'Test Skateboard Deck',
        ':description' => 'Test skateboard deck for SkateBox',
        ':userID' => $sellerID,
        ':price' => 29.99,
        ':catID' => 0,
        ':stock' => 100
    ]);

    $itemID = $conn->lastInsertId();

    echo "Test product added with itemID: $itemID<br>";

    // Add test order

    $stmt = $conn->prepare("INSERT INTO tblorders
        (userID, orderDate)
        VALUES (:userID, :orderDate)");

    $stmt->execute([
        ':userID' => $buyerID,
        ':orderDate' => date('Y-m-d')
    ]);

    $orderNumber = $conn->lastInsertId();

    echo "Test order added with orderNumber: $orderNumber<br>";

    // Add test order item using the generated IDs

    $stmt = $conn->prepare("INSERT INTO tblorderitems
        (orderNumber, itemID, quantity)
        VALUES (:orderNumber, :itemID, :quantity)");

    $stmt->execute([
        ':orderNumber' => $orderNumber,
        ':itemID' => $itemID,
        ':quantity' => 3
    ]);

    echo "Test order item added (3 units sold)<br>";

    // Add test photo association

    $stmt = $conn->prepare("INSERT INTO tblitemhasphoto
        (itemID) VALUES (:itemID)");

    $stmt->execute([':itemID' => $itemID]);

    $photoID = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO tblphotos
        (photoID, photoLink)
        VALUES (:photoID, :photoLink)");

    $stmt->execute([
        ':photoID' => $photoID,
        ':photoLink' => 'testphoto.jpg'
    ]);

    echo "Test photo record added<br>";
    echo "<br>Installation completed successfully!";

} catch (PDOException $e) {
    echo "Installation failed: " . htmlspecialchars($e->getMessage());
}
?>
