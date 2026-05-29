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

			<div class="banner-area">
				<div class="container">
					<div class="row justify-content-center generic-height align-items-center">
						<div class="col-lg-8">
							<div class="banner-content text-center">
								<span class="text-white top text-uppercase">Centros Espíritas</span>
								<h1 class="text-white text-uppercase">Procure um centro espírita próximo de você!</h1>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- About Generic Start -->
			<section class="about-generic-area">
				<!-- Start Feature Area -->
			<section class="featured-area">
				<div class="container">
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
			</section>
			<!-- End Generic Start -->
			
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
		<script src="js/jquery.sticky.js"></script>
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/jquery.nice-select.min.js"></script>
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>
