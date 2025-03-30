<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $cd_tipo= $_GET["cd_tipo"];
    include_once "../classes/cls_tomadores.php";
    $oEmpresa = new cls_tomadores(NULL,$cd_tipo);
    $result = $oEmpresa->getDashEmpresa();
    echo json_encode($result);
?>