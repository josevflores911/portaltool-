<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../../classes/cls_tabelausuario.php";

    $object = new cls_tabelausuario();
    $result = $object->getAllStates( );
    echo json_encode($result);
?>
