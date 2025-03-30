<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_updatenotasxaliquotas.php";
    $id_nota= $_GET["id_nota"];
    $id_user= $_GET["id_user"];
    $id_servicoxgrupo = $_GET["id_servicoxgrupo"];
    $vl_base= $_GET["vl_base"];
    $vl_percIR= $_GET["vl_percIR"];
    $vl_percISS= $_GET["vl_percISS"];
    $vl_percPCC= $_GET["vl_percPCC"];
    $vl_percINSS= $_GET["vl_percINSS"];
    $vl_percISSBI= $_GET["vl_percISSBI"];
    $cd_servmuni= $_GET["cd_servmuni"];
    $te_servmuni= $_GET["te_servmuni"];

    $onotas_aliquotas = new cls_updatenotasxaliquotas($id_nota, $id_user, $id_servicoxgrupo, $vl_base, 
                                                    $vl_percIR, $vl_percISS, $vl_percPCC, 
                                                    $vl_percINSS, $vl_percISSBI, $cd_servmuni, $te_servmuni);

    $result = $onotas_aliquotas->gravar_notasAliquotas();
    echo json_encode($result);
?>