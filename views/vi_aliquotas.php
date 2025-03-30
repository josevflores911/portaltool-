<?php
   error_reporting(0);
   error_reporting(E_ALL);
  $id_user = $_POST['id_user'];
?>
<head>
    <!-- scss -->
    <meta http-equiv="Pragma" content="no-cache, no-store">
    <meta name="version" content="1.0.3"/>
    <link rel="stylesheet" href="assets/styles/vi_padrao.scss">
    <link rel="stylesheet" href="assets/styles/vi_ntservices.scss">
    <!-- scripts -->
    <script src='scripts/vi_ntservices.js'></script>
</head>
<body>
    <div class="generic"></div>
    <div class="modal fade" id="show_types" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="show_seltypes" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-dark">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="text-white">Pesquisar em</h4>
                    <button type="button" class="modal-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-transparent">
                    <table class="table table-borderless tb-select-types">
                        <tbody style="overflow:auto;">
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-todos" width='18' alt='Pesquisa em todos os campos' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-todos">Em todos os campos</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-NNFS" width='18' alt='Pesquisa no campo Nota Fiscal' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-nnfs">Nº Nota Fiscal</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-Dt_Emissao" width='18' alt='Pesquisa no campo Dt. Emissão' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-dtemissao">Data de emissão</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-VS" width='18' alt='Pesquisa no campo Valor' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-vlnota">Valor Nota Fiscal</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-RSP" width='18' alt='Pesquisa no campo Prestador' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2"aria-labelledby="type-prestador">Prestador</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-RST" width='18' alt='Pesquisa no campo Tomador' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-tomador">Tomador</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="type-STATUS" width='18' alt='Pesquisa no campo Status' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-status">Status</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='aplicar_pesquisa' data-bs-dismiss="modal">Aplicar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="show_fields" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="show_selfields" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-dark">
                <!-- Modal Header -->
                <div class="modal-header">
                    <div class="modal-title">
                        <img src="assets/images/check_on.png" width='18' id="field-todos" alt='todos' />&nbsp;
                        <label class="text-white">Todos os campos</label>
                    </div>
                    <button type="button" class="modal-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-transparent">
                    <table class="table table-borderless tb-select-fields">
                        <tbody style="overflow:auto;">
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-NNFS" width='18' alt='NNFS' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Nº Nota Fiscal</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-DH" width='18' alt='Dt. Emissão' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2" >Data de emissão</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-VS" width='18' alt='Valor NNFS' /></td>
                                <td scope="col" c class="col col-sm-4 text-white br-2">Valor Nota Fiscal</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col"><img src="assets/images/check_on.png" id="field-RSP" width='18' alt='Rz. Social Prestador' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Prestador</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-RST" width='18' alt='Rz. Social Tomador' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2">Tomador</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-STATUS" width='18' alt='Status' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Status</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="assets/images/check_on.png" id="field-DCINF" width='18' alt='Campo Observação' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Observação</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='aplicar' data-bs-dismiss="modal">Aplicar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="card card-trasparent">
          <div class="card-body">
            <label for='pesquisa'>Pesquisar:</label>
            <input type="text" id='pesquisa' class="bg-dark text-white rounded" maxlength='90' size='45' placeholder="pesquisar">&nbsp;&nbsp;
            <img src="assets/images/vassoura.png" width="21px" id="limpar_pesquisa" alt="limpar pesquisa" style="display:none;"/>&nbsp;&nbsp;
            <span><label class="text-white" data-bs-toggle="modal" data-bs-target="#show_types">Tipo de pesquisa</label>
            <img src="assets/images/btmenu_opt.png" class="rotate-90" data-bs-toggle="modal" data-bs-target="#show_types" width='8.5rem' id="show_seltypes"></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card card-trasparent">
          <div class="card-body">
            <label for="total_rec">Total de registros:</label>
            <input type="text" class="bg-dark text-white text-center rounded" size='6' id="total_rec" name="total_rec" readonly>
            &nbsp;&nbsp;&nbsp;
            <lable class="text-white" data-bs-toggle="modal" data-bs-target="#show_fields">Selecionar colunas&nbsp;</lable>
            <img src="assets/images/btmenu_opt.png" class="rotate-90" data-bs-toggle="modal" data-bs-target="#show_fields" width='8.5rem' id="show_selfields">
          </div>
        </div>
      </div>
    </div>
   
    <div class="tableFixHead content-fluid">
        <table class='table tb_services table-responsive borderless' data-user="<?php echo $id_user; ?>">
            <thead>
                <tr scope="row" class="text-center bg-dark text-white justify-content-between" >
                    <th scope="col"style='display:none'></th>
                    <th scope="col" class="col col-sm-3 br-1"><span class='d-flex float-start'><img src="assets/images/nochecked.png" width="21px" id="todos" title="Marcar todas as linhas" /></span><b>Ações</b></th>
                    <th scope="col" class="col col-sm-3"><b>NNFS</b><span class='d-flex float-end'><img src="assets/images/no-order.png" id="ordenar" tabindex='0' /></span></th>
                    <th scope="col" class="col col-sm-3"><b>Dt. Emissão<span class='d-flex float-end'><img src="assets/images/full-order.png" tabindex='1' id="ordenar"/></span></b></th>
                    <th scope="col" class="col col-sm-4"><b>Valor NNFS<span class='d-flex float-end'><img src="assets/images/no-order.png" id="ordenar" tabindex='2' /></span></b></th>
                    <th scope="col" class="col col-sm-5"><b>Rz. Social Prestador<span class='d-flex float-end'><img src="assets/images/no-order.png" tabindex='3' id="ordenar" /></span></b></th>
                    <th scope="col" class="col col-sm-5"><b>Rz. Social Tomador<span class='d-flex float-end'><img src="assets/images/no-order.png" tabindex='4' id="ordenar" /></span></b></th>
                    <th scope="col" class="col col-sm-3"><b>Status</b><span class='d-flex float-end'><img src="assets/images/no-order.png" tabindex='5'  id="ordenar" /></span></th>
                    <th scope="col" class="col col-sm-5"><b>Campo Observação</b></th>
                    <th scope='col'stylee="display:none;">&nbsp;</th>
                </tr>
            </thead>
            <tbody class='tbody' id="tbody_service">
            </tbody>
            <tfoot>
                <tr>
                    <td scope='row' colspan='10'>
                        <div class="d-flex justify-content-between rodape">
                            <div class="p-1">&nbsp;</div>
                            <div class="p-2">
                                <div class="card card-trasparent">
                                    <div class="card-body">
                                        <img src="assets/images/first.png" width='16px' alt='first' id='first'>
                                        <img src="assets/images/prev.png" width='16px' alt='prev' id='prev'>
                                        <input type='text' class='rounded text-white text-center bg-dark' value='1' size='3' maxlength='3' id="sel-page">
                                        <img src="assets/images/prev.png" width='16px' alt='next' id='next' class='rotate-180'>
                                        <img src="assets/images/first.png" width='16px' alt='last' id='last' class='rotate-180'>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="card card-trasparent">
                                    <div class="card-body">
                                        <label for="sel-linhas" class="text-white">Linhas por página</label>
                                        <select class="selectpicker text-center" id="sel-linhas">
                                            <option value="8">8</option>
                                            <option value="15">15</option>
                                            <option value="20" selected>20</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="p-1">&nbsp;</div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="waiting">
        <img src='assets/images/spin-wait.gif' width='32px' alt='waiting'>
    </div>
 </body>

