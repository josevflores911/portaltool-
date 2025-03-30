<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_notastributos.php";
    $id_nota= $_GET['id_nota'];
    $cd_aliquota= $_GET['nm_aliquota'];
    if (isset($_GET['cs_original'])) {
        $cs_original = $_GET['cs_original'];
    } else {
        $cs_original = NULL;
    }
    $onotas_aliquotas = new cls_notastributos($id_nota);
    $valiquotas = $onotas_aliquotas->getAliquota($cd_aliquota);
    echo json_encode($valiquotas);
?>