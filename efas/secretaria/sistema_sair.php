<?php include("sistema_mod_include.php"); ?>
<?php
session_destroy();

redireciona("index.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Você saiu do sistema! Para acessar novamente faça seu login."));

?>