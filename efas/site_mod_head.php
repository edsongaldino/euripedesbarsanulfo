<title>Encontro Fraterno Auta de Souza - Inscrição Online</title>
<base href="<?php echo baseUrl(); ?>" />
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Custom Theme files -->
<!--theme-style-->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />	
<link href="css/modern.css" rel="stylesheet" type="text/css" media="all" />
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Encontro fraterno auta de souza, concafras, espiritismo, auta de souza" />

<meta name='author' content='Datapix tecnologia - www.datapix.com.br - (65) 3927-5480'/>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="robots" content="ALL" />
<meta name="revisit-after" content="1" />
<meta name="rating" content="General" />
<meta name="classification" content="Evento" />
<link rel="shortcut icon" href="favicon.ico" type="image/icon" />

<!-- Meta Keyword -->
<meta name="twitter:image" content="<?php echo baseUrl(); ?>/images/logo_efas.jpg">

<meta property="og:url" content="<?php echo baseUrl(); ?>/inscricao.php" />
<meta property="og:title" content="Encontro Fraterno Auta de Souza - Inscrição Online" />
<meta property="og:description" content="Encontro Fraterno Auta de Souza é um evento espírita que proporciona aos seus participantes a troca de experiências, estudo da Doutrina Espírta e trabalho no campo do bem" />
<meta property="og:image" content="<?php echo baseUrl(); ?>/images/logo_efas.jpg" />

<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1067">
<meta property="og:image:height" content="600">

    <script>
    function buscar_cidades(){
      var estado = $('#estado_participante').val();
      if(estado){
        var url = 'RetornaCidade.php?estado='+estado;
        $.get(url, function(dataReturn) {
          $('#load_cidades').html(dataReturn);
        });
      }
    }
    </script>

    <script type="text/javascript">

    function mascara(telefone){ 

        if(telefone.value.length == 0)
            telefone.value = '(' + telefone.value; //quando começamos a digitar, o script irá inserir um parênteses no começo do campo.
        if(telefone.value.length == 3)
            telefone.value = telefone.value + ') '; //quando o campo já tiver 3 caracteres (um parênteses e 2 números) o script irá inserir mais um parênteses, fechando assim o código de área.
        
        if(telefone.value.length == 9)
            telefone.value = telefone.value + '-'; //quando o campo já tiver 8 caracteres, o script irá inserir um tracinho, para melhor visualização do telefone.
    
    }
    </script>