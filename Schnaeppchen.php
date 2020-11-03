<?php
session_start();

require_once "zugangDB.php";

$AnmeldenameErr = "";
$Anmeldename = $Password = "";
$Registrierung = $Anmelden = "";
$sqlMeldung = "";
$PasswordÄnderung = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["Anmeldename"])) {
    $AnmeldenameErr = "Anmeldename ist Pflicht";
  }
  else {
    $Anmeldename = test_input($_POST["Anmeldename"]);
    echo "$Anmeldename";
    if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9@._]{4,255}$/",$Anmeldename)) {
      echo "Test: $Anmeldename";
      $AnmeldenameErr = "nur Buchstaben erlaubt";
    }
  }
  $_SESSION["Anmeldename"] = $Anmeldename;
  if ($_POST["aktion"] == "Registrierung" ) {
    // Registrieungsanweisungen
    header("Location: Registrierung.php");
  }
  elseif ($_POST["aktion"] == "PasswordÄnderung" && empty($AnmeldenameErr)) {
    // Änderungsanweisungen
    header("Location: passwordaenderung.php");
  }
  elseif ($_POST["aktion"] == "Anmelden") {
    if (empty($_POST["Password"])) {
      $Password = test_input($_POST["Password"]);
    }
    else {
      $Password = ($_POST["Password"]);
    }
  
    if (($AnmeldenameErr) == "") {
      $sql = "Select * From anmeldungen";
      $sql .= " where Anmeldename = '$Anmeldename';";

      $result = $conn->query($sql);

      //if (password_verify($Password, $sqlpwd))
      //if ($Password === $sqlpwd)
      include "Anmelden.php";
      exit;
    }
  }
  elseif ($_POST["aktion"] == "OK") {
    include "Anmelden.php";
  }
}
include "anmeldung.html";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

