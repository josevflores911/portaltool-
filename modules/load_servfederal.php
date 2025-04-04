<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ('../classes/cls_lerservicos.php');
    if (isset($_GET['id_grupo'])) {
        $id_grupo = $_GET['id_grupo'];
    } else {
        $id_grupo = NULL;
    }
    if (isset($_GET['dt_inivigencia'])) {
        $dt_inivigencia = $_GET['dt_inivigencia'];
    } else {
        $dt_inivigencia = NULL;
    }

    if (isset($_GET['dt_fimvigencia'])) {
        $dt_fimvigencia = $_GET['dt_fimvigencia'];
    } else {
        $dt_fimvigencia = NULL;
    }

    $oservicos = new cls_lerservicos();
    $vservfederal = $oservicos->getListServicosFederais($id_grupo,$dt_inivigencia, $dt_fimvigencia);
    echo json_encode($vservfederal);
?>