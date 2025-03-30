<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_servicosmuni.php";
    $id_muni = $_GET['id_muni'];
    $id_servico = $_GET['id_servico'];
    $cd_servmuni = $_GET['cd_servmuni'];
    if (isset($_GET['cd_subgrupomuni'])) {
        $cd_subgrupomuni = $_GET['cd_subgrupomuni'];
        if (strlen($cd_subgrupomuni) == 0) {
            $cd_subgrupomuni = NULL;    
        }
    } else {
        $cd_subgrupomuni = NULL;
    }
    $te_servmuni = $_GET['te_servmuni'];
    $oservmuni = new cls_servicosmuni($id_muni, $id_servico, $cd_servmuni, $cd_subgrupomuni, $te_servmuni);
    $result = $oservmuni->gravar_servmuni();
    echo json_encode($result);
?>