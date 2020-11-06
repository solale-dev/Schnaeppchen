<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";

$Kontonummer = $IBAN = "";
//$Abbrechen = $Ok = "";
$bankauswahl = array("Wählen Sie ein Bank aus!");
$sql = "SELECT `bankenID`, `Bezeichnung` FROM `banken`;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $bankauswahl[$row["bankenID"]] =  $row["Bezeichnung"];
  }
}
$Bankauswahl = $bankauswahl[0];

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
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
  if ($frmError == "" && $_POST["aktion"] == "Ok") {
    $sql = "INSERT INTO `banken`(`Bankauswahl`, `Kontonummer`, `IBAN`)";
    $sql .= " values('$Bankauswahl', '$Kontonummer', '$IBAN');";
    if ($conn->query($sql) === TRUE) {
      //$last_id = $conn->insert_id;
      $sqlError = "";
      $_SESSION["nextFrm"] = "anzeige.php";
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
  $cmbhaupt = cmbFeld("Bankauswahl",$bankauswahl,$Bankauswahl);
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
  echo <<<EOF
  <h2>Bankverbindung</h2>
 
  <form method="post" action="$self">
    <span class="error">$frmError</span>
    <span class="error">$sqlError</span>
    <br>
    $cmbBank
    <br><br><br>
    Kontonummer <input type="number" name="Kontonummer" value="">
    <br><br><br>
    IBAN <input type="text" name="IBAN" value="">
    <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button1" value="Ok">Ok</button>
  </form>
EOF;
}

