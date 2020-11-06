<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";
$Anmeldename = $Password = "";
$Registrierung = $Anmelden = $PasswordÄnderung = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //Eingabe-Check
  if (empty($_POST["Anmeldename"])) {
    $frmError .= "<br>Anmeldename ist Pflicht";
  }
  else {
    $Anmeldename = test_input($_POST["Anmeldename"]);
    if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9@._]{4,255}$/",$Anmeldename)) {
      $frmError .= "<br>nur Buchstaben erlaubt";
    }
  }
  $_SESSION["Anmeldename"] = $Anmeldename;
  //Verarbeitung
  if (empty($frmError)) {
    if ($_POST["aktion"] == "Registrierung" ) {
      // Registrieungsanweisungen
      $_SESSION["nextFrm"] = "Registrierung.php";
    }
    elseif ($_POST["aktion"] == "PasswordÄnderung" && empty($frmError)) {
      // Änderungsanweisungen
      $_SESSION["nextFrm"] = "passwortaenderung.php";
    }
    elseif ($_POST["aktion"] == "Anmelden") {
      if (empty($_POST["Password"])) {
        $frmError .= "<br>Passwort darf nicht leer sein";
      }
      else {
        $Password = test_input($_POST["Password"]);
        if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9@._]{4,255}$/",$Password)) {
          $frmError .= "<br>Password nicht gültig";
        }
      }
      if (empty($frmError)) {
        // Verarbeitung Anmeldecheck
        $sql = "Select * From anmeldungen";
        $sql .= " where Anmeldename = '$Anmeldename';";
  
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if (!password_verify($Password, $row["Password"])) {
          $sqlError .= "<br>Anmeldename oder Passwort falsch";
        }
        else {
          //unset($_POST);
          $_SESSION["nextFrm"] = "anzeige.php";
          header("Location: /schnaeppchen/index.php");
          exit;
        }
      }
    }
  }  
}
if (!(empty($frmError) && empty($sqlError) && $_SERVER["REQUEST_METHOD"] == "POST")) {
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
  echo <<<EOF
  <h1>Anmeldung</h1>
  <p><span class="error">* Pflichtfeld</span></p>
  <form method="post" action="$self">
  <span class="error">$frmError</span>
  <span class="error">$sqlError</span>
  <br><br><br>
  Anmeldename: <input required type="text" name="Anmeldename" value="$Anmeldename">
  Password: <input type="password" name="Password" value="">
  <br><br><br>
  <button name="aktion" class="button button1" value="Registrierung">Registrierung</button>
  <button name="aktion" class="button button2" value="Anmelden">Anmelden</button>
  <br><br><br>
  <button name="aktion" class="button button3" value="PasswordÄnderung">Password Änderung</button>
  </form>

  EOF;
}