<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once '../classes/cls_grupos.php';
    if (isset($_GET['id_nota'])) {
        $id_nota = $_GET['id_nota'];
    } else {
        $id_nota = null;
    }
    if (isset($_GET['id_grupo'])) {
        $id_grupo = $_GET['id_grupo'];
    } else {
        $id_grupo = NULL;
    }
    $oGrupo = new cls_grupos($id_nota, $id_grupo);
    $list_grupo = $oGrupo->getCursor();
    echo json_encode($list_grupo);
?>