<html>
<head>
<script>
function showHint() 
{
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", "table_gen.php", false);
  xmlhttp.send();
  document.getElementById("table").innerHTML = xmlhttp.responseText;
  if (xmlhttp.responseText.indexOf('table') == -1)
    clearInterval(timerId);
  
}
</script>
</head>
<body>
<p><span id="table"></span></p>

</body>
<script>
var timerId = setInterval(showHint, 1000);
</script>
</html> 