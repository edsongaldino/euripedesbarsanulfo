<?php include("../sistema_mod_include.php"); ?>
<link href="/sistema/css/style.css" rel="stylesheet">
<?php
$conexao = conecta_mysql();
$mensagem_ok = null;
$mensagem_erro = null;

$codigo_curso = campo_form_decodifica($_GET["codigo_curso"]);

// consulta usuários cadastrados no sistema
$sql_consulta_cursos = "SELECT curso.codigo_curso, curso.nome_curso, curso.sinopse_curso FROM curso WHERE curso.codigo_curso = '".$codigo_curso."'";
$query_consulta_cursos = mysqli_query($conexao,$sql_consulta_cursos) or mascara_erro_mysql($sql_consulta_cursos);
$resultado_consulta_curso = mysqli_fetch_assoc($query_consulta_cursos);
?>
<style>

/* open-sans-regular - latin */
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: url('font/open-sans-v15-latin-regular.eot'); /* IE9 Compat Modes */
  src: local('Open Sans Regular'), local('OpenSans-Regular'),
       url('font/open-sans-v15-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('font/open-sans-v15-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
       url('font/open-sans-v15-latin-regular.woff') format('woff'), /* Modern Browsers */
       url('font/open-sans-v15-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
       url('font/open-sans-v15-latin-regular.svg#OpenSans') format('svg'); /* Legacy iOS */
}

.mensagem-transferir-atendimento {
    color: #F09A1D;
    font-size: 16px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    font-family: 'Open Sans';
}

.campo-curso {
    width: 400px;
    height: 50px;
    font-size: 16px;
    color: #0085B2;
    margin: 10px auto 20px 100px;
}

.sinopse-curso {
    width: 100%;
    height: 100px;
    font-size: 16px;
    color: #0085B2;
    font-family: 'Open Sans';
    margin-bottom: 20px;
}

.btn-cancelar {
    width: 189px !important;
    height: 52px !important;
    background: url(img/btn-cancelar.png);
    border: 0;
    float: left;
    margin-left: 50px !important;
}
.btn-cancelar:hover {
    cursor:pointer;
}

.btn-transferir {
    width: 189px !important;
    height: 52px !important;
    background: url(img/btn-transferir.png);
    border: 0;
    float: right;
    margin-right: 50px !important;
}
.btn-transferir:hover {
    cursor:pointer;
}
.select-curso {
    width: 100%;
    height: 50px;
    float: left;
    margin-bottom: 30px;
}

.mensagem-sucesso {
    color: #00B259;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    margin-top:80px;
    font-family: 'Open Sans';
}

.mensagem-erro {
    color: #F09A1D;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    margin-top:80px;
    font-family: 'Open Sans';
}


.nome-curso {
    color: #007979;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    font-family: 'Open Sans';
}

.sinopse-curso {
    color: #00B259;
    font-size: 20px;
    text-align: center;
    margin-bottom: 20px;
    font-family: 'Open Sans';
}


</style>

<div class="nome-curso"><?php echo utf8_encode($resultado_consulta_curso["nome_curso"]);?></div>
<div class="sinopse-curso"><?php if($resultado_consulta_curso["sinopse_curso"]): echo $resultado_consulta_curso["sinopse_curso"]; else: echo ":( A sinopse deste curso ainda não foi cadastrada!"; endif;?></div>

<?php fecha_mysql($conexao); ?>