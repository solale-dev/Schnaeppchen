<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";

$BieteSuche = $Anzeigetext = $Veröffentlichungsdatum = $Telefon = "";
$Abbrechen = $Ok = $Bestätigen = "";
$hauptrubriken = array("Wählen Sie eine Hauptrubrik aus!");
$sql = "SELECT `hauptrubrikenID`, `hauptrubrik` FROM `hauptrubriken`;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $hauptrubriken[$row["hauptrubrikenID"]] =  $row["hauptrubrik"];
  }
}
$Hauptrubriken = $hauptrubriken[0];
$unterrubriken = array("Wählen Sie eine Unterrubrik aus!");
$Unterrubriken = $unterrubriken[0];

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
  if (empty($_POST["BieteSuche"])) {
    $frmError .= "<br>BieteSuche ist Pflicht";
  }
  else {
    $BieteSuche = $_POST["BieteSuche"];
  } 
 
  if (!empty($_POST["Hauptrubriken"]) && array_key_exists($_POST["Hauptrubriken"], $hauptrubriken)) {
    $Hauptrubriken = $_POST["Hauptrubriken"];
  } else {
    $frmError .= "<br>Hauptrubriken hat nicht ausgewählt!";
  }
  if (!empty($_POST["Unterrubriken"]) /* Check unterrubrikenid zwischen 1 und 105 */ ) {
    $Unterrubriken = $_POST["Unterrubriken"];
  } else {
   $frmError .= "Unterrubriken hat nicht ausgewählt!";
  }
  if (empty($_POST["Anzeigetext"])) {
    $frmError .= "Anzeigetext ist Leer";
  }
  else {
    $Anzeigetext = test_input($_POST["Anzeigetext"]);
    if (!preg_match("/^[a-zA-Z-' .0-9]*$/",$Anzeigetext)) {
      $frmError .= "Anzeigetext passt nicht in dieses Format";
    }
  }
  if (isset($_POST["Veröffentlichungsdatum"])) {
    $Veröffentlichungsdatum = $_POST["Veröffentlichungsdatum"];
  }
  if (empty($_POST["Telefon"])) {
    $frmError .= "Telefon ist Leer";
  }
  else {
    $Telefon = test_input($_POST["Telefon"]);
    if (!preg_match("/^[0-9]*$/",$Telefon)) {
      $frmError .= "Telefon passt nicht in dieses Format";
    }
  }

  if ($frmError == "" && $_POST["aktion"] == "Speichern") {
    $Zeile = 30;
    $prozeile = 1;
    $Preis = (int)(strlen($Anzeigetext)/$zeile) + 1 * $prozeile;
    $sql = "INSERT INTO `anzeigen`(`BieteSuche`, `unterrubrikenID`, `Anzeigetext`, `veröffentlichungsdatum`, `Telefon`, `KundenID`, `Preis`)";
    $sql .= " values('$BieteSuche', '$Unterrubriken', '$Anzeigetext', '$Veröffentlichungsdatum', '$Telefon', 1, '$Preis');";

    if ($conn->query($sql) === TRUE) {
      //$last_id = $conn->insert_id;
      $sqlError = "";
      $_SESSION["nextFrm"] = "uebersicht.php";
      header("Location: /schnaeppchen/index.php?anzeigeID=" . $conn->insert_id);
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
  $cmbhaupt = cmbFeld("Hauptrubriken",$hauptrubriken,$Hauptrubriken);
  $cmbUnter = cmbFeld("Unterrubriken",$unterrubriken,$Unterrubriken);
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
  echo <<<EOF
  <h2>Anzeige</h2>
 
  <form method="post" action="$self">
    <span class="error">$frmError</span>
    <span class="error">$sqlError</span>
    <br>
    Biete <input required type="radio" name="BieteSuche" value="Biete">
    Suche <input required type="radio" name="BieteSuche" value="Suche">
    <br><br><br>
    $cmbhaupt
    $cmbUnter
    <br><br><br>
    <p>Preis pro 30 Zeilen 1€</p>
    Anzeigetext <textarea name="Anzeigetext" rows="5" cols="30"></textarea>
    <br><br<<br>
    Veröffentlichungsdatum <input type="date" name="Veröffentlichungsdatum" value="">
    <br><br><br>
    Telefon <input type="tel" name="Telefon" value="">
    <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button1" value="Speichern">Speichern</button>
  </form>
EOF;
}
