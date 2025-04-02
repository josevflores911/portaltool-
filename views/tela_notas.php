<?php
    error_reporting(0);
    error_reporting(E_ALL);
    if (isset($_POST['id_agencia'])) {
        $id_agencia = $_POST['id_agencia'];
    } else {
        $id_agencia = NULL; // Default value
    }

?>
<head>
    <script src="scripts/vi_cadastro.js?v=1.10"></script>
    <style>
        .modal-notas > .modal-header {
            background-color: darkblue;
            color: white;
        }
        .table-responsive .table-notas {
            display: block;
            border-radius: 3;
            border: 1px solid darkblue;
            overflow: auto;
            width: 100%!important;
            height:55vh !important;
            max-width: 150%;
            max-height:95vh;
            overflow: auto;
        }
    </style>
    <script src="scripts/tela_notas.js?v=1.10"></script>
<head>
    <input type="hidden" id="id_agencia" value="<?php echo $id_agencia; ?>" />
    class="modal fade" id="modal-notas" tabindex="-5" aria-labelledby="modalNotaLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header" >
                <h5 class="modal-title text-white text-center" id="modalNotasLabel">Notas Tomadas</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid align-items-center p-0">
                    <div class="container-fluid p-0">
                        <div class="card card-notas mx-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-notas table-sm table-hover table-striped table-bordered">
                                        <thead class='sticky-top'>
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
                                    </table>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>