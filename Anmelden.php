<?php

require_once "zugangDB.php";
function cmbFeld($name, $liste, $wert="", $titel="", $klasse="", $weitere=""){
  $cmbfeld = "<select name='$name' id='$name'";
  if (!empty($titel))
    $cmbfeld .= " title='$titel'";
  if (!empty($klasse))
    $cmbfeld .= " class='$klasse'";
  $cmbfeld .= " $weitere>\n";
  
  foreach($liste as $key => $element) {
    $cmbfeld .= "  <option value='$key'";
    if ($element == $wert)
      $cmbfeld .= " selected='selected'";
    $cmbfeld .= ">$element</option>\n";
  }
  $cmbfeld .= "</select>\n";

  return $cmbfeld;
}

$AnzeigeErr = "";
$sqlMeldung = "";
$sql = "";
$BieteSuche = $Anzeigetext = $Veröffentlichungsdatum = $Telefon = "";
$Abbrechen = $Ok = $Bestätigen = "";
$hauptrubriken = array("Wählen Sie eine Hauptrubrik aus!");
$sql = "SELECT `hauptrubrikenID`, `hauptrubrik` FROM `hauptrubriken`;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $hauptrubriken[$row["hauptrubrikenID"]] =  $row["hauptrubrik"]; //echo "<br> hauptrubik: ".$row["hauptrubrikenID"];
  }
}
$Hauptrubriken = $hauptrubriken[0];
$unterrubriken = array("Wählen Sie eine Unterrubrik aus!");
/*
$sql = "SELECT `unterrubrikenID`, `Unterrubrik` FROM `unterrubriken` where `hauptrubrikenID` = 0";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $unterrubriken[$row["unterrubrikenID"]] =  $row["Unterrubrik"]; //echo "<br> unterrubik: ".$row["hauptrubrikenID"];
  }
}
*/
$Unterrubriken = $unterrubriken[0];



if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
  if (empty($_POST["BieteSuche"])) {
    $AnzeigeErr .= " Formular ist Leer";
  }
  else {
    $BieteSuche = $_POST["BieteSuche"];
  } 
 
  if (!empty($_POST["Hauptrubriken"]) && in_array($_POST["Hauptrubriken"], $hauptrubriken)) {
    $Hauptrubriken = $_POST["Hauptrubriken"];
  } else {
    $AnzeigeErr .= "Formular ist Leer";
  }
  if (!empty($_POST["Unterrubriken"]) && in_array($_POST["Unterrubriken"], $unterrubriken)) {
    $Unterrubriken = $_POST["Unterrubriken"];
  } else {
    $AnzeigeErr .= "Formular ist Leer";
  }
  if (empty($_POST["Anzeigetext"])) {
    $AnzeigeErr .= "Formular ist Leer";
  }
  else {
    $Anzeigetext = test_input($_POST["Anzeigetext"]);
    if (!preg_match("/^[a-zA-Z-' .0-9]*$/",$Anzeigetext)) {
      $AnzeigeErr .= "Formular ist Leer";
    }
  }
  if (isset($_POST["Veröffentlichungsdatum"])) {
    $Veröffentlichungsdatum = $_POST["Veröffentlichungsdatum"];
  }
  if ($AnzeigeErr == "") {
    $sql = "INSERT INTO `anzeigen`(`BieteSuche`, `unterrubrikenID`, `Anzeigetext`, `veröffentlichungsdatum`, `KundenID`)";
    $sql .= " values('$BieteSuche', '$Unterrubriken', '$Anzeigetext', '$Veröffentlichungsdatum', 1);";

    if ($conn->query($sql) === TRUE) {
      //$last_id = $conn->insert_id;
      $sqlMeldung = "";
    } else {
      $sqlMeldung = "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}
?>

<!DOCTYPE HTML>  
<html>
<head>
<style>
input, select, textarea {
  border: 1px solid darkblue;
  box-sizing: border-box;
  font-size: 14px;
  font-family: 'serif';
  width: 300px;
  padding: 6px;
}
textarea {
  height: 250px;
}
input[type=text]:focus {background-color: lightblue;}
.error {color: red;}
button {
  height: 40px;
  background: green;
  color: white;
  border: 10px solid darkgreen;
  font-size: 14px;
  font-family: 'serif';
}
button:hover {
  border: 12px solid black;
}
</style>
</head>
<body>

<?php
if ($sqlMeldung != "Anzeige speichert") {
  echo $sqlMeldung, "<br>";
  $self = htmlspecialchars($_SERVER["PHP_SELF"]);
  $cmbhaupt = cmbFeld("Hauptrubriken",$hauptrubriken,$Hauptrubriken);
  $cmbUnter = cmbFeld("Unterrubriken",$unterrubriken,$Unterrubriken);
echo <<<EOF
  <h2>Anzeige</h2>
<form method="post" action="$self">
    Biete <input required type="radio" name="BieteSuche" value="Biete">
    Suche <input required type="radio" name="BieteSuche" value="Suche">
    <br><br><br>
    $cmbhaupt
    $cmbUnter
    <br><br><br>
    Veröffentlichungsdatum <input type="date" name="Veröffentlichungsdatum" value="">
    <br><br><br>
        Anzeigetext <textarea name="Anzeigetext" rows="5" cols="30"></textarea>
        <br><br><br>
        Telefon <input type="tel" name="Telefon" value="">
        <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button1" value="Speichern">Speichern</button>
    
    <br><br><br>
</form>
EOF;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo "<h2>Übersicht:</h2>",
   "$BieteSuche -> $Hauptrubriken -> $Unterrubriken <br>",
   "$Veröffentlichungsdatum<br>",
   "$Telefon<br>",
   "$Anzeigetext<br>";
}
$conn->close();
?>
    <!--<br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button2"value="Bestätigen">Bestätigen</button>-->
    <script src="ajax.js"></script>
</body>
</html>