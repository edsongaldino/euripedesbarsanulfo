<?php require_once("sistema_mod_include.php"); ?>
<?php
//require_once("ferramenta/phpqrcode/qrlib.php");
$codigo_participante = $_GET["codigo_participante"];
$conexao = conecta_mysql();
// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_dados_cracha = "
							SELECT 
							participante.nome_participante, participante.nome_participante_cracha, participante.data_nascimento_participante,
							dados_complementares.nome_responsavel, dados_complementares.telefone_responsavel, dados_complementares.observacoes_crianca,
							comissao_trabalho.nome_comissao_trabalho,
							inscricao_evento.tipo_inscricao
							FROM inscricao_evento
							JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
							LEFT JOIN comissao_trabalho_participante ON (participante.codigo_participante = comissao_trabalho_participante.codigo_participante)
							LEFT JOIN comissao_trabalho ON (comissao_trabalho_participante.codigo_comissao_trabalho = comissao_trabalho.codigo_comissao_trabalho)
							LEFT JOIN dados_complementares ON (participante.codigo_participante = dados_complementares.codigo_participante)
							WHERE inscricao_evento.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' AND participante.codigo_participante = '".$codigo_participante."'
							";
$query_consulta_dados_cracha = mysqli_query($conexao,$sql_consulta_dados_cracha) or mascara_erro_mysql($sql_consulta_dados_cracha);
$resultado_consulta_dados_cracha = mysqli_fetch_assoc($query_consulta_dados_cracha);

$idade = calcula_idade($resultado_consulta_dados_cracha["data_nascimento_participante"]);

//Seleciona cursos do participante
$sql_consulta_curso = "SELECT 
							participante_evento_curso.codigo_curso, curso.nome_curso, tema_curso.nome_tema_curso
							FROM participante_evento_curso
							JOIN curso ON (participante_evento_curso.codigo_curso = curso.codigo_curso)
							JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso) 
						WHERE participante_evento_curso.codigo_participante = '".$codigo_participante."' AND participante_evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_curso = mysqli_query($conexao,$sql_consulta_curso) or mascara_erro_mysql($sql_consulta_curso);	

if($resultado_consulta_dados_cracha["nome_comissao_trabalho"]){
	
	//$cracha.= '<div class="qrcode">'.QRcode::png($resultado_consulta_dados_cracha["nome_participante"]).'</div>';
	$cracha.= '<div class="dados_participante">';
	$cracha.= '<div class="nome_participante_cracha">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante_cracha"],'ISO-8859-1','UTF-8').'</div>';
	$cracha.= '<div class="nome_participante">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante"],'ISO-8859-1','UTF-8').'</div>';
	$cracha.= '<div class="trabalhador_comissao">'."TRABALHADOR".'</div>';
	$cracha.= '<div class="nome_comissao">'.$resultado_consulta_dados_cracha["nome_comissao_trabalho"].'</div>';
	$cracha.= '</div>';

}elseif($resultado_consulta_dados_cracha["tipo_inscricao"] == "C"){
		
	//$cracha.= '<div class="qrcode">'.QRcode::png($resultado_consulta_dados_cracha["nome_participante"]).'</div>';	
	$cracha.= '<div class="dados_participante">';
	$cracha.= '<div class="nome_participante_cracha">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante_cracha"],'ISO-8859-1','UTF-8').'</div>';
	$cracha.= '<div class="nome_participante">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante"],'ISO-8859-1','UTF-8').'</div>';
	$cracha.= '<div class="nome_responsavel">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_responsavel"],'ISO-8859-1','UTF-8')." - ".masc_tel(limpa_campo($resultado_consulta_dados_cracha["telefone_responsavel"])).'</div>';
	while($resultado_consulta_curso = mysqli_fetch_assoc($query_consulta_curso)){
	$cracha.= '<div class="nome_curso_crianca">'.$resultado_consulta_curso["nome_tema_curso"]." - <strong>".$resultado_consulta_curso["nome_curso"].'</strong></div>';
	}
	$cracha.= '</div>';

}else{
	
	//$cracha.= '<div class="qrcode">'.QRcode::png($resultado_consulta_dados_cracha["nome_participante"]).'</div>';	
	$cracha.= '<div class="dados_participante">';
	$cracha.= '<div class="nome_participante_cracha">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante_cracha"],'ISO-8859-1','UTF-8').'</div>';
	$cracha.= '<div class="nome_participante">'.mb_convert_encoding($resultado_consulta_dados_cracha["nome_participante"],'ISO-8859-1','UTF-8').'</div>';
	while($resultado_consulta_curso = mysqli_fetch_assoc($query_consulta_curso)){
	$cracha.= '<div class="nome_curso">'.$resultado_consulta_curso["nome_tema_curso"]." - <b>".$resultado_consulta_curso["nome_curso"].'</b></div>';
	}
	$cracha.= '</div>';
	
}
?>
<style>
.dados_cracha{width:10cm; height:3cm; float:left;margin-top: 6.5cm;margin-left: -0.5cm; }
.dados_cracha .qrcode{width:3cm; height:3cm; float:left; background-color:#333; margin:0px 0 0 0px;}
.dados_cracha .dados_participante{width:8cm; height:3cm; margin:auto; }
.dados_cracha .dados_participante .nome_participante_cracha{font-size:30px; text-transform:uppercase; line-height:30px; margin-top:10px; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .nome_participante{font-size:12px; line-height:20px; text-transform:uppercase; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .nome_curso{font-size:11px; text-align:center; line-height:20px; font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .nome_curso_crianca{font-size:13px; text-align:center; line-height:20px; font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .nome_responsavel{font-size:12px; line-height:20px; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .trabalhador_comissao{font-size:18px; line-height:20px; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.dados_cracha .dados_participante .nome_comissao{font-size:14px; line-height:20px; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
</style>
<div class="dados_cracha">
	<?php echo $cracha;?>
</div>