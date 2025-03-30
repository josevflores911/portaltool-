<?php
   error_reporting(0);
   error_reporting(E_ALL);
   $id_user = $_POST['id_user'];
   require_once '../classes/cls_loadimg.php';
   $oImg = new cls_loadimg();
   $check_on = $oImg->getImageUrl("check_on")["source"];
   $check_off = $oImg->getImageUrl("check_off")["source"];
   $radio_on = $oImg->getImageUrl("radio_on")["source"];
   $radio_off = $oImg->getImageUrl("radio_off")["source"];
   $noorder = $oImg->getImageUrl("no_order")["source"];
   $fullorder = $oImg->getImageUrl("full_order")["source"];
   $pageorder = $oImg->getImageUrl("page_order")["source"];
?>

<head>
    <!-- scss 
    <meta http-equiv="Pragma" content="no-cache, no-store">
-->
    <meta name="version" content="1.0.3"/>
    <link rel="stylesheet" href="assets/styles/vi_padrao.scss">
    <link rel="stylesheet" href="assets/styles/vi_usuarios.scss">
    <!-- scripts -->
    <script src='scripts/vi_usuarios.js'></script>
</head>
<body>
    <img src='<?php echo $check_on; ?>' id="img_check_on" style="display:none;">
    <img src='<?php echo $check_off; ?>' id="img_check_off" style="display:none;">
    <img src='<?php echo $radio_on; ?>' id="img_radio_on" style="display:none;">
    <img src='<?php echo $radio_off; ?>' id="img_radio_off" style="display:none;">
    <img src='<?php echo $noorder; ?>' id="img_noorder" style="display:none;" >
    <img src='<?php echo $fullorder; ?>' id="img_fullorder" style="display:none;" >
    <img src='<?php echo $pageorder; ?>' id="img_pageorder" style="display:none;" >

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
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-todos" width='18' alt='Pesquisa em todos os campos' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-todos">Em todos os campos</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-nome" width='18' alt='Pesquisa no campo Nota Fiscal' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-nome">Nome</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-te_email" width='18' alt='Pesquisa no campo Dt. Emissão' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-te_email">E-mail</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-te_tipo" width='18' alt='Pesquisa no campo Valor' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-te_tipo">Tipo de usuário</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-nm_area" width='18' alt='Pesquisa no campo Prestador' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2"aria-labelledby="type-nm_area">Área</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-cs_admin" width='18' alt='Pesquisa no campo Tomador' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-cs_admin">Admin</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-cs_conferente1" width='18' alt='Pesquisa no campo Status' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-cs_conferente1">Conferente</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="type-cs_online" width='18' alt='Pesquisa no campo Status' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2" aria-labelledby="type-cs_online">Online</td>
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
                        <img src="#" width='18' id="field-todos" alt='todos' />&nbsp;
                        <label class="text-white">Todos os campos</label>
                    </div>
                    <button type="button" class="modal-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body bg-transparent">
                    <table class="table table-borderless tb-select-fields">
                        <tbody style="overflow:auto;">
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-nome" width='18' alt='Nome' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Nome</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-te_email" width='18' alt='E-mail' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2" >E-mail</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-te_tipo" width='18' alt='Tipo Usuário' /></td>
                                <td scope="col" c class="col col-sm-4 text-white br-2">Tipo de Usuário</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-nm_area" width='18' alt='Área' /></td>
                                <td scope="col" c class="col col-sm-4 text-white br-2">Área</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col"><img src="#" id="field-cs_admin" width='18' alt='Administrador' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Admin</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-cs_conferente1" width='18' alt='Conferente' /></td>
                                <td scope="col" class="col col-sm-4 text-white br-2">Conferente</td>
                            </tr>
                            <tr scope="row" class="justify-content-between">
                                <td scope="col" class='col col-sm-1 br-1'><img src="#" id="field-cs_online" width='18' alt='Online' /></td>
                                <td scope="col"  class="col col-sm-4 text-white br-2">Online</td>
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
            <img src="#" class="rotate-90" data-bs-toggle="modal" data-bs-target="#show_types" width='10rem' id="show-seltypes"></span>
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
            <img src="#" class="rotate-90" data-bs-toggle="modal" data-bs-target="#show_fields" width='10rem' id="show-selfields">
          </div>
        </div>
      </div>
    </div>
   
    <div class="tableFixHead content-fluid">
        <table class='table tb_usuarios table-responsive table-stripless borderless' data-user="<?php echo $id_user; ?>">
            <thead class="sticky-top; bg-dark text-white" style="overflow-x:auto;">
            </thead>
            <tbody id="tbody_usuarios">
            </tbody>
            <tfoot class='bg-transparent'>
                <tr>
                    <td scope='row' colspan='10'>
                        <div class="d-flex justify-content-between rodape">
                            <div class="p-1">&nbsp;</div>
                            <div class="p-2">
                                <div class="card card-trasparent">
                                    <div class="card-body">
                                        <img src="#" width='16px' alt='first' id='first'>
                                        <img src="#" width='16px' alt='prev' id='prev'>
                                        <input type='text' class='rounded text-white text-center bg-dark' value='1' size='3' maxlength='3' id="sel-page">
                                        <img src="#" width='16px' alt='next' id='next' class='rotate-180'>
                                        <img src="#" width='16px' alt='last' id='last' class='rotate-180'>
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
        <img src='#' width='32px' id="spin" alt='waiting'>
    </div>
 </body>