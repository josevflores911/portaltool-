<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_anexos.php";
    $id_nota = $_GET["id_nota"];
    if (isset($_GET['modulo'])) {
        $mod = $_GET["modulo"];
    } else {
        $mod = NULL;
    }

    if (is_null($mod)) {
        $oanexo = new cls_anexos($id_nota);
    } else {
        $oanexo = new cls_anexos($id_nota,NULL, NULL,NULL,NULL, $modulo=$mod);
    }
    $anexos = $oanexo->getCursor();
    echo json_encode($anexos);
?>