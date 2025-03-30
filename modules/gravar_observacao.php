<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_nota = $_GET['id_nota'];
    $id_user = $_GET['id_user'];
    $te_texto = $_GET['dados'];

    include_once "../classes/cls_gravarobs.php";

    $gravar_obs = new cls_gravarobs($id_nota, $id_user, $te_texto);
    $result = $gravar_obs->gravarObservacao();
    echo json_encode($result);
?>