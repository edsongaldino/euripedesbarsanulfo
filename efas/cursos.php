<?php include "sistema_mod_include.php";?>
<?php
$publico = $_GET["publico"];

$conexao = conecta_mysql();
// consulta cursos 0 à 11 anos
$sql_consulta_cursos_criancas = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
									JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
									JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
									JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
									JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
								 WHERE (curso.codigo_tema_curso = '1' OR curso.codigo_tema_curso = '2') AND evento_curso.codigo_evento = '11' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_cursos_criancas = mysqli_query($conexao, $sql_consulta_cursos_criancas) or mascara_erro_mysql($sql_consulta_cursos_criancas);

// consulta tema específico 12 e 13 anos
$sql_consulta_tema_especifico_jovem = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '3' AND evento_curso.codigo_evento = '11' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_especifico_jovem = mysqli_query($conexao, $sql_consulta_tema_especifico_jovem) or mascara_erro_mysql($sql_consulta_tema_especifico_jovem);

// consulta tema atual 12 e 13 anos
$sql_consulta_tema_atual_jovem = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '5' AND evento_curso.codigo_evento = '11' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_atual_jovem = mysqli_query($conexao, $sql_consulta_tema_atual_jovem) or mascara_erro_mysql($sql_consulta_tema_atual_jovem);


// consulta tema específico 12 e 13 anos e adulto
$sql_consulta_tema_especifico_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '4' AND evento_curso.codigo_evento = '11' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_especifico_adulto = mysqli_query($conexao, $sql_consulta_tema_especifico_adulto) or mascara_erro_mysql($sql_consulta_tema_especifico_adulto);

// consulta tema atual 12 e 13 anos e adulto
$sql_consulta_tema_atual_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '6' AND evento_curso.codigo_evento = '11' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_atual_adulto = mysqli_query($conexao, $sql_consulta_tema_atual_adulto) or mascara_erro_mysql($sql_consulta_tema_atual_adulto);

?>
<!DOCTYPE html>
<html>
<head>
    <?php include "site_mod_head.php";?>
</head>
<body>
<!--header-->
<div class="top"></div>
<div class="header">
	<div class="container">
			<div class="header-top">
				<?php include "site_mod_topo.php";?>
			</div>
			<div class="banner-main">
                <?php include "site_mod_banner.php";?>
	        </div>
    </div>
</div>
<!--//header-->
<!--content-->

    <div class="container cursos">
        
            <h3 class="titulo">Cursos - EFAS 2024 | Várzea Grande - MT</h3>

            <div class="row">

                <?php if($publico == "criancas"){?>
                <div class="col-md-12">
                    <div class="grid-categories">
                        <div class="cate-top">
                        <h4>Temas Específicos para crianças - (0 à 11 anos)</h4>
                        <ul class="popular">
                            <?php while($resultado_consulta_cursos_criancas = mysqli_fetch_assoc($query_consulta_cursos_criancas)) {?>
                            <li><a href="#"><i class="glyphicon glyphicon-chevron-right"> </i><?php echo $resultado_consulta_cursos_criancas["referencia"];?> - <?php echo utf8_encode($resultado_consulta_cursos_criancas["nome_curso"]);?> </a></li>
                            <?php }?>
                        </ul>
                        </div>              
                    </div>
                </div>
                <?php }?>

                <?php if($publico == "jovens"){?>
                <div class="col-md-12">
                    <div class="grid-categories">
                        <div class="cate-top">
                        <h4 class="jovem">Temas Específicos para jovens (12 e 13 anos)</h4>
                        <ul class="popular">
                            <?php while($resultado_consulta_cursos_especifico_jovem = mysqli_fetch_assoc($query_consulta_tema_especifico_jovem)) {?>
                            <li><a href="#"><i class="glyphicon glyphicon-chevron-right"> </i><?php echo $resultado_consulta_cursos_especifico_jovem["referencia"];?> - <?php echo utf8_encode($resultado_consulta_cursos_especifico_jovem["nome_curso"]);?> </a></li>
                            <?php }?>
                        </ul>
                        </div>              
                    </div>
                </div>

                <div class="col-md-12">
                <div class="grid-categories">
                    <div class="cate-top">
                        <h4 class="jovem">Temas Atuais para jovens (12 e 13 anos)</h4>
                        <ul class="popular">
                            <?php while($resultado_consulta_cursos_atual_jovem = mysqli_fetch_assoc($query_consulta_tema_atual_jovem)) {?>
                            <li><a href="#"><i class="glyphicon glyphicon-chevron-right"> </i><?php echo $resultado_consulta_cursos_atual_jovem["referencia"];?> - <?php echo utf8_encode($resultado_consulta_cursos_atual_jovem["nome_curso"]);?> </a></li>
                            <?php }?>
                        </ul>
                    </div>              
                </div>
                </div>


                <?php }?>

                <?php if($publico == "adultos"){?>

                <div class="col-md-12">
                    <div class="grid-categories">
                        <div class="cate-top">
                            <h4 class="adulto">Temas Específicos Adultos (Jovens acima de 14 anos)</h4>
                            <ul class="popular">
                                <?php while($resultado_consulta_especifico_adulto = mysqli_fetch_assoc($query_consulta_tema_especifico_adulto)) {?>
                                <li><a href="#"><i class="glyphicon glyphicon-chevron-right"> </i><?php echo $resultado_consulta_especifico_adulto["referencia"];?> - <?php echo utf8_encode($resultado_consulta_especifico_adulto["nome_curso"]);?> <b>(<?php echo utf8_encode($resultado_consulta_especifico_adulto["nome_instituto"]);?>)</b> </a></li>
                                <?php }?>
                            </ul>
                        </div>              
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="grid-categories">
                        <div class="cate-top">
                        <h4 class="adulto">Temas Atuais Adultos (Jovens acima de 14 anos)</h4>
                        <ul class="popular">
                            <?php while($resultado_consulta_atual_adulto = mysqli_fetch_assoc($query_consulta_tema_atual_adulto)) {?>
                            <li><a href="#"><i class="glyphicon glyphicon-chevron-right"> </i><?php echo $resultado_consulta_atual_adulto["referencia"];?> - <?php echo utf8_encode($resultado_consulta_atual_adulto["nome_curso"]);?> <b>(<?php echo utf8_encode($resultado_consulta_atual_adulto["nome_instituto"]);?>)</b> </a></li>
                            <?php }?>
                        </ul>
                        </div>              
                    </div>
                </div>  
                <?php }?>
                
            <div class="clearfix"> </div>
        </div>
</div>
<!--//content-->
<!--footer-->
<div class="footer">
	<?php include "site_mod_rodape.php";?>
</div>
<!--//footer-->
</body>
</html>