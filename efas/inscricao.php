<?php include "sistema_mod_include.php";?>
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
	<!--
        <div class="contact-top ">
            <h3>INSCRIÇÕES ABERTAS ATÉ 05 DE SETEMBRO. APÓS ESSA DATA, SOMENTE PRESENCIAL</h3>
        </div>

		
		 <div class="info-inscricao">
            Já fez sua inscrição mas ainda não pagou? Clique abaixo para fazer o pagamento:
        </div>
		
		<div class="info-inscricao">
			Novas inscrições poderão ser feitas no dia do evento pelo valor de 20,00 (Adulto) e 10,00 (Criança)
        </div>
		
       

		
		
		<div class="content-mid box-inscricao">
			<div class="col-md-3 mid">
				<a href="consultar_inscricao.php">
				<div class="mid1">
					<h4>Acompanhar inscrição</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>
			
			<div class="clearfix"> </div>
		</div>
		

		
        <div class="info-inscricao">
            As inscrições pela internet estarão abertas até o dia 05/09/2024. Faça sua inscrição antecipada e evite filas e aglomerações!<br/>
            O pagamento deverá ser feito via Mercado Pago. Em caso de dúvidas, envie um e-mail para <b>secretaria@euripedesbarsanulfo.org.br</b>, juntamente com o número de sua inscrição.<br/><br/>
			Escolha uma das opções abaixo para realizar sua inscrição:
        </div>
		content-mid-->
         
		<div class="content-mid box-inscricao">

			<div class="col-md-3 mid">
				<a href="inscricao_crianca.php">
				<div class="mid1 child-btn">
					<h4>Inscrição para Crianças</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>

			<div class="col-md-3 mid">
				<a href="inscricao_jovem.php">
				<div class="mid1 youth-btn">
					<h4>Inscrição para Jovens</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>

			<div class="col-md-3 mid">
				<a href="inscricao_adulto.php">
				<div class="mid1 adult-btn">
					<h4>Inscrição para Adultos</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>

            <div class="col-md-3 mid">
				<a href="inscricao_trabalhador.php">
				<div class="mid1 worker-btn">
					<h4>Inscrição para Trabalhadores</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>

			<div class="clearfix"> </div>
		</div>
 
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