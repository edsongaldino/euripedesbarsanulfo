<?php
session_start();
// Destroi sessões administrativas para voltar ao site
unset($_SESSION["key_acesso"]);
unset($_SESSION["email_usuario_acesso"]);
unset($_SESSION["nome_usuario_acesso"]);
header("Location: index.php");
exit;
?>
