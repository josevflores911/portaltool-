<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $cd_uf = $_GET["cd_uf"];
    include_once "../classes/cls_municipios.php";
    $dash = 1;
    $omuni = new cls_municipios($cd_uf, $dash);
    $result = $omuni->getDashMuni();
    echo json_encode($result);
?>