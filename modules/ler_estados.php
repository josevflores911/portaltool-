<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_estados.php";
    $oEstados = new cls_estados();
    $list_estados = $oEstados->getData();
    $result = ($list_estados) ? json_encode($list_estados): json_encode(array());
    echo $result;
?>