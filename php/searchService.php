<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user"])) {

    header("Location: registration.html");
}

$tableName = $_POST["table"];
echo $_POST["table"];

echo "<h1>$tableName Table</h1>";

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
        array_push($cols, $row["COLUMN_NAME"]);
    }
} else {
    echo "0 results";
}


$sql = "SELECT * FROM $tableName";
echo $sql;
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "<table>";

    echo "<tr>";
    $i = 0;
    while ($i < $numOfColumns) {
        echo "<th>" . $cols[$i] . "</th>";
        $i++;
    }
    echo "</tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        $i = 0;
        while ($i < $numOfColumns) {
            echo "<td>" . $row[$cols[$i]] . "</td>";
            $i++;
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();


?>