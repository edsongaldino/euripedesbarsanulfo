// mostra a camada
function mostrar_camada(camada,display) {
	document.getElementById(camada).style.display = display;
}

// oculta a camada
function ocultar_camada(camada) {
	document.getElementById(camada).style.display = "none";
}

// confirma acao
function confirma_acao(msg,url){
	if(confirm(msg)) {
		window.open(url,"_parent");
	}
}

// bloqueia da tela anterior
function bloqueia_tela() {
	document.getElementById("tela_bloqueio").style.display = "block";
}

// desbloqueia da tela anterior
function desbloqueia_tela() {
	document.getElementById("tela_bloqueio").style.display = "none";
}

// ver o resumo do solicitacao
function ver_solicitacao(codigo_atendimento,caminho_raiz) {
	height_padrao = 60;
	
	box_solicitacao = document.getElementById("box_solicitacao_" + codigo_atendimento);
	bt_ver_solicitacao = document.getElementById("bt_ver_solicitacao_" + codigo_atendimento);
	img_ver_solicitacao = document.getElementById("img_ver_solicitacao_" + codigo_atendimento);
	
	if(box_solicitacao.style.height == (height_padrao + "px")) {
		opcao = "mais";
	} else {
		opcao = "menos";
	}
	
	if(opcao == "mais") {
		// ajusta altura, altera o label e altera o botao
		box_solicitacao.style.height = "auto";
		img_ver_solicitacao.src = caminho_raiz + "/imagem/geral/botao_ver_solicitacao_menos.png";
		bt_ver_solicitacao.title = "Ocultar a solicitação";
	} else {
		// ajusta altura, altera o label e altera o botao
		box_solicitacao.style.height = height_padrao + "px";
		img_ver_solicitacao.src = caminho_raiz + "/imagem/geral/botao_ver_solicitacao_mais.png";
		bt_ver_solicitacao.title = "Ver toda a solicitação";
	}
}

// carrega pagina dentro do modulo do atendimento
function modulo_atendimento(codigo_atendimento,opcao,pagina) {
	box_atendimento = document.getElementById("box_atendimento_" + codigo_atendimento);
	box_modulo = document.getElementById("box_modulo_" + codigo_atendimento);

	if(opcao == "mais") {
		// ajusta
		ajax_loadContent("box_modulo_" + codigo_atendimento,pagina);
		bloqueia_tela();
		box_modulo.style.display = "";
		box_atendimento.style.zIndex = 99999;
	} else if(opcao == "menos") {
		// ajusta
		ajax_loadContent("box_modulo_" + codigo_atendimento,pagina);
		desbloqueia_tela();
		box_modulo.style.display = "none";
		box_atendimento.style.zIndex = "";
	}
}

// mostra as interações
function interacao(codigo_atendimento,codigo,opcao,tipo) {
	if(tipo == "interagir") {
		pagina = "interagir_interacao.php?codigo=" + codigo;
	} else if(tipo == "ver") {
		pagina = "ver_interacao.php?codigo=" + codigo;
	} else {
		pagina = "vazio.html";
	}
	
	if(opcao == "+") {
		opcao = "mais";
	} else if(opcao == "-") {
		opcao = "menos";
		pagina = "vazio.html";
	}

	modulo_atendimento(codigo_atendimento,opcao,pagina);
}

// mostra o formulario do colaborador responsavel
function colaborador_responsavel(codigo_atendimento,codigo,opcao) {
	pagina = "editar_colaborador_responsavel.php?codigo=" + codigo;
	
	if(opcao == "+") {
		opcao = "mais";
	} else if(opcao == "-") {
		opcao = "menos";
		pagina = "vazio.html";
	}	

	modulo_atendimento(codigo_atendimento,opcao,pagina);
}

$(document).ready(function() {	
	$("input[@type=radio]").bind("click", function(){		
		if($("input[@type=radio]:checked").val() == "F") {
			$("#box_pf").attr({style: 'display:block;'});
			$("#box_pj").attr({style: 'display:none;'});
		} else {
			$("#box_pf").attr({style: 'display:none;'});
			$("#box_pj").attr({style: 'display:block;'});
		}
		
		if($("input[@type=radio]:checked").val() == "aceito_termos") {
			$("#botao_habilitado").attr({style: 'display:block;'});
			$("#botao_desabilitado").attr({style: 'display:none;'});
		}
		
	});	
	
	$("input[@type=checkbox]").bind("click", function(){		
		
		if($("input[@type=checkbox]:checked").val() == "sim") {
			$("#botao_habilitado").attr({style: 'display:block;'});
			$("#botao_desabilitado").attr({style: 'display:none;'});
		}
		else{
			$("#botao_habilitado").attr({style: 'display:none;'});
			$("#botao_desabilitado").attr({style: 'display:block;'});
		}
		
	});
	
});

function MostraFavorecidoPJ(){  
	var divR = document.getElementById("favorecido_conta");  
	var txtR = document.getElementById("razao_social");  
	divR.innerHTML = txtR.value;  
}
function MostraFavorecidoPF(){  
	var divR = document.getElementById("favorecido_conta");  
	var txtR = document.getElementById("nome_proprietario_pf");  
	divR.innerHTML = txtR.value;  
}




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