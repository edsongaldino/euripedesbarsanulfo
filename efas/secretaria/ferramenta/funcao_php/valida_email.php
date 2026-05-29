<?php
function valida_email($email) {
	$email = strtolower(strtr($email, "АЮЦБИЙМСТУЗЭГаюцбиймстузэг ,", "aaaaeeiooouucAAAAEEIOOOUUC_."));
	$email = ereg_replace("[^a-z0-9_.@-]", "", $email);
	
	return $email;
}
?>