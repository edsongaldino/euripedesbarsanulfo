<?php
// *** You should define SERVER_ROOT manually when use Apache alias directive or IIS virtual directory ***
define('PAC_PATH', str_replace(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])),'', str_replace('\\', '/',dirname(__FILE__))));
define('DEBUG', false);

/******** DO NOT MODIFY ***********/
require_once('pac.php');
/**********************************/
?>
