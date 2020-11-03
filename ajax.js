function loadUnterrubriken(id) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      /*alert(this.responseText);*/
      unterrubriken.innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "unterrubriken.php?id=" + id, true);
  xhttp.send();
}

hauptrubriken = document.getElementById("Hauptrubriken");
unterrubriken = document.getElementById("Unterrubriken");
unterrubriken.style = "display: none";
hauptrubriken.addEventListener("change", ()=> {unterrubriken.style = "display: inline-block;";loadUnterrubriken(hauptrubriken.value);});