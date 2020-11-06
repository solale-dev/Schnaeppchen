<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";

$Anrede = $Name = $Vorname = "";
$Telefon = $Email = $Anmeldename = "";
$Straße = $PLZ = $Ort = "";
$Kontonummer = $IBAN = "";
$Abbrechen = $Ok = "";
$bankauswahl = array("Wählen Sie ein Bank aus!");
$sql = "SELECT `bankenID`, `Bezeichnung`, `PLZ`  FROM `banken`;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $bankauswahl[$row["bankenID"]] =  $row["Bezeichnung"] . "-" . $row["PLZ"];
  }
}
$Bankauswahl = $bankauswahl[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check der Variable auf gültige Eingabe
  if (empty($_POST["Anrede"])) {
    $frmError = "Anrede ist Pflicht";
  }
  else {
    $Anrede= test_input($_POST["Anrede"]);
  }
  if (empty($_POST["Name"])) {
    $frmError = "Name ist Pflicht";
  }
  else {
    $Name = test_input($_POST["Name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$Name)) {
      $frmError = "nur Buchstaben erlaubt";
    }
  }
  if (empty($_POST["Vorname"])) {
    $frmError = "Vorname ist Pflicht";
  }
  else {
    $Vorname = test_input($_POST["Vorname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$Vorname)) {
      $frmError = "nur Buchstaben erlaubt";
    }
  }
  if (empty($_POST["Telefon"])) {
    $frmError = " Telefon ist Pflicht";
  } else {
    $Telefon = test_input($_POST["Telefon"]);
    if (!preg_match("/^[0-9]*$/",$Telefon)) {
      $frmErrot = "nur Zahlen erlaubt";
    }

  } 
  if (empty($_POST["Email"])) {
    $frmError = "Email is Pflicht";
  } else {
    $Email = test_input($_POST["Email"]);
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      $frmError = "ungültiges Email-format";
    }
  }
  if (empty($_POST["Straße"])) {
    $Straße = test_input($_POST["Straße"]);
  }
  else {
    $Straße = ($_POST["Straße"]);
  }

    if (!preg_match("/^[a-zA-Z-' ]*$/",$Straße)) {
      $frmError = "nur Buchstaben erlaubt";
    }
    if (empty($_POST["PLZ"])) {
      $PLZ = test_input($_POST["PLZ"]);
    }
    else {
      $PLZ = ($_POST["PLZ"]);
    }
    if (empty($_POST["Ort"])) {
      $Ort = test_input($_POST["Ort"]);
    }
    else {
      $Ort = ($_POST["Ort"]);
    }
    if (!empty($_POST["Bankauswahl"]) /* Check bankenID zwischen 1 und 15789 */ ) {
      $Bankauswahl = $_POST["Bankauswahl"];
      } else {
      $frmError .= "Bank hat nicht ausgewählt!";
      }
     
      if (empty($_POST["Kontonummer"])) {
        $frmError .= "Kontonummer ist Leer";
      }
      else {
        $Kontonummer = test_input($_POST["Kontonummer"]);
        if (!preg_match("/^[0-9]*$/",$Kontonummer)) {
          $frmError .= "Kontonummer passt nicht in dieses Format";
        }
      }
      if (empty($_POST["IBAN"])) {
        $frmError .= "IBAN ist Pflicht";
      }
      else {
        $IBAN = test_input($_POST["IBAN"]);
        if (!preg_match("/^[a-zA-Z-' .0-9]*$/",$IBAN)) {
          $frmError .= "IBAN passt nicht in dieses Format";
        }
      }
    // Wenn keine Fehler dann speichern in DB
    if ($frmError == "") {
      $sql = "INSERT INTO (Anrede, Name, Vorname, Telefon, Email, Straße, PLZ, Ort, Bankauswahl, Kontonummer)";
      $sql .= " values('$Anrede', '$Name', '$Vorname', '$Telefon', '$Email', '$Straße', '$PLZ', '$Ort', '$Bankauswahl', '$Kontonummer');";
      if ($conn->query($sql) === TRUE) {
        //$last_id = $conn->insert_id;
        $sqlError = "";
        $_SESSION["nextFrm"] = "anzeige.php";
        header("Location: /schnaeppchen/index.php");
        exit;
      } else {
        $sqlError = "Error: " . $sql . "<br>" . $conn->error;
      }
    }
    elseif ($frmError == "" && $_POST["aktion"] == "Abbrechen") {
      $_SESSION["nextFrm"] = "anmeldung.php";
      header("Location: /schnaeppchen/index.php");
      exit;
    }
    
} 

if (!(empty($frmError) && empty($sqlError) && $_SERVER["REQUEST_METHOD"] == "POST")) {
  $cmbBank = cmbFeld("Bankauswahl",$bankauswahl,$Bankauswahl);
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
  echo <<<EOF
  <h2>Registrierung</h2>
  <p><span class="error">* Pflichtfeld</span></p>
  <form method="post" action="$self">
    <span class="error">$frmError</span>
    <span class="error">$sqlError</span>
    <br> Anrede:
    Frau <input required type="radio" name="Anrede" value="Frau">
    Herr<input required type="radio" name="Anrede" value="Herr">
    Divers<input required type="radio" name="Anrede" value="Divers">
    <br><br><br><br>
    Vorname: <input type="text" name="Vorname" value="">
    <br><br>
    Name: <input required type="text" name="Name" value="">
    <br><br>
    Telefon: <input required type="tel" name="Telefon" value="">
    <br><br>
    Email: <input required typ="email" name="Email" value="">
    <br><br>
    Straße: <input typ="text" name="Straße" value="">
    <br><br>
    PLZ: <input typ="number" name="PLZ" value="">
    <br><br> 
    Ort:<input type="text" name="Ort" value="">
    <br><br><br>
    $cmbBank
    <br><br><br>
    Kontonummer <input type="text" name="Kontonummer" value="">
    <br><br><br>
    IBAN <input type="text" name="IBAN" value="">
    <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button1" value="Ok">Ok</button>
  </form>
EOF;
}
