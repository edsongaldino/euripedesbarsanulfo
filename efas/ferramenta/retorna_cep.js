function RetornaEndereco() {
	// Se o campo CEP não estiver vazio
	if($.trim($("#cep_endereco").val()) != ""){
	/*
	Para conectar no serviço e executar o json, precisamos usar a função
	getScript do jQuery, o getScript e o dataType:"jsonp" conseguem fazer o cross-domain, os outros
	dataTypes não possibilitam esta interação entre domínios diferentes
	Estou chamando a url do serviço passando o parâmetro "formato=javascript" e o CEP digitado no formulário
	http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val()
	*/
	$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_endereco").val(), function(){
	// o getScript dá um eval no script, então é só ler!
	//Se o resultado for igual a 1
					
	if (resultadoCEP["tipo_logradouro"] != '') {
		if (resultadoCEP["resultado"]) {
		// troca o valor dos elementos
			$("#logradouro_endereco").val(unescape(resultadoCEP["tipo_logradouro"]) + " " + unescape(resultadoCEP["logradouro"]));
			$("#bairro_endereco").val(unescape(resultadoCEP["bairro"]));
			$("#cidade_endereco").val(unescape(resultadoCEP["cidade"]));
			$("#estado_endereco").val(unescape(resultadoCEP["uf"]));
			$("#numero").focus();
			}
		}		
	});
	}
}