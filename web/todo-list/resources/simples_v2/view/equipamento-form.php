<?php
require_once(__DIR__."/../class/System.php");


$ativacao = (int)$_GET['ativacao'];
if($_GET['id_contrato']){
    $id_contrato_plano_pessoa = (int)$_GET['id_contrato'];
}else{
    $id_contrato_plano_pessoa = (int)$_POST['id_contrato_plano_pessoa'];
}

if($ativacao == 1){
    $focus = "autofocus";
}

$verifica_contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
if(!$verifica_contrato && $ativacao == 1){
    echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
    exit;
}

if(isset($_GET['alterar'])){

    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_catalogo_equipamento_qi_contrato = $id");

    if($dados){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';

        $id_contrato = $dados[0]['id_contrato_plano_pessoa'];

        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

        $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }else{

        echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=quadro-informativo'>Clique para voltar.</a></div>";
        exit;
    }
    
}else {
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $configuracao = '';
    $id_pessoa = '';
    $observacao = '';

    if($ativacao == 1){
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        
        if($dados_contrato[0]['nome_contrato']){
            $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
        }

       $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
    }
}
?>
<div class="container-fluid">

    <?php
    if($ativacao):
        $dados_sistema_gestao_contrato_li = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa ORDER BY id_sistema_gestao_contrato desc");
        $id_sistema_gestao_contrato_li = $dados_sistema_gestao_contrato_li[0]['id_sistema_gestao_contrato'];
        echo "<input type='hidden' id='id_sistema_gestao_contrato_li' name='pular' value='$id_sistema_gestao_contrato_li' />";

        $dados_sistema_chat_contrato_li = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_sistema_chat_contrato_li = $dados_sistema_chat_contrato_li[0]['id_sistema_chat_contrato'];
        echo "<input type='hidden' id='id_sistema_chat_contrato_li' name='pular' value='$id_sistema_chat_contrato_li' />";

        $dados_informacao_geral_contrato_li = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_informacao_geral_contrato_li = $dados_informacao_geral_contrato_li[0]['id_informacao_geral_contrato'];
        echo "<input type='hidden' id='id_dados_informacao_geral_contrato_li' name='pular' value='$id_dados_informacao_geral_contrato_li' />";

        $dados_localizacao_contrato_li = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_localizacao_contrato_li = $dados_localizacao_contrato_li[0]['id_localizacao_contrato'];
        echo "<input type='hidden' id='id_dados_localizacao_contrato_li' name='pular' value='$id_dados_localizacao_contrato_li' />";

        $dados_plantonista_contrato_li = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_plantonista_contrato_li = $dados_plantonista_contrato_li[0]['id_plantonista_contrato'];
        echo "<input type='hidden' id='id_dados_plantonista_contrato_li' name='pular' value='$id_dados_plantonista_contrato_li' />";

        $dados_horario_contrato_li = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 1");
        $id_dados_horario_contrato_li = $dados_horario_contrato_li[0]['id_horario_contrato'];
        echo "<input type='hidden' id='id_dados_horario_contrato_li' name='pular' value='$id_dados_horario_contrato_li' />";

        $dados_prazo_retorno_contrato_li = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 1");
        $id_dados_prazo_retorno_contrato_li = $dados_prazo_retorno_contrato_li[0]['id_prazo_retorno_contrato'];
        echo "<input type='hidden' id='id_dados_prazo_retorno_contrato_li' name='pular' value='$id_dados_prazo_retorno_contrato_li' />";

        $dados_configuracao_roteadores_contrato_li = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_configuracao_roteadores_contrato_li = $dados_configuracao_roteadores_contrato_li[0]['id_configuracao_roteadores_contrato'];
        echo "<input type='hidden' id='id_dados_configuracao_roteadores_contrato_li' name='pular' value='$id_dados_configuracao_roteadores_contrato_li' />";

        $dados_equipamento_li = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_equipamento_li = $id_dados_equipamento_li[0]['id_catalogo_equipamento_qi_contrato'];
        echo "<input type='hidden' id='id_dados_equipamento_li' name='pular' value='$id_dados_equipamento_li' />";

        $dados_reinicio_equipamento_contrato_li = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_reinicio_equipamento_contrato_li = $dados_reinicio_equipamento_contrato_li[0]['id_reinicio_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_reinicio_equipamento_contrato_li' name='pular' value='$id_dados_reinicio_equipamento_contrato_li' />";

        $dados_equipamento_contrato_li = DBRead('', 'tb_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_equipamento_contrato_li = $dados_equipamento_contrato_li[0]['id_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_equipamento_contrato_li' name='pular' value='$id_dados_equipamento_contrato_li' />";

        $dados_sinal_equipamento_contrato_li = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_sinal_equipamento_contrato_li = $dados_sinal_equipamento_contrato_li[0]['id_sinal_equipamento_contrato'];
        echo "<input type='hidden' id='id_dados_sinal_equipamento_contrato_li' name='pular' value='$id_dados_sinal_equipamento_contrato_li' />";

        $dados_velocidade_minima_encaminhar_contrato_li = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_velocidade_minima_encaminhar_contrato_li = $dados_velocidade_minima_encaminhar_contrato_li[0]['id_velocidade_minima_encaminhar_contrato'];
        echo "<input type='hidden' id='id_dados_velocidade_minima_encaminhar_contrato_li' name='pular' value='$id_dados_velocidade_minima_encaminhar_contrato_li' />";

        $dados_parametros_li = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_parametros_li = $dados_parametros_li[0]['id_parametros'];
        echo "<input type='hidden' id='id_dados_parametros_li' name='pular' value='$id_dados_parametros_li' />";

        $dados_ura_contrato_li = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_ura_contrato_li = $dados_ura_contrato_li[0]['id_ura_contrato'];
        echo "<input type='hidden' id='id_dados_ura_contrato_li' name='pular' value='$id_dados_ura_contrato_li' />";

        $dados_manual_contrato_li = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_manual_contrato_li = $dados_manual_contrato_li[0]['id_manual_contrato'];
        echo "<input type='hidden' id='id_dados_manual_contrato_li' name='pular' value='$id_dados_manual_contrato_li' />";

        $dados_plano_cliente_contrato = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_dados_plano_cliente_contrato = $dados_plano_cliente_contrato[0]['id_plano_cliente_contrato'];
        echo "<input type='hidden' id='id_dados_plano_cliente_contrato' name='pular' value='$id_dados_plano_cliente_contrato' />";
    ?>
    <ol class="breadcrumb">
        <li class="colorir-breadcrumbs-success"><a id="li_sistema_gestao" style="cursor: pointer; color: #3c763d;">Sistema de gestão</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_sistema_chat" style="cursor: pointer; color: #3c763d;">Sistema de chat</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_informacao_geral" style="cursor: pointer; color: #3c763d;">Informações gerais e de registro</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_localizacao" style="cursor: pointer; color: #3c763d;">Localização</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_plantonista" style="cursor: pointer; color: #3c763d;">Plantonistas</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_horario" style="cursor: pointer; color: #3c763d;">Horários</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_prazo_retorno" style="cursor: pointer; color: #3c763d;">Prazos de retorno</a></li>
        <li class="colorir-breadcrumbs-success"><a id="li_conexao_cabo" style="cursor: pointer; color: #3c763d;">Conexões de cabos</a></li>
        <li class="active"><a id="li_equipamento" style="cursor: pointer;"><strong>Equipamentos</strong></a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_tempo_reinicio" style="cursor: pointer;">Tempo de reinicio de equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_acesso_equipamento" style="cursor: pointer;">Acesso a equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_sinal_equipamento" style="cursor: pointer;">Sinais dos equipamentos</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_velocidade_encaminhamento" style="cursor: pointer;">Velocidade mínima para encaminhamento</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_parametro" style="cursor: pointer;">Parâmetros</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_ura" style="cursor: pointer;">URA</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_manual" style="cursor: pointer;">Manual</a></li>
        <li class="colorir-breadcrumbs-info"><a id="li_plano" style="cursor: pointer;">Planos</a></li>
    </ol>
    <?php
    endif;
    ?>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> equipamento:</h3>
                    <?php if (isset($_GET['alterar'])) {

                        if($ativacao == 1){
                            $exclui_ativacao = 1;
                        }else{
                            $exclui_ativacao = 0;
                        }

                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=Equipamento.php?excluir= $id&exclui-ativacao=$exclui_ativacao&id-contrato=$id_contrato_plano_pessoa&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } ?>
                </div>
                <form method="post" action="/api/ajax?class=Equipamento.php" id="equipamento_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">

                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Contrato (cliente):</label>
                                    <?php
                                    if($ativacao == 1){
                                        echo "<div class='page-header header-plano-ativacao'>";
                                            echo "<h4>$contrato</h4>";
                                        echo "</div>";
                                    }else{
                                    ?>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    if($operacao == 'alterar'){
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato' />";
                                    }else{
                                        echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">Equipamentos vinculados:</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-6'>*Marca</th>
                                                        <th class='col-md-5'>*Modelo</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $dados_catalogo_equipamento_marca = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE status = 1 ORDER BY nome ASC");
                                                        
                                                    if ($dados_catalogo_equipamento_marca) {
                                                        $cont = 0;

                                                        foreach ($dados_catalogo_equipamento_marca as $conteudo_catalogo_equipamento_marca) {
                                                            if($cont == 0){
                                                                $auxiliar = $conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca'];
                                                                $select_equipamento_marca .= "<option value='' name='teste'>Selecione</option>";
                                                            }
                                                            $cont ++;
                                                            $select_equipamento_marca .="<option value='".$conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca']."'>".$conteudo_catalogo_equipamento_marca['nome']."</option>";
                                                        }
                                                    }

                                                    if($operacao == "alterar"){
                                                        $dados_catalogo_equipamento = DBRead('', 'tb_catalogo_equipamento_qi a', "INNER JOIN tb_catalogo_equipamento b ON a.id_catalogo_equipamento = b.id_catalogo_equipamento INNER JOIN tb_catalogo_equipamento_marca c ON b.id_catalogo_equipamento_marca = c.id_catalogo_equipamento_marca INNER JOIN tb_catalogo_equipamento_qi_contrato d ON a.id_catalogo_equipamento_qi_contrato = d.id_catalogo_equipamento_qi_contrato WHERE d.id_contrato_plano_pessoa = '".$id_contrato."' ORDER BY nome ASC");

                                                        foreach($dados_catalogo_equipamento as $conteudo){

                                                            echo "
                                                            <tr class='linha_equipamento'>
                                                                <td>
                                                                    <select class='form-control input-sm id_catalogo_equipamento_marca' name='id_catalogo_equipamento_marca[]' onchange='carregarModelo($(this));'>";
                                                                    $sel_tipo_equipamento_marca[$conteudo['id_catalogo_equipamento_marca']] = 'selected';
                                                                    foreach ($dados_catalogo_equipamento_marca as $conteudo_catalogo_equipamento_marca) {
                                                                        $selected = $conteudo['id_catalogo_equipamento_marca'] == $conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca'] ? "selected" : "";

                                                                        echo "<option value='".$conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca']."' ".$selected.">".$conteudo_catalogo_equipamento_marca['nome']."</option>";
                                                                    }
                                                                    echo 
                                                                    "</select>
                                                                </td>
                                                                <td>
                                                                    <select class='form-control input-sm id_catalogo_equipamento' name='id_catalogo_equipamento[]'>";
                                                                   
                                                                    $dados_catalogo_equipamento_qi = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento_marca = '".$conteudo['id_catalogo_equipamento_marca']."' ORDER BY modelo ASC");

                                                                    $sel_tipo_equipamento[$conteudo['id_catalogo_equipamento']] = 'selected';
                                                                    foreach ($dados_catalogo_equipamento_qi as $conteudo_catalogo_equipamento_marca) {
                                                                        $selected = $conteudo['id_catalogo_equipamento'] == $conteudo_catalogo_equipamento_marca['id_catalogo_equipamento'] ? "selected" : "";

                                                                        echo "<option value='".$conteudo_catalogo_equipamento_marca['id_catalogo_equipamento']."' ".$selected.">".$conteudo_catalogo_equipamento_marca['modelo']."</option>";
                                                                    }
                                                                    
                                                                    echo "
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <button class='center-block btn btn-danger btn-sm removeLinha' role='button'>
                                                                        <i class='fa fa-trash-o' aria-hidden='true'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>";

                                                           
                                                            
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-equipamento' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                <?php

                                $pagina = 'configuracao-roteadores-form';

                                if($ativacao == 1){

                                    $dados_pular = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                    $id_dados_pular = $dados_pular[0]['id_reinicio_equipamento_contrato'];

                                    $dados_voltar = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                                    $id_dados_voltar = $dados_voltar[0]['id_configuracao_roteadores_contrato'];

                                    echo "<input type='hidden' value='1' name='ativacao' />";
                                    
                                    echo "<input type='hidden' value='" . $pagina . "' name='pagina' />";

                                    echo "<input type='hidden' id='id_dados_voltar' name='voltar' value='$id_dados_voltar' />";
                                    echo "<button class='btn btn-primary btn-comando-ativacao' id='voltar' type='button'><i class='fa fa-arrow-left' aria-hidden='true'></i> Voltar</button>";

                                    echo "<button class='btn btn-primary btn-comando-ativacao' name='salvar' value='1' id='ok' type='submit'><i class='fa fa-arrow-right' aria-hidden='true'></i> Salvar e continuar</button>";

                                    echo "<input type='hidden' id='id_dados_pular' name='pular' value='$id_dados_pular' />";
                                    echo "<button class='btn btn-primary btn-comando-ativacao' id='pular' type='button'><i class='fa fa-share' aria-hidden='true'></i> Pular</button>";

                                }else{
                                    echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function carregarModelo(id_catalogo_equipamento_marca) {
        id_catalogo_equipamento_marca.find('option[name="teste"]').hide();
        var id = id_catalogo_equipamento_marca.val();

        $.ajax({
            url: "/api/ajax?class=EquipamentoMarcaBusca.php",
            dataType: "json",
            data: {
                parametros: {
                    'id_marca' : id,
                },
                token: '<?= $request->token ?>'
            },
            success: function (data) {
                id_catalogo_equipamento_marca.parent().parent().find("select[name='id_catalogo_equipamento[]']").html(data);

            }
        });
    }
    var idVoltar = $("#voltar").val();
    
    var idContrato = $("#id_contrato_plano_pessoa").val();
    var idDadosPular = $("#id_dados_pular").val();
    var idDadosVoltar = $("#id_dados_voltar").val();

    $('#pular').on('click', function(){
        if(idDadosPular){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&alterar="+idDadosPular+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }

    });
    $('#voltar').on('click', function(){

        if(idDadosVoltar){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&alterar="+idDadosVoltar+"&ativacao=1&id_contrato="+idContrato+"&tela=3";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&ativacao=1&id_contrato="+idContrato+"&tela=3";
        }
    });

    $("#adiciona-equipamento").on('click', function(){
        $("tbody").append("<tr class='linha_equipamento'><td><select class='form-control input-sm id_catalogo_equipamento_marca' name='id_catalogo_equipamento_marca[]' onchange='carregarModelo($(this));'><?=$select_equipamento_marca?></select></td><td><select class='form-control input-sm id_catalogo_equipamento' name='id_catalogo_equipamento[]'><option value=''>Selecione a marca...</option></select></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");

    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o equipamento?')){
            $(this).parent().parent().remove();
        }
        return false;
    });

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
            if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato() {
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function () {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });



    $(document).on('submit', '#equipamento_form', function(){

        var naoSalva = 0;
        var naoSalva2 = 0;
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();

        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == 0){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }
        if(!$("tr.linha_equipamento").length){
            alert("Deve haver pelo menos um modelo configurado!");
            return false;
        }

        $(".id_catalogo_equipamento").each(function(index_segundo){
            id_equipamento = $(this).val();
            if(id_equipamento == ''){
                ++naoSalva;
            }
        });

        $(".id_catalogo_equipamento").each(function(index_segundo){
            id_equipamento = $(this).val();
            
            if(id_equipamento == ''){
                ++naoSalva;
            }

            var cont = 0;
            $(".id_catalogo_equipamento").each(function(a){
                if($(this).val() == id_equipamento && $(this).val() != ''){
                   cont++;
                }
                if(cont > 1){
                    naoSalva2 = 1;
                }
                
            });
            
        });

        if(naoSalva >= 1){
            alert("Não é possível inserir um equipamento sem modelo!");
            return false;
        }

        if(naoSalva2 >= 1){
            alert("Não é possível inserir mais de um equipamento com o mesmo modelo!");
            return false;
        }

        modalAguarde();
    });

    var id_sistema_gestao_contrato_li = $("#id_sistema_gestao_contrato_li").val();
    var id_sistema_chat_contrato_li = $("#id_sistema_chat_contrato_li").val();
    var id_dados_informacao_geral_contrato_li = $("#id_dados_informacao_geral_contrato_li").val();
    var id_dados_localizacao_contrato_li = $("#id_dados_localizacao_contrato_li").val();
    var id_dados_plantonista_contrato_li = $("#id_dados_plantonista_contrato_li").val();
    var id_dados_horario_contrato_li = $("#id_dados_horario_contrato_li").val();
    var id_dados_prazo_retorno_contrato_li = $("#id_dados_prazo_retorno_contrato_li").val();
    var id_dados_configuracao_roteadores_contrato_li = $("#id_dados_configuracao_roteadores_contrato_li").val();
    var id_dados_equipamento_li = $("#id_dados_equipamento_li").val();
    var id_dados_reinicio_equipamento_contrato_li = $("#id_dados_reinicio_equipamento_contrato_li").val();
    var id_dados_equipamento_contrato_li = $("#id_dados_equipamento_contrato_li").val();
    var id_dados_sinal_equipamento_contrato_li = $("#id_dados_sinal_equipamento_contrato_li").val();
    var id_dados_velocidade_minima_encaminhar_contrato_li = $("#id_dados_velocidade_minima_encaminhar_contrato_li").val();
    var id_dados_parametros_li = $("#id_dados_parametros_li").val();
    var id_dados_ura_contrato_li = $("#id_dados_ura_contrato_li").val();
    var id_dados_manual_contrato_li = $("#id_dados_manual_contrato_li").val();
    var id_dados_plano_cliente_contrato = $("#id_dados_plano_cliente_contrato").val();


    $('#li_sistema_gestao').on('click', function(){
        if(id_sistema_gestao_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-gestao-form&alterar="+id_sistema_gestao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-gestao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sistema_chat').on('click', function(){
        if(id_sistema_chat_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-form&alterar="+id_sistema_chat_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_informacao_geral').on('click', function(){
        if(id_dados_informacao_geral_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&alterar="+id_dados_informacao_geral_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_localizacao').on('click', function(){
        if(id_dados_localizacao_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&alterar="+id_dados_localizacao_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=localizacao-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plantonista').on('click', function(){
        if(id_dados_plantonista_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&alterar="+id_dados_plantonista_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantonista-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_horario').on('click', function(){
        if(id_dados_horario_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=horario-form&alterar="+id_dados_horario_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=horario-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_prazo_retorno').on('click', function(){
        if(id_dados_prazo_retorno_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=prazo-retorno-form&alterar="+id_dados_prazo_retorno_contrato_li+"&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=prazo-retorno-form&ativacao=1&id_contrato="+idContrato+"&tela=1";
        }
    });

    $('#li_conexao_cabo').on('click', function(){
        if(id_dados_configuracao_roteadores_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&alterar="+id_dados_configuracao_roteadores_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_equipamento').on('click', function(){
        if(id_dados_equipamento_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=equipamento-form&alterar="+id_dados_configuracao_roteadores_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_tempo_reinicio').on('click', function(){
        if(id_dados_reinicio_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&alterar="+id_dados_reinicio_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_acesso_equipamento').on('click', function(){
        if(id_dados_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=acesso-equipamento-form&alterar="+id_dados_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=acesso-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_sinal_equipamento').on('click', function(){
        if(id_dados_sinal_equipamento_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sinal-equipamento-form&alterar="+id_dados_sinal_equipamento_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=sinal-equipamento-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_velocidade_encaminhamento').on('click', function(){
        if(id_dados_velocidade_minima_encaminhar_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&alterar="+id_dados_velocidade_minima_encaminhar_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_parametro').on('click', function(){
        if(id_dados_parametros_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=parametro-form&alterar="+id_dados_parametros_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=parametro-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_ura').on('click', function(){
        if(id_dados_ura_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&alterar="+id_dados_ura_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=ura-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_manual').on('click', function(){
        if(id_dados_manual_contrato_li){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=manual-form&alterar="+id_dados_manual_contrato_li+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=manual-form&ativacao=1&id_contrato="+idContrato;
        }
    });

    $('#li_plano').on('click', function(){
        if(id_dados_plano_cliente_contrato){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plano-cliente-form&alterar="+id_dados_plano_cliente_contrato+"&ativacao=1&id_contrato="+idContrato;
        }else{
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plano-cliente-form&ativacao=1&id_contrato="+idContrato;
        }
    });

</script>