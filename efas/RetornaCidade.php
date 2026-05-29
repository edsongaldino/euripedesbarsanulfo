<?php include("sistema_mod_include.php"); ?>
<?php
     
    //get search term
    $estado = $_GET['estado'];
    
    $conexao = conecta_mysql();

    $sql = "SELECT codigo_cidade, nome_cidade FROM cidade WHERE codigo_estado = '".$estado."' ORDER BY nome_cidade ASC";  //busco todos os estados e ordeno pela sigla
    $res = mysqli_query($conexao,$sql);
    $num = mysqli_num_rows($res);  //numero de estados encontrados

    for ($i = 0; $i < $num; $i++) {
        $dados = mysqli_fetch_array($res);
        $arrCidades[$dados['codigo_cidade']] = mb_convert_encoding($dados['nome_cidade'],"UTF-8");
    }

    fecha_mysql($conexao);

    
?>
<span>Cidade :</span>
<select name="cidade_participante" id="cidade_participante" required>

     <?php foreach($arrCidades as $value => $nome){
        echo "<option value='{$value}'>{$nome}</option>";
        }
     ?>

</select>