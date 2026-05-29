<?php include("sistema_mod_include.php"); ?>
<!DOCTYPE html>
<html lang="en">
  
<head>
<?php include "site_mod_head_interno.php";?> 
<link href="css/pages/plans.css" rel="stylesheet"> 
</head>

<body>

<div class="navbar navbar-fixed-top">
	<?php include "site_mod_topo_interno.php";?> 
</div> <!-- /navbar -->
    



    
<div class="subnavbar">

	<div class="subnavbar-inner">
	
		<div class="container">

			<?php include "site_mod_menu.php";?>

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div> <!-- /subnavbar -->
    
    
<div class="main">
	
	<div class="main-inner">

	    <div class="container">
	
	      <div class="row">
	      	
	      	<div class="span12">
	      		
	      		<div class="widget">
						
					<div class="widget-header">
						<i class="icon-th-large"></i>
						<h3>Selecione para qual evento deseja efetuar sua inscrição</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						<div class="pricing-plans plans-3">
							
						<div class="plan-container">
					        <div class="plan">
						        <div class="plan-header">
					                
						        	<div class="plan-title">
						        		4069º ENCONTRO FRATERNO AUTA DE SOUZA	        		
					        		</div> <!-- /plan-title -->
					                
						            <div class="plan-price">
					                	<img src="img/banner_efas_inscricao_3420.jpg"/>
									</div> <!-- /plan-price -->
									
						        </div> <!-- /plan-header -->	        
						        
						        <div class="plan-features">
									<ul>
										<li><strong>de 01 à 02 de Junho de 2019</strong></li>
										<li>Wantuil de Freitas - Cuiabá, MT</li>
										<li>Inscrição crianças até 11 anos: <strong>R$ 10,00</strong></li>
										<li>Inscrição Jovens e Adultos:<strong> R$ 20,00</strong></li>
									</ul>
								</div> <!-- /plan-features -->
								
								<div class="plan-actions">				
									<a href="inscricao.php?acao=<?php echo campo_form_codifica("inscrever");?>" class="btn">Realizar Inscrição</a>				
								</div> <!-- /plan-actions -->
					
							</div> <!-- /plan -->
					    </div> <!-- /plan-container --><!-- /plan-container -->
					    
					    
				
				
					</div> <!-- /pricing-plans -->
						
					</div> <!-- /widget-content -->
						
				</div> <!-- /widget -->					
				
		    </div> <!-- /span12 -->     	
	      	
	      	
	      </div> <!-- /row -->
	
	    </div> <!-- /container -->
	    
	</div> <!-- /main-inner -->
    
</div> <!-- /main --> 
    
<div class="footer">
	
	<div class="footer-inner">
		
		<div class="container">
			
			<div class="row">
				
    			<?php include "site_mod_rodape.php";?>
    			
    		</div> <!-- /row -->
    		
		</div> <!-- /container -->
		
	</div> <!-- /footer-inner -->
	
</div> <!-- /footer -->
<script src="js/bootstrap.js"></script>
</body>

</html>
