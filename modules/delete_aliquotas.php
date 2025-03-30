<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_notastributos.php";
    $id_nota = $_GET["id_nota"];
    $onotas_aliquotas = new cls_notastributos($id_nota);
    $result = $onotas_aliquotas->delete_Aliquotas();
    echo json_encode($result);
?>