<head>
   <script src="scripts/listar_notas.js?v=<?php echo time();?>"></script>
   <link rel="stylesheet" href="assets/styles/lista_notas.css?v-<?php echo time(); ?>">
</head>
<?php
     // Inicia sessão e obtém dados do usuário
     if (session_status() === PHP_SESSION_NONE) {
         session_start();
     }

     $id_user = $_POST["id_user"];
     $nm_user = $_POST["nm_user"];
     include_once ("../classes/cls_usuarios.php");
     $ousers = new cls_usuarios($id_user);
     $result = $ousers->getTipoUser($id_user);
     if ($result['Error'] == '0') {
         $tp_user = $result['tp_user'];
     } else {
         $tp_user = '';
     }
    
     $bsystem = $ousers->isAdmin($id_user);

?>
<nav class="navbar-tex">
  <div class="text-center text-white" style="position:absolute;top:1pt; left:50%; transform: translate(-50%,-1%);"><h1>Bem-vindo <?php echo $nm_user; ?></h1></div>
  <div class="row">
      <div id="close_panel"><img class="open-menu" src="assets/images/close_panel.png" width='23rem'></div>
   </div>
</nav>
<div class="principal">
    <div style="width:10px;height:10px;background:red;">a</div><!--borrar-->
</div>

<div class="wrapper">
   <div class="row">
     <div class="logo"><img class="center-block" src="assets/images/globalconsult_logo.gif" alt="Wi logo" width='108pt' style='position:relative; top:4em;'/></div>
   </div>
   <div class="sidebar" style="width:15.5rem;">
      <ul class="list-group">
        <li class="list-group-item text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target='#menu-system' aria-expanded="false" aria-controls="menu-system">
          Módulos
          <span class="badge rounded-pill"><img src="assets/images/btmenu_opt.png" class="rotate-90" id="btmenu"></span>
        </li>
        <div class= "container-full collapse fade" id="menu-system">
            <ul class="list-group">
               <li class="sub-group text-white" id="menu-sistem-1" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Tela de Acesso</li>
               <li class="sub-group text-white" id="menu-sistem-2" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Importação Mensal de ISS</li>
               <li class="sub-group text-white" id="menu-sistem-3" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Importação Diária ISS</li>
               <li class="sub-group text-white" id="menu-sistem-4" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Cadastro de Agências</li>
               <li class="sub-group text-white" id="menu-sistem-6" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Usuários</li>
               <li class="sub-group text-white" id="menu-sistem-7" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Alíquotas</li>
               <li class="sub-group text-white" id="menu-sistem-8" data-user="<?php echo $id_user; ?>" data-tpuser="<?php echo $tp_user; ?>">Cadastro de Usuario</li>
            </ul>
         </div>
         <?php 
         if ($tp_user == 'Sistema' or $tp_user =="Administrador" or $tp_user == "Gestor") 
         { ?>
         <li class="list-group-item d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target='#menu-dashboard' aria-expanded="false" aria-controls="menu-dashboard">
            Dashboard
            <span class="badge rounded-pill"><img src="assets/images/btmenu_opt.png" class="rotate-90" id="btmenu"></span>
         </li>
            <div class= "container-full collapse fade" id="menu-dashboard">
               <ul class="list-group">
                  <li class="sub-group text-white" id="menu-dashboard-1" data-user="<?php echo $id_user; ?>">Dashboard Cadastros</li>
                  <li class="sub-group text-white" id="menu-dashboard-2" data-user="<?php echo $id_user;?>">Dashboard Recolhimentos</li>
                  <li class="sub-group text-white" id="menu-dashboard-3" data-user="<?php echo $id_user;?>">Relatórios</li>
               </ul>
            </div>
         <?php } ?>
        <li class="list-group-item menu-text d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#sair-sistema" aria-expanded="false" aria-controls="sair-sistema">
          Sair do sistema
          <span class="badge rounded-pill"><img src="assets/images/btmenu_opt.png" class="rotate-90" id="btmenu"></span>
        </li>
        <div class="row row-group collapse fade text-center" id="sair-sistema">
            <div class="card body bg-dark">
               <div class="card-text text-center text-white">
                     <label>Deseja sair do sistema?</label>
               </div>
               <div class="card-text">
                     <span class="d-inline-flex float-right sm-4" style='height: 2.5em;'>
                        <input type="button" class="btn btn-success" value="Sim" data-user="<?php echo $id_user;?>" id='button-sim'>&nbsp;&nbsp;
                        <input type="button" class="btn btn-danger" value="Não">
                     </span>
               </div>
               <div class="card-footer" style='display:none;' id='waiting'>
                  <img src='assets/images/spin-wait.gif' width='32px' alt='waiting'>
               </div>
            </div>
         </div>
      </ul>
   </div>
</div>

