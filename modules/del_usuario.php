<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ('../classes/cls_usuarios.php');
    $id_user = $_GET['id_user'];
    $oUser = new cls_usuarios($id_user);
    $result = $user->delCursor();
    echo json_encode($result);
?>