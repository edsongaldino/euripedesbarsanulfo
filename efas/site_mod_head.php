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
    $(document).ready(function() {
        // 1. Pre-fill form fields from URL params if they exist (used after category redirect)
        var params = new URLSearchParams(window.location.search);
        if (params.has('nome')) $("#nome_participante").val(params.get('nome'));
        if (params.has('cracha')) $("#nome_participante_cracha").val(params.get('cracha'));
        if (params.has('email')) $("#email_participante").val(params.get('email'));
        if (params.has('centro')) $("#centro_espirita_participante").val(params.get('centro'));
        if (params.has('nasc')) $("#data-nascimento").val(params.get('nasc'));
        if (params.has('fone')) {
            var foneVal = params.get('fone');
            if ($("#telefone_participante").length) {
                $("#telefone_participante").val(foneVal);
            } else if ($("#telefone_responsavel").length) {
                $("#telefone_responsavel").val(foneVal);
            }
        }

        // 2. Validate age when birth date is entered/changed
        $(document).on('blur change', '#data-nascimento', function() {
            var value = $(this).val();
            if (!value || value.indexOf('_') !== -1 || value.length < 10) return;

            var partes = value.split('/');
            if (partes.length !== 3) return;
            var diaNasc = parseInt(partes[0], 10);
            var mesNasc = parseInt(partes[1], 10);
            var anoNasc = parseInt(partes[2], 10);

            if (isNaN(diaNasc) || isNaN(mesNasc) || isNaN(anoNasc)) return;

            // Calculate age relative to current date
            var hoje = new Date();
            var diaHoje = hoje.getDate();
            var mesHoje = hoje.getMonth() + 1;
            var anoHoje = hoje.getFullYear();

            var idade = anoHoje - anoNasc;
            if (mesHoje < mesNasc || (mesHoje === mesNasc && diaHoje < diaNasc)) {
                idade--;
            }

            var currentPage = window.location.pathname.split('/').pop();
            var targetPage = "";
            var categoryName = "";

            if (idade <= 11) {
                targetPage = "inscricao_crianca.php";
                categoryName = "Crianças (0 a 11 anos)";
            } else if (idade === 12 || idade === 13) {
                targetPage = "inscricao_jovem.php";
                categoryName = "Jovens (12 e 13 anos)";
            } else {
                // Age >= 14
                // Workers should be >= 14, but they can register on workers page if they are already on it
                if (currentPage === "inscricao_trabalhador.php") {
                    return;
                }
                targetPage = "inscricao_adulto.php";
                categoryName = "Adultos (a partir de 14 anos)";
            }

            // Redirect if current page does not match expected category
            if (currentPage !== targetPage) {
                // Collect already filled data
                var nome = encodeURIComponent($("#nome_participante").val() || "");
                var cracha = encodeURIComponent($("#nome_participante_cracha").val() || "");
                var fone = encodeURIComponent($("#telefone_participante").val() || $("#telefone_responsavel").val() || "");
                var email = encodeURIComponent($("#email_participante").val() || "");
                var centro = encodeURIComponent($("#centro_espirita_participante").val() || "");
                var nasc = encodeURIComponent(value);

                var redirectUrl = targetPage + "?nome=" + nome + "&cracha=" + cracha + "&fone=" + fone + "&email=" + email + "&centro=" + centro + "&nasc=" + nasc;

                showCategoryAlert(idade, categoryName, targetPage, redirectUrl);
            }
        });
    });

    function showCategoryAlert(idade, categoryName, targetPage, redirectUrl) {
        $("#custom-age-modal").remove();

        var modalHtml = `
            <div id="custom-age-modal" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                font-family: 'Inter', sans-serif;
                opacity: 0;
                transition: opacity 0.3s ease;
            ">
                <div style="
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                    width: 90%;
                    max-width: 450px;
                    padding: 30px;
                    text-align: center;
                    border: 1px solid #e2e8f0;
                    transform: scale(0.9);
                    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                ">
                    <div style="
                        width: 60px;
                        height: 60px;
                        background: #fef3c7;
                        color: #d97706;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 20px;
                        font-size: 28px;
                    ">
                        ⚠️
                    </div>
                    
                    <h3 style="
                        font-family: 'Montserrat', sans-serif !important;
                        font-weight: 800 !important;
                        font-size: 1.35em !important;
                        color: #0f172a !important;
                        margin-bottom: 12px !important;
                        text-transform: none !important;
                    ">Categoria Incorreta</h3>
                    
                    <p style="
                        color: #475569 !important;
                        font-size: 0.98em !important;
                        line-height: 1.6 !important;
                        margin-bottom: 20px !important;
                    ">
                        Com base na data de nascimento, o participante tem <strong>${idade} anos</strong>.<br><br>
                        A categoria correta é:<br>
                        <span style="
                            display: inline-block;
                            background: #f0fdf4;
                            color: #16a34a;
                            padding: 6px 12px;
                            border-radius: 6px;
                            font-weight: 700;
                            margin-top: 5px;
                            font-size: 1.05em;
                            border: 1px solid #bbf7d0;
                        ">${categoryName}</span>
                    </p>
                    
                    <p style="
                        color: #64748b !important;
                        font-size: 0.9em !important;
                        margin-bottom: 25px !important;
                    ">
                        Vamos redirecionar você e manter os dados já preenchidos.
                    </p>
                    
                    <button id="custom-age-modal-btn" style="
                        background: #0284c7;
                        color: #ffffff;
                        border: none;
                        border-radius: 8px;
                        padding: 12px 30px;
                        font-family: 'Montserrat', sans-serif;
                        font-size: 1em;
                        font-weight: 700;
                        cursor: pointer;
                        width: 100%;
                        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
                        transition: all 0.2s ease;
                        outline: none;
                    ">
                        Ir para Categoria Correta
                    </button>
                </div>
            </div>
        `;

        $("body").append(modalHtml);

        setTimeout(function() {
            $("#custom-age-modal").css("opacity", "1");
            $("#custom-age-modal > div").css("transform", "scale(1)");
        }, 10);

        $("#custom-age-modal-btn").hover(
            function() { $(this).css("background", "#0369a1"); },
            function() { $(this).css("background", "#0284c7"); }
        );

        $("#custom-age-modal-btn").on("click", function() {
            $("#custom-age-modal").css("opacity", "0");
            $("#custom-age-modal > div").css("transform", "scale(0.9)");
            setTimeout(function() {
                window.location.href = redirectUrl;
            }, 300);
        });
    }

    function mascara(telefone){ 

        if(telefone.value.length == 0)
            telefone.value = '(' + telefone.value; //quando começamos a digitar, o script irá inserir um parênteses no começo do campo.
        if(telefone.value.length == 3)
            telefone.value = telefone.value + ') '; //quando o campo já tiver 3 caracteres (um parênteses e 2 números) o script irá inserir mais um parênteses, fechando assim o código de área.
        
        if(telefone.value.length == 9)
            telefone.value = telefone.value + '-'; //quando o campo já tiver 8 caracteres, o script irá inserir um tracinho, para melhor visualização do telefone.
    
    }
    </script>