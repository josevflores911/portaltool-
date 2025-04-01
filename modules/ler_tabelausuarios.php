<?php
    error_reporting(0);
    error_reporting(E_ALL);
    
    require_once ("../classes/cls_tabelausuario.php");

    $users = new cls_tabelausuario();
    $result = $users->getAllActiveUsers();

    echo json_encode($result);
?>