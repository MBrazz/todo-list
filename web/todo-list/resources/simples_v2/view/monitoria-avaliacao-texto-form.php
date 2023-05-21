<?php
require_once(__DIR__."/../class/System.php");

$id_usuario_atendente = $_GET['id_usuario'];
$id_monitoria_mes = $_GET['id_monitoria_mes'];
$id_usuario_analista = $_SESSION['id_usuario'];
$id_atendimento = $_GET['id_atendimento'];

$verifica_avaliacao = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_atendimento = $id_atendimento AND considerar = 1", 'id_monitoria_avaliacao_texto');

if($verifica_avaliacao){
    
    echo '<div class="container-fluid">
            <div class="alert alert-danger text-center">
                <strong>Este atendimento já foi avaliado!</strong>
            </div
          </div>';
    
    die();
}

$verifica_plano_acao = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE id_monitoria_mes = $id_monitoria_mes", 'id_monitoria_mes_plano_acao_chamado');

if($verifica_plano_acao){
    
    echo '<div class="container-fluid">
            <div class="alert alert-danger text-center">
                <strong>Plano de ação para este formulário já foi gerado!</strong>
            </div
          </div>';
    
    die();
}

$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_atendente'", "b.nome");

$verifica = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes AND status = 1");
$data_referencia = $verifica[0]['data_referencia'];

$data = explode("-",$data_referencia);
$legenda_mes_referencia = $data[1].'/'.$data[0];

$tipo_monitoria = $verifica[0]['tipo_monitoria'];
$classificacao_atendente = $verifica[0]['classificacao_atendente'];

if ($classificacao_atendente == 1) {
    $legenda_classificacao = 'Em treinamento';

} else if ($classificacao_atendente == 2) {
    $legenda_classificacao = 'Período de experiência';

} else {
    $legenda_classificacao = 'Efetivado';
}

if ($tipo_monitoria == 1) {
    $legenda_monitoria = 'Via Telefone';

} else if ($tipo_monitoria == 2) {
    $legenda_monitoria = 'Via Texto';

} else {
    $legenda_monitoria = 'Efetivado';
}

$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id_usuario_atendente' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

$inicial_seg = $turno[0]['inicial_seg'];
$final_seg = $turno[0]['final_seg'];

if($inicial_seg > $final_seg){
    $hora1 = '2000-10-10 '.$inicial_seg.':00';
    $hora2 = '2000-10-11 '.$final_seg.':00';
    $data1 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora1)));
    $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($hora2)));
    $resultado = strtotime($data2) - strtotime($data1);

}else{
    $hora1 = strtotime(''.$inicial_seg.'');
    $hora2 = strtotime(''.$final_seg.'');
    $resultado = ($hora2-$hora1);
}

$h = ($resultado/(60*60))%24;

if($h >= 5){
    $turno = 'Integral';
}else{
    $turno = 'Meio turno';
}

?>

<style>
    .input-sm{
        min-width: 80px !important;
    }
    .quesito{
        min-width: 440px !important;
    }
    .passo{
        /* max-width: 26px !important; */
        min-width: 35px !important;
    }
    .pontos{
        /* max-width: 15px !important; */
        min-width: 35px !important;
    }
    .tirar{
        /* max-width: 10px !important; */
        min-width: 35px !important;
    }
    .hr-css {
      color: #e8e8e8;
      background-color: #e8e8e8;
      height: 4px;
    }
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <!-- row-->
                    <div class="row">

                        <!-- col-->
                        <div class="col-md-4">
                            <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Formulário de avaliação: <strong><?=$atendente[0]['nome']?></strong></h3>
                        </div>
                        <!--end col-->

                        <!-- col-->
                        <div class="col-md-4">
                            <h3 class="panel-title text-center">Mês referência: 
                                <strong><?=$legenda_mes_referencia?></strong>
                                (<?= $legenda_monitoria ?> - <?= $legenda_classificacao ?>)
                            </h3>
                        </div>
                        <!--end col-->

                        <!-- col-->
                        <div class="col-md-4">
                            <h3 class="panel-title pull-right">Carga horária: <strong><?=$turno?></strong></h3>
                        </div>
                        <!--end col-->
                    </div>
                    <!-- end row-->
                </div>
                <form method="post" action="/api/ajax?class=MonitoriaAvaliacaoTexto.php" id="monitoria_avaliacao_texto" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <!--panel-body-->
                    <div class="panel-body">

                        <?php
                            $dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento LEFT JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema LEFT JOIN tb_area_problema f ON e.id_area_problema = f.id_area_problema WHERE a.id_atendimento = $id_atendimento", "a.*, c.nome, e.descricao as descricao_subarea_problema, f.nome as descricao_area_problema");

                            $conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

                            $id_contrato_plano_pessoa = $dados_atendimento[0]['id_contrato_plano_pessoa'];
                
                            if ($conteudo_empresa[0]['nome_contrato']) {
                                $nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
                            } else {
                                $nome_contrato = '';
                            }
                
                            $contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;
                
                            $nome_empresa = $contrato;
                
                            if ($dados_atendimento[0]['fone2']) {
                                $legenda_telefone = $dados_atendimento[0]['fone1'].' | '.$dados_atendimento[0]['fone1'];
                            } else {
                                $legenda_telefone = $dados_atendimento[0]['fone1'];
                            }

                            $situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

                            if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
                                if ($dados_atendimento[0]['cpf_cnpj']) {
                                    $cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];
                                }
                            } else {
                                $cpf_cnpj = ' - - - - - - - - - - -';
                            }
                            
                            if ($dados_atendimento[0]['descricao_dado_adicional'] && $dados_atendimento[0]['dado_adicional']) {
                                $dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'].' - '. $dados_atendimento[0]['dado_adicional'];

                            } else {
                                $dado_adicional = ' - - - - - - - - - - -';
                            }

                            if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
                                $protocolo =  $dados_atendimento[0]['protocolo'];
                            } else {
                                $protocolo = ' - - - - - - - - - - -';
                            }

                            if ($dados_atendimento[0]['descricao_subarea_problema']) {
                                $sub_area_problema = $dados_atendimento[0]['descricao_subarea_problema'];

                            } else {
                                $sub_area_problema = ' - - - - - - - - - - -';
                            }

                            if ($dados_atendimento[0]['descricao_area_problema']) {
                                $area_problema = $dados_atendimento[0]['descricao_area_problema'];

                            } else {
                                $area_problema = ' - - - - - - - - - - -';
                            }

                            $os = $dados_atendimento[0]['os']. PHP_EOL .$dados_atendimento[0]['nome'];
                           
                        ?>

                        <br>
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">

                                <div class="row">
                                    <div class="col-md-4">
                                        <h3 class="panel-title text-left pull-left">Dados do atendimento:</h3>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <button id='cronometro' type="button" class="btn btn-default btn-xs" style="padding-left: 20px; padding-right: 20px;margin-left: 3px">00:00</button>
                                    </div>
                                    <div class="col-md-4 text-right pull-right">
                                        <button type="button" id="btn-submit-auditoria" class="btn btn-primary btn-xs">
                                            <i class="fa fa-eye"></i> Auditoria
                                        </button>
                                        <a class="btn btn-primary btn-xs" href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-texto-busca&id_usuario=<?=$id_usuario_atendente?>&id_monitoria_mes=<?=$id_monitoria_mes?>">
                                            <i class="fa fa-refresh"></i> Escolher outro atendimento
                                        </a>
                                        <button type="button" class="btn btn-danger btn-xs" id="desvincular_erro" style="display: none;">
                                            <i class="fa fa-close"></i> <span>Desvincular Reclamação/Erro</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" id="vincular_erro">
                                            <i class="fa fa-bug"></i> <span id="span_vincular_erro">Vincular Reclamação/erro</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-body">
                                
                                <!-- row-->
                                <div class="row">
                                    <!-- col-->
                                    <div class="col-md-12">
                                        <table class="table table-hover dataTable" style="margin-bottom: 0;">
                                            <thead> 
                                                <tr>
                                                <th>Data</th>
                                                <th>Empresa</th>
                                                <th>Protocolo</th>
                                                <th>Assinante</th>
                                                <th>CPF/CNPJ</th>
                                                <th>Dado adicional</th>
                                                <th>Contato</th>
                                                <th>Telefone (1/2)</th>
                                                <th>Área do problema</th>
                                                <th>Subarea do problema</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <tr>
                                                    <td><?=  converteDataHora($dados_atendimento[0]['data_fim']) ?></td>
                                                    <td><button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#sistemagestao"><?= $nome_empresa ?></button></td>
                                                    <td><?= $protocolo ?></td>
                                                    <td><?= $dados_atendimento[0]['assinante'] ?></td>
                                                    <td><?= $cpf_cnpj ?></td>
                                                    <td><?= $dado_adicional ?></td>
                                                    <td><?= $dados_atendimento[0]['contato'] ?></td>
                                                    <td><?= $legenda_telefone ?></td>
                                                    <td><?= $sub_area_problema ?></td>
                                                    <td><?= $area_problema ?></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                                <hr class="hr-css">

                                <!-- row-->
                                <div class="row">
                                    
                                    <!-- col-->
                                    <div class="col-md-12" style="padding-bottom: 40px; padding-left: 20px;">
                                        <label>OS:</label><br>
                                        <span><?= $os ?></span>
                                    </div>
                                    <!--end col-->

                                </div>
                                <!--end row-->  
                            </div>
                        </div>

                        <!-- row (table) -->
                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                    $dados_quesitos = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE a.id_monitoria_mes = '".$verifica[0]['id_monitoria_mes']."'");
                                ?>

                                <div class='table-responsive'>
                                    <table class='table table-bordered' style='font-size: 14px; background-color: #F2F2F2;' id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center passo">Passo</th>
                                                <th class="col-md-7 quesito">Quesito</th>
                                                <th class="pontos text-center">
                                                    <span style="color: green">Atendeu</span></th>
                                                <th class="tirar text-center"
                                                    ><span style="color: #B40404;">Não atendeu</span>
                                                </th>
                                                <th class="text-center">
                                                   <span>Avaliação</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($dados_quesitos as $conteudo){

                                                    if($conteudo['passo_atendimento'] == 1){
                                                        $cor = '#CEF6CE';
                                                    }else if($conteudo['passo_atendimento'] == 2){
                                                        $cor = '#A9F5A9';
                                                    }else if($conteudo['passo_atendimento'] == 3){
                                                        $cor = '#82FA58';
                                                    }else if($conteudo['passo_atendimento'] == 4){
                                                        $cor = '#2EFE64';
                                                    }else if($conteudo['passo_atendimento'] == 5){
                                                        $cor = '#088A4B';
                                                    }else if($conteudo['passo_atendimento'] == 6){
                                                        $cor = '#2E64FE';
                                                    }
                                            ?>
                                                    <tr>
                                                        <td class="text-center" style="background-color: <?=$cor?> ">
                                                            <strong><?=$conteudo['passo_atendimento']?></strong>
                                                        </td>
                                                        <td>
                                                            <?=$conteudo['descricao']?>
                                                        </td>
                                                        <td class="text-center">
                                                            <!-- <strong><?=$conteudo['pontos_valor']?></strong> -->
                                                            <button type="button" class="btn btn-default btn-sm pontos-manter" style="min-width: 100px !important;" attr-pm="<?=$conteudo['pontos_valor']?>">
                                                                <i class="fa fa-thumbs-o-up" aria-hidden="true" style="font-size: 16px; color:green;"></i>
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <!-- <strong>
                                                                <span style="color: #B40404;"><?=$conteudo['pontos_tirar']?>
                                                                </span>
                                                            </strong> -->
                                                           <button type="button" class="btn btn-default btn-sm pontos-tirar" style="min-width: 100px !important;" attr-pm="<?=$conteudo['pontos_valor']?>" attr-pt="<?=$conteudo['pontos_tirar']?>">
                                                                <i class="fa fa-thumbs-o-down" aria-hidden="true" style="font-size: 16px; color: #B40404;"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="quesitos[]" value="<?=$conteudo['id_monitoria_mes_quesito']?>">
                                                            <!-- <input type="hidden" class="form-control number_int input-sm pontos_resultado" name="pontos[]" type="text" value="" autocomplete="off" required>
                                                            <input type="text" class="form-control input-sm check" style="background-color: #FBFBEF;" value="Não avaliado!" readonly> -->
                                                            <input type="hidden" class="form-control number_int input-sm pontos_resultado" value="<?=$conteudo['pontos_valor']?>" name="pontos[]" type="text" autocomplete="off" required >
                                                            <input type="text" class="form-control input-sm check" style="background-color: #CEF6CE;" value="Atendeu!" readonly>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end row-->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observação:</label>
                                    <textarea name="obs_avaliacao" class="form-control" id="obs_avaliacao"  rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="hr-css">
                        <!-- row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <input type="checkbox" name="atendimento_encantador" id="atendimento_encantador" value="1">
                                    </span>
                                    <input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Atendimento encantandor" style="cursor: context-menu; background-color: white;">
                                    <span class="glyphicon glyphicon-star form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <input type="checkbox" name="cliente_irritado" id="cliente_irritado" value="1">
                                    </span>
                                    <input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Cliente irritado" style="cursor: context-menu; background-color: white;">
                                    <span class="fa fa-frown-o form-control-feedback" style="margin-top: 10px;"></span>
                                </div>
                            </div>
                        </div>
                        <!-- row-->

                        <input type="hidden" name="id_atendimento" id="id_atendimento" value="<?=$id_atendimento?>">
                        <input type="hidden" name="id_monitoria_mes" id="id_monitoria_mes" value="<?=$id_monitoria_mes?>">
                        <input type="hidden" name="data_referencia" id="data_referencia" value="<?=$data_referencia?>">
                        <input type="hidden" name="id_erro" id="id_erro" value="">
                        <input type="hidden" name="id_usuario_analista" id="id_usuario_analista" value="<?=$id_usuario_analista?>">
                        <input type="hidden" name="id_usuario_atendente" id="id_usuario_atendente" value="<?=$id_usuario_atendente?>">
                        <input type="hidden" name="duracao_avaliacao" id="duracao_avaliacao" value=""> <!-- salvar em segundos (INT) -->
                        
                    </div>
                    <!--end panel-body-->

                    <!--panel-footer-->
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="1" name="inserir"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit" onclick="if (!confirm('Tem certeza que deseja salvar esta avaliação?')) { return false; } else { }">
                                    <i class="fa fa-floppy-o"></i> Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--end panel-footer-->
                </form>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
<!--end container-fluid-->

<form action="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-atendimento" method="POST" target="_blank" id="form-auditoria">
    <?php
        $data_de_ate_auditoria = explode(' ', $dados_atendimento[0]['data_fim']);
    ?>
    <input type="hidden" name="gerar" value="1">
    <input type="hidden" name="tipo_relatorio" value="11">
    <input type="hidden" name="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa?>">
    <input type="hidden" name="data_de" value="<?=converteData($data_de_ate_auditoria[0])?>">
    <input type="hidden" name="data_ate" value="<?=converteData($data_de_ate_auditoria[0])?>">
    <input type="hidden" name="operador" value="<?=$id_usuario_atendente?>">
</form>

<script>
    $("#btn-submit-auditoria").click(function() {
        $("#form-auditoria").submit();
    });
</script>

<!-- Modal erros -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vincular Reclamação/Erro</h4>
      </div>
      <div class="modal-body">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <?php

                        $dataultimodia = new DateTime($data_referencia);
                        $dataultimodia->modify('last day of this month');

                        $dataprimeirodia = new DateTime(getDataHora());
                        $dataprimeirodia->modify('first day of this month');
                        
                        $erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.canal_atendimento = 2 AND a.status = 1 AND a.id_usuario = '$id_usuario_atendente' AND a.data_cadastrado >= '".$dataprimeirodia->format("Y-m-d")."' AND a.data_cadastrado <= '".$dataultimodia->format("Y-m-d")."' ORDER BY a.data_erro ASC", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');
                        
                        //var_dump($erros);
                        if($erros){
                            foreach($erros as $conteudo){

                                if($conteudo['protocolo'] == ''){
                                    $protocolo = 'N/D';
                                }else{
                                    $protocolo = $conteudo['protocolo'];
                                }
                        
                    ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3 class="panel-title"><strong><?=$conteudo['nome_empresa']?></strong></h3>
                                        </div>
                                        <div class="col-md-4">
                                            <h3 class="panel-title text-center">Protocolo: <?=$protocolo?></h3>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary btn-xs pull-right btn-vincular" id="<?=$conteudo['id_erro_atendimento']?>" >
                                            <i class="fa fa-bug"></i><span class="txt-vincular"> Vincular</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" style="margin-bottom: 8px;">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-3">Tipo Reclamação/Erro</th>
                                                    <th class="col-md-3">Criado por</th>
                                                    <th class="col-md-3">Assinante</th>
                                                    <th class="col-md-3">Data Reclamação/Erro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?=$conteudo['descricao_erro']?></td>
                                                    <td><?=$conteudo['usuario_cadastrou']?></td>
                                                    <td><?=$conteudo['assinante']?></td>
                                                    <td><?=converteData($conteudo['data_erro'])?> <?=$conteudo['hora_erro']?> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>
                                    <div class='conteudo-editor' style="margin-left: 7px;">
                                        <strong>Descrição</strong><br><br>
                                        <?=$conteudo['descricao_cliente']?>
                                    </div>
                                </div>
                            </div>

                            <hr>

                    <?php
                            }
                        }
                        else{
                            echo '<p class="alert alert-info" style="text-align: center">Não foram encontrados Reclamações/Erros</p>';
                        }
                    ?>
                </div>
            </div>
            <!-- endo row -->
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">
            OK
        </button> -->
        <!-- <button type="button" class="btn btn-primary">
            <i class="fa fa-floppy-o"></i> Salvar
        </button> -->
      </div>
    </div>
  </div>
</div>
<!-- end modal erros -->

<!-- Modal QI -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" id="sistemagestao" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informações</h4>
      </div>
      <div class="modal-body">
            
            <div class="row">
                <div class="col-md-12">
                    <span><strong>Quadro informativo</strong></span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <span>
                        <strong>
                            <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?=$id_contrato_plano_pessoa?>" target="_blank" class="btn-xs btn btn-primary" style="min-width: 100%;">
                                <i class="fa fa-info" aria-hidden="true"></i> Quadro informativo
                            </a>
                        </strong>
                    </span>
                </div>
            </div><hr>
            <!-- MATHEUS -->
            <div class="row">
                <div class="col-md-12">
                    <span><strong>Manual</strong></span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <span>
                        <strong>
                            <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?=$id_contrato_plano_pessoa?>" target="_blank" class="btn-xs btn btn-primary" style="min-width: 100%;">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i> Manual
                            </a>
                        </strong>
                    </span>
                </div>
            </div><hr>
            <!-- MATHEUS -->
            <?php 
                
                $sistema_gestao = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_tipo_sistema_gestao b ON a.id_tipo_sistema_gestao = b.id_tipo_sistema_gestao WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa ");

                if ($sistema_gestao) {
            ?>
                    <div class="row" style="padding-bottom: 15px;">
                        <div class="col-md-12">
                            <span><strong>Sistema de gestão</strong></span><br>
                            <span>
                                <strong>
                                    <a href="<?=$sistema_gestao[0]['link']?>" target="_blank" class="btn-xs btn btn-primary" style="min-width: 100%;">
                                        <?=$sistema_gestao[0]['nome']?>
                                    </a>
                                </strong>
                            </span><br><br>

                            <?php if ($sistema_gestao[0]['observacao'] != '') { ?>
                                <span>&nbsp Observação:  <?=$sistema_gestao[0]['observacao']?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="col-md-6">Usuário</th>
                                        <th class="col-md-6">Senha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                if($sistema_gestao):
                                    foreach($sistema_gestao as $conteudo):  
                                        
                                        $usuarios = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '".$conteudo['id_sistema_gestao_contrato']."' ORDER BY contador ASC");

                                        foreach ($usuarios as $conteudo_usuario) {

                                    ?>
                                        <tr>
                                            <td>
                                                <input class="form-control input-sm" type="text" readonly value="<?=$conteudo_usuario['usuario']?>">
                                            </td>
                                            <td>
                                                <input class="form-control input-sm" type="text" readonly value="<?=$conteudo_usuario['senha']?>">
                                            </td>
                                            <td>
                                                <?=$conteudo_usuario['observacao']?>
                                            </td>
                                        </tr>
                                    <?php	
                                        }
                                    endforeach;
                                endif;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div><hr>

            <?php 
                } 
                
                $sistema_chat = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

                if ($sistema_chat) {
                    $sistema_chat_modal = DBRead('', 'tb_sistema_chat_contrato a', "INNER JOIN tb_tipo_sistema_chat b ON a.id_tipo_sistema_chat = b.id_tipo_sistema_chat INNER JOIN tb_sistema_chat_acesso c ON a.id_sistema_chat_contrato = c.id_sistema_chat_contrato WHERE a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa", "b.nome, a.id_sistema_chat_contrato, a.observacao, a.link, c.usuario, c.senha");

                ?>
                    <div class="row" style="padding-bottom: 15px;">
                        <div class="col-md-12">
                            <span><strong>Sistema de chat</strong></span><br>
                            <span>
                                <strong>
                                    <a href="<?=$sistema_chat_modal[0]['link']?>" target="_blank" class="btn-xs btn btn-primary" style="min-width: 100%;">
                                        <?=$sistema_chat_modal[0]['nome']?>
                                    </a>
                                </strong>
                            </span><br><br>

                            <?php if ($sistema_chat_modal[0]['observacao'] != '') { ?>
                                <span>&nbsp Observação:  <?=$sistema_chat_modal[0]['observacao']?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <table class="table">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Senha</th>
                                </tr>
                            </thead>
                            <tbody>
					      	<?php
                                
                            foreach ($sistema_chat_modal as $conteudo_sistema_chat) {
                                echo'
                                <tr>
                                    <td><input class="form-control input-sm" type="text" readonly value="'.$conteudo_sistema_chat['usuario'].'"></td>
                                    <td><input class="form-control input-sm" type="text" readonly value="'.$conteudo_sistema_chat['senha'].'"></td>
                                </tr>';
                            }
								
		                	?>
                        </tbody>
                    </table>
                <?php
                }
            ?>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- end modal QI-->

<script>

    function createCell(cell, text, style) {

        var div = document.createElement('div'), // create DIV element
            txt = document.createElement(text); // create text node

        txt.className = "form-control input-sm";
        txt.setAttribute("name", "teste");

        if(text == 'select'){
            var option1 = document.createElement("option");
            option1.text = "Sem nota";
            txt.add(option1);

            var option2 = document.createElement("option");
            option2.text = "Nota 1";
            txt.add(option2);

            var option3 = document.createElement("option");
            option3.text = "Nota 2";
            txt.add(option3);

            var option4 = document.createElement("option");
            option4.text = "Nota 3";
            txt.add(option4);

            var option5 = document.createElement("option");
            option5.text = "Nota 4";
            txt.add(option5);

            var option6 = document.createElement("option");
            option6.text = "Nota 5";
            txt.add(option6);
        }

        div.appendChild(txt);                    // append text node to the DIV
        div.setAttribute('class', style);        // set DIV class attribute
        //div.setAttribute('className', style);    // set DIV class attribute for IE (?!)
        cell.appendChild(div);                   // append DIV to the table cell
    }

    function appendColumn() {
        var tbl = document.getElementById('myTable'), // table reference
            i;
        var select = 'select';
        var input = 'input';

        // open loop for each row and append cell
        for (i = 0; i < tbl.rows.length; i++) {
            if(i == 0){
                createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length), select, 'col');
            }else{
                createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length), input, 'col');
            }
        }
    }

    function deleteColumns() {
        var tbl = document.getElementById('my-table'), // table reference
            lastCol = tbl.rows[0].cells.length - 1,    // set the last column index
            i, j;
        // delete cells with index greater then 0 (for each row)
        for (i = 0; i < tbl.rows.length; i++) {
            for (j = lastCol; j > 0; j--) {
                tbl.rows[i].deleteCell(j);
            }
        }
    }

    function selectAreaSubareaProblema(id_area_problema){        
        $("select[name=id_subarea_problema]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectAreaSubareaProblema.php",
            {area_problema: id_area_problema, token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=id_subarea_problema]").html(valor);
            }
        )        
    }

    $(document).on('change', 'select[name=id_area_problema]', function(){
        selectAreaSubareaProblema($(this).val());
    });

    $('.btn-vincular').on('click', function(){

        btn = $(this); 

        $('.btn-vincular').removeClass('btn-success');
        $('.btn-vincular').addClass('btn-primary');
        $('.btn-vincular').find('.txt-vincular').text(' Vincular');

        btn.removeClass('btn-primary');
        btn.addClass('btn-success');
        btn.find('.txt-vincular').text(' Vinculado');

        $('#id_erro').val(btn.attr("id"));
        
        $('#vincular_erro').removeClass('btn-primary');
        $('#vincular_erro').addClass('btn-success');
        $('#span_vincular_erro').text(' Erro vinculado');

        $('#desvincular_erro').show();
    });

    $('#desvincular_erro').on('click', function(){
        $('#id_erro').val('');
        $('#desvincular_erro').hide();

        $('.btn-vincular').removeClass('btn-success');
        $('.btn-vincular').addClass('btn-primary');
        $('.btn-vincular').find('.txt-vincular').text(' Vincular');

        $('#vincular_erro').removeClass('btn-success');
        $('#vincular_erro').addClass('btn-primary');
        $('#span_vincular_erro').text(' Vincular Reclamação/Erro');
    });

    $('.pontos-manter').on('click', function(){
        obj = $(this);
        
        obj.parent().parent().find('.pontos_resultado').val(obj.attr("attr-pm"));

        obj.parent().parent().find('.check').css('background-color', '#CEF6CE').val('Atendeu!');
    })

    $('.pontos-tirar').on('click', function(){
        obj = $(this);

        var resultado = parseInt(obj.attr("attr-pm")) - parseInt(obj.attr("attr-pt"));
        
        obj.parent().parent().find('.pontos_resultado').val(resultado);

        obj.parent().parent().find('.check').css('background-color', '#F5A9A9').val('Não atendeu!');
    })

    $(document).on('submit', '#monitoria_avaliacao_texto', function(){

        var cont = 0;
        
        $(".check").each(function(i, e){

            if($(this).val() == 'Não avaliado!'){
                if(cont == 0){
                    $(this).focus();
                }
                cont++;
            }
        });

        if(cont != 0){
            alert('Há quesitos que ainda nao foram avaliados!');
            return false;
        }

        modalAguarde();

    });

    var segundos_totais = 0+"0";
    var segundo = 0+"0";
    var minuto = 0+"0";
    var hora = 0+"0";
		 
    function tempo(){	

        segundos_totais++;

        if (segundo < 59){
            segundo++
            if(segundo < 10){segundo = "0"+segundo}
        }else 
            if(segundo == 59 && minuto < 59){
                segundo = 0+"0";
        minuto++;
        if(minuto < 10){minuto = "0"+minuto}
            }
        if(minuto == 59 && segundo == 59 && hora < 23){
            segundo = 0+"0";
            minuto = 0+"0";
            hora++;
            if(hora < 10){hora = "0"+hora}
        }else 
            if(minuto == 59 && segundo == 59 && hora == 23){
                segundo = 0+"0";
        minuto = 0+"0";
        hora = 0+"0";
            }

        document.getElementById("cronometro").innerHTML = minuto +":"+ segundo
        document.getElementById("duracao_avaliacao").value = segundos_totais;
    }

    setInterval('tempo()',983);
 
</script>