$(document).ready( function() {
   /* Executa a requisição quando o campo CEP perder o foco */
   $('#nome_participante').blur(function(){
           /* Configura a requisição AJAX */
           $.ajax({
                url : 'consultar_participante.php', /* URL que será chamada */ 
                type : 'POST', /* Tipo da requisição */ 
                data: 'nome_participante=' + $('#nome_participante').val(), /* dado que será enviado via POST */
                dataType: 'json', /* Tipo de transmissão */
                success: function(data){
                    if(data.sucesso == 1){
                        $('#nome_participante_cracha').val(data.nome_participante_cracha);
                        $('#data_nascimento_participante').val(data.data_nascimento_participante);
                        $('#telefone_participante').val(data.telefone_participante);
                        $('#email_participante').val(data.email_participante);
                        $('#centro_espirita_participante').val(data.centro_espirita_participante);
 
                        $('#comissao_trabalho').focus();
                    }
                }
           });   
   return false;    
   })
});