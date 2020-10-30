<!DOCTYPE HTML>  
<html>
<head>
<style>
input{
  border: 1px solid darkblue;
  box-sizing: border-box;
  font-size: 14px;
  font-family: 'serif';
  width: 300px;
  padding: 6px;
}
input[type=text]:focus {background-color: lightblue;}
input[type=password]:focus {background-color: lightblue;}
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
.button1 {background-color: lightgreen;}
.button2 {background-color: green;}
.button3 {background-color: yellow;}
</style>
</head>
<body>
<h1>Anmeldung</h1>
<p><span class="error">* Pflichtfeld</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
Anmeldename: <input required type="text" name="Anmeldename" value="">
<span class="error">* <?php echo $AnmeldenameErr;?></span>
<br><br>
Password: <input type="password" name="Password" value="">
<br><br><br>
<button name="aktion" class="button button1" value="Registrierung">Registrierung</button>
<button name="aktion" class="button button2">Anmelden</button>
<br><br><br>
<button name="aktion" class="button button3" value="PasswordÄnderung">Password Änderung</button>
</form>
<?php
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo $sqlMeldung, "<br>";
 }
  ?>
</body>
</html>