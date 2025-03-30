<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../../classes/cls_tabelausuario.php";

    $nm_estado = $_POST["nm_estado"];
    
    $object = new cls_tabelausuario();
    $result = $object->getAllCountyByState( $nm_estado);
    echo json_encode($result);
?>