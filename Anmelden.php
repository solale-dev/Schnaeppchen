<?php

function cmbFeld($name, $liste, $wert="", $titel="", $klasse="", $weitere=""){
  $cmbfeld = "<select name='$name'";
  if (!empty($titel))
    $cmbfeld .= " title='$titel'";
  if (!empty($klasse))
    $cmbfeld .= " class='$klasse'";
  $cmbfeld .= " $weitere>\n";
  
  foreach($liste as $element) {
    $cmbfeld .= "  <option";
    if ($element == $wert)
      $cmbfeld .= " selected='selected'";
    $cmbfeld .= ">$element</option>\n";
  }
  $cmbfeld .= "</select>\n";

  return $cmbfeld;
}

$sqlMeldung = "";
$AnzeigeErr = $AnzeigetextErr= "";
$Biete = $Suche = "";
$hauprubriken = array("Wählen Sie eine Hauptrubrik aus!", "$sql");
$Hauptrubriken = $hauprubriken[0];
$unterrubriken = array("");
$Unterrubriken = $unterrubriken[0];
$Abbrechen = $Ok = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["Biete"])) {
    $AnzeigeErr .= " Formular ist Leer";
  }
  else {
    $Biete = $_POST["Beite"];
  }

  if (empty($_POST["Suche"])) {
      $AnzeigeErr = " Formular ist Leer";
  }
  else {
    $Suche = $_POST["Suche"];
  } 
 
  if (!empty($_POST["Hauptrubriken"]) && in_array($_POST["Hauptrubriken"], $hauprubriken)) {
    $Hauptrubriken = $_POST["Hauptrubriken"];
  } else {
    $AnzeigeErr .= "Formular ist Leer";
  }
  if (!empty($_POST["Unterrubriken"]) && in_array($_POST["Unterrubriken"], $Unterrubriken)) {
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
      $AnzeigetextErr = "ungültiges Anzeigetext-format";
    }
  }
  if (($AnzeigeErr . $AnzeigetextErr) == "") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Schnaeppchen";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT `hauptrubrikenID`, `hauptrubrik` FROM `hauptrubriken`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<br> haptrubik: ".$row["hauptrubrikenID"];
      }
    }
  }
 }
 $conn->close();
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
  $cmbhaupt = cmbFeld("Hauptrubriken",$hauprubriken,$Hauptrubriken);
echo <<<EOF
  <h2>Anzeige</h2>
  <span class="error">$AnzeigeErr</span>
<form method="post" action="$self">
    Biete <input required type="radio" name="BieteSuche" value="Biete">
    Suche <input required type="radio" name="BieteSuche" value="Suche">
    <br><br><br>
    $cmbhaupt
    <br><br><br>
        Anzeigetext <textarea name="Anzeigetext" rows="5" cols="30"></textarea>
        <br><br><br>
    <button name="aktion" class="button button1" value="Abbrechen">Abbrechen</button>
    <button name="aktion" class="button button2"value="OK" >Ok</button>
    <br><br><br>
</form>
EOF;
}
else {
  echo $sqlMeldung,"<br>";
}
?>
</body>
</html>