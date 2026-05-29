<?php
function protege($valor,$int = false) {
	if($int) {
		return (int) addslashes(trim($valor));
	} else {
		return addslashes(trim($valor));
	}
}
?>