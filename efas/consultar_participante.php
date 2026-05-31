<?php include "sistema_mod_include.php"; ?>
<?php
   /**
   * função que devolve em formato JSON os dados do cliente
   */
  function retorna( $nome, $db )
  {
    $nome = mysqli_real_escape_string($db, $nome);
    $nome_double = mysqli_real_escape_string($db, utf8_encode($nome));
    $sql = "SELECT 
                participante.nome_participante, participante.nome_participante_cracha, participante.data_nascimento_participante, participante.centro_espirita_participante,
                email_participante.descricao_email_participante, telefone_participante.numero_telefone_participante

                FROM participante 
                LEFT JOIN email_participante ON participante.codigo_participante = email_participante.codigo_participante
                LEFT JOIN telefone_participante ON participante.codigo_participante = telefone_participante.codigo_participante
                WHERE (participante.nome_participante LIKE '%".$nome."%' OR participante.nome_participante LIKE '%".$nome_double."%')
                ORDER BY 
                    (CASE WHEN email_participante.descricao_email_participante IS NOT NULL AND email_participante.descricao_email_participante != '' THEN 1 ELSE 0 END) DESC, 
                    (CASE WHEN telefone_participante.numero_telefone_participante IS NOT NULL AND telefone_participante.numero_telefone_participante != '' THEN 1 ELSE 0 END) DESC,
                    participante.codigo_participante DESC
                LIMIT 1";

    $query = mysqli_query($db, $sql);

    $arr = Array();
    if( $query && mysqli_num_rows($query) )
    {
      while( $dados = mysqli_fetch_object($query) )
      {
        $arr['nome_participante_cracha'] = fix_double_utf8($dados->nome_participante_cracha);
        $arr['data_nascimento_participante'] = converte_data_portugues($dados->data_nascimento_participante);
        $arr['numero_telefone_participante'] = $dados->numero_telefone_participante;
        $arr['email_participante'] = fix_double_utf8($dados->descricao_email_participante);
        $arr['centro_espirita_participante'] = fix_double_utf8($dados->centro_espirita_participante);
      }
    }
    else
      $arr['nome_participante_cracha'] = '';

    return json_encode( $arr );
  }

/* só se for enviado o parâmetro, que devolve os dados */
if( isset($_GET['nome']) )
{
  $conexao = conecta_mysql();
  echo retorna( $_GET['nome'], $conexao );
}
?>