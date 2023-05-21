<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Sistema - Boletos:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                            <label>Busca Número:</label>
                            <input class="form-control input-sm" type="text" name="numero" id="numero" onKeyUp="call_busca_ajax();" placeholder="Informe o número do boleto..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                            <label>Busca #ID:</label>
                            <input class="form-control input-sm" type="text" name="id" id="id" onKeyUp="call_busca_ajax();" placeholder="Informe o #ID do boleto..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Situação:</label>
                                <select class="form-control input-sm" name="situacao" id="situacao" onchange="call_busca_ajax();">
	                                <option value="">Todos</option>
	                                <option value="ALTERACAO VENCIMENTO PENDENTE">ALTERACAO VENCIMENTO PENDENTE</option>
	                                <option value="BAIXA PENDENTE">BAIXA PENDENTE</option>
	                                <option value="BAIXADO">BAIXADO</option>
	                                <option value="EMITIDO">EMITIDO</option>
	                                <option value="FALHA">FALHA</option>
	                                <option value="LIQUIDADO">LIQUIDADO</option>
	                                <option value="REGISTRADO">REGISTRADO</option>
	                                <option value="REJEITADO">REJEITADO</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var numero = $('#numero').val();
        var id = $('#id').val();
        var situacao = $('#situacao').val();

        if (numero.length < inicia_busca && numero.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'numero': numero,
            'id': id,
            'situacao': situacao,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'SistemaBoletoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>