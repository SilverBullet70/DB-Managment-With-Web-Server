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
$sql = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = N'" . $tableName . "';";


$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}

echo json_encode($data);

$conn->close();




