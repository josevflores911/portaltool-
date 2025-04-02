<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once '../classes/cls_tbagencias.php';

    $id_agencia = $_GET["id_agencia"];
    $cd_tipo = $_GET["cd_currposition"];
    if (isset($_GET['cd_responsavel'])) {
        $cd_responsavel = $_GET["cd_responsavel"];
    } else {
        $cd_responsavel = "N";
    }

    $oAgencia = new cls_tbagencias($id_agencia);
    $result = $oAgencia->getAnalista($cd_tipo, $cd_responsavel);
    echo json_encode($result);
    
?>