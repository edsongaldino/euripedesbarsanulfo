<?php include("sistema_mod_include.php"); ?>
<!DOCTYPE html>
<html lang="en">
  
<head>
<?php include "site_mod_head_interno.php";?>
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
	      
	      	<div class="info-box">
               <div class="row-fluid stats-box">
                  <div class="span3">
                  	<div class="stats-box-title">Crianças</div>
                    <div class="stats-box-all-info"><i class="icon-user" style="color:#F38630;"></i> <?php echo calcula_total_inscritos("C");?></div>
                  </div>
                  
                  <div class="span3">
                    <div class="stats-box-title">Jovens</div>
                    <div class="stats-box-all-info"><i class="icon-user"  style="color:#E0E4CC"></i> <?php echo calcula_total_inscritos("J");?></div>
                  </div>
                  
                  <div class="span3">
                    <div class="stats-box-title">Adultos</div>
                    <div class="stats-box-all-info"><i class="icon-user" style="color:#69D2E7"></i> <?php echo calcula_total_inscritos("A");?></div>
                  </div>

				  <div class="span3">
                    <div class="stats-box-title">Trabalhadores</div>
                    <div class="stats-box-all-info"><i class="icon-user" style="color:#69D2E7"></i> <?php echo calcula_total_inscritos("T");?></div>
                  </div>
               </div>
               
               
             </div>
               
               
         </div>
         </div>      
	      	
	  	  <!-- /row -->
	
	      <div class="row">
	      	
	      	<div class="span6">
	      		
	      		<div class="widget">
						
					<div class="widget-header">
						<i class="icon-star"></i>
						<h3>Relatório de Participantes</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<canvas id="pie-chart" class="chart-holder" height="250" width="538"></canvas>
					</div> <!-- /widget-content -->
						
				</div> <!-- /widget -->
				
	      		
	      		
	      		
		    </div> <!-- /span6 -->
	      	
	      	
	      	<div class="span6">
	      		
	      		<div class="widget">
							
					<div class="widget-header">
						<i class="icon-list-alt"></i>
						<h3>Relatório por idade</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<canvas id="bar-chart" class="chart-holder" height="250" width="538"></canvas>
					</div> <!-- /widget-content -->
				
				</div> <!-- /widget -->
									
		      </div> <!-- /span6 -->
	      	
	      </div> <!-- /row -->
	      
	      
	      
	      
			
	      
	      
	    </div> <!-- /container -->
	    
	</div> <!-- /main-inner -->
    
</div> <!-- /main -->
    

   
    
<div class="footer">
	
	<div class="footer-inner">
		
		<div class="container">
			
			<div class="row">
				
    			<div class="span12">
    				<?php include "site_mod_rodape.php";?>
    			</div> <!-- /span12 -->
    			
    		</div> <!-- /row -->
    		
		</div> <!-- /container -->
		
	</div> <!-- /footer-inner -->
	
</div> <!-- /footer -->
    

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/excanvas.min.js"></script>
<script src="js/chart.min.js" type="text/javascript"></script>
<script src="js/bootstrap.js"></script>
<script src="js/base.js"></script>
<script>

    var pieData = [
				{
				    value: <?php echo calcula_total_inscritos("C");?>,
				    color: "#F38630"
				},
				{
				    value: <?php echo calcula_total_inscritos("J");?>,
				    color: "#E0E4CC"
				},
				{
				    value: <?php echo calcula_total_inscritos("A");?>,
				    color: "#69D2E7"
				}

			];

    var myPie = new Chart(document.getElementById("pie-chart").getContext("2d")).Pie(pieData);

    var barChartData = {
        labels: ["0 à 6 anos", "6 à 11 anos", "12 e 13 anos", "14 à 18 anos", "18 á 30 anos", "30 à 40 anos", "Acima de 40 anos"],
        datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    data: [65, 59, 90, 81, 56, 55, 40]
				}
			]

    }

    var myLine = new Chart(document.getElementById("bar-chart").getContext("2d")).Bar(barChartData);
	var myLine = new Chart(document.getElementById("bar-chart1").getContext("2d")).Bar(barChartData);
	var myLine = new Chart(document.getElementById("bar-chart2").getContext("2d")).Bar(barChartData);
	var myLine = new Chart(document.getElementById("bar-chart3").getContext("2d")).Bar(barChartData);
	
	</script>


  </body>

</html>
