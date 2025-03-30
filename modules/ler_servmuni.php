<?php
    error_reporting(0);
    error_reporting(E_ALL);
    if (isset($_GET['dt_emissao'])) {
        $data = $_GET['dt_emissao'];
    } else {
        $data = NULL;
    }
    if (isset($_GET['id_grupo'])) {
        $id_grupo = $_GET['id_grupo'];
    } else {
        $id_grupo = NULL;
    }
    $id_muni = $_GET['id_muni'];
    if (isset($_GET['cd_servmuni'])) {
        $cd_servmuni = $_GET['cd_servmuni'];
        $bpesq_servico=TRUE;
    } else {
        $cd_servmuni = NULL;
        $bpesq_servico=FALSE;
    }
    
    if (isset($_GET['cd_subgrupomuni'])) {
        $cd_subgrupomuni = $_GET['cd_subgrupomuni'];
    } else {
        $cd_subgrupomuni = NULL;
    }
    require_once '../classes/cls_lerservicos.php';
    if ($bpesq_servico == FALSE) {
        $oservico = new cls_lerservicosmuni($id_muni, $id_grupo,$data);
    } else {
        $oservico = new cls_buscaservmuni($id_muni, $data, $cd_servmuni, $cd_subgrupomuni);
    }
    $result = $oservico->getCursor();
    echo json_encode($result);
?>