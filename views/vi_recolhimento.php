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
?>
<head>
    <meta charset="UTF-8">
    <title>Tela de Recolhimento</title>
    <script src="scripts/vi_recolhimentos.js?nocache=<?php echo time();?>" type="text/javascript"></script>
    <link rel="stylesheet" href="assets/styles/vi_recolhimentos.scss?nocache=<?php echo time();?>">
    <style>
        
    </style>

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
                <button class="btn-close " data-dismiss="modal" aria-label="Close"></button>
            </div>
           <div class="modal-body bg-white text-dark">
                <div class="waiting">
                     <img src='#' width='32px' id="spin" alt='waiting'>
                </div>
                <div class="generic-notas"></div>
                <div class="card card-info-municipio">
                    <div class="card-body" id="info-municipio">
                        <div class="section mt-0 mb-0">
                            <div class="row align-items-center justify-content-around" style="width: 100%; background-color: darkblue; color: white; margin: 0;">
                                <!-- Municipio -->
                                <div class="col-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-0 text-white">Municipio</span>
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
                        <br style='height:4em;'>
                        <div class="section mt-0 mb-0">
                            <div class="row-fluid tableFixHead" id="table-recolhimentos">
                                <table class="table table-responsive" id="tabela-agencias">
                                    <thead class="justify-content-between sticky-top bg-darkblue text-white" style="background-color: darkblue; color: #fff;">
                                        <tr scope='row' class='row-fluid' style='max-width:2000px'>
                                            <th scope='row'></th>
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
                                            <th scope='col' class='text-center'>Vl Prot.</th>
                                            <th scope='col' class='text-center'>Guia</th>
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
                                    <tfoot class='sticky-bottom' style='height:19px !important;'>
                                        <tr class='justify-content-around'>
                                            <td scope='row' colspan='2'>
                                                <div class="card card-trasparent ">
                                                    <div class="card-body">
                                                        <img src="<?php echo $first;?>" width='16px' alt='first' id='first'>
                                                        <img src="<?php echo $prev;?>" width='16px' alt='prev' id='prev'>
                                                        <input type='text' class='rounded text-white text-center bg-transparent' value='1' size='3' maxlength='3' id="sel-page">
                                                        <img src="<?php echo $next;?>" width='16px' alt='next' id='next' class='rotate-180'>
                                                        <img src="<?php echo $last;?>" width='16px' alt='last' id='last' class='rotate-180'>
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope='row' colspan='6' >&nbsp;</td>
                                            <td scope='row' colspan='3' >
                                                <div class="card bg-transparent float-end" style='float:left; left:-5em;' >
                                                    <div class="card-body">
                                                        <label for="sel-linhas" class="bg-transparent text-white ">Linhas por página</label>
                                                        <select class="selectpicker text-center bg-white text-black" id="sel-linhas">
                                                            <option value="8">8</option>
                                                            <option value="15">15</option>
                                                            <option value="20" selected>20</option>
                                                            <option value="25">25</option>
                                                            <option value="30">30</option>
                                                            <option value="50">50</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td colspan='21'>&nbsp;</td>
                                        </tr>
                                    </tfoot>
                                </table>
                               
                            </div>
                        </div>
                        <div class="section mt-0 mb-0">
                            <form name='form-contato-agencia' id='form-contato-agencia' method='POST'>
                                <div class="form-group">
                                    <!-- First Line: Three Input Fields -->
                                    <div class="row" style='background-color: whitesmoke;'>
                                        <div class="row-fluid text-center"><h6>Contatos c/Agência</h6></div>
                                        <div class="col-md-4">
                                            <label for="nm_contato" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="nm_contato-addon1" title="Sistema">Nome</span>
                                                <input type="text" class="form-control" id="nm_contato" aria-describedby="nm_contato-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="te_email" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="te_email-addon1" title="Sistema">E-mail</span>
                                                <input type="text" class="form-control" id="te_email" aria-describedby="te_email-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="te_telefone" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="te_telefone-addon1" title="Sistema">Telefone</span>
                                                <input type="text" class="form-control" id="te_telefone" aria-describedby="te_telefone-addon1">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Line: Three Input Fields -->
                                    <div class="row mt-2">
                                        <div class="row-fluid text-center"><h6>Contatos c/Suporte da Agência</h6></div>
                                        <div class="col-md-4">
                                            <label for="nm_contato-sup" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="nm_contato-sup-addon1" title="Sistema">Nome</span>
                                                <input type="text" class="form-control" id="nm_contato-sup" aria-describedby="nm_contato-sup-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="te_email-sup" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="te_email-sup-addon1" title="Sistema">E-mail</span>
                                                <input type="text" class="form-control" id="te_email-sup" aria-describedby="te_email-sup-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="te_telefone-sup" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="te_telefone-sup-addon1" title="Sistema">Telefone</span>
                                                <input type="text" class="form-control" id="te_telefone-sup" aria-describedby="te_telefone-sup-addon1">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class='row-fluid'/>
                                    <!-- Third Line: Two Textareas -->
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="te_observacao" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" title="Observações">Observações</span>
                                                <textarea class="form-control" id="te_observacao" rows="12" columns="40"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="te_logs" class="form-label"></label>
                                            <div class="input-group">
                                                <span class="input-group-text" title="Ocorrências">Movimentações</span>
                                                a<textarea class="form-control" id="te_logs" rows="12" columns="40"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
