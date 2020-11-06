<?php
require_once "zugangDB.php";
include_once "eingabe.php";

  if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["anzeigeID"])) {
  $BieteSuche = $Unterrubriken = $Anzeigetext = $Veröffentlichungsdatum = $Telefon = "";
  
  $azid = $_GET["anzeigeID"];
  $sql = "SELECT `BieteSuche`, `unterrubrikenID`,`Anzeigetext`, `veröffentlichungsdatum`,`Telefon`, Preis FROM `anzeigen`WHERE `anzeigenID` = $azid";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  
  $BieteSuche = $row["BieteSuche"];
  $Unterrubriken = $row["unterrubrikenID"];
  $Anzeigetext = $row["Anzeigetext"];
  $Veröffentlichungsdatum = $row["veröffentlichungsdatum"];
  $Telefon = $row["Telefon"];
  $Preis = $row["Preis"];

  echo "<h2>Übersicht</h2>",
     "$BieteSuche<br>",
     "$Unterrubriken <br>",
     "$Anzeigetext <br>", 
     "$Veröffentlichungsdatum <br>",
     "$Telefon <br>",
     "$Preis <br>";
  }
else {
  $_SESSION["nextFrm"] = "anzeige.php";
  header("Location: /schnaeppchen/index.php");
  exit;
}
