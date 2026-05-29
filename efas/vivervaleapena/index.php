<?php require_once("site-mod-include.php");?>
<?php
    $msg_erro        = protege(decodifica($_GET["me"]));
    $mensagem        = protege(decodifica($_GET["mm"]));

    //Abre a conexão
    $pdo = Database::conexao();
    
	//consulta centros
    $sql_consulta_centros = "SELECT * FROM centro_espirita WHERE centro_espirita.status <> 'E' ORDER BY RAND()";
    $result = $pdo->query( $sql_consulta_centros );
    $centros = $result->fetchAll( PDO::FETCH_ASSOC );

?>
<!DOCTYPE html>
<html lang="pt-br" class="no-js">
	<head>
	<?php include "site-mod-head.php";?>
	</head>
	<body>
		<div class="main-wrapper-first">
			<header>
			<?php include "site-mod-topo.php";?>	
			</header>
		</div>
		<div class="main-wrapper">
			<div class="active-banner-slider">
				<div class="item d-flex align-items-center">
					<div class="container">
						<div class="content">
							<h1 class="text-white text-uppercase">Campanha de Prevenção <br> ao Suicídio - Camilo Castelo Branco</h1>
							<p class="text-white">A Campanha realiza várias atividades que objetivam evidenciar a alegria de viver, trazendo a compreensão de que as provas da vida são sublimes lições que bem suportadas nos conduzem a paz interior.</p>
							<a href="#" class="primary-btn d-inline-flex align-items-center"><span class="mr-10">Saiba mais</span><span class="lnr lnr-arrow-right"></span></a>
						</div>
					</div>
				</div>
				<div class="item d-flex align-items-center">
					<div class="container">
					<div class="content">
							<h1 class="text-white text-uppercase">Campanha de Prevenção <br> ao Suicídio - Camilo Castelo Branco</h1>
							<p class="text-white">A Campanha realiza várias atividades que objetivam evidenciar a alegria de viver, trazendo a compreensão de que as provas da vida são sublimes lições que bem suportadas nos conduzem a paz interior.</p>
							<a href="#" class="primary-btn d-inline-flex align-items-center"><span class="mr-10">Saiba mais</span><span class="lnr lnr-arrow-right"></span></a>
						</div>
					</div>
				</div>
				<div class="item d-flex align-items-center">
					<div class="container">
					<div class="content">
							<h1 class="text-white text-uppercase">Campanha de Prevenção <br> ao Suicídio - Camilo Castelo Branco</h1>
							<p class="text-white">A Campanha realiza várias atividades que objetivam evidenciar a alegria de viver, trazendo a compreensão de que as provas da vida são sublimes lições que bem suportadas nos conduzem a paz interior.</p>
							<a href="#" class="primary-btn d-inline-flex align-items-center"><span class="mr-10">Saiba mais</span><span class="lnr lnr-arrow-right"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="main-wrapper">
			<!-- Start Feature Area -->
			<section class="featured-area">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-8">
							<div class="section-title text-center">
								<h3>Centros Espíritas</h3>
								<span class="text-uppercase">Encontre uma casa espírita perto de você!</span>
							</div>
						</div>
					</div>
					<div class="row">

						<?php foreach($centros AS $centro_espirita):?>
						<div class="col-md-4">
							<div class="single-feature">
								<span class="lnr lnr-home"></span>
								<div class="desc text-center">
									<h6 class="title text-uppercase nome-centro"><?php echo $centro_espirita["nome_centro"];?></h6>
									<p><span class="lnr lnr-map-marker"></span><?php echo $centro_espirita["endereco_centro"];?></p>
									<p><a href="tel:<?php echo limpa_campo($centro_espirita["telefone_centro"]);?>"><span class="lnr lnr-phone-handset"></span> <b><?php echo $centro_espirita["telefone_centro"];?></b></a></p>
									<p><a href="http://api.whatsapp.com/send?1=pt_BR&phone=55<?php echo limpa_campo($centro_espirita["whatsapp_centro"]);?>"><span class="lnr lnr-bubble"></span> <b><?php echo $centro_espirita["whatsapp_centro"];?></b></a></p>
									<p><a href="<?php echo $centro_espirita["localizador_centro"];?>" target="_blank" class="genric-btn success circle"><span class="lnr lnr-location"></span> COMO CHEGAR</a></p>
								</div>
							</div>
						</div>
						<?php endforeach;?>

					</div>
				</div>
			</section>
			<!-- End Feature Area -->
			<!-- Start Story Area -->
			<section class="story-area">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-3">
							<div class="story-title">
								<h3 class="text-white">Depressão tem cura!</h3>
								<span class="text-uppercase text-white">Tratamento Espiritual</span>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="story-box">
								<h6 class="text-uppercase">O que é o tratamento espiritual?</h6>
								<p>O tratamento espiritual, à distância ou não, visa atender casos onde há perturbações físicas, emocionais e espirituais. Há diversas formas de intervir no corpo espiritual e curar traumas, possessividade e até mesmo doenças.</p>
								<a href="casas-espiritas.php" class="primary-btn d-inline-flex align-items-center"><span class="mr-10">Saiba onde buscar ajuda!</span><span class="lnr lnr-arrow-right"></span></a>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End Story Area -->

			<!-- Start Subscription Area -->
			<section class="subscription-area">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-8">
							<div class="section-title text-center">
								<h3>Cadastre seu e-mail</h3>
								<span class="text-uppercase">Receba novidades sobre eventos e palestras</span>
							</div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-lg-6">
							<div id="mc_embed_signup">
								<form target="_blank" novalidate action="" method="get" class="subscription relative">
									<input type="email" name="EMAIL" placeholder="Email address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Seu e-mail'" required>
									<div style="position: absolute; left: -5000px;">
										<input type="text" name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="">
									</div>
									<button class="primary-btn hover d-inline-flex align-items-center"><span class="mr-10">Quero participar</span><span class="lnr lnr-arrow-right"></span></button>
									<div class="info"></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End Subscription Area -->
			<!-- Start Footer Widget Area -->
			<section class="footer-widget-area">
				<footer>
					<?php include "site-mod-footer.php";?>
				</footer>
			</section>
			<!-- End Footer Widget Area -->

		</div>




		<script src="js/vendor/jquery-2.2.4.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/jquery.ajaxchimp.min.js"></script>
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/jquery.nice-select.min.js"></script>
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>
