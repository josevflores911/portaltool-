<head>
    <!-- scss -->
    <meta http-equiv="Pragma" content="no-cache, no-store">
    <meta name="version" content="1.0.3"/>
    <link rel="stylesheet" href="assets/styles/form_incluirservico.scss">

    <script src="scripts/form_incluirservico.js"></script>
</head>

<div class="modal fade" id="show_incluirservicos" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="show_incluirservicos" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark content-fluid modal-centered">
            <div class="modal-header">
                <h5 class="text-white">Incluir novo serviço no município <label class='text-white'></label></h5>
                <button type="button" class="modal-close text-white">&times;</button>
            </div>
            <div class="modal-body bg-transparent">
                <div class="row-fluid row-group justify-content-between">
                    <div class="col col-sm-3 flex-start">
                        <label class="form-control text-white bg-dark" for="cd_estado" style="position:relative;top:0.65em;"><b>Estado:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-8 flex-start">
                        <select class="form-select selectpicker text-dark bg-white" data-cd_uf='SP' name="cd_estado" id="cd_estado" data-live-search="true">
                        </select>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-3 flex-start">
                        <label class="form-control text-white bg-dark" for="nm_muni" style="position:relative;top:0.65em;"><b>Município:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-8 flex-start">
                        <select class="form-select selectpicker text-dark bg-white" data-nm_muni='' name="nm_muni" id="nm_muni" data-live-search="true" style="float:left">
                        </select>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-3 flex-start" ><label class="form-control text-white bg-dark" for="sel-group"><b>Grupo de Serviço:</b></label></div>
                    <div class="col col-sm-8 flex-start">
                        <select class="form-select selectpicker text-dark bg-white" name="sel-group" id="sel-group" data-live-search="true" style="float:left">
                        </select>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-3 flex-start" ><label class="form-control text-white bg-dark" for="sel-servfederal"><b>Serviço Federal (Lei Compl. 116/2003):</b></label></div>
                    <div class="col col-sm-8 flex-start">
                        <select class="form-select selectpicker text-dark bg-white" name="sel-servfederal" id="sel-servfederal" data-live-search="true" style="float:left">
                        </select>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-2 flex-start br-1">
                        <label class="form-control text-white bg-dark" for="dt_inivigencia"><b>Início de Vigência:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-4 flex-start" >
                        <input type="date" class="form-control text-dark bg-white input-sm rounded" data-dt_emissao='' id="dt_inivigencia">
                    </div>
                    <div class="col col-sm-2 flex-start">
                        <label class="form-control text-white bg-dark" for="dt_fimvigencia"><b>Fim de Vigência:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-4 flex-start" >
                        <input type="date" class="form-control text-dark bg-white input-sm rounded" id="dt_fimvigencia">
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-2 flex-start">
                        <label class="form-control text-white bg-dark" for="cd_servmuni"><b>Código serviço municipal:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-4 flex-start" >
                        <input type="text" class="form-control text-dark bg-white input-sm rounded" size="8" maxlength="8" id="cd_servmuni">
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-2 flex-start">
                        <label class="form-control text-white bg-dark" for="te_servmuni"><b>Descrição serviço municipal:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-4 flex-start" >
                        <textarea class="form-control text-dark bg-white input-sm rounded" rows="8" cols="60" id="te_servmuni"></textarea>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                    <div class="col col-sm-2 flex-start">
                        <label class="form-control text-white bg-dark" for="te_resptributaria"><b>Resp. Tributária:</b></label>&nbsp;-&nbsp;
                    </div>
                    <div class="col col-sm-4 flex-start" >
                        <textarea class="form-control text-dark bg-white input-sm rounded" rows="8" cols="60" id="te_resptributaria" placeholder="opcional"></textarea>
                    </div>
                </div>
                <div class="row-fluid row-group  justify-content-between">
                   <div class="col col-sm-3 flex-start" >
                        <label class="form-control text-white bg-dark" for="vl_percali" style="position:relative;top:0.5em;"><b>Perc. ISS Municipal:</b></label>
                    </div>
                    <div class="col col-sm-8 flex-start" >
                        <input type="text" class="form-control float-start input-sm input-sm-20 w-20 bg-white text-dark rounded" id="vl_percali" size='4' maxlength='5'>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-dark text-white">
                <div class="d-inline-flex row-fluid row-group">
                    <div class="col col-md-6 float-start">
                        <label for="" class="bg-dark text-white" id="lbl_message" style="float:left;font-style:italic;"></label>
                    </div>
                    <div class="col col-md-6 float-end">
                        <button class="btn btn-danger text-center text-white" data-bs-dismiss="modal" id="cancelar_servico" title="Cancela a edição" style="float:right;margin-right:1.5em;">Cancelar</button>
                        <button class="btn btn-primary text-center text-white" id="gravar_servico" title="Gravar novo serviço" style="float:right;">Gravar</button>
                   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>