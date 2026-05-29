<?php
function redireciona($url,$target = "_self") {
	echo '<script type="text/javascript">';
	echo "window.open('".$url."','".$target."');";
	echo "</script>";
	
	die("Aguarde...");
}
?>