<head>
   <script src="scripts/vi_tabelausuarios.js"></script>
   <script src="scripts/load_tabelausuarios.js"></script>
   
   <!-- <link rel="stylesheet" href="public/styles.css"> -->
   <script src="scripts/load_statusmuni.js?nocache='<?php echo time(); ?>'"></script> 

</head>

<div class="generic"></div>
<div class="container mt-4">
   <?php //include('mensagem.php'); ?>
   <div id="modal"></div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h4> Lista de usuarios
                  <a type="button" id="btcriar" class="btn btn-primary float-end">Adicionar usuario</a>
               </h4>
            </div>

            <div style="height:500px;background:white;">

            hola

      

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

        
    </div>
   


            </div>
            <div class="card-body">
               <table class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Codigo</th>
                        <th>Tipo Usuário</th>
                        <th>Estados</th>
                        <th>Municipios</th>
                        <th>Agencias</th>
                        <th>inicio</th>
                        <th>fim</th>
                        <!-- ate 90 dias -->
                     </tr>
                  </thead>


                  <tbody>
                     <tr>
                        <td>1</td>
                        <td>Juan Pérez</td>
                        <td>1234ABCD</td>
                        <td>Administrador</td>
                        <td>São Paulo</td>
                        <td>São Paulo</td>
                        <td>Agência A</td>
                        <td>01/01/2023</td>
                        <td>01/01/2024</td>
                        <td>
                           <div style="display:flex; flex-direction:row; justify-content:space-around">
                              <div>
                                 <a href="views/edit-view.php?id=1" class="btn btn-success btn-sm">Editar</a>
                              </div>
                              <div>
                                 <form action="services/controller.php" method="POST" class="d-inline">
                                    <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="1" class="btn btn-danger btn-sm">
                                    <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                    </button>
                                 </form>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td>2</td>
                        <td>Ana Souza</td>
                        <td>5678EFGH</td>
                        <td>Usuário</td>
                        <td>Rio de Janeiro</td>
                        <td>Rio de Janeiro</td>
                        <td>Agência B</td>
                        <td>15/02/2023</td>
                        <td>15/02/2024</td>
                        <td>
                           <div style="display:flex; flex-direction:row; justify-content:space-around">
                              <div>
                                 <a href="views/edit-view.php?id=2" class="btn btn-success btn-sm">Editar</a>
                              </div>
                              <div>
                                 <form action="services/controller.php" method="POST" class="d-inline">
                                    <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="2" class="btn btn-danger btn-sm">
                                    <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                    </button>
                                 </form>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td>3</td>
                        <td>Carlos Silva</td>
                        <td>9101IJKL</td>
                        <td>Supervisor</td>
                        <td>Minas Gerais</td>
                        <td>Belo Horizonte</td>
                        <td>Agência C</td>
                        <td>20/03/2023</td>
                        <td>20/03/2024</td>
                        <td>
                           <div style="display:flex; flex-direction:row; justify-content:space-around">
                              <div>
                                 <a href="views/edit-view.php?id=3" class="btn btn-success btn-sm">Editar</a>
                              </div>
                              <div>
                                 <form action="services/controller.php" method="POST" class="d-inline">
                                    <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="3" class="btn btn-danger btn-sm">
                                    <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                    </button>
                                 </form>
                              </div>
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>