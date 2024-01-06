
<?php

session_start();
if (!isset($_SESSION["user"])) {
    header("Location: registration.html");
}

$user = $_POST["username"];
$pass = $_POST["password"];

$encr_pass = md5($pass);

$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";




$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT FROM login WHERE username = '$user'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "Username already exists";
    exit();
} else {


    $sql = "INSERT INTO login VALUES ('$user', '$encr_pass');";
    $result = $conn->query($sql);
    $conn->close();

    header("Location: registration.html");
}
?>