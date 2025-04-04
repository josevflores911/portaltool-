<?php
   error_reporting(0);
   error_reporting(E_ALL);
   $id_user = $_POST['id_user'];
   $tp_user = $_POST['tp_user'];
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
    <meta http-equiv="Pragma" content="Cache-Control: no-cache, no-store, must-revalidate Expires: 0">
    <meta name="version" content="1.0.3"/>

    <link rel="stylesheet" href="assets/styles/vi_padrao.scss">
    <link rel="stylesheet" href="assets/styles/vi_acessos.scss">
    <!-- scripts -->
    <script src="scripts/load_compet.js?nocache='<?php echo time(); ?>'"></script> 
    <script src="scripts/load_municipios.js?nocache='<?php echo time(); ?>'"></script> 
    <script src="scripts/load_statusmuni.js?nocache='<?php echo time(); ?>'"></script> 
    <script src="scripts/load_users.js?nocache='<?php echo time(); ?>'"></script>
    <script src="scripts/vi_acessos.js?nocache='<?php echo time(); ?>'"></script>

</head>
<body>
    <img src='<?php echo $check_on; ?>' id="img_check_on" style="display:none;">
    <img src='<?php echo $check_off; ?>' id="img_check_off" style="display:none;">
    <img src='<?php echo $radio_on; ?>' id="img_radio_on" style="display:none;">
    <img src='<?php echo $radio_off; ?>' id="img_radio_off" style="display:none;">
    <img src='<?php echo $noorder; ?>' id="img_noorder" style="display:none;" >
    <img src='<?php echo $fullorder; ?>' id="img_fullorder" style="display:none;" >
    <img src='<?php echo $pageorder; ?>' id="img_pageorder" style="display:none;" >
    <input type="hidden" name="id_user" class="id_user" value="<?php echo $id_user; ?>" />
    <input type="hidden" name="tp_user" class="tp_user" value="<?php echo $tp_user; ?>" />

    <div class="generic"></div>
    <div class="row">
        <!-- First card with 70% width -->
        <div class="col-md-7" style="width:70%;">
            <div class="card card-transparent float-left rounded-2" style='margin: 0; padding:1; box-sizing: border-box;'>
                <div class="card-body filtros">
                    <div class="d-flex justify-content-between gap-3">
                        <select class="form-select-sm-5" id="sel_competencias" style="width:20vw;"></select>
                        <select class="form-select-sm-8" id="sel_municipios" style="width:35vw;"></select>
                        <select class="form-select-sm-6" id="sel_statusmuni" style="width:35vw;"></select>
                        <select class="form-select-sm-5" id="sel_users" style="width:32vw;"></select>
                        <button class="form-control bg-primary text-white fn-bold" id="bt_filter" style='width:15vw !important;'>Filtrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second card with 30% width -->
        <div class="col-md-4" style="width:30%;">
            <div class="card card-transparent">
                <div class="card-body">
                    <label class='bg-white text-dark' for="total_rec">Total de registros:</label>
                    <input type="text" class="bg-white text-dark text-center  border-1 rounded" size="6" id="total_rec" name="total_rec" readonly>
                </div>
            </div>
        </div>
    </div>
   
    <div class="content-fluid  table-responsive tableFixHead ">
        <table class='table tb_municipios table-borderless md-5'>
            <thead class="sticky-top bg-darkblue text-white" style="background-color: darkblue; color: #fff;">
                <tr>
                    <th class='col-sm-auto' style='width:3% !important;text-align: center;'>UF<span class='d-flex float-end'><img src='<?php echo $noorder; ?>' data-order='img_noorder' id='ordenar' tabindex='0' /></span></th>
                    <th class='col-sm-auto' style='width:10.4% !important;text-align: center;'>Município<span class='d-flex float-end'><img src='<?php echo $fullorder; ?>' data-order='img_fullorder' id='ordenar' tabindex='1' /></span></th>
                    <th class='col-sm-auto' style='width:10.4% !important;text-align: center;'>Responsável<span class='d-flex float-end'><img src='<?php echo $noorder; ?>' data-order='img_noorder' id='ordenar' tabindex='2' /></span></th>
                    <th class='col-sm-auto' style='width:5.86% !important;text-align: center;'>Status<span class='d-flex float-end'><img src='<?php echo $noorder; ?>' data-order='img_noorder' id='ordenar' tabindex='3' /></span></th>
                    <th class='col-sm-auto' style='width:5.5% !important;text-align: center;'>Valor ISS<span class='d-flex float-end'><img src="<?php echo $noorder; ?>" data-order='img_noorder' id='ordenar' tabindex='4' /></span></th>
                    <th class='col-sm-auto' style='width:3.5% !important;text-align: center;'>Obrigação</th>
                    <th class='col-sm-auto' style='width:3% !important;text-align: center;'>SLA OA</th>
                    <th class='col-sm-auto' style='width:3.5% !important;text-align: center;'>Guia</th>
                    <th class='col-sm-auto' style='width:3% !important;text-align: center;'>SLA</th>
                    <th class='col-sm-auto' style='width:3.5% !important;text-align: center;'>Recolhimento</th>
                    <th class='col-sm-auto' style='width:3% !important;text-align: center;'>SLAR</th>
                    <th class='col-sm-auto' style='width: 0.85% !important;text-align:center;'>Desif</th>
                    <th class='col-sm-auto' style='width:5.5% !important;text-align:center;'>Dem. Contábil</th>
                    <th class='col-sm-auto' style='width:6.5% !important;text-align:center;'>Apuração mensal ISS</th>
                    <th class='col-sm-auto' style='width:7.5% !important;text-align:center;'>Info. comuns nos municípios</th>
                    <th class='col-sm-auto' style='width:8.5% !important;text-align:center;'>Dem. partilhas dos lançamentos contábeis</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="tbody_municipios" class='bg-white text-dark'>
            </tbody>
            <tfoot class='bg-transparent' class='sticky-bottom' style="border:none; ">
                <tr>
                    <td scope='row' colspan='4'>
                        <div class="card card-trasparent ">
                            <div class="card-body">
                                <img src="#" width='16px' alt='first' id='first'>
                                <img src="#" width='16px' alt='prev' id='prev'>
                                <input type='text' class='rounded text-white text-center bg-dark' value='1' size='3' maxlength='3' id="sel-page">
                                <img src="#" width='16px' alt='next' id='next' class='rotate-180'>
                                <img src="#" width='16px' alt='last' id='last' class='rotate-180'>
                            </div>
                        </div>
                    </td>
                    <td scope='row' colspan='4' >
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
                    <td colspan='8'>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="waiting">
        <img src='#' width='32px' id="spin" alt='waiting'>
    </div>

 </body>