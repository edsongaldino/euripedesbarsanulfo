<?php
function redireciona($url,$target = "_self") {
	$url = addslashes(trim($url));
	$target = addslashes(trim($target));
	
	echo '<script type="text/javascript">';
	echo "window.open('".$url."','".$target."');";
	echo "</script>";
	
	die("Aguarde");
}
?>