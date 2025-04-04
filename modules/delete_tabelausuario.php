<?php
    error_reporting(0);
    error_reporting(E_ALL);
    
    $userid=$_POST['id_user'];

    require_once ("../classes/cls_tabelausuario.php");

    $users = new cls_tabelausuario();
    $result = $users->deleteUser($userid);

    echo json_encode($result);
?>