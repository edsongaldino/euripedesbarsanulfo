<?php include("../sistema_mod_include.php"); ?>
<link href="/sistema/css/style.css" rel="stylesheet">
<?php
$conexao = conecta_mysql();
$mensagem_ok = null;
$mensagem_erro = null;

$codigo_curso = campo_form_decodifica($_GET["codigo_curso"]);

// consulta usuários cadastrados no sistema
$sql_consulta_cursos = "SELECT curso.codigo_curso, curso.nome_curso, evento_curso.quantidade_vagas FROM curso 
                            JOIN evento_curso ON evento_curso.codigo_curso = curso.codigo_curso 
                            WHERE curso.codigo_curso = '".$codigo_curso."' AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."'";
$query_consulta_cursos = mysqli_query($conexao,$sql_consulta_cursos) or mascara_erro_mysql($sql_consulta_cursos);
$resultado_consulta_curso = mysqli_fetch_assoc($query_consulta_cursos);

if(campo_form_decodifica($_POST["acao"]) == "atualizar-vagas"){

    $codigo_curso = $_POST["codigo_curso"];
    $vagas_curso = $_POST["vagas_curso"];

    // Atualiza curso
    $sql_atualiza_curso = "UPDATE evento_curso SET quantidade_vagas = '".$vagas_curso."' WHERE codigo_curso = '".$codigo_curso."' AND codigo_evento = '".$_SESSION["codigo_evento_acesso"]."'";
    $query_atualiza_curso = mysqli_query($conexao,$sql_atualiza_curso) or mascara_erro_mysql($sql_atualiza_curso);

    if($query_atualiza_curso){
        $mensagem_ok = ";) O curso foi atualizado!";
    }else{
        $mensagem_erro = ":( Ops, ocorreu algum erro. Tente novamente.";
    }

}else{
    $codigo_curso = campo_form_decodifica($_GET["codigo_curso"]);
}

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

.vagas-curso {
    width: 100%;
    height: 80px;
    font-size: 80px;
    line-height:80px;
    color: #0085B2;
    font-family: 'Open Sans';
    margin-bottom: 20px;
    text-align:center;
    color: #333;
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


</style>
<?php if($mensagem_ok){?>

<div class="mensagem-sucesso"><?php echo $mensagem_ok;?></div>

<?php }elseif($mensagem_erro){?>

<div class="mensagem-erro"><?php echo $mensagem_erro;?></div>

<?php }else{?>
<form action="atualizar_vagas.php" method="post" enctype="multipart/form-data">
        
    <div class="mensagem-transferir-atendimento">Insira Abaixo o novo número de vagas do Curso: <b><?php echo $resultado_consulta_curso["nome_curso"];?></b></div>
    <input type="hidden" name="acao" value="<?php echo campo_form_codifica("atualizar-vagas"); ?>">
    <input type="hidden" name="codigo_curso" id="codigo_curso" class="codigo_curso" value="<?php echo $codigo_curso;?>">
    <input type="text" class="vagas-curso" name="vagas_curso" id="vagas_curso" value="<?php echo $resultado_consulta_curso["quantidade_vagas"];?>">
    
    <input type="submit" class="btn-transferir" value="">
    <a title="Close" class="fancybox-item fancybox-close" onclick="parent.$.fancybox.close();"><input type="submit" class="btn-cancelar" value=""></a>

</form>
<?php }?>
<?php fecha_mysql($conexao); ?>