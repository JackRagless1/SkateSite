
<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$username = trim($_POST["Username"] ?? "");
$password = $_POST["Password"] ?? "";

if ($username == "" || $password == "") {
    header("Location: login.php?error=empty");
    exit();
}

$stmt = $conn->prepare("SELECT UserID, Username, Password, Type
                        FROM tblusers
                        WHERE Username = :username");

$stmt->bindValue(":username", $username);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["Password"])) {
    session_regenerate_id(true);

    $_SESSION["loggedinuser"] = $user["UserID"];
    $_SESSION["Type"] = $user["Type"];
    $_SESSION["Username"] = $user["Username"];

    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=invalid");
    exit();
}
?>
