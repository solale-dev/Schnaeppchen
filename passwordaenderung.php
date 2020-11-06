<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";

$Anmeldename = $Password = "";
$altesPassword = $neuesPassword = $Passwordwiederholen = "";
$Abbrechen = $Ok = "";

$Anmeldename = $_SESSION["Anmeldename"];

if ($_SERVER["REQUEST_METHOD"] == "POST"){

  if ($_POST["aktion"] == "OK") {
    if (empty($_POST["altesPassword"])) {
      $frmError .= "<br>Password ist pflicht";
    }
    else {
      $Password = $_POST["altesPassword"];
    }
    if (empty($_POST["neuesPassword"])) {
      $frmError .= "<br>Password ist pflicht";
    }
    else {
      $neuesPassword = $_POST["neuesPassword"];
    }
    if (empty($_POST["Passwordwiederholen"])) {
      $frmError .= "<br>Password ist pflicht";
    }
    else {
      $Passwordwiederholen = $_POST["Passwordwiederholen"];
    }
    if ($neuesPassword != $Passwordwiederholen) {
      $frmError .= "<br>Password nicht identisch";
    }

    if ($frmError = "") {
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
        $sqlError = "Password NICHT aktualisiert";
      }
    }
  }
  if ($sqlError == "" && ($_POST["aktion"] == "Abbrechen" || $_POST["aktion"] == "OK" )) {
    $_SESSION["nextFrm"] = "anmeldung.php";
    header("Location: /schnaeppchen/index.php");
    exit;
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
elseif ($frmError == "" && $_POST["aktion"] == "Abbrechen") {
  $_SESSION["nextFrm"] = "anmeldung.php";
  header("Location: /schnaeppchen/index.php");
  exit;
}
?>
</body>
</html>