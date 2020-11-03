<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Schnaeppchen";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT `hauptrubrikenID`, `hauptrubrik` FROM `hauptrubriken`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $arr = "array(";
  while($row = $result->fetch_assoc()) {
    $arr .= $row["hauptrubrikenID"] . '=>"' . $row["hauptrubrik"] . '",';
  }
  $arr .= ")";
  echo $arr;
}
$conn->close();