<?php include("sistema_mod_include.php"); ?>
<style>
.ficha_inscricao{width:19cm; height:12cm; float:left; margin:-25px 0 0 -25px;}
.ficha_inscricao .nome_participante{position:absolute; top:240px; left:310px; font-size:18px; line-height:20px; text-transform:uppercase; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.ficha_inscricao .codigo_participante{position:absolute; top:240px; left:120px; font-size:30px; line-height:20px; text-transform:uppercase; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
.ficha_inscricao .nome_participante_comprovante{position:absolute; top:465px; left:30px; font-size:18px; line-height:20px; text-transform:uppercase; text-align:center;  font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}


</style>
<div class="ficha_inscricao">
<img src="img/ficha_inscricao.jpg">
<?php
$codigo_participante = $_GET["codigo_participante"];
conecta_mysql();
// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_ficha_inscricao = "
							SELECT 
								inscricao_evento.codigo_participante, 
								participante.codigo_cidade, participante.nome_participante, participante.nome_participante_cracha, participante.centro_espirita_participante, participante.data_nascimento_participante, 
								telefone_participante.numero_telefone_participante,
								dados_complementares.nome_responsavel, dados_complementares.telefone_responsavel, dados_complementares.observacoes_crianca,
								comissao_trabalho.nome_comissao_trabalho, comissao_trabalho.codigo_comissao_trabalho,
								email_participante.descricao_email_participante
								FROM inscricao_evento 
								JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante) 
								LEFT JOIN telefone_participante ON (participante.codigo_participante = telefone_participante.codigo_participante)
								LEFT JOIN email_participante ON (participante.codigo_participante = email_participante.codigo_participante)
								LEFT JOIN dados_complementares ON (participante.codigo_participante = dados_complementares.codigo_participante)
								LEFT JOIN comissao_trabalho_participante ON (participante.codigo_participante = comissao_trabalho_participante.codigo_participante)
								LEFT JOIN comissao_trabalho ON (comissao_trabalho_participante.codigo_comissao_trabalho = comissao_trabalho.codigo_comissao_trabalho)
							WHERE inscricao_evento.codigo_evento = 1 AND participante.codigo_participante = '".$codigo_participante."'
							";
$query_consulta_ficha_inscricao = mysqli_query($conexao, $sql_consulta_ficha_inscricao) or mascara_erro_mysql($sql_consulta_ficha_inscricao);
$resultado_consulta_ficha_inscricao = mysqli_fetch_assoc($query_consulta_ficha_inscricao);

//Seleciona cursos do participante
$sql_consulta_curso = "SELECT 
						participante_evento_curso.codigo_curso, curso.nome_curso, tema_curso.nome_tema_curso
						FROM participante_evento_curso
						JOIN curso ON (participante_evento_curso.codigo_curso = curso.codigo_curso)
						JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso) 
					WHERE participante_evento_curso.codigo_participante = '".$codigo_participante."' ORDER BY curso.codigo_tema_curso DESC";
$query_consulta_curso = mysqli_query($conexao, $sql_consulta_curso) or mascara_erro_mysql($sql_consulta_curso);	


	

	echo '<div class="nome_participante">'.utf8_decode($resultado_consulta_ficha_inscricao["nome_participante"]).'</div>';
	echo '<div class="codigo_participante">'.utf8_decode($resultado_consulta_ficha_inscricao["codigo_participante"]).'</div>';
	
	echo '<div class="nome_participante_comprovante">'.utf8_decode($resultado_consulta_ficha_inscricao["nome_participante"]).'</div>';




?>
</div>