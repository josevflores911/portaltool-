<?php
    error_reporting(0);
    error_reporting(E_ALL);
    if (isset($_GET['cd_estado'])) {
        $cd_estado = $_GET['cd_estado'];
    } else {
        $cd_estado = NULL;
    }
    require_once ("../classes/cls_municipios.php");

    $omuni = new cls_municipios();
    $result = $omuni->getMuniByEstado($cd_estado);
    echo json_encode($result);
?>