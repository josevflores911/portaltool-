<?php
   error_reporting(0);
   error_reporting(E_ALL);
   if (session_status() === PHP_SESSION_NONE) {
        session_start();
   }
   $id_user = $_POST['id_user'];
   $tp_user = $_POST['tp_user'];
   $id_muni = $_POST['id_muni'];
   if (isset($_POST['id_userxmunicipios'])) {
       $id_userxmunicipios = $_POST['id_userxmunicipios'];
       $_SESSION['id_userxmunicipios'] = $id_userxmunicipios;
   }
   if (isset($_POST['id_obrigacaoacessoria'])) {
       $id_obrigacaoacessoria = $_POST['id_obrigacaoacessoria'];
       $_SESSION['id_obrigacaoacessoria'] = $id_obrigacaoacessoria;
   }
   $_SESSION['id_user'] = $id_user;
   $_SESSION['tp_user'] = $tp_user;
   $_SESSION['id_muni'] = $id_muni;
   if (isset($_POST['dt_compet'])) {
       $dt_compet = $_POST['dt_compet'];
       $_SESSION['dt_compet'] = $dt_compet;
   } else {
        $dt_compet = '__/____';
        $_SESSION['dt_compet'] = NULL;
   }
   if (isset($_POST['qt_agencias'])) {
       $qt_agencias = $_POST['qt_agencias'];
       $_SESSION['qt_agencias'] = $qt_agencias;
   } else {
       $qt_agencias = 0;
       $_SESSION['qt_agencias'] = NULL;
   }

   if (isset($_POST['nm_muni'])) {
       $nm_muni = $_POST['nm_muni'];
       $_SESSION['nm_muni'] = $nm_muni;
   } else {
       $nm_muni = '';
   }
   require_once '../classes/cls_loadimg.php';
   $oImg = new cls_loadimg();
   $first = $oImg->getImageUrl("first")["source"];
   $last = $oImg->getImageUrl("last")["source"];
   $next = $oImg->getImageUrl("next")["source"];
   $prev = $oImg->getImageUrl("prev")["source"];
   $waiting = $oImg->getImageUrl("spin")["source"];
   $load = $oImg->getImageUrl("load")["source"]
?>
<head>
    <meta charset="UTF-8">
    <title>Tela de Recolhimento</title>
    <script src="scripts/vi_recolhimentos.js?nocache=<?php echo time();?>" type="text/javascript"></script>
    <link rel="stylesheet" href="styles/vi_recolhimentos.scss?nocache=<?php echo time();?>">
</head>
<div class='generic-recolhimento'></div>
<input type='hidden' id='id_muni' value='<?php echo $id_muni; ?>'>
<input type='hidden' id='id_user' value='<?php echo $id_user; ?>'>
<input type='hidden' id='tp_user' value='<?php echo $tp_user; ?>'>
<!-- Container Offcanvas para a Tela de Recolhimento -->
<div class="modal fade modal-fullscreen" id="modal-recolhimento" tabindex="-1" data-bs-backdrop="static" aria-hidden="true" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header text-center text-white">
                <h5 class="modal-title bg-transparent text-white">Tela de Recolhimento</h5>
                <button class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
           <div class="modal-body bg-white text-dark">
                <div class="card card-info-municipio">
                    <div class="card-body" id="info-municipio">
                        <div class="section text-blue mt-0 mb-0">
                            <div class="row align-items-center justify-content-around" style="width: 100%; margin: 0;">
                                <!-- Municipio -->
                                <div class="col-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Município</span>
                                        <input type="text" class="form-control form-control-sm bg-transparent text-white" id="municipio" value="<?php echo $nm_muni;?>" readonly>
                                    </div>
                                </div>

                                <!-- Data Vencimento -->
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Data Vencimento</span>
                                        <input type="text" class="form-control form-control-sm bg-transparent text-white" id="dt-vencimento" value="" readonly>
                                    </div>
                                </div>

                                <!-- Forma de Contato -->
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Forma de Contato</span>
                                        <input type="text" class="form-control form-control-sm bg-transparent text-white" id="info-contato" value="" readonly>
                                    </div>
                                </div>

                                <!-- Agências -->
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Agências</span>
                                        <input type="text" class="form-control form-control-sm bg-transparent text-white" id="info-total" value="" readonly>
                                    </div>
                                </div>

                                <!-- Competência -->
                                <div class="col-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Competência</span>
                                        <input type="text" class="form-control form-control-sm bg-transparent text-white" id="info-dtcompet" value="<?php echo $dt_compet;?>" readonly>
                                    </div>
                                </div>

                                <!-- Unificado -->
                                <div class="col-1">
                                    <label for="tp-recolhimento" class="form-check-label text-white">Unificado</label>
                                    <input id="tp-recolhimento" class="form-check-input" type="checkbox">
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- TABELA COM SCROLL PRÓPRIO -->
                        <div class="row-fluid tableFixHead" id="table-recolhimentos">
                            <table class="table table-responsive-md" id="tabela-agencias">
                                <div class="waiting-recolhimento">
                                        <img src='<?php echo $load;?>' width='100%' id="spin" alt='waiting'>
                                    </div>
                                <thead class="sticky-top">
                                    <tr scope='row' class='row-fluid'>
                                        <th scope='row' style='width:1.4em !important;' class="text-center"></th>
                                        <th scope='col' class='text-center'>Esteira</th>
                                        <th scope='col' class='text-center'>nº agencia</th>
                                        <th scope='col' class='text-center'>CNPJ Original</th>
                                        <th scope='col' class='text-center'>CCM</th>
                                        <th scope='col' class='text-center'>Tributo</th>
                                        <th scope='col' class='text-center'>Tipo Tributo</th>
                                        <th scope='col' class='text-center'>Valor Base</th>
                                        <th scope='col' class='text-center'>Base ISS</th>
                                        <th scope='col' class='text-center'>Valor ISS</th>
                                        <th scope='col' class='text-center'>Juros</th>
                                        <th scope='col' class='text-center'>Multa</th>
                                        <th scope='col' class='text-center'>Taxa Exc.</th>
                                        <th scope='col' class='text-center'>Arred.</th>
                                        <th scope='col' class='text-center'>Desc.</th>
                                        <th scope='col' class='text-center'>Outras Div.</th>
                                        <th scope='col' class='text-center'>Total Recolher</th>
                                        <th scope='col' class='text-center'>Elaboração</th>
                                        <th scope='col' class='text-center'>Prot.</th>
                                        <th scope='col' class='text-center'>&nbsp;</th>
                                        <th scope='col' class='text-center'>Vl Prot.</th>
                                        <th scope='col' class='text-center'>Guia</th>
                                        <th scope='col' class='text-center'>&nbsp;</th>
                                        <th scope='col' class='text-center'>Vl Guia</th>
                                        <th scope='col' class='text-center'>Correção</th>
                                        <th scope='col' class='text-center'>Divergência</th>
                                        <th scope='col' class='text-center'>Aprovação</th>
                                        <th scope='col' class='text-center'>Status Validação</th>
                                        <th scope='col' class='text-center'>Status Validação +</th>
                                        <th scope='col' class='text-center'>Justificativa</th>
                                        <th scope='col' class='text-center'>Obs. Justificativa</th>
                                        <th scope='col' class='text-center'>Comp. Pgto</th>
                                        <th scope='col' class='text-center'>Status Pgto</th>
                                        <th scope='col' class='text-center'>Contabil</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    </tr>
                                </thead>

                                <tbody id="tbody-recolhimentos"></tbody>
                                <tfoot class='sticky-bottom'>
                                    <tr class='justify-content-around'>
                                        <td scope='row' colspan='6'>
                                            <div class="pagination-controls">
                                                <img src="<?php echo $first;?>" width='16px' alt='first' id='first' class="pagination-button">
                                                <img src="<?php echo $prev;?>" width='16px' alt='prev' id='prev' class="pagination-button">
                                                <input type='text' class='rounded text-white text-center bg-transparent' value='1' size='3' maxlength='3' id="sel-page">
                                                <img src="<?php echo $next;?>" width='16px' alt='next' id='next' class="pagination-button">
                                                <img src="<?php echo $last;?>" width='16px' alt='last' id='last' class="pagination-button">
                                                <span id="pagination-info" class="text-white ml-2"></span>
                                            </div>
                                        </td>
                                        <td scope='row' colspan='3' >&nbsp;</td>
                                        <td scope='row' colspan='3' >
                                            <div class="float-end" style='float:left; left:-5em;'>
                                                <label for="sel-linhas" class="text-white">Linhas por página</label>
                                                <select class="form-select form-select-sm bg-white text-black" id="sel-linhas">
                                                    <option value="8">8</option>
                                                    <option value="15">15</option>
                                                    <option value="20" selected>20</option>
                                                    <option value="25">25</option>
                                                    <option value="30">30</option>
                                                    <option value="50">50</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td colspan='23'>&nbsp;</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- SEÇÃO DE ABAS FORA DO SCROLL -->
                        <div class="section mt-4">
                            <ul class="nav nav-tabs" id="contactTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="agency-tab" data-bs-toggle="tab" data-bs-target="#agency-contact" href="#agency-contact" role="tab" aria-controls="agency-contact" aria-selected="true">Contatos c/Agência</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="support-tab" data-bs-toggle="tab" data-bs-target="#support-contact" href="#support-contact" role="tab" aria-controls="support-contact" aria-selected="false">Contatos c/Suporte da Agência</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" href="#notes" role="tab" aria-controls="notes" aria-selected="false">Observações</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="contactTabsContent">
                                <div class="tab-pane fade show active" id="agency-contact" role="tabpanel" aria-labelledby="agency-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="nm_contato" placeholder="Nome">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="te_email" placeholder="E-mail">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="te_telefone" placeholder="Telefone">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="support-contact" role="tabpanel" aria-labelledby="support-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="nm_contato-sup" placeholder="Nome">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="te_email-sup" placeholder="E-mail">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="text" class="form-control" id="te_telefone-sup" placeholder="Telefone">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <textarea class="form-control" id="te_observacao" rows="6" placeholder="Observações"></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <textarea class="form-control" id="te_logs" rows="6" placeholder="Movimentações" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-primary" onclick="salvarContatoAgencia();">Salvar</button>
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
           </div>
        </div>
    </div>
</div>

