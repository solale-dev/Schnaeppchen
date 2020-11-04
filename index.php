<?php
session_start();
if (empty($_SESSION["nextFrm"])) {
  $_SESSION["nextFrm"] = "anmeldung.php";
}
$nextFrm = $_SESSION["nextFrm"];

include "header.php";
include $nextFrm;
include "footer.php";