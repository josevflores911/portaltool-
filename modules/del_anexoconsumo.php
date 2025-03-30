<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once '../classes/cls_anexos.php';

    $id_nota = $_GET["id_nota"];
    $id_anexo = $_GET["id_anexo"];

    $oanexo = new cls_anexos($id_nota,NULL, NULL, NULL, NULL, "ntconsumo");
    $result = $oanexo->deleteAnexo($id_anexo);
    echo json_encode($result);
?>