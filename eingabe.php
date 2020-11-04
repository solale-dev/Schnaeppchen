<?php
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
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
