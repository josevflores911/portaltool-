<?php
require_once '../classes/cls_loadimg.php';
$oImg = new cls_loadimg();
$first = $oImg->getImageUrl("first")["source"];
$last = $oImg->getImageUrl("last")["source"];
$next = $oImg->getImageUrl("next")["source"];
$prev = $oImg->getImageUrl("prev")["source"];


?>
<head>
   
    <script src="scripts/vi_cadagencias.js"></script>
    <script src="scripts/vi_cadestado.js?nocache=<?php echo time(); ?>"></script>
    <script src="scripts/vi_cadmunicipios.js?nocache=<?php echo time(); ?>"></script>
    <script src="scripts/tilt.jquery.min.js"></script>
    <link rel="stylesheet" href="assets/styles/vi_padrao.scss">
    <link rel="stylesheet" href="assets/styles/vi_cadastro.scss">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="scripts/vi_cadastro.js?v=1.10"></script>
</head>

    <div class="container-fluid">
        <div class="card card-fluid">
            <div class="card-header">
                <h5 class="card-title">Tela De Cadastro</h5>
            </div>
            <div class="card-body">
                <form id="formCadastro" onsubmit="return false;" method="POST">
                    <section class="form-group my-5 sticky-md-top" style="position:relative;top:-5rem;">
                        <div class="row">
                            <!-- Estado select: 2 columns -->
                            <div class="col-md-2">
                            <label for="estadoSelect" class="form-label"></label>
                            <div class="input-group">
                                <span class="input-group-text" id="estado-addon" title="Estado">Estado</span>
                                <select class="form-select" id="estadoSelect" name="estado" aria-describedby="estado-addon">
                                    <option value="">Escolha...</option>
                                    <option value="1">Opção 1</option>
                                    <option value="2">Opção 2</option>
                                </select>
                            </div>
                            </div>
                            <!-- Município select: 5 columns -->
                            <div class="col-md-5">
                                <label for="municipioSelect" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="municipio-addon" title="Município">Município</span>
                                    <select class="form-select selectpicker"  data-live-search="true" id="municipioSelect" name="municipio" aria-describedby="municipio-addon">
                                        <option value="">Escolha...</option>
                                     
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="filtro" class="form-label"></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id='text_filtro' aria-describedby="filtro-addon1">
                                    <div class="input-group-append">
                                        <span>
                                            <button class="btn btn-primary" id="bt_filtro"><img src="assets/images/filtrar.png" width="16" height="16" alt=""/></button>
                                            <button class="btn btn-secondary" id="bt_limpar"><img src="assets/images/vassoura.png" width="16" height="16" alt="" /></button>
                                        </span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </section>
                    <section class="form-group my-5 table-responsive" style="max-height: 150%; border-collapse: collapse; overflow: auto; position:relative;top:-8rem;">
                        <div class="col-md-2">
                            <label for="total_agencias" class="form-label"></label>
                            <div class="input-group col-my-2">
                                <span class="input-group-text" id="total_agencias-addon" title="Total de agências">Total agências</span>
                                <input type="text" id="total_agencias" size='4' value="999" class="form-control input-sm" aria-describedby="total_agencias-addon">
                            </div>
                        </div>
                        <div class="row-fluid table-agencias" >
                            <table class="data-table table table-stripless table-hover  table-borderless">
                                <thead class="sticky-md-top">
                                    <tr>
                                        <th scope='row' style='width:2.5rem;'>&nbsp;</th>
                                        <th scope='col' class="col-md-1">Nº</th>
                                        <th scope='col' class="col-md-3">CNPJ ORIGINAL</th>
                                        <th scope='col' class="col-md-2">CCM</th>
                                        <th scope='col' class="col-md-3">CENTRO ASSOCIADO</th>
                                        <th scope='col' class="col-md-5">ENDEREÇO COMPLETO</th>
                                        <th scope='col' class="col-md-4" style='display:none'>Sistema</th>
                                        <th scope='col' class="col-md-5" style='display:none'>LINK P</th>
                                        <th scope='col' class="col-md-3" style='display:none'>user P</th>
                                        <th scope='col' class="col-md-3" style='display:none'>senha P</th>
                                        <th scope='col' class="col-md-5" style='display:none'>LINK T</th>
                                        <th scope='col' class="col-md-3" style='display:none'>user T</th>
                                        <th scope='col' class="col-md-3" style='display:none'>senha T</th>
                                        <th scope='col' class="col-md-5" style='display:none'>Cont. Prefeitura</th>
                                        <th scope='col' class="col-md-3" style='display:none'>E-mail</th>
                                        <th scope='col' class="col-md-3" style='display:none'>Telefone</th>
                                        <th scope='col' class="col-md-5" style='display:none'>Cont. Suporte</th>
                                        <th scope='col' class="col-md-3" style='display:none'>E-mail Sup.</th>
                                        <th scope='col' class="col-md-3" style='display:none'>Telefone Sup</th>

                                    </tr>
                                </thead>
                                <tbody class='bg-white text-dark'></tbody>
                                <tfoot class='sticky-bottom bg-darkblue' style='display:fixed'>
                                    <tr>
                                        <td colspan='3' class='text-white bg-darkblue'>
                                            <img src="<?php echo $first;?>" width='16px' alt='first' id='first'>
                                            <img src="<?php echo $prev;?>" width='16px' alt='prev' id='prev'>
                                            <input type='text' class='rounded text-dark text-center bg-white' value='1' size='3' maxlength='3' id="sel-page">
                                            <img src="<?php echo $next;?>" width='16px' alt='next' id='next' class='rotate-180'>
                                            <img src="<?php echo $last;?>" width='16px' alt='last' id='last' class='rotate-180'>
                                        </td>
                                        <td colspan='3' class='text-white bg-darkblue'>
                                            <label for="sel-linhas" class="bg-transparent text-white ">Linhas por página</label>
                                            <select class="selectpicker text-center bg-white text-black" id="sel-linhas">
                                                <option value="5">5</option>
                                                <option value="10" selected>10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                </tfoot>
                            </table>
                    </section>
                    <section class="form-group my-5 sticky-md-top dados-municipio" style="position:relative;top:-9.5rem;">
                        <hr class="section-divider">
                        <h5>Dados do Município <span><label class='form-control-label lb-municipio text-dark'>&nbsp;</label></span></h5>

                        <div class="row">
                            <div class="col-md-5">
                                <label for="nm_sistema" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="sistema-addon1" title="Sistema" style='left:-0.76em; position: relative;'>Sistema</span>
                                    <input type="text" class="form-control" id='nm_sistema' aria-describedby="sistema-addon1">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="bt_exportar" class="form-label" style='background-color: whitesmoke;'>&nbsp;</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="sistema-addon1" title="Sistema" style='background-color: whitesmoke; border:none;'>&nbsp;</span>
                                    <button type="button" class="btn btn-primary text-white" style="font-weight: bold;" id="geracao-txt">Gerar TXT</button>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid form-group d-inline-flex gap-1">
                            <div class="col-md-8">
                                <label for="te_link" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="te_linP-addon1" title="Sistema">Link P</span>
                                    <input type="text" class="form-control" id='te_link' aria-describedby="te_linkP-addon1">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="nm_usuario" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="nm_usuario-addon1" title="Sistema">Usuário P</span>
                                    <input type="text" class="form-control" id='nm_usuario' aria-describedby="nm_usuario-addon1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="te_senha" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="te_senha-addon1" title="Sistema">Senha P</span>
                                    <input type="text" class="form-control" id='te_senha' aria-describedby="te_senha-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid form-group d-inline-flex gap-1">
                            <div class="col-md-8">
                                <label for="te_linkt" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="te_lint-addon1" title="Sistema">Link T</span>
                                    <input type="text" class="form-control" id='te_linkt' aria-describedby="te_linkt-addon1">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="nm_usuariot" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="nm_usuariot-addon1" title="Sistema">Usuário T</span>
                                    <input type="text" class="form-control" id='nm_usuariot' aria-describedby="nm_usuariot-addon1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="te_senhat" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="te_senhat-addon1" title="Sistema">Senha T</span>
                                    <input type="text" class="form-control" id='te_senhat' aria-describedby="te_senhat-addon1">
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider"/>
                        <div class="row-fluid form-group d-inline-flex gap-1 table-responsive">
                            <div class="container-fluid ">
                                <table class="table table-sm data-table table-borderless table-stripless table-obrigacoes">
                                    <thead class="sticky-top">
                                        <tr>
                                            <th scope='row' class='col-md-3'>OBRIGAÇÃO</th>
                                            <th scope='col' class='col-md-1'>DESIF</th>
                                            <th scope='col' class='col-md-2'>PRAZO ENTREGA</th>
                                            <th scope='col' class='col-md-3'>PERIODICIDADE</th>
                                            <th scope='col' class='col-md-1'>UNIF./SEP.</th>
                                            <th scope='col' class='col-md-3'>OBSERVAÇÕES GERAIS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Demonstrativo Contábil</td>
                                            <td>
                                                <select id="desif-contabil" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_1' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_1' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh_1" id='tp-recolh_1' class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao1' class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>Apuração Mensal ISS</td>
                                            <td>
                                                <select id="desif-iss" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_2' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_2' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh_2" id='tp-recolh_2' class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao2' class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>Info Comuns p/ Municípios</td>
                                            <td>
                                                <select id="desif-infomuni" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_3' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_3' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh" class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao3' class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>Demonst. das Partidas dos Lanç. Contábeis</td>
                                            <td>
                                                <select id="desif-lancContab" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_4' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_4' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh" class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao4' class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>Manual</td>
                                            <td>
                                                <select id="desif-manual" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_5' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_5' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh" class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao5' class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>Outros</td>
                                            <td>
                                                <select id="desif-outros" name="desif" class="form-select">
                                                    <option value="Sim">Sim</option>
                                                    <option value="Não" selected>Não</option>
                                                </select>
                                            </td>
                                            <td><input type="date" id='dt_prazo_6' class="form-control form-control-sm"></td>
                                            <td><input type="text" id='periodicidade_6' class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="tp-recolh" class="form-select">
                                                    <option value="Unificado">Unificado</option>
                                                    <option value="Separado">Separado</option>
                                                </select>
                                            </td>
                                            <td><input type="text" id='te_observacao6' class="form-control form-control-sm"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr class="section-divider"/>
                        <div class="row-fluid form-group d-inline-flex gap-1">
                            <div class="col-md-6">
                                <label for="nm_elaborador" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="analista-addon1" title="Sistema">Analista Elaborador</span>
                                    <input type="text" class="form-control" id='nm_elaborador' aria-describedby="analista-addon1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nm_aprovador" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="aprovador-addon1" title="Sistema">Analista Aprovador</span>
                                    <input type="text" class="form-control" id='nm_aprovador' aria-describedby="aprovador-addon1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="te_tempo" class="form-label"></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="te_tempo-addon1" title="Sistema">Tempo Estimado (Minutos)</span>
                                    <input type="text" class="form-control" id='te_tempo' aria-describedby="te_tempo-addon1">
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider" />
                        <h5 class="mb-2">Informações Adicionais</h5>
                        <div class="row-fluid form-group">
                            <div class="row-fluid text-center"><h5>Contato c/a Prefeitura</h5></div>
                            <div class="row-fluid form-group d-inline-flex gap-1">
                                <div class="col-md-7">
                                    <label for="nm_contato" class="form-label"></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="nm_contato-addon1" title="Sistema">Nome</span>
                                        <input type="text" class="form-control" id='nm_contato' aria-describedby="nm_contato-addon1">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label for="te_email" class="form-label"></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="te_email-addon1" title="Sistema">E-mail</span>
                                        <input type="text" class="form-control" id='te_email' aria-describedby="te_email-addon1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="te_telefone" class="form-label"></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="te_telefone-addon1" title="Sistema">Telefone</span>
                                        <input type="text" class="form-control" id='te_telefone' aria-describedby="te_telefone-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid form-group" style='position: relative; top:3em;'>
                                <div class="row-fluid text-center"><h5>Contato c/ Suporte da Prefeitura</h5></div>
                                <div class="row-fluid form-group d-inline-flex gap-1">
                                    <div class="col-md-7">
                                        <label for="nm_contato_sup" class="form-label"></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="nm_contato_sup-addon1" title="Sistema">Nome</span>
                                            <input type="text" class="form-control" id='nm_contato_sup' aria-describedby="nm_contato_sup-addon1">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <label for="te_email_sup" class="form-label"></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="te_email_sup-addon1" title="Sistema">E-mail</span>
                                            <input type="text" class="form-control" id='te_email_sup' aria-describedby="te_email_sup-addon1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="te_telefone_sup" class="form-label"></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="te_telefone_sup-addon1" title="Sistema">Telefone</span>
                                            <input type="text" class="form-control" id='te_telefone_sup' aria-describedby="te_telefone_sup-addon1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
            <div class="card-footer">
                <div class="w-100 text-center">
                    <button type="reset" class="btn btn-secondary me-2">Limpar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>

       </div>
    </div>
