<ul class="mainnav">
<?php $pagina = $_GET['pagina'];?>
<li <?php if($pagina==1){echo 'class="active"';}?>><a href="eventos.php?pagina=1"><i class="icon-calendar"></i><span>Eventos</span> </a> </li>
<li <?php if($pagina==4){echo 'class="active"';}?>><a href="cursos.php?pagina=4"><i class="icon-book"></i><span>Cursos</span> </a> </li>
<?php if($_SESSION["codigo_tipo_usuario_acesso"] <> '4'){?>
<li <?php if($pagina==5){echo 'class="active"';}?>><a href="participantes.php?pagina=5"><i class="icon-user"></i><span>Participantes</span> </a> </li>
<?php }?>
<li <?php if($pagina==7){echo 'class="active"';}?>><a href="relatorios.php?pagina=7"><i class="icon-bar-chart"></i><span>Relatórios</span> </a> </li>
</ul>