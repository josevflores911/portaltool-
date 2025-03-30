<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_aliquotas.php";

    $id_servicoxgrupo = $_GET["id_servicogrupo"];
    $id_user = $_GET["id_user"];
    $tp_aliquota = $_GET["tp_aliquota"];
    $vl_aliquota = $_GET["vl_aliquota"];

    $oaliquota = new cls_aliquotas();
    $result = $oaliquota->update_aliquotas($id_user, $id_servicoxgrupo, $tp_aliquota, $vl_aliquota);
    echo json_encode($result);
?>
