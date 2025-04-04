<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_agencia = $_POST['id_agencia'];
    $id_sistem = $_POST['id_sistem'];
    require_once('../classes/cls_agenciamunicipio.php');

    $oAgenciasXSistema = new cls_agenciamunicipio();
    $result = $oAgenciasXSistema->getAgenciaXSistema($id_agencia,$id_sistem);
    echo json_encode($result);
?>