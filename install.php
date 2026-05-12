<?php
$servername="localhost";
$username="root";
$password="password";
$conn= new PDO("mysql:host=$servername",$username,$password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql="CREATE DATABASE IF NOT EXISTS SkateSite";
$conn->exec($sql);
$sql="USE SkateSite";
$conn->exec($sql);
echo("DB created successfully<br>");

// create users table
$stmt=$conn->prepare("DROP TABLE IF EXISTS tblusers;
CREATE TABLE tblusers (
UserID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
Username VARCHAR(20) NOT NULL,
Password VARCHAR(200) NOT NULL,
Type BOOL NOT NULL,
Address VARCHAR(100) NOT NULL,
Postcode VARCHAR(20) NOT NULL
);
");
$stmt->execute();
echo("tblusers created<br>");

$hashedpassword=password_hash("password",PASSWORD_DEFAULT);
echo($hashedpassword);
echo("<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblusers 
(UserID,Username,Password,Type,Address,Postcode)
VALUES
(NULL,'test.u',:Password,0,'123 test street','PE8 4JQ'),
(NULL,'ragless.j',:Password,1,'138 Bailbrook Lane','BA1 7AB')
");
$stmt->bindParam(":Password", $hashedpassword);
$stmt->execute();

//create orders table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblorders;
CREATE TABLE tblorders (
    userID INT(6) NOT NULL,
    orderNumber INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    orderDate DATE NOT NULL
);
");
$stmt->execute();
echo("tblorders created<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblorders 
(UserID,orderNumber,orderDate)
VALUES
(1,NULL,'2026-04-24')
");
$stmt->execute();

//create orderitems table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblorderitems;
CREATE TABLE tblorderitems (
    orderNumber INT(6) NOT NULL,
    itemID INT(6) NOT NULL,
    quantity INT(4) NOT NULL
);
");
$stmt->execute();
echo("tblorderitems created<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblorderitems 
(orderNumber,itemID,quantity)
VALUES
(1,123456,3)
");
$stmt->execute();

//create items table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblitems;
CREATE TABLE tblitems (
    itemID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(50) NOT NULL,
    descrip VARCHAR(200) NOT NULL,
    userID INT(6) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    catID INT(1) NOT NULL,
    stock INT(6) NOT NULL
);
");
$stmt->execute();
echo("tblitems created<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblitems 
(itemID,itemName,descrip,userID,price,catID,stock)
VALUES
(NULL,'test item','test description',1,29.99,0,100)
");
$stmt->execute();

//create categories table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblcategories;
CREATE TABLE tblcategories (
    catID INT(1) NOT NULL,
    category VARCHAR(20) NOT NULL
);
");
$stmt->execute();
echo("tblcategories created<br>");

//add categories
$stmt=$conn->prepare("INSERT INTO tblcategories
    (catID, Category)
    VALUES
    (0,'Decks'),
    (1,'Trucks'),
    (2,'Wheels'),
    (3,'Bearings'),
    (4,'Grip Tape'),
    (5,'Accessories'),
    (6,'Boards')
");
$stmt->execute();

//create itemhasphoto table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblitemhasphoto;
CREATE TABLE tblitemhasphoto (
    itemID INT(6) NOT NULL,
    photoID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY
);
");
$stmt->execute();
echo("tblitemhasphoto created<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblitemhasphoto
(itemID,photoID)
VALUES
(1,NULL)
");
$stmt->execute();

//create photos table
$stmt = $conn->prepare("
DROP TABLE IF EXISTS tblphotos;
CREATE TABLE tblphotos (
    photoID INT(6) NOT NULL,
    photoLink VARCHAR(20)
);
");
$stmt->execute();
echo("tblphotos created<br>");

//making test data
$stmt=$conn->prepare("INSERT INTO tblphotos
(photoID,photoLink)
VALUES
(1,'testphoto.jpg')
");
$stmt->execute();
?>