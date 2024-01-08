<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {

    header("Location: registration.html");
}
if (!isset($_POST["table"]) || !isset($_POST["column"]) || !isset($_POST["condition"])) {
    die("Required parameters are not set.");
}
$tableName = $_POST["table"];
$column = $_POST["column"];
$condition = $_POST["condition"];

$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT COUNT(COLUMN_NAME) AS 'num'
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'cars'
AND TABLE_NAME = '$tableName'";

$numOfColumns = 0;
$cols =  array();
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $numOfColumns = $row["num"];
    }
} else {
    echo "0 results";
}


$sql = "SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = N'$tableName'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $cols[$row["COLUMN_NAME"]] = $row["COLUMN_NAME"];
       // array_push($cols, $row["COLUMN_NAME"]);
    }
} else {
    echo "0 results";
}


$sql = "SELECT ". $column ." FROM $tableName";
if($condition != "null"){
    $sql .= " WHERE " . $condition;
}


$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {

    if($column == "*"){
        array_push($data, $cols);
    }

    while ($row = $result->fetch_assoc()) {

        array_push($data, $row);
        
    }
}

echo json_encode($data);

$conn->close();