<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_gravar_tbgruposxservicos.php";
    $id_grupo = $_GET[ "id_grupo"];
    $id_servico= $_GET["id_servico"];
    $id_servmuni= $_GET["id_servmuni"];
    $dt_ini_vigencia= $_GET["dt_ini_vigencia"];
    $dt_fim_vigencia= $_GET["dt_fim_vigencia"];
    $vl_percIR= $_GET["vl_percIR"];
    $vl_percISS= $_GET["vl_percISS"];
    $vl_percISSBI= $_GET["vl_percISSBI"];
    $vl_percPCC= $_GET["vl_percPCC"];
    $vl_percINSS= $_GET["vl_percINSS"];
    $bitributacao= $_GET["bitributacao"];

    $ogruposervicos = new cls_gravar_tbgruposxservicos($id_grupo, $id_servico, $id_servmuni, $dt_ini_vigencia,
                                                       $dt_fim_vigencia,$vl_percIR,$vl_percISS, $vl_percISSBI, 
                                                       $vl_percINSS,$vl_percPCC,$bitributacao);
    $result = $ogruposervicos->gravar_gruposervico();
    echo json_encode($result);
?>