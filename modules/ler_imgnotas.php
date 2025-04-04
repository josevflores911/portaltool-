<?php
    include_once "../classes/cls_imgnotas.php";

    $id_nota = $_GET["id_nota"];
    $id_user = $_GET["id_user"];
    $tdr = $_GET["tdr"];
    if (isset($_GET["validar"])) {
        $validar = $_GET["validar"];
    } else {
        $validar = NULL;
    }

    $oImg = new cls_imgnotas($id_nota, $id_user, $tdr, $validar);
    $result = $oImg->getContent();
    echo $result;
?>