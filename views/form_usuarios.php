<?php
    $id_user = $_POST['id_user'];
    include_once("../classes/cls_usuarios.php");
    $oUser = new cls_usuarios($id_user);
    $vtipo = $oUser->getTipoUser($id_user);
    $cd_tipo = $vtipo['id_tipo'];
    $system = $oUser->is_UserSYS();
    $vtypes = $oUser->getUserTypes();
?>
<head>
    <script src="form_usuarios.js"></script>
    <style>
        input, label {
           font-size: 10pt;
        }
        button#bt-validar {
            display: block;
            width: 4.87rem!important;
            height: 1.65rem!important;
            padding:0px;
            float: right;
            font-size: 8pt;
        }
        button#bt-clear {
            display: block;
            width: 4.87rem!important;
            height: 1.65rem!important;
            padding:0px;
            float: right;
            margin-right:5px;
        }

    </style>
</head>
<div class="row justify-content-center align-items-center rounded-t-5">
    <div class="card d-flex wd-60">
        <div class="card-content">
            <div class="card-header wd-60 bg-dark">
                <div class="card-title text-center text-white">Cadastro de usuários</div>
            </div>
            <div class="card-body d-flex wd-60  bg-white">
                <form class="card-text form-login" action="" autocomplete="off">
                    <div class="mb-3">
                        <div class="input-group" style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="username" class="form-label text-dark bg-white">Nome:</label></span>
                            <input type="text" class="form-control" id="username" placeholder="Nome do usuário" size='40' maxlength="60" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group" style="background-color: white; color:black;">      
                            <span class="input-group-text" id="basic-addon1"><label for="te_email" class="form-label">Email:</label></span>
                            <input type="email" class="form-control" id="te_email" placeholder="Email" aria-label="te_email" maxlength="120" size="60" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group" style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="te_senha" class="form-label">Senha</label></span>
                            <input type="password" class="form-control" id="te_senha" placeholder="********" size="20" maxlength="20" aria-lable="te_senha" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group" style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="te_senha2" class="form-label">Redigite Senha</label></span>
                            <input type="password" class="form-control" id="te_senha2" placeholder="********" size="20" maxlength="20" aria-lable="te_senha" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group"style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="sel_tpuser" class="form-label">Tipo de usuário</label></span>
                            <select class="form-control select-picker" aria-labelledby="basic-addon1" id="sel_tpuser">
                                <?php
                                    foreach ($vtypes as $row) {
                                        $id_tipo = $row['id_tipo'];
                                        $te_tipo = $row['te_tipo'];
                                        echo "<option value='$id_tipo'>$te_tipo</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="input-group"style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="tp_empresa" class="form-label">Tipo prestador</label></span>
                            <div class="row mb-5">
                                <div class="col col-sm-5">
                                    <input type="radio" class="form-control" value='M' id="tp_empresa"><label class="form-label">Matriz</label>
                                </div>
                                <div class="col col-sm-5">
                                    <input type="radio" class="form-control" value='F' id="tp_empresa"><label class="form-label">Filial</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group"style="background-color: white; color:black;">
                            <span class="input-group-text" id="basic-addon1"><label for="sel_prestadores" class="form-label">Prestadores</label></span>
                            <select class="form-control select-picker" aria-labelledby="basic-addon1" id="sel_prestadores">
                            </select>
                        </div>
                    </div>



                </form>
            </div>
            <div class="card-footer  bg-dark" style="height:-4.5em;">
                <div class="form-group">
                    <label class="message justify-content-center text-white float-start" style="white-space: nowrap;"></label>
                </div>
                <div class="form-group waiting"><img src='assets/images/spin-wait.gif' id="spin" width='32px' alt='waiting'></div>
                <div class="form-group">
                    <Buttom type="button" id="btenviar" class="btn-primary float-end cursor-pointer">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</div>