<?php
require_once "zugangDB.php";
include_once "eingabe.php";

  if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["kommentareID"])) {
  $BieteSuche = $Unterrubriken = $Anzeigetext = $Veröffentlichungsdatum= "";
  
  $kid = $_GET["KundenID"];
  $sql = "SELECT * FROM `anzeigen` WHERE KundenID = $kid";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  
  $Bieteuche = $row["BieteSuche"];
  $Unterrubriken = $row["unterrubrikID"];
  $Anzeigetext = $row["Anzeigetext"];
  $Veröffentlichungsdatum = $row["Veröffentlichungsdatum"];

  $conn->close();

  echo "<h2>Übersischt</h2>",
     "$BieteSuche $Unterrubriken $Anzeigetext $Veröffentlichungsdatum", "<br>";
  }
else {
  echo "Fehlerhafte Eingabe";
}
