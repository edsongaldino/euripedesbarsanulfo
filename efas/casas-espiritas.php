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
<div class="contact">
    <div class="container">
        <div class="contact-top ">
            <h3>Casas Espíritas</h3>
        </div>		

		<center>
			
			<script type="text/javascript">
			<!--
				function abrirJanela(pagina, largura, altura) {
				// Definindo centro da tela
				var esquerda = (screen.width - largura)/2;
				var topo = (screen.height - altura)/2;
				// Abre a nova janela
				minhaJanela = window.open(pagina,'','height=' + altura + ', width=' + largura + ', top=' + topo + ', left=' + esquerda);
			}
				-->
			</script>
			<!-- CUIABÁ  --> 
			<a onclick="javascript:abrirJanela('https://secretaria.efas.euripedesbarsanulfo.org.br/ce/cuiaba.php', 1700, 800);"><img src="ce/img/cuiaba.png" alt="Smiley face" width="300" height="300" style="vertical-align:middle;margin:0px 10px"></a>
			
			<!-- VARZEA GRANDE  --> 
			<a onclick="javascript:abrirJanela('https://secretaria.efas.euripedesbarsanulfo.org.br/ce/vg.php', 1700, 800);"><img src="ce/img/vg.png" alt="Smiley face" width="300" height="300" style="vertical-align:middle;margin:0px 10px"></a>
			
			<!-- INTERIOR  --> 
			<a onclick="javascript:abrirJanela('https://secretaria.efas.euripedesbarsanulfo.org.br/ce/interior.php', 1700, 800);"><img src="ce/img/interior.png" alt="Smiley face" width="300" height="300" style="vertical-align:middle;margin:0px 10px"></a>
		
		
		</center>
       
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