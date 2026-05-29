<?php
// Função para converte valores para o padrão brasileiro REAL
function calcula_idade($data_nascimento) {
 
    //Data atual
    $dia = date('d');
    $mes = date('m');
    $ano = date('Y');
 
    //Data do aniversário
    $data_nascimento = explode('-', $data_nascimento);
    $dianasc = ($data_nascimento[2]);
    $mesnasc = ($data_nascimento[1]);
    $anonasc = ($data_nascimento[0]);

 
    //Calculando sua idade
    $idade = $ano - $anonasc; // simples, ano- nascimento!
 
    if ($mes < $mesnasc) // se o mes é menor, só subtrair da idade
    {
        $idade--;
        return $idade;
    }
    elseif ($mes == $mesnasc && $dia <= $dianasc) // se esta no mes do aniversario mas não passou ou chegou a data, subtrai da idade
    {
        $idade--;
        return $idade;
    }
    else // ja fez aniversario no ano, tudo certo!
    {
        return $idade;
    }
}
?>