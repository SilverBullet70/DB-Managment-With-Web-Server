<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {

    header("Location: registration.html");
}
if (!isset($_POST["table"])) {
    die("Required parameters are not set.");
}

$tableName = $_POST["table"];

$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT i.TABLE_NAME, COLUMN_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME 
FROM information_schema.TABLE_CONSTRAINTS i 
LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME 
WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY' 
AND i.TABLE_SCHEMA = DATABASE()
AND i.TABLE_NAME = '$tableName';";


$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}

echo json_encode($data);

$conn->close();
