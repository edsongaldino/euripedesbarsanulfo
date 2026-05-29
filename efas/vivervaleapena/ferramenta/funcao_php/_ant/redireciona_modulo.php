<?php
function redireciona_modulo($funcao_js,$me,$mm,$reload = false) {
	if($me) {
		echo '<script type="text/javascript">';
		echo 'window.opener.desbloqueia_tela();';
		echo 'self.close();';
		
		if($reload) {
			echo 'opener.location.reload();';
		}
		
		if($mm) {
			echo 'window.opener.alert("'.$mm.'");';
		}
		
		echo '</script>';
	} else {
		echo '<script type="text/javascript">';
		echo 'window.opener.desbloqueia_tela();';
		echo 'window.opener.'.$funcao_js.'();';
		echo 'self.close();';
		echo '</script>';
	}
	
	die("Aguarde...");
}
?>