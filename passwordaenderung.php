<?php
$Anmeldename = $Password = "";
$altesPassword = $neuesPassword = $Passwordwiederholen = "";
$altesPasswordErr = $neuesPasswordErr = $PasswordwiederholenErr = "";
$Abbrechen = $Ok = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["Anmeldename"])) {
  $Anmeldename = "";
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schnaeppchen";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
/*$AN =  $_GET["Anmeldename"];
$sql = "SELECT * FROM `anmeldungen` WHERE Anmeldename = $AN";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$Anmeldename = $row["Anmeldename"];
$conn->close();

echo "$Anmeldename";*/

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if (empty($_POST["Anmeldename"])) {
    $AnmeldenameErr = "Anmeldename ist Pflicht";
  }
  else {
    $Anmeldename = test_input($_POST["Anmeldename"]);
    if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9@._]{5,255}$/",$Anmeldename)) {
      $AnmeldenameErr = "nur Buchstaben erlaubt";
    }
  }
  
  if (empty($_POST["Password"])) {
    $PasswordErr = "Password ist pflicht";
  }
  else {
    $Password = ($_POST["Password"]);
  }

  if (($AnmeldenameErr . $PasswordErr) == "") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Schnaeppchen";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
  }  
}
$sql = "UPDATE 'anmeldungen' SET 'Password' = 'Password' , 'password' = '';";
$sql .= " where KundenID = '';";

?>
<!DOCTYPE HTML>
<html>
  <body>
  <h2>Password Änderung</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    alltes Password: <input required type="Password" name="altesPassword" value="">
    <span class="error">* <?php echo $altesPasswordErr;?></span>
    <br><br><br>
    neues Password: <input required type="Password" name="neuesPassword" value="">
    <span class="error">* <?php echo $neuesPasswordErr;?></span>
    <br><br><br>
    Password wiederholen: <input required type="Password" name="Passwordwiederholen" value="">
    <span class="error">* <?php echo $PasswordwiederholenErr;?></span>
    <br><br><br>
    <button name="aktion" class="button button1" value="Anmeldung">Abbrechen</button>
    <button name="aktion" class="button button2"value="Anmeldung" >Ok</button>
    <br><br><br>
</form>
</body>
</html>