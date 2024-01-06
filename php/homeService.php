<?php



$servername = "localhost:3306/";
$username = "root";
$password = "";
$dbname = "cars";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}


$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   // output data of each row
   while($row = $result->fetch_assoc()) {
         $table = $row["Tables_in_cars"];
       echo "<a href='table.php?table=$table'>$table</a><br>";
   }
} else {
   echo "0 results";
}

?>