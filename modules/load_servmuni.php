<<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ('../classes/cls_lerservicos.php');
    $id_nota = $_GET['id_nota'];
    $oservicos = new cls_lerservicos($id_nota);
    $vservfederal = $oservicos->getListServicosMunicipais();
    echo json_encode($vservfederal);
?>