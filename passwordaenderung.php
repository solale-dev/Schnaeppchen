<?php
session_start();

$sqlMeldung = "";
$Anmeldename = $Password = "";
$altesPassword = $neuesPassword = $Passwordwiederholen = "";
$PasswordErr = "";
$Abbrechen = $Ok = "";

$Anmeldename = $_SESSION["Anmeldename"];

if ($_SERVER["REQUEST_METHOD"] == "POST"){

  if (empty($_POST["altesPassword"])) {
    $PasswordErr .= "<br>Password ist pflicht";
  }
  else {
    $Password = $_POST["altesPassword"];
  }
  if (empty($_POST["neuesPassword"])) {
    $PasswordErr .= "<br>Password ist pflicht";
  }
  else {
    $neuesPassword = $_POST["neuesPassword"];
  }
  if (empty($_POST["Passwordwiederholen"])) {
    $PasswordErr .= "<br>Password ist pflicht";
  }
  else {
    $Passwordwiederholen = $_POST["Passwordwiederholen"];
  }
  if ($neuesPassword != $Passwordwiederholen) {
    $PasswordErr .= "<br>Password nicht identisch";
  }

  if ($PasswordErr == "") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Schnaeppchen";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    // altes Passwort überprüfen
    $sql = "SELECT `Password` FROM `anmeldungen` WHERE `Anmeldename` = '$Anmeldename';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if (password_verify($Password, $row["Password"])) {

      $passwordhash = password_hash($neuesPassword, PASSWORD_DEFAULT);
      $sql = "UPDATE `anmeldungen` SET `Password`= '$passwordhash' where `Anmeldename` = '$Anmeldename';";
      $conn->query($sql);
      $sqlMeldung = "Password aktualisiert";
    }
    else {
      $sqlMeldung = "Password NICHT aktualisiert";
    }
    $conn->close();
  }  
}
?>
<!DOCTYPE HTML>
<html>
  <body>
<?php
if ($sqlMeldung != "Password aktualisiert") {
  echo $sqlMeldung, "<br>";
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
echo <<<EOF
  <h2>Password Änderung für $Anmeldename</h2>
  <span class="error">$PasswordErr</span>
<form method="post" action="$self">
    alltes Password: <input required type="Password" name="altesPassword" value="">
    <br><br><br>
    neues Password: <input required type="Password" name="neuesPassword" value="">
    <br><br><br>
    Password wiederholen: <input required type="Password" name="Passwordwiederholen" value="">
    <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button2"value="OK" >Ok</button>
    <br><br><br>
</form>
EOF;
}
else {
  echo $sqlMeldung,"<br>",
  '<a href="http://localhost/schnaeppchen/schnaeppchen.php">Schnäppchen</a>';
}
?>
</body>
</html>