<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$isInscricao = (
    strpos($currentPage, 'inscricao') !== false ||
    strpos($currentPage, 'confirma_') !== false ||
    strpos($currentPage, 'consultar_') !== false ||
    strpos($currentPage, 'grava_') !== false
);
?>
<div class="logo">
    <h1><a href="index.php">EFAS MT</a></h1>
</div>
<div class="top-nav">
    <span class="menu"><img src="images/menu.png" alt=""> </span>
    <ul>
        <li ><a href="index.php" class="hvr-sweep-to-bottom <?php echo ($currentPage == 'index.php' || $currentPage == '') ? 'color' : 'color1'; ?>"><i class="glyphicon glyphicon-home"></i>Home  </a> </li>
        <li ><a href="efas.php" class="hvr-sweep-to-bottom <?php echo ($currentPage == 'efas.php') ? 'color' : 'color1'; ?>"><i class="glyphicon glyphicon-picture"></i>O que é o EFAS?  </a> </li>
	    <li><a href="inscricao.php" class="hvr-sweep-to-bottom <?php echo $isInscricao ? 'color' : 'color1'; ?>"><i class="glyphicon glyphicon-calendar"></i>Inscrição </a></li>
        <div class="clearfix"> </div>
    </ul>
    <!--script-->
    <script>
        $("span.menu").click(function(){
            $(".top-nav ul").slideToggle(500, function(){
            });
        });
    </script>				
</div>
<div class="clearfix"> </div>
