<?php
$AnmeldenameErr = $PasswordErr = "";
$Anmeldename = $Password = "";
$Registrierung = $Anmelden = "";
$sqlMeldung = "";
$PasswordÄnderung = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["aktion"] == "Registrierung" ) {
    // Registrieungsanweisungen
    include "registrierung.php";
    exit;
  }
  elseif ($_POST["aktion"] == "PasswordÄnderung" ) {
    // Änderungsanweisungen
    header("Location: passwordaenderung.php");
  }
  elseif ($_POST["aktion"] == "Anzeige") {
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
      $sql = "Select * From anmeldungen";
      $sql .= " where Anmeldename = '$Anmeldename';";

      $result = $conn->query($sql);

      //if (password_verify($Password, $sqlpwd))
      //if ($Password === $sqlpwd)
      $conn->close();
      include "anzeige.php";
      exit;
    }
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

