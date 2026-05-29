<div class="navbar-inner">
<div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="eventos.php">Sistema de Inscrições - EFAS</a>
  <div class="nav-collapse">
    <ul class="nav pull-right">
      <li class="dropdown">
      <a href="editar_conta.php?codigo_usuario=<?php echo campo_form_codifica($_SESSION["codigo_usuario_acesso"]);?>"><i
                        class="icon-cog"></i> Minha conta </a>
        <ul class="dropdown-menu">
          <li><a href="javascript:;">Configurações</a></li>
          <li><a href="javascript:;">Ajuda</a></li>
        </ul>
      </li>
      <li class="dropdown"><a href="sistema_sair.php"><i
                        class="icon-user"></i> Sair</a>
      </li>
    </ul>
    <form class="navbar-search pull-right">
      <input type="text" class="search-query" placeholder="Search">
    </form>
  </div>
  <!--/.nav-collapse --> 
</div>
<!-- /container --> 
</div>
<!-- /navbar-inner -->