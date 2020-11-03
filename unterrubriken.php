<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Schnaeppchen";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if (!empty($_GET["id"])) {
  $id = $_GET["id"];
  $sql = "SELECT `unterrubrikenID`, `Unterrubrik` FROM `unterrubriken` where `hauptrubrikenID` = $id";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $arr = "<option value='0'>Wählen Sie ein Elemnt aus!</option>";
    while($row = $result->fetch_assoc()) {
      $arr .= "<option value='" . $row["unterrubrikenID"] . "'>" . $row["Unterrubrik"] . "</option>";
    }
    echo $arr;
  }
}
else {
  $arr="array()";
}
$conn->close();