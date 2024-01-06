
<?php

session_start();

$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";
$user = $_POST["uname"];
$pass = $_POST["psw"];
$pass = md5($pass);

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM login WHERE (username = '$user' AND password = '$pass');";
$result = $conn->query($sql);
echo $result->num_rows;
echo $sql;
if ($result->num_rows > 0) {
    $_SESSION["user"] = $user;
   header("Location: ../index.php");
} else {
   header("Location: registration.html");
}
$conn->close();

?>