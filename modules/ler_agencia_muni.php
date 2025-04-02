<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_muni = $_POST['nm_muni'];
    require_once('../classes/cls_agencias_muni.php');

    $oAgencias = new cls_agencias_muni($id_muni);
    $result = $oAgencias->getCursor();
    echo json_encode($result);
?>