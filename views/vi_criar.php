<!-- Modal -->
<div class="modal fade" id="criar_usuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Criar usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
        

        <!-- <div class="container mt-5"> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Adicionar usuario
                                
                            </h4>
                        </div>

                        <div class="card-body">
                    <form action="../services/controller.php" method="POST">
                        <!-- Primeira linha (nome, código e senha) -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" maxlength="50" placeholder="Ingresse seu nome" required>
                            </div>
                            <div class="col-md-4">
                                <label>Código</label>
                                <input type="text" name="codigo" class="form-control" maxlength="20" minlength="4" placeholder="Ingresse o código" required>
                            </div>
                            <div class="col-md-4">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control" maxlength="20" placeholder="Ingresse sua senha" required>
                            </div>
                        </div>

                        <!-- Segunda linha (tipo de usuário, estado, município e agência) -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Tipo de Usuário</label>
                                <select name="tipo_usuario" class="form-select" required>
                                    <option value="" disabled selected>Selecione um tipo de usuário</option>
                                    <?php
                                    if (mysqli_num_rows($tipos) > 0) {
                                        while ($tipo = mysqli_fetch_assoc($tipos)) {
                                            echo "<option value='{$tipo['id']}'>{$tipo['tipo']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Estado</label>
                                <select id="estado" name="estado" class="form-select" required>
                                    <option value="" disabled selected>Selecione um estado</option>
                                    <?php
                                    if (mysqli_num_rows($estados) > 0) {
                                        while ($estado = mysqli_fetch_assoc($estados)) {
                                            echo "<option value='{$estado['id']}'>{$estado['nome']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="municipio">Municipio:</label>
                                <select id="municipio" name="municipio" class="form-select"  required disabled>
                                    <option value="">Seleccionar Municipio</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="agencia">Agência:</label>
                                <select id="agencia" name="agencia" class="form-select"  required disabled>
                                    <option value="">Seleccionar Agência</option>
                                </select>
                            </div>

                           
                        </div>

                        <!-- Terceira linha (período início e fim) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Período - Início</label>
                                <input id="data_inicio" type="date" name="inicio_periodo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Período - Fim</label>
                                <input id="data_fim" type="date" name="fim_periodo" class="form-control" required>
                            </div>
                        </div>

                        
                    </form>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        <!-- </div> -->

        <script>
            // Define a data atual no formato YYYY-MM-DD para o campo de data
            const today = new Date().toISOString().split('T')[0];

            document.getElementById('data_inicio').value = today;
            document.getElementById('data_fim').value = today;
        </script>


         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
            <button type="button" class="btn btn-primary">Salvar</button>
         </div>
      </div>
   </div>
</div>

<!-- <div class="mb-3" >
<button type="submit" name="create_usuario" class="btn btn-primary mr-2">Salvar</button>
<a href="../index.php" class="btn btn-danger floar-end">Voltar</a>
</div> -->