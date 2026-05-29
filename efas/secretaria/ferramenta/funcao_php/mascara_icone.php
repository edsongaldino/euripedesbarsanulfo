<?php
function mascara_icone($sigla) {
	switch($sigla) {
		case "A":
			return '<img src="'.SUBPASTA_RAIZ.'/imagem/icone/situacao_ativo.png" alt="Ativo" title="Ativo" width="60" height="20" border="0" />';
			break;
		case "I":
			return '<img src="'.SUBPASTA_RAIZ.'/imagem/icone/situacao_inativo.png" alt="Inativo" title="Inativo" width="60" height="20" border="0" />';
			break;
		case "N":
			return '<img src="'.SUBPASTA_RAIZ.'/imagem/icone/situacao_novo.png" alt="Novo" title="Novo" width="60" height="20" border="0" />';
			break;
		case "R":
			return '<img src="'.SUBPASTA_RAIZ.'/imagem/icone/situacao_respondido.png" alt="Respondido" title="Respondido" width="60" height="20" border="0" />';
			break;
		default:
			return "-";
			break;
	}
}
?>