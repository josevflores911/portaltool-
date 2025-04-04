<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ('../classes/cls_aliquotas.php');
    $id_muni = $_GET['id_muni'];
    $id_grupo = $_GET['id_grupo'];
    $id_servmuni = $_GET['id_servmuni'];
    $dt_ini = $_GET["dt_ini_vigencia"];
    $dt_fim = $_GET["dt_fim_vigencia"];
    $oaliquotas = new cls_aliquotas($id_muni);
    $result = $oaliquotas->lerAliquotas($id_grupo,$id_servmuni, $dt_ini, $dt_fim);
    echo json_encode($result);
?>