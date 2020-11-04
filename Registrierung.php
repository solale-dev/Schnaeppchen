<?php
require_once "zugangDB.php";
include_once "eingabe.php";

$frmError = "";
$sqlError = "";

$Anrede = $Name = $Vorname = "";
$Telefon = $Email = $Anmeldename = "";
$Straße = $PLZ = $Ort = "";
$Registrierung = "";

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
    // Wenn keine Fehler dann speichern in DB
    if ($frmError == "") {
      $sql = "INSERT INTO (Anrede, Name, Vorname, Telefon, Email, Straße, PLZ, Ort)";
      $sql .= " values('$Anrede', '$Name', '$Vorname', '$Telefon', '$Email', '$Straße', '$PLZ', '$Ort');";
      if ($conn->query($sql) === TRUE) {
          
          $sqlError = "New record created successfully";
        } else {
          $sqlError = "Error: " . $sql . "<br>" . $conn->error;
        }
    
    }
} 
?>
<!DOCTYPE HTML>
<html>

<head>
  <style>
    input {
      border: 1px solid darkblue;
      box-sizing: border-box;
      font-size: 14px;
      font-family: 'serif';
      width: 300px;
      padding: 6px;
    }

    input[type=text]:focus {
      background-color: lightblue;
    }

    input[type=password]:focus {
      background-color: lightblue;
    }

    .error {
      color: red;
    }

    button {
      height: 40px;
      background: green;
      color: white;
      border: 10px solid darkgreen;
      font-size: 14px;
      font-family: 'serif';
    }

    button:hover {
      border: 2px solid black;
    }

    .button {
      border: none;
      color: black;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
    }

    .button1 {
      background-color: lightgreen;
    }

    .button2 {
      background-color: green;
    }

    .button3 {
      background-color: yellow;
    }
  </style>
</head>

<body> 
  <h2>Registrierung</h2>
  <p><span class="error">* Pflichtfeld</span></p>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 Anrede:
<input required type="radio" name="Anrede"<?php if (isset($Anrede) && $Anrede=="Frau") echo "checked";?> value="Frau">Frau
<input required type="radio" name="Anrede"<?php if (isset($Anrede) && $Anrede=="Herr") echo "checked";?> value="Herr">Herr
<input required type="radio" name="Anrede"<?php if (isset($Anrede) && $Anrede=="Divers") echo "checked";?> value="Divers">Divers 
<br><br><br><br>
Vorname: <input type="text" name="Vorname" value="<?php echo $Vorname;?>">
<br><br>
Name: <input required type="text" name="Name" value="<?php echo $Name;?>">
<span class="error">* <?php echo $NameErr;?></span>
<br><br>
Telefon: <input required type="tel" name="Telefon" value="<?php echo $Telefon;?>">
<span class="error">* <?php echo $TelefonErr;?></span>
<br><br>
Email: <input required typ="email" name="Email" value=<?php echo $Email;?>>
<span class="error">* <?php echo $EmailErr;?></span>
<br><br>
Straße: <input typ="text" name="Straße" value=<?php echo $Straße;?>>
<br><br>
PLZ: <input typ="number" name="PLZ" value=<?php echo $PLZ;?>> Ort:<input type="text" name="Ort" value=<?php echo $Ort;?>>
<br><br><br>
<button name="aktion" class="button button1" value="Registrierung">Registrierung</button>
</form>

</body>

</html>