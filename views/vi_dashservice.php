<head>
    <!-- scss -->
    <meta http-equiv="Pragma" content="no-cache, no-store">
    <meta name="version" content="1.0.3"/>
    <link rel="stylesheet" href="assets/styles/vi_dashservice.scss?v=<?echo time();?>">
    <!-- scripts -->
    <script src="scripts/utils.js?v=<?echo time();?>"></script>
    <script src='scripts/vi_dashservice.js?v=<?php echo time();?>'></script>
    <!-- JQUERY SELECTPICKER -->
   
</head>
<body>
    <div class="container-fluid">
        <input type="hidden" class="hd_periodo1" value="">
        <input type="hidden" class="hd_periodo2" value="">
        <input type="hidden" class="hd_value1"  value="">
        <input type="hidden" class="hd_value2"  value="">
        <input type="hidden" class="hd_cduf"  value="">
        <input type="hidden" class="hd_idmuni"  value="">
        <input type="hidden" class="hd_idprestador"  value="">
        <input type="hidden" class="hd_idtomador"  value="">
        <input type="hidden" class="hd_idtomador"  value="">

       <div class="modal fade" id="sel_periodo" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="show_msgdelete" aria-hidden="true">
            <div class="modal-dialog modal-md modal-centered" >
                <div class="modal-content bg-dark content-fluid" style="width: 20.6vw !important;">
                    <div class="modal-header mw-100 text-center">
                        <h6 class="text-white">Selecione o período</h6>
                        <button type="button" class="modal-close fn-small bg-dark" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body bg-transparent">
                        <div class="row row-fluid justify-content-between">
                            <div class="col-3 float-start">
                               <div class="form1-check br-2">
                                    <input class="form-check-input input-sm" type="checkbox" value="1" id="todo-periodo" checked>
                               </div>
                            </div>
                            <div class="col-8">
                                <label class="form-control-label fn-bold" for="todo">Todo Período</label>
                            </div>
                        </div>

                        <div class="row row-fluid sel-estado justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-estado" class="form-control-label bg-transparent text-white fn-bold">Estado</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-estado" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-muni justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-muni" class="form-control-label bg-transparent text-white fn-bold">Município</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-muni" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-prestadores justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-empresa" class="form-control-label bg-transparent text-white fn-bold">Prestadores</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-prestadores" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-tomadores justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-empresa" class="form-control-label bg-transparent text-white fn-bold">Tomadores</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-tomadores" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-servicos justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-servico" class="form-control-label bg-transparent text-white fn-bold">Serv. Federal</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-servico" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>

                        <br>

                        <div class="row row-fluid" id="sel-periodo" style="display:none">
                            <div class="row row-fluid justify-content-between">
                                <div class="col col-sm-3 br-1">
                                    <label class="form-control-label fn-bold" for="dt-inicio">Início</label>
                                </div>
                                <div class="col-8 float-end">
                                    <input type="date" class="form-control input-sm" id="dt-inicio">
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col col-sm-3 br-1">
                                    <label class="form-control-label fn-bold" for="dt-fim">Fim</label>
                                </div>
                                <div class="col-8 float-end">
                                    <input type="date" class="form-control input-sm" id="dt-fim">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-dark">
                        <div class="d-inline-flex mw-100 float-end">
                            <button type="button" class="btn btn-primary fn-normal limpar" style="float:right;padding:3px; margin-right:0.5em;">Limpar</button>
                            <button type="button" class="btn btn-success button-sm aplicar" style="float:right;padding:3px; margin-right:0.5em;">Aplicar</button>
                        </div>
                    </div>
                </div>
            </div>
       </div> 
       <div class="modal fade" id="sel_valores" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="show_msgdelete" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-centered">
                <div class="modal-content bg-dark content-fluid">
                    <div class="modal-header mw-100 text-center">
                        <h6 class="text-white">Selecione a faixa de valores</h6>
                        <button type="button" class="modal-close fn-small bg-dark" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body bg-transparent">
                       <div class="row row-fluid sel-estado justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-estado2" class="form-control-label bg-transparent text-white fn-bold">Estado</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-estado2" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-muni justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-muni2" class="form-control-label bg-transparent text-white fn-bold">Município</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-muni2" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-prestadores justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-prestadores2" class="form-control-label bg-transparent text-white fn-bold">Prestadores</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-prestadores2" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-tomadores justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-tomadores2" class="form-control-label bg-transparent text-white fn-bold">Tomadores</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-tomadores2" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>
                        <div class="row row-fluid sel-servicos justify-content-between" style="display:none">
                            <div class="col-3 float-start">
                                <label for="select-servico2" class="form-control-label bg-transparent text-white fn-bold">Serv. Federal</label>
                            </div>
                            <div class="col-8">
                                <select class="selectpicker bg-dark text-white"  data-live-search="true" id="select-servico2" style="float:left; font-style:italic; width:auto !important;">
                                </select>
                            </div>
                        </div>

                        <div class="form-flex">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-5">
                                    <div class="input-group-text fn-bold">Valor inicial:</div>
                                </div>
                                <input type="text" class="form-control" id="vl-inicio" size="11" maxlength="22">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-5">
                                    <div class="input-group-text fn-bold">Valor final:</div>
                                </div>
                                <input type="text" class="form-control" id="vl-final" size="11" maxlength="22">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-dark">
                        <div class="row row-fluid float-end">
                            <button type="button" class="btn btn-primary button-sm limpar" style="padding:3px; margin-right:0.5em;">Limpar</button>
                        </div>
                        <div class="row row-fluid float-end">
                            <button type="button" class="btn btn-success button-sm aplicar" style="padding:3px; margin-right:0.5em;">Aplicar</button>
                        </div>
                    </div>
                </div>
            </div>
       </div> 
       <div class="card card-fluid card-top bg-transparent dashboard-1">
            <div class="card-group" >
                <div class="card float-start card-menu card-sm" style="display:block; background:transparent; height:90vh !important;"> 
                    <div class="card-header" style="height:4.8vh !important; background-color: black">
                        <div class="card-title fn-bold text-center"><h6>Menu de opções</h6></div>
                    </div>
                    <div class="card-body text-center" style="max-height: 85vh !important; overflow-y:auto; font-size:auto !important">
                        <div class="form-check">
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="1" name="dash-periodo" id="periodo">
                                </div>
                                <div class="col-sm-10" >
                                    <label for="por-periodo" class="form-control-label fn-bold"  style='font-size:16pt;font-family:Verdana;position:relative; top:-0.12em;'>Período</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="2" name="dash-periodo" id="estado">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-estado" class="form-control-label">Estado</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="3" name="dash-periodo" id="muni">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-muni" class="form-control-label">Município</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="4" name="dash-periodo" id="prestador">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-prestador" class="form-control-label">Prestadores</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="5" name="dash-periodo" id="tomador">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-tomador" class="form-control-label">Tomadores</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="5" name="dash-periodo" id="servico">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-servico" class="form-control-label" >Serviço</label>
                                </div>
                            </div>
                            <br><br>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="6" name="dash-valores" id="por-valores">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-valores" class="form-control-label fn-bold" style='font-size:16pt;font-family:Verdana;position:relative; top:-0.1299em;'>Valores</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="7" name="dash-valores" id="por-estado">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-estado" class="form-control-label">Estado</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="8" name="dash-valores" id="por-muni">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-muni" class="form-control-label">Município</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="9" name="dash-valores" id="por-prestador">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-prestador" class="form-control-label">Prestadores</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="10" name="dash-valores" id="por-tomador">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-tomador" class="form-control-label">Tomadores</label>
                                </div>
                            </div>
                            <div class="row row-fluid justify-content-between">
                                <div class="col-sm-2 br-1">
                                    <input type="radio" class="form-input-check" value="11" name="dash-valores" id="por-servico">
                                </div>
                                <div class="col-sm-10">
                                    <label for="por-servico" class="form-control-label">Serviço</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mw-100">
                        <div class="row row-fluid justify-content-between">
                            <div class="col-sm-4 float-start">
                                <button type="button" class="btn btn-primary button-sm limpar" style="float:left">Limpar</button>
                            </div>
                            <div class="col-sm-4 float-end">
                                <button type="button" class="btn btn-success button-sm aplicar" style="float:right">Executar</button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-fluid panel-1 float-end card-content card-mb-5 card-charts" style="width:86% !important; height:100% !important; display:block"> 
                    <div class="card-header bg-black" style="height:4.8vh !important">
                        <div class="card-title fn-bold text-center"><h6>dashboard</h6></div>
                    </div>
                    <div class="card-body bg-transparent" style="height:75h !important">
                        <div class="dash_charts">
                            <div class="card-group mw-100 ">
                                <div class="card card-container" style='display:block; height:75vh !important;position:relative; top:-5vh;'>
                                    <div class="card-body">
                                        <div class="card-group justify-content-between"  style='height:30vh;width:40vw;font-size:small;'>
                                            <div class="card p-2" style='position:relative; top:3vh'>
                                                <div class='card-header card-top'>
                                                    <div class="chart-qtdnotas">
                                                        <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:9vh;left:50%; '>
                                                            <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="card-body centered">
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-4 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Consumo</label></div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-qtnfc' style='float:right;font-style:italic'>999.999</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-percnfc' style='float:right;font-style:italic'>999.99%</label>
                                                        </div>
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-4 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Serviço</label></div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-nfse' style='float:right;font-style:italic'>999.999</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-percnfse' style='float:right;font-style:italic'>999.99%</label>
                                                        </div>
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-4 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Transporte</label></div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-qtcte' style='float:right;font-style:italic'>999.999</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-perccte' style='float:right;font-style:italic'>999.99%</label>
                                                        </div>
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-4 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Danfes</label></div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-qtdanfe' style='float:right;font-style:italic'>999.999</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-percdanfe' style='float:right;font-style:italic'>999.99%</label>
                                                        </div>
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-4 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Total de notas</label></div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-qttotal' style='float:right;font-style:italic'>9.999.999</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label' style='float:right;font-style:italic'>100,00%</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card p-2"  style='position:relative; top:3vh'>
                                                <div class='card-header card-top'>
                                                    <div class="chart-vlnotas">
                                                        <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:10vh;left:50%; '>
                                                            <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="card-body" style='position:relative; top: 2em; margin-right:0.5em; margin-left:0.5em;'>
                                                    <div class="row row-fluid justify-content-between" style='white-space: nowrap;'>
                                                        <div class="col-5 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>PJ</label>
                                                        </div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-vltotalpj' style='float:right;font-style:italic'>99.999.999.999,99</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-vlperclpj' style='float:right;font-style:italic'>999,99%</label>
                                                        </div>
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-5 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>PF</label>
                                                        </div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-vltotalpf' style='float:right;font-style:italic'>99.999.999.999,99</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label lbl-vlpercpf' style='float:right;font-style:italic'>100,00%</label>
                                                        </div>
    
                                                    </div>
                                                    <div class="row row-fluid justify-content-between">
                                                        <div class="col-5 fn-bold text-white float-start">
                                                            <label class='form-contol-label' style='float:left; white-space:nowrap'>Total</label>
                                                        </div>
                                                        <div class="col-4 text-white float-end">
                                                            <label class='form-contol-label lbl-vltotal' style='float:right;font-style:italic'>999.999.999.999,99</label>
                                                        </div>
                                                        <div class="col-3 text-white float-end">
                                                            <label class='form-contol-label' style='float:right;font-style:italic'>100,00%</label>
                                                        </div>
                                                      
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid mw-78" style='display:block; position:relative; top:8vh; width:32.5vw; height:30vh'>
                                            <div class="col-md-5 chart-line" style="width:100%;">
                                                <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:10vh;left:50%; '>
                                                    <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-container bg-transparent card" style='display:block; height:75vh !important;'>
                                    <div class="card-header">
                                        <div class="card-title text-center text-white">Relação 10+ <label for="" class='form-label text-white' id='lbl_tipo'>Prestadores de Serviço</label</div>
                                    </div>
                                    <div class="card-body" style="width:100%">
                                        <div class="container table-10mais" style='font-size:10pt; '>
                                            <div class="row text-white fw-bold justify-content-between">
                                                <div class="col-6">Razão social</div>
                                                <div class="col-2">Qtd. Nota</div>
                                                <div class="col-2">Vl. Total</div>
                                                <div class="col-2">%</div>
                                            </div>
                                            <div class='row table-body'>
                                                <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:10vh;left:50%; '>
                                                    <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                         <div class="card-group">
                                                <div class="card card-container bg-transparent">
                                                    <div class="card-body mw-50 chart-service" style="width:50%">
                                                        <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:10vh;left:50%; '>
                                                             <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card card-container bg-transparent" style='margin-left:5em;'>
                                                    <div class="card-body mw-50 chart-consumo" style="width:50%">
                                                        <div class= 'waiting' style='width:32px !important; height:32px !important;position:relative; top:10vh;left:50%; '>
                                                            <img src='#' id="spin" alt='waiting' style='width: 30px!important;'>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>
                    <div class="card-footer bg-transparent">
                        <style>
                            section{
                                overflow :hidden;
                                width: 100%;
                                display:inline-block;
                                border: transparent;
                                height: 9.5vh;
                                white-space: nowrap;
                            }

                            .marquee-status{
                                width: 100%;
                                display: flex;
                                position:relative;
                                flex-direction:row;
                                left: 95%;
                                flex-wrap: nowrap;
                                animation: move 25s infinite linear; /* set the time to what you want of course */
                            }
                            @keyframes move {
                                 0% { left: 0; }
                                100% { left: -100%; }
                            }
                            @keyframes move {
                                to {
                                    transform: translateX(-200%);
                                }
                            }
                            .marquee-status > span{
                                font-size: 9pt;
                                float: left;
                                width: 50%;
                                margin-right: 1.5em;
                                margin-left: 1.5em;
                            }
                        </style>
                        <section>
                            <div class="marquee-status">

                            </div>
                        </section>

                    </div>
                </div>

            </div>
       </div>
    </div>
</body>