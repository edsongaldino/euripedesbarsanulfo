<?php include("sistema_mod_include.php"); ?>
<?php
$conexao = conecta_mysql();

$mensagem_sucesso = "";
$mensagem_erro = "";

// Trata mensagens de retorno
if (isset($_GET['sucesso'])) {
    if ($_GET['sucesso'] == 1) $mensagem_sucesso = "Evento salvo com sucesso!";
    if ($_GET['sucesso'] == 2) $mensagem_sucesso = "Vínculos de cursos atualizados com sucesso!";
    if ($_GET['sucesso'] == 3) $mensagem_sucesso = "Evento excluído com sucesso!";
    if ($_GET['sucesso'] == 4) $mensagem_sucesso = "Evento selecionado como ATIVO!";
}
if (isset($_GET['erro'])) {
    if ($_GET['erro'] == 1) $mensagem_erro = "Não é possível excluir este evento pois ele já possui inscrições vinculadas.";
    if ($_GET['erro'] == 2) $mensagem_erro = "Ocorreu um erro ao processar a solicitação.";
}

// Ações POST
if (isset($_POST["acao"])) {
    $acao = campo_form_decodifica($_POST["acao"]);

    if ($acao == "salvar_evento") {
        $codigo_evento = isset($_POST['codigo_evento']) ? (int)$_POST['codigo_evento'] : 0;
        $nome_evento = protege_campo($_POST['nome_evento']);
        $data_inicial_evento = protege_campo($_POST['data_inicial_evento']);
        $data_final_evento = protege_campo($_POST['data_final_evento']);
        $descricao_evento = protege_campo($_POST['descricao_evento']);
        $local_evento = protege_campo($_POST['local_evento']);
        $codigo_cidade = (int)$_POST['codigo_cidade'];

        if ($codigo_evento > 0) {
            $sql = "UPDATE evento SET 
                        nome_evento = '$nome_evento', 
                        data_inicial_evento = '$data_inicial_evento', 
                        data_final_evento = '$data_final_evento', 
                        descricao_evento = '$descricao_evento', 
                        local_evento = '$local_evento', 
                        codigo_cidade = $codigo_cidade 
                    WHERE codigo_evento = $codigo_evento";
            $query = mysqli_query($conexao, $sql);
        } else {
            $sql = "INSERT INTO evento (nome_evento, data_inicial_evento, data_final_evento, descricao_evento, local_evento, codigo_cidade) 
                    VALUES ('$nome_evento', '$data_inicial_evento', '$data_final_evento', '$descricao_evento', '$local_evento', $codigo_cidade)";
            $query = mysqli_query($conexao, $sql);
        }

        if ($query) {
            redireciona("eventos.php?pagina=1&sucesso=1");
        } else {
            redireciona("eventos.php?pagina=1&erro=2");
        }
        exit;
    }

    if ($acao == "salvar_vinculos") {
        $codigo_evento = (int)$_POST['codigo_evento'];
        $cursos_selecionados = isset($_POST['cursos_selecionados']) ? $_POST['cursos_selecionados'] : [];
        $novos_cursos = isset($_POST['novos_cursos']) ? $_POST['novos_cursos'] : [];

        $erro = false;
        
        // 1. Cadastra novos cursos no banco
        $novos_ids_vagas_ref = [];
        foreach ($novos_cursos as $idx => $n_curso) {
            $nome_c = protege_campo($n_curso['nome']);
            $cod_inst = (int)$n_curso['codigo_instituto'];
            $cod_tema = (int)$n_curso['codigo_tema_curso'];
            $vagas = (int)$n_curso['vagas'];
            $referencia = protege_campo($n_curso['referencia']);

            $sql_c_insert = "INSERT INTO curso (nome_curso, codigo_instituto, codigo_tema_curso, situacao_curso) 
                             VALUES ('$nome_c', $cod_inst, $cod_tema, 'A')";
            if (mysqli_query($conexao, $sql_c_insert)) {
                $new_id = mysqli_insert_id($conexao);
                $novos_ids_vagas_ref[] = [
                    'id' => $new_id,
                    'vagas' => $vagas,
                    'referencia' => $referencia
                ];
            } else {
                $erro = true;
                $_SESSION['ultimo_erro_db'] = mysqli_error($conexao) . " (ao tentar inserir curso '$nome_c')";
            }
        }

        if (!$erro) {
            // 2. Limpa vínculos antigos
            $sql_delete = "DELETE FROM evento_curso WHERE codigo_evento = $codigo_evento";
            mysqli_query($conexao, $sql_delete);

            // 3. Insere vínculos dos cursos existentes selecionados
            foreach ($cursos_selecionados as $codigo_curso) {
                $codigo_curso = (int)$codigo_curso;
                $vagas = isset($_POST['vagas'][$codigo_curso]) ? (int)$_POST['vagas'][$codigo_curso] : 50;
                $referencia = isset($_POST['referencia'][$codigo_curso]) ? protege_campo($_POST['referencia'][$codigo_curso]) : date('Y');

                $sql_insert = "INSERT INTO evento_curso (codigo_evento, codigo_curso, quantidade_vagas, referencia) 
                               VALUES ($codigo_evento, $codigo_curso, $vagas, '$referencia')";
                if (!mysqli_query($conexao, $sql_insert)) {
                    $erro = true;
                    $_SESSION['ultimo_erro_db'] = mysqli_error($conexao) . " (ao tentar vincular curso existente ID $codigo_curso)";
                }
            }

            // 4. Insere vínculos dos novos cursos inseridos
            foreach ($novos_ids_vagas_ref as $vinculo) {
                $new_id = $vinculo['id'];
                $new_vagas = $vinculo['vagas'];
                $new_ref = $vinculo['referencia'];

                $sql_insert = "INSERT INTO evento_curso (codigo_evento, codigo_curso, quantidade_vagas, referencia) 
                               VALUES ($codigo_evento, $new_id, $new_vagas, '$new_ref')";
                if (!mysqli_query($conexao, $sql_insert)) {
                    $erro = true;
                    $_SESSION['ultimo_erro_db'] = mysqli_error($conexao) . " (ao tentar vincular novo curso ID $new_id)";
                }
            }
        }

        if ($erro) {
            redireciona("eventos.php?pagina=1&erro=2");
        } else {
            redireciona("eventos.php?pagina=1&sucesso=2");
        }
        exit;
    }
}

// Ações GET
if (isset($_GET['sub_acao'])) {
    $sub_acao = campo_form_decodifica($_GET['sub_acao']);

    if ($sub_acao == "definir_ativo") {
        $codigo_evento = (int)campo_form_decodifica($_GET['codigo_evento']);
        $_SESSION["codigo_evento_acesso"] = $codigo_evento;

        // Atualiza no banco de dados do usuário para persistir no próximo login
        if (isset($_SESSION["codigo_usuario_acesso"])) {
            $codigo_usuario = (int)$_SESSION["codigo_usuario_acesso"];
            $sql_user_update = "UPDATE usuario SET codigo_evento = $codigo_evento WHERE codigo_usuario = $codigo_usuario";
            mysqli_query($conexao, $sql_user_update);
        }

        redireciona("eventos.php?pagina=1&sucesso=4");
        exit;
    }

    if ($sub_acao == "excluir") {
        $codigo_evento = (int)campo_form_decodifica($_GET['codigo_evento']);

        // Verifica se há inscrições associadas ao evento
        $sql_check = "SELECT COUNT(*) FROM inscricao_evento WHERE codigo_evento = $codigo_evento";
        $query_check = mysqli_query($conexao, $sql_check);
        $row_check = mysqli_fetch_row($query_check);

        if ($row_check[0] > 0) {
            redireciona("eventos.php?pagina=1&erro=1");
        } else {
            // Exclui cursos vinculados
            $sql_del_cursos = "DELETE FROM evento_curso WHERE codigo_evento = $codigo_evento";
            mysqli_query($conexao, $sql_del_cursos);

            // Exclui o evento
            $sql_del_evento = "DELETE FROM evento WHERE codigo_evento = $codigo_evento";
            $query_del = mysqli_query($conexao, $sql_del_evento);

            if ($query_del) {
                redireciona("eventos.php?pagina=1&sucesso=3");
            } else {
                redireciona("eventos.php?pagina=1&erro=2");
            }
        }
        exit;
    }
}

// Consulta todos os eventos
$sql_eventos = "SELECT evento.codigo_evento, evento.nome_evento, evento.data_inicial_evento, evento.data_final_evento, 
                       evento.local_evento, cidade.nome_cidade 
                FROM evento 
                JOIN cidade ON (evento.codigo_cidade = cidade.codigo_cidade) 
                ORDER BY evento.data_inicial_evento DESC";
$query_eventos = mysqli_query($conexao, $sql_eventos);

// Subtelas (Novo/Editar/Vincular)
$sub = isset($_GET['sub']) ? $_GET['sub'] : '';
$evento_editar = null;
$evento_vincular = null;

if ($sub == 'editar' && isset($_GET['codigo_evento'])) {
    $cod_evt = (int)campo_form_decodifica($_GET['codigo_evento']);
    $sql_edit = "SELECT * FROM evento WHERE codigo_evento = $cod_evt LIMIT 1";
    $query_edit = mysqli_query($conexao, $sql_edit);
    $evento_editar = mysqli_fetch_assoc($query_edit);
}

if ($sub == 'vincular' && isset($_GET['codigo_evento'])) {
    $cod_evt = (int)campo_form_decodifica($_GET['codigo_evento']);
    $sql_vin = "SELECT * FROM evento WHERE codigo_evento = $cod_evt LIMIT 1";
    $query_vin = mysqli_query($conexao, $sql_vin);
    $evento_vincular = mysqli_fetch_assoc($query_vin);

    // Consulta todos os temas
    $sql_consulta_tema = "SELECT codigo_tema_curso, descricao_tema_curso FROM tema_curso ORDER BY descricao_tema_curso ASC";
    $query_consulta_tema = mysqli_query($conexao, $sql_consulta_tema);
    $temas_opcoes = [];
    while ($r_tema = mysqli_fetch_assoc($query_consulta_tema)) {
        $temas_opcoes[] = $r_tema;
    }

    // Consulta todos os institutos
    $sql_consulta_institutos = "SELECT codigo_instituto, nome_instituto FROM instituto ORDER BY nome_instituto ASC";
    $query_consulta_institutos = mysqli_query($conexao, $sql_consulta_institutos);
    $institutos_opcoes = [];
    while ($r_inst = mysqli_fetch_assoc($query_consulta_institutos)) {
        $institutos_opcoes[] = $r_inst;
    }

    // Consulta todos os cursos cadastrados
    $sql_todos_cursos = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, tema_curso.descricao_tema_curso 
                         FROM curso 
                         JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
                         JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
                         ORDER BY nome_curso ASC";
    $query_todos_cursos = mysqli_query($conexao, $sql_todos_cursos);

    // Consulta cursos que já estão vinculados
    $sql_vinculados = "SELECT evento_curso.codigo_curso, curso.nome_curso, tema_curso.descricao_tema_curso, instituto.nome_instituto, 
                              evento_curso.quantidade_vagas, evento_curso.referencia 
                       FROM evento_curso 
                       JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
                       JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
                       JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
                       WHERE evento_curso.codigo_evento = $cod_evt
                       ORDER BY curso.nome_curso ASC";
    $query_vinculados = mysqli_query($conexao, $sql_vinculados);
    $cursos_vinculados_dados = [];
    while ($row = mysqli_fetch_assoc($query_vinculados)) {
        $cursos_vinculados_dados[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php include "site_mod_head_interno.php";?>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <?php include "site_mod_topo_interno.php";?> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <?php include "site_mod_menu.php";?>
    </div>
  </div>
</div>
<!-- /subnavbar -->

<div class="main">
  <div class="main-inner">
    <div class="container">
        
        <!-- Alertas de sucesso/erro -->
        <?php if ($mensagem_sucesso) { ?>
            <div class="alert alert-success" style="border-radius: 8px; margin-bottom: 20px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Sucesso!</strong> <?php echo $mensagem_sucesso; ?>
            </div>
        <?php } ?>
        <?php if ($mensagem_erro) { ?>
            <div class="alert alert-danger" style="border-radius: 8px; margin-bottom: 20px; background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Atenção!</strong> <?php echo $mensagem_erro; ?>
                <?php if (isset($_SESSION['ultimo_erro_db']) && $_SESSION['ultimo_erro_db']) { 
                    echo "<br><span style='font-size: 11px; font-weight: 500;'>Erro técnico do Banco de Dados: " . htmlspecialchars($_SESSION['ultimo_erro_db']) . "</span>";
                    unset($_SESSION['ultimo_erro_db']);
                } ?>
            </div>
        <?php } ?>

        <?php if ($sub == 'novo' || $sub == 'editar') { 
            // Query Cidades para o select dropdown
            $sql_cidades = "SELECT codigo_cidade, nome_cidade FROM cidade ORDER BY nome_cidade ASC";
            $query_cidades = mysqli_query($conexao, $sql_cidades);
            
            $titulo_tela = ($sub == 'editar') ? "Editar Evento: " . $evento_editar['nome_evento'] : "Cadastrar Novo Evento";
            $btn_label = ($sub == 'editar') ? "Atualizar Evento" : "Salvar Evento";
            
            $val_codigo = ($sub == 'editar') ? $evento_editar['codigo_evento'] : 0;
            $val_nome = ($sub == 'editar') ? htmlspecialchars($evento_editar['nome_evento']) : '';
            $val_data_ini = ($sub == 'editar') ? $evento_editar['data_inicial_evento'] : '';
            $val_data_fim = ($sub == 'editar') ? $evento_editar['data_final_evento'] : '';
            $val_local = ($sub == 'editar') ? htmlspecialchars($evento_editar['local_evento']) : '';
            $val_cidade = ($sub == 'editar') ? (int)$evento_editar['codigo_cidade'] : 0;
            $val_desc = ($sub == 'editar') ? htmlspecialchars($evento_editar['descricao_evento']) : '';
        ?>
            <!-- Form Novo / Editar Evento -->
            <div class="widget">
                <div class="widget-header">
                    <i class="icon-calendar"></i>
                    <h3><?php echo $titulo_tela; ?></h3>
                </div>
                <div class="widget-content">
                    <form action="eventos.php" method="post" class="form-horizontal">
                        <input type="hidden" name="codigo_evento" value="<?php echo $val_codigo; ?>">
                        
                        <div class="control-group">
                            <label class="control-label" for="nome_evento">Nome do Evento</label>
                            <div class="controls">
                                <input type="text" class="span8" id="nome_evento" name="nome_evento" value="<?php echo $val_nome; ?>" placeholder="Ex: 4069º Encontro Fraterno Auta de Souza" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="data_inicial_evento">Data de Início</label>
                            <div class="controls">
                                <input type="date" id="data_inicial_evento" name="data_inicial_evento" value="<?php echo $val_data_ini; ?>" required style="height: 38px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="data_final_evento">Data de Término</label>
                            <div class="controls">
                                <input type="date" id="data_final_evento" name="data_final_evento" value="<?php echo $val_data_fim; ?>" required style="height: 38px;">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="local_evento">Local de Realização</label>
                            <div class="controls">
                                <input type="text" class="span6" id="local_evento" name="local_evento" value="<?php echo $val_local; ?>" placeholder="Ex: Escola Estadual Fernando Leite">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="codigo_cidade">Cidade</label>
                            <div class="controls">
                                <select id="codigo_cidade" name="codigo_cidade" required style="width: 320px; height: 38px;">
                                    <option value="">Selecione uma Cidade...</option>
                                    <?php while($cid = mysqli_fetch_assoc($query_cidades)) { 
                                        $selected = ($cid['codigo_cidade'] == $val_cidade) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $cid['codigo_cidade']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($cid['nome_cidade']); ?> (MT)</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="descricao_evento">Descrição / Observações</label>
                            <div class="controls">
                                <textarea class="span8" id="descricao_evento" name="descricao_evento" rows="4" placeholder="Algum detalhe adicional sobre o evento..."><?php echo $val_desc; ?></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("salvar_evento"); ?>">
                            <button type="submit" class="btn btn-primary"><?php echo $btn_label; ?></button>
                            <a href="eventos.php?pagina=1" class="btn" style="margin-left: 8px;">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>

        <?php } elseif ($sub == 'vincular' && $evento_vincular) { ?>
            <!-- Vincular Cursos Form -->
            <div class="widget">
                <div class="widget-header">
                    <i class="icon-book"></i>
                    <h3>Vincular Cursos ao Evento: <strong><?php echo htmlspecialchars($evento_vincular['nome_evento']); ?></strong></h3>
                </div>
                <div class="widget-content">
                    
                    <!-- Bloco de Busca / Inclusão de Curso -->
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                        <h4 style="margin-top: 0; margin-bottom: 16px; color: #1e293b; font-size: 14px; font-weight: 600;">Adicionar Curso ao Evento</h4>
                        
                        <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: center;">
                            <!-- Dropdown de Busca de Cursos Existentes -->
                            <select id="busca_curso" style="width: 550px; height: 38px; margin: 0 !important;">
                                <option value="">Pesquisar curso existente...</option>
                                <?php 
                                mysqli_data_seek($query_todos_cursos, 0);
                                while($cur_t = mysqli_fetch_assoc($query_todos_cursos)) { 
                                    $info_ext = htmlspecialchars($cur_t['nome_curso']) . " - " . htmlspecialchars($cur_t['nome_instituto']) . " (" . htmlspecialchars($cur_t['descricao_tema_curso']) . ")";
                                ?>
                                    <option value="<?php echo $cur_t['codigo_curso']; ?>"><?php echo $info_ext; ?></option>
                                <?php } ?>
                            </select>
                            
                            <button type="button" class="btn btn-primary" id="btn-add-existente"><i class="icon-plus"></i> Adicionar Curso</button>
                            <button type="button" class="btn btn-success" id="btn-toggle-novo"><i class="icon-edit"></i> Criar Novo Curso</button>
                        </div>

                        <!-- Form de Criação de Novo Curso (Inicialmente oculto) -->
                        <div id="form-novo-curso" style="display: none; border-top: 1px solid #e2e8f0; margin-top: 20px; padding-top: 20px;">
                            <h5 style="margin-top: 0; margin-bottom: 12px; font-weight: 600; color: #0d9488;">Cadastrar Novo Curso e Incluir na Lista</h5>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Nome do Curso</label>
                                    <input type="text" id="novo_nome_curso" placeholder="Ex: Fluidoterapia à Luz do Espiritismo" style="width: 100% !important; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Instituto</label>
                                    <select id="novo_codigo_instituto" style="width: 100% !important; height: 38px;">
                                        <option value="">Selecione o Instituto...</option>
                                        <?php foreach ($institutos_opcoes as $inst_op) { ?>
                                            <option value="<?php echo $inst_op['codigo_instituto']; ?>"><?php echo htmlspecialchars($inst_op['nome_instituto']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1.2fr 0.4fr 0.4fr; gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Tema / Faixa Etária</label>
                                    <select id="novo_codigo_tema_curso" style="width: 100% !important; height: 38px;">
                                        <option value="">Selecione o Tema...</option>
                                        <?php foreach ($temas_opcoes as $tema_op) { ?>
                                            <option value="<?php echo $tema_op['codigo_tema_curso']; ?>"><?php echo htmlspecialchars($tema_op['descricao_tema_curso']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Vagas</label>
                                    <input type="number" id="novo_vagas_curso" value="50" min="1" style="width: 100% !important; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Referência</label>
                                    <input type="text" id="novo_ref_curso" value="<?php echo date('Y'); ?>" style="width: 100% !important; box-sizing: border-box;">
                                </div>
                            </div>
                            
                            <div style="text-align: right;">
                                <button type="button" class="btn btn-success" id="btn-confirmar-novo">Confirmar e Incluir</button>
                                <button type="button" class="btn btn-link" id="btn-cancelar-novo" style="color: #64748b;">Cancelar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de Cursos Selecionados -->
                    <form action="eventos.php" method="post" id="form-vincular">
                        <input type="hidden" name="codigo_evento" value="<?php echo $evento_vincular['codigo_evento']; ?>">
                        
                        <table class="table table-striped table-bordered" id="tabela-vinculados">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Tipo</th>
                                    <th>Nome do Curso</th>
                                    <th>Tema / Idade</th>
                                    <th>Instituto</th>
                                    <th style="width: 100px;">Vagas</th>
                                    <th style="width: 100px;">Referência</th>
                                    <th style="width: 40px; text-align: center;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (empty($cursos_vinculados_dados)) { ?>
                                    <tr id="linha-sem-cursos">
                                        <td colspan="7" style="text-align: center; color: #64748b; padding: 24px; font-style: italic;">
                                            Nenhum curso selecionado para este evento. Use as opções acima para buscar ou criar um novo.
                                        </td>
                                    </tr>
                                <?php } else {
                                    foreach ($cursos_vinculados_dados as $cur_vin) { 
                                        $codigo_curso = $cur_vin['codigo_curso'];
                                    ?>
                                        <tr id="row-curso-<?php echo $codigo_curso; ?>">
                                            <td style="vertical-align: middle;">
                                                <span class="label label-info" style="padding: 4px 8px; border-radius: 4px;">Existente</span>
                                                <input type="hidden" name="cursos_selecionados[]" value="<?php echo $codigo_curso; ?>">
                                            </td>
                                            <td style="font-weight: 600; vertical-align: middle;">
                                                <?php echo htmlspecialchars($cur_vin['nome_curso']); ?>
                                            </td>
                                            <td style="vertical-align: middle;"><?php echo htmlspecialchars($cur_vin['descricao_tema_curso']); ?></td>
                                            <td style="vertical-align: middle;"><?php echo htmlspecialchars($cur_vin['nome_instituto']); ?></td>
                                            <td>
                                                <input type="number" name="vagas[<?php echo $codigo_curso; ?>]" value="<?php echo $cur_vin['quantidade_vagas']; ?>" min="1" class="span1" style="margin: 0 !important; width: 80px !important;">
                                            </td>
                                            <td>
                                                <input type="text" name="referencia[<?php echo $codigo_curso; ?>]" value="<?php echo htmlspecialchars($cur_vin['referencia']); ?>" class="span1" style="margin: 0 !important; width: 80px !important;">
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <button type="button" class="btn btn-danger btn-mini" onclick="removerLinha('row-curso-<?php echo $codigo_curso; ?>')" style="padding: 4px 8px !important;"><i class="icon-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>

                        <div class="form-actions" style="margin-top: 24px; padding-left: 0; text-align: right;">
                            <input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("salvar_vinculos"); ?>">
                            <button type="submit" class="btn btn-primary">Salvar Vínculos</button>
                            <a href="eventos.php?pagina=1" class="btn" style="margin-left: 8px;">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Banco de dados de cursos em Javascript para inclusão dinâmica -->
            <script>
            const bancoCursos = {
                <?php 
                mysqli_data_seek($query_todos_cursos, 0);
                while($c_js = mysqli_fetch_assoc($query_todos_cursos)) {
                    echo $c_js['codigo_curso'] . ": {
                        nome: " . json_encode($c_js['nome_curso']) . ",
                        tema: " . json_encode($c_js['descricao_tema_curso']) . ",
                        instituto: " . json_encode($c_js['nome_instituto']) . "
                    },\n";
                }
                ?>
            };

            let novoCursoIndex = 0;

            $(document).ready(function() {
                // Alternar visualização do formulário de novo curso
                $('#btn-toggle-novo').click(function() {
                    $('#form-novo-curso').slideToggle();
                    $('#busca_curso').val('');
                });

                $('#btn-cancelar-novo').click(function() {
                    $('#form-novo-curso').slideUp();
                    limparFormNovo();
                });

                // Adicionar curso existente selecionado no dropdown
                $('#btn-add-existente').click(function() {
                    const codCurso = $('#busca_curso').val();
                    if (!codCurso) {
                        alert('Selecione um curso existente na lista.');
                        return;
                    }

                    // Verifica se já está na tabela
                    if ($('#row-curso-' + codCurso).length > 0) {
                        alert('Este curso já está adicionado na lista.');
                        return;
                    }

                    const dados = bancoCursos[codCurso];
                    if (dados) {
                        // Oculta aviso de lista vazia
                        $('#linha-sem-cursos').remove();

                        const currentYear = new Date().getFullYear();
                        const rowHtml = `
                            <tr id="row-curso-${codCurso}">
                                <td style="vertical-align: middle;">
                                    <span class="label label-info" style="padding: 4px 8px; border-radius: 4px;">Existente</span>
                                    <input type="hidden" name="cursos_selecionados[]" value="${codCurso}">
                                </td>
                                <td style="font-weight: 600; vertical-align: middle;">${escaparHtml(dados.nome)}</td>
                                <td style="vertical-align: middle;">${escaparHtml(dados.tema)}</td>
                                <td style="vertical-align: middle;">${escaparHtml(dados.instituto)}</td>
                                <td>
                                    <input type="number" name="vagas[${codCurso}]" value="50" min="1" class="span1" style="margin: 0 !important; width: 80px !important;">
                                </td>
                                <td>
                                    <input type="text" name="referencia[${codCurso}]" value="${currentYear}" class="span1" style="margin: 0 !important; width: 80px !important;">
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <button type="button" class="btn btn-danger btn-mini" onclick="removerLinha('row-curso-${codCurso}')" style="padding: 4px 8px !important;"><i class="icon-trash"></i></button>
                                </td>
                            </tr>
                        `;
                        $('#tabela-vinculados tbody').append(rowHtml);
                        $('#busca_curso').val(''); // Limpa busca
                    }
                });

                // Confirmar e incluir novo curso na lista temporária
                $('#btn-confirmar-novo').click(function() {
                    const nome = $.trim($('#novo_nome_curso').val());
                    const instId = $('#novo_codigo_instituto').val();
                    const instNome = $('#novo_codigo_instituto option:selected').text();
                    const temaId = $('#novo_codigo_tema_curso').val();
                    const temaNome = $('#novo_codigo_tema_curso option:selected').text();
                    const vagas = $('#novo_vagas_curso').val();
                    const ref = $.trim($('#novo_ref_curso').val());

                    if (!nome || !instId || !temaId || !vagas || !ref) {
                        alert('Por favor, preencha todos os campos do novo curso.');
                        return;
                    }

                    // Oculta aviso de lista vazia
                    $('#linha-sem-cursos').remove();

                    // Cria linha dinâmica
                    const idx = novoCursoIndex++;
                    const rowHtml = `
                        <tr id="row-novo-${idx}">
                            <td style="vertical-align: middle;">
                                <span class="label label-success" style="padding: 4px 8px; border-radius: 4px;">Novo</span>
                                <input type="hidden" name="novos_cursos[${idx}][nome]" value="${escaparAttr(nome)}">
                                <input type="hidden" name="novos_cursos[${idx}][codigo_instituto]" value="${instId}">
                                <input type="hidden" name="novos_cursos[${idx}][codigo_tema_curso]" value="${temaId}">
                            </td>
                            <td style="font-weight: 600; vertical-align: middle; color: #0d9488;">${escaparHtml(nome)}</td>
                            <td style="vertical-align: middle;">${escaparHtml(temaNome)}</td>
                            <td style="vertical-align: middle;">${escaparHtml(instNome)}</td>
                            <td>
                                <input type="number" name="novos_cursos[${idx}][vagas]" value="${vagas}" class="span1" style="margin: 0 !important; width: 80px !important;">
                            </td>
                            <td>
                                <input type="text" name="novos_cursos[${idx}][referencia]" value="${escaparAttr(ref)}" class="span1" style="margin: 0 !important; width: 80px !important;">
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <button type="button" class="btn btn-danger btn-mini" onclick="removerLinha('row-novo-${idx}')" style="padding: 4px 8px !important;"><i class="icon-trash"></i></button>
                            </td>
                        </tr>
                    `;

                    $('#tabela-vinculados tbody').append(rowHtml);
                    limparFormNovo();
                    $('#form-novo-curso').slideUp();
                });
            });

            // Remover linha da tabela
            function removerLinha(rowId) {
                $('#' + rowId).remove();
                
                // Se a tabela ficou vazia, mostra o aviso
                if ($('#tabela-vinculados tbody tr').length === 0) {
                    const emptyRow = `
                        <tr id="linha-sem-cursos">
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 24px; font-style: italic;">
                                Nenhum curso selecionado para este evento. Use as opções acima para buscar ou criar um novo.
                            </td>
                        </tr>
                    `;
                    $('#tabela-vinculados tbody').append(emptyRow);
                }
            }

            // Helpers para sanitização de HTML
            function escaparHtml(string) {
                return String(string).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
            function escaparAttr(string) {
                return String(string).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            }

            function limparFormNovo() {
                $('#novo_nome_curso').val('');
                $('#novo_codigo_instituto').val('');
                $('#novo_codigo_tema_curso').val('');
                $('#novo_vagas_curso').val('50');
                $('#novo_ref_curso').val(new Date().getFullYear());
            }
            </script>

        <?php } else { ?>
            <!-- Event Listing (Default view) -->
            <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1e293b; margin: 0;">Gestão de Eventos</h2>
                <a href="eventos.php?pagina=1&sub=novo" class="btn btn-primary"><i class="icon-plus" style="margin-right: 6px;"></i> Cadastrar Evento</a>
            </div>

            <div class="widget">
                <div class="widget-header">
                    <i class="icon-calendar"></i>
                    <h3>Eventos do Sistema</h3>
                </div>
                <div class="widget-content" style="padding: 0;">
                    <table class="table table-striped table-bordered" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>ID</th>
                                <th>Nome do Evento</th>
                                <th>Período</th>
                                <th>Local / Cidade</th>
                                <th>Cursos</th>
                                <th style="width: 200px; text-align: right;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query_eventos)) { 
                                $is_active = ($row['codigo_evento'] == $_SESSION["codigo_evento_acesso"]);
                                
                                // Conta quantos cursos estão vinculados a este evento
                                $evt_id = $row['codigo_evento'];
                                $sql_cnt = "SELECT COUNT(*) FROM evento_curso WHERE codigo_evento = $evt_id";
                                $query_cnt = mysqli_query($conexao, $sql_cnt);
                                $row_cnt = mysqli_fetch_row($query_cnt);
                                $total_cursos = $row_cnt[0];
                            ?>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php if ($is_active) { ?>
                                            <span class="label label-success" style="background-color: #0d9488; padding: 4px 8px; border-radius: 4px; font-weight: 600;">ATIVO</span>
                                        <?php } else { ?>
                                            <a class="btn btn-mini btn-link" href="eventos.php?pagina=1&sub_acao=<?php echo campo_form_codifica("definir_ativo"); ?>&codigo_evento=<?php echo campo_form_codifica($row['codigo_evento']); ?>" style="padding: 2px 6px; font-weight: 600; color: #475569; text-decoration: none;">Ativar</a>
                                        <?php } ?>
                                    </td>
                                    <td style="vertical-align: middle;"><?php echo $row['codigo_evento']; ?></td>
                                    <td style="font-weight: 600; vertical-align: middle; color: #1e293b;">
                                        <?php echo htmlspecialchars($row['nome_evento']); ?><br>
                                        <small style="font-weight: normal; font-size: 11px; margin-top: 4px; display: inline-block;">
                                            <span style="color: #64748b;">Links rápidos:</span>
                                            <a href="../?evento=<?php echo $row['codigo_evento']; ?>" target="_blank" style="color: #0d9488; margin-left: 8px; font-weight: 500;"><i class="icon-external-link"></i> Portal de Inscrição</a>
                                            <a href="./?evento=<?php echo $row['codigo_evento']; ?>" style="color: #1e3a8a; margin-left: 12px; font-weight: 500;"><i class="icon-cog"></i> Ativar na Secretaria</a>
                                        </small>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <?php 
                                            $d_ini = date("d/m/Y", strtotime($row['data_inicial_evento']));
                                            $d_fim = date("d/m/Y", strtotime($row['data_final_evento']));
                                            echo $d_ini . " a " . $d_fim;
                                        ?>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <?php echo htmlspecialchars($row['local_evento']); ?> <br>
                                        <small style="color: #64748b; font-weight: 400;"><?php echo htmlspecialchars($row['nome_cidade']); ?> (MT)</small>
                                    </td>
                                    <td style="vertical-align: middle; font-weight: 600; color: #0d9488;">
                                        <?php echo $total_cursos; ?>
                                    </td>
                                    <td class="td-actions" style="vertical-align: middle;">
                                        <a href="eventos.php?pagina=1&sub=vincular&codigo_evento=<?php echo campo_form_codifica($row['codigo_evento']); ?>" class="btn" title="Vincular Cursos ao Evento">
                                            <i class="icon-book" style="margin-right: 4px;"></i> Cursos
                                        </a>
                                        <a href="eventos.php?pagina=1&sub=editar&codigo_evento=<?php echo campo_form_codifica($row['codigo_evento']); ?>" class="btn" title="Editar Evento">
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a href="eventos.php?pagina=1&sub_acao=<?php echo campo_form_codifica("excluir"); ?>&codigo_evento=<?php echo campo_form_codifica($row['codigo_evento']); ?>" class="btn btn-danger" onclick="return confirm('Deseja realmente excluir este evento? Esta ação apagará todos os vínculos de cursos vinculados a ele.');" title="Excluir Evento" style="background-color: #fee2e2 !important; border-color: #fecaca !important; color: #b91c1c !important;">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        
    </div>
  </div>
</div>

<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <?php include "site_mod_rodape.php";?>
      </div>
    </div>
  </div>
</div>
<?php
mysqli_free_result($query_eventos);
fecha_mysql($conexao);
?>
<script src="js/bootstrap.js"></script>
</body>
</html>
