<?php
session_start();
if (empty($_SESSION["nextFrm"])) {
  $_SESSION["nextFrm"] = "anmeldung.php";
}
$nextFrm = $_SESSION["nextFrm"];
//$letztFrm = $_SESSION["letztFrm"];
include "header.php";
include $nextFrm;
//include $letztFrm;
include "footer.php";