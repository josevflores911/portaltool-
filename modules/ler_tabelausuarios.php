<?php
    error_reporting(0);
    error_reporting(E_ALL);
    
    require_once ("../classes/cls_tabelausuario.php");

    $ouser = new cls_tabelausuario();
    $result = $ouser->getCursor();

    echo json_encode($result);
?>