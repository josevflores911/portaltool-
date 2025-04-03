<?php
    error_reporting(0);
    error_reporting(E_ALL);
    if (isset($_POST['id_agencia'])) {
        $id_agencia = $_POST['id_agencia'];
    } else {
        $id_agencia = NULL; // Default value
    }

    if (isset($_POST['dt_compet'])) {
        $dt_compet = $_POST['dt_compet'];
    } else {
        $dt_compet = NULL; // Default value
    }
    require_once '../classes/cls_loadimg.php';
    $oImg = new cls_loadimg();
    $first = $oImg->getImageUrl("first")["source"];
    $last = $oImg->getImageUrl("last")["source"];
    $next = $oImg->getImageUrl("next")["source"];
    $prev = $oImg->getImageUrl("prev")["source"];
    $waiting = $oImg->getImageUrl("spin")["source"]
?>
<head>
    <style>
        .waiting-notas {
            position: fixed;
            top:50%;
            left: 50%;
            transform: translate(-50%, -50%) !important;
            z-index: 1000;
        }
        #modal-notas .modal-dialog {
            display: flex;
            position: fixed;
            top: 50%;
            left: 50%;
            width: 960pt !important;
            transform: translate(-50%, -50%);
            height: max-content;
            align-items: center;
            justify-content: center;
            overflow-y:none;
            z-index: 1050;
        }
        #modal-notas > .modal-header {
            background-color: darkblue;
            color: white;
        }
        table.table-notas {
            display: block;
            flex-grow: 1;
            table-layout: auto;
            width: 100% !important;
            max-width: 120%;
            height: 90% !important;
            max-height: 150% !important;
            margin-bottom: 1rem;
            border-collapse: collapse;
            background-color: white
        }
        table.table-notas tbody {
            color: white;
            width: 100%!important;
            overflow-y: auto;
            height: 60vh !important;
            border-collapse: collapse;
            max-height: 120%;
        }

        table.table-notas thead {
            background-color: darkblue;
            color: white;
            width: 100%!important;
        }
        table.table-notas > tfoot {
            background-color: darkblue;
            position: sticky;
            bottom: 0; /* Keeps it at the bottom */
            z-index: 1; /* Ensures it remains above table content */
        }
        .table-responsive {
            display: block;
            border-radius: 3;
            border: 1px solid darkblue;
            overflow: auto;
            width: 100%!important;
            height:70vh !important;
            max-width: 145%;
            max-height:95vh;
        }
    </style>
    <script src="scripts/tela_notas.js?v=1.10"></script>
<head>
    <input type="hidden" id="id_agencia" value="<?php echo $id_agencia; ?>" />
    <input type="hidden" id="dt_compet" value="<?php echo $dt_compet; ?>" />
    <div class="modal fade" id="modal-notas" tabindex="-5" aria-labelledby="modalNotaLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" >
                    <h5 class="modal-title text-white text-center" id="modalNotasLabel">Notas Tomadas</h5>
                    <button type="button" class="btn-close bg-transparent text-white">X</button>
                </div>
                <div class="modal-body" style='width: 100% !important; max-width: 1400px; height:100%; max-height:120%; overflow: auto;'>
                    <div class="table-responsive tableFixHead">
                        <table class="table table-notas table-sm table-hover table-striped table-borderless table-wrapper">
                            <thead class='sticky-top' style='white-space: nowrap;'>
                                <tr>
                                    <th scope="col">UF</th>
                                    <th scope="col">Bairro Fornecedor</th>
                                    <th scope="col">Endereço Fornecedor</th>
                                    <th scope="col">CEP Fornecedor</th>
                                    <th scope="col">Tp Doc</th>
                                    <th scope="col">Nº Registro</th>
                                    <th scope="col">Cd. Serv.</th>
                                    <th scope="col">Nº Doc.</th>
                                    <th scope="col">Dt Retenção</th>
                                    <th scope="col">Dt Compet</th>
                                    <th scope="col">Dt Venc.</th>
                                    <th scope="col">Vl Total</th>
                                    <th scope="col">Vl Base</th>
                                    <th scope="col">Alíq</th>
                                    <th scope="col">Valor ISS</th>
                                    <th scope="col">Just</th>
                                    <th scope="col">Diverg. Alíq</th>
                                    <th scope="col">Diverg. Vl</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class='sticky-bottom' style='height:18px !important;'>
                                <tr class='justify-content-around' style='width:100%'>
                                    <td scope='row' colspan='5'>
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
                                    <td scope='row' colspan='5' >
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
                                    <td scope='row' colspan='4' >&nbsp;</td>
                           
                                    <td colspan='4'>&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="waiting-notas">
                       <img src='<?php echo $waiting;?>' width='32px' id="spin" alt='waiting'>
                    </div>
                </div>
            </div>
        </div>
    </div>