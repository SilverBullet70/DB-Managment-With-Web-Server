<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {

    header("Location: registration.html");
}
if (!isset($_POST["table"]) || !isset($_POST["values"])) {
    die("Required parameters are not set.");
}
$tableName = $_POST["table"];
$values = $_POST["values"];


$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "INSERT INTO $tableName VALUES ($values);";



$result = $conn->query($sql);


$conn->close();