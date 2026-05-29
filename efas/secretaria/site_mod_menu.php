<ul class="mainnav">
<?php $pagina = $_GET['pagina'];?>
<!--
<li <?php if($pagina==1){echo 'class="active"';}?>><a href="eventos.php?pagina=1"><i class="icon-calendar"></i><span>Eventos</span> </a> </li>
<li <?php if($pagina==2){echo 'class="active"';}?>><a href="meus_cadastros.php?pagina=2"><i class="icon-group"></i><span>Meus cadastros</span> </a> </li>
<li <?php if($pagina==3){echo 'class="active"';}?>><a href="minhas_inscricoes.php?pagina=3"><i class="icon-check"></i><span>Minhas inscrições</span> </a> </li>
-->
<li <?php if($pagina==4){echo 'class="active"';}?>><a href="cursos.php?pagina=4"><i class="icon-book"></i><span>Cursos</span> </a> </li>
<?php if($_SESSION["codigo_tipo_usuario_acesso"] <> '4'){?>
<li <?php if($pagina==5){echo 'class="active"';}?>><a href="participantes.php?pagina=5"><i class="icon-user"></i><span>Participantes</span> </a> </li>
<?php }?>
<!--
<li <?php if($pagina==6){echo 'class="active"';}?>><a href="trabalhadores.php?pagina=6"><i class="icon-group"></i><span>Trabalhadores</span> </a> </li>
-->
<li <?php if($pagina==7){echo 'class="active"';}?>><a href="relatorios.php?pagina=7"><i class="icon-bar-chart"></i><span>Relatórios</span> </a> </li>
</ul>