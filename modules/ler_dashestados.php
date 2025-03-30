<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_estados.php";
    $sel_dash = $_GET["sel_dash"];
    $oEstados = new cls_estados($sel_dash);
    $result = $oEstados->getDashEstados();
    echo json_encode($result);
?>