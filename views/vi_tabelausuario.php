<head>
    <script src="scripts/vi_tabelausuarios.js"></script>
    <script src="scripts/load_tabelausuarios.js"></script>

    <link rel="stylesheet" href="assets/styles/tabelausuarios.css">
</head>

<div class="generic"></div>
<div class="container mt-4">
    <div id="modal"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4> Lista de usuarios
                        <a type="button" id="btcriar" class="btn btn-primary float-end">Adicionar usuario</a>
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Tabla con encabezado -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Codigo</th>
                                <th>Status</th>
                                <th>Tipo Usuário</th>
                                <th>Estados</th>
                                <th>Municipios</th>
                                <th>Agencias</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>
                        <tbody></tbody> <!-- Aquí se insertarán las filas dinámicamente -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
