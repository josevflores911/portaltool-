<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_user = $_POST['id_user'];
?>
<head>
    <!-- scss -->
    <meta http-equiv="Pragma" content="no-cache, no-store">
    <meta name="version" content="1.0.3"/>
    <link rel="stylesheet" href="assets/styles/vi_exportatudo.scss">
    <!-- scripts -->
    <script src='./scripts/vi_exportatudo.js'></script>
</head>
<body>
    <div class="container-fluid card-exportatudo">
        <div class="card bg-dark d-flex card-parms">
            <div class="card-header text-center text-white">
                <div class="card-title">Selecione o sistema</div>
            </div>
            <div class="card-body text-center text-white">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exportatudo" id="sys-consumo" value='1'>
                    <label class="form-check-label" for="sys-consumo" style='cursor:pointer'>Notas de consumo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exportatudo" id="sys-servico" value='2'>
                    <label class="form-check-label" for="sys-servico" style='cursor:pointer'>Notas de serviço</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exportatudo" id="sys-transporte" value='3'>
                    <label class="form-check-label" for="sys-transporte" style='cursor:pointer'>Notas de transporte</label>
                </div>
                <br>
                <table class="bg-dark text-white tb-fields">
                    <thead>
                        <tr>
                            <th colspan='2'><b>Selecione os campos</b></th>
                        </tr>
                        <tr>
                            <th colspan='2'>&nbsp;</th>
                        </tr>
                        <tr style="vertical-align:middle">
                            <th><input type="checkbox" value="1" id="todos"></th>
                            <th><b>Todos</b></th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card bg-transparent card-charts">
            <div class="card-header text-center" style="width:65rem">
                <div class="card-title" style="float:center">lista de registros</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">

    
                </div>
                
            </div>
        </div>
    </div>
</body>
