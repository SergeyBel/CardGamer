<?php

$langs = array(
	'php' => array("sign" => "<?", 
					"name" => 'PHP', 
					"file" => "strategy.php", 
					'c' => "php.exe -l upload\\strategy.php",
					"g" => "php.exe main.php \"php.exe upload\\strategy.php\""),
	'c' => array("sign" => "#include", "name" => 'C++', "file" => "strategy.cpp", 'c' => "g++.exe -Wall upload\\strategy.cpp -o upload\\strategy.exe"),
	'py' => array("sign" => "print(", "name" => 'Python', "file" => "strategy.py", 'c' => "C:\\python\\python.exe -m py_compile upload\\strategy.py"),

	);

?>
<html>
<head>
<title>AI Cup</title>
</head>
<body>
<form method="post">
<?php

if(!empty($_POST['code'])) {
	$lang = false;
	foreach ($langs as $key => $l) {
		if(strpos($_POST['code'], $l['sign']) !== FALSE) {
			$lang = $l;
			break;
		}
	}

	if(!$lang)
		echo "<p>Language not detected!</p>";
	else 
		ProcessInput($lang);
}

function ProcessInput($l) {
	echo "<p>Language: $l[name]</p>";
	file_put_contents("upload\\$l[file]", $_POST['code']);
	echo "<pre>";
	passthru($l['c']." 2>&1", $ret);
	echo "</pre>";
	echo "Syntax check exit code: $ret";	
	if($ret)
		return;
	echo "<p>Game with simple:</p>";
	echo "<pre>";
	passthru($l['g']." 2>$1");
	echo "</pre>";
}

?>
<p><textarea name="code" style="width: 100%; height: 400px;    font-family: monospace;"><?=empty($_POST['code'])?"":htmlspecialchars($_POST['code']) ?></textarea></p>
<input type="submit" name="send" />
</form>
</body>
<script type="text/javascript">
var textareas = document.getElementsByTagName('textarea');
var count = textareas.length;
for(var i=0;i<count;i++){
    textareas[i].onkeydown = function(e){
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1; 
        }
    }
}
</script>
</html> 